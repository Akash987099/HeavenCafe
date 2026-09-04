<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Pos;
use Illuminate\Support\Facades\Auth;

class PosLeaveController extends Controller
{
    protected $leave;
    protected $pos;

    public function __construct()
    {
        $this->leave = new Leave();
        $this->pos   = new Pos();
    }

    public function index()
    {
        $user = Auth::guard('pos')->user();

        if ($user->role == 1) {

            // Manager: own + staff leaves
            $staffIds = $this->pos
                ->where('user_id', $user->id)
                ->pluck('id')
                ->toArray();

            $userIds = array_merge(
                [$user->id],
                $staffIds
            );

        } else {

            // Staff: only own leaves
            $userIds = [$user->id];
        }

        $query = $this->leave
            ->whereIn('pos_user_id', $userIds);

        $totalLeaves = (clone $query)->count();

        $pendingLeaves = (clone $query)
            ->where('status', 'pending')
            ->count();

        $approvedLeaves = (clone $query)
            ->where('status', 'approved')
            ->count();

        $rejectedLeaves = (clone $query)
            ->where('status', 'rejected')
            ->count();

        $leaves = (clone $query)
            ->with('pos')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view(
            'pos.leave.index',
            compact(
                'leaves',
                'totalLeaves',
                'pendingLeaves',
                'approvedLeaves',
                'rejectedLeaves'
            )
        );
    }

    public function add(){
        return view('pos.leave.add');
    }

    public function save(Request $request){
        // dd($request->all());
        $request->validate([
            'leave_type'   => 'required',
            'total_days'   => 'required',
            'start_date'   => 'required',
            'end_date'     => 'required',
            'reason'       => 'required',
        ]);

        $user = Auth::guard('pos')->user();

        $leave = $this->leave;
        $leave->pos_user_id = $user->id;
        $leave->leave_type = $request->leave_type;
        $leave->total_days = $request->total_days;
        $leave->store_id   = $user->store_id;
        $leave->start_date = $request->start_date;
        $leave->end_date   = $request->end_date;
        $leave->reason   = $request->reason;

        $save = $leave->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function view($id)
    {
        $user = Auth::guard('pos')->user();

        $query = $this->leave
            ->with(['pos', 'approver'])
            ->where('id', $id);

        if ($user->role == 2) {
            $query->where('pos_user_id', $user->id);
        }

        if ($user->role == 1) {

            $staffIds = $this->pos
                ->where('user_id', $user->id)
                ->pluck('id')
                ->toArray();

            $userIds = array_merge(
                [$user->id],
                $staffIds
            );

            $query->whereIn('pos_user_id', $userIds);
        }

        $leave = $query->firstOrFail();

        return view(
            'pos.leave.view',
            compact('leave')
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,cancelled,pending',
            'manager_remark' => 'nullable|string|max:1000',
        ]);

        $user = Auth::guard('pos')->user();

        // Sirf Manager status change kar sakta hai
        if ($user->role != 1) {
            abort(403, 'Unauthorized action.');
        }

        $leave = $this->leave
            ->where('id', $id)
            ->firstOrFail();

        // Manager ke staff ki leave hi update karne dena
        $staffIds = $this->pos
            ->where('user_id', $user->id)
            ->pluck('id')
            ->toArray();

        $allowedUserIds = array_merge(
            [$user->id],
            $staffIds
        );

        if (!in_array($leave->pos_user_id, $allowedUserIds)) {
            abort(403, 'Unauthorized action.');
        }

        $leave->status = $request->status;

        $leave->manager_remark = $request->manager_remark;

        if (in_array($request->status, ['approved', 'rejected'])) {
            $leave->approved_by = $user->id;
            $leave->approved_at = now();
        } else {
            $leave->approved_by = null;
            $leave->approved_at = null;
        }

        $leave->save();

        return redirect()
            ->route('leave.view', $leave->id)
            ->with('success', 'Leave status updated successfully!');
    }
}