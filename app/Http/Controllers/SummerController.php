<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Summer;

class SummerController extends Controller
{
    protected $summer;

    public function __construct()
    {
        $this->summer = new Summer();
    }

    public function index()
    {
        $summer = $this->summer->orderBy('position', 'ASC')->paginate(config('pagination_limit'));
        return view('summer.index', compact('summer'));
    }

    public function add()
    {
        return view('summer.add');
    }

    public function export()
    {
        $summaries = $this->summer->orderBy('position', 'asc')->orderBy('id', 'desc')->get(['name', 'sub_name', 'status']);
        $fileName = 'summer_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($summaries) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name', 'Title', 'Status']);

            foreach ($summaries as $index => $summer) {
                fputcsv($file, [$index + 1, $summer->name, $summer->sub_name, (int) $summer->status === 1 ? 'Active' : 'Inactive']);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required'
        ]);

        $summer = $this->summer;

        if ($request->hasFile('image')) {

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('summer'), $imageName);

            $summer->image = 'summer/' . $imageName;
        }

        $summer->name = $request->name;
        $summer->sub_name = $request->title;
        // $summer->time = $request->time;
        $save = $summer->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function edit($id)
    {
        // dd($id);
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $summer = $this->summer->find($id);

        if (!$summer) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('summer.edit', compact('summer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required'
        ]);

        $summer = $this->summer->find($request->id);

        if (!$summer) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        if ($request->hasFile('image')) {

            if ($summer->image && file_exists(public_path($summer->image))) {
                unlink(public_path($summer->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('summer'), $imageName);

            $summer->image = 'summer/' . $imageName;
        }

        $summer->name = $request->name;
        $summer->sub_name = $request->title;
        // $summer->time = $request->time;

        if ($summer->save()) {
            return redirect()->back()->with('success', 'Updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }

    public function updatePosition(Request $request)
    {
        try {
            $positions = $request->positions;

            foreach ($positions as $index => $id) {
                Summer::where('id', $id)->update([
                    'position' => $index + 1
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Position updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function status(Request $request)
    {
        $status = $request->value;
        $id = $request->id;

        if (empty($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID not found'
            ], 400);
        }

        $summer = $this->summer->find($id);

        if (!$summer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $summer->status = $summer->status == '1' ? '0' : '1';
        $summer->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }
}
