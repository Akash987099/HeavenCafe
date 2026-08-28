<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    protected $faq;

    public function __construct()
    {
        $this->faq = new Faq();
    }

    public function index()
    {
        $faqs = $this->faq->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('faq.index', compact('faqs'));
    }

    public function add()
    {
        return view('faq.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $faq = new Faq();
        $faq->name = $request->name;
        $faq->description = $request->description;
        $faq->status = $request->status;

        if ($faq->save()) {
            return redirect()->route('faq.index')->with('success', 'FAQ created successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create FAQ!');
    }

    public function edit($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID not found!');
        }

        $faq = $this->faq->find($id);

        if (!$faq) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('faq.edit', compact('faq'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:faq,id',
            'name' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $faq = $this->faq->find($request->id);

        if (!$faq) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $faq->name = $request->name;
        $faq->description = $request->description;
        $faq->status = $request->status;

        if ($faq->save()) {
            return redirect()->route('faq.index')->with('success', 'FAQ updated successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Update failed!');
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;

        if (empty($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID not found'
            ], 400);
        }

        $faq = $this->faq->find($id);

        if (!$faq) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $faq->status = $faq->status == 1 ? 0 : 1;
        $faq->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully'
        ], 200);
    }

    public function delete($id)
    {
        try {
            $faq = $this->faq->findOrFail($id);
            $faq->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'exceptionError',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
