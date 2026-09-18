<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policy;

class PolicyController extends Controller
{
    protected $policy;

    public function __construct(){
        $this->policy = new Policy();
    }

    public function index(){
        $policy = $this->policy->paginate(config('constants.pagination_limit'));
        return view('policy.index', compact('policy'));
    }

    public function add(){
        return view('policy.add');
    }

    public function save(Request $request){
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required',
        ]);

        $policy = $this->policy;
        $policy->name = $request->name;

        if ($request->hasFile('file')) {

            $imageName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(public_path('policy'), $imageName);

            $policy->pdf = 'policy/' . $imageName;
        }

        $save = $policy->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function delete($id)
    {
        try {
            $policy = $this->policy->findOrFail($id);

            if ($policy->pdf && file_exists(public_path($policy->pdf))) {
                unlink(public_path($policy->pdf));
            }

            $policy->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'exceptionError',
                'error'  => $e->getMessage()
            ], 500);
        }
    }

}
