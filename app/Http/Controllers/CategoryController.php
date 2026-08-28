<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    protected $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function index()
    {
        $category = $this->category->orderBy('position', 'asc')->paginate(config('constants.pagination_limit'));
        return view('category.index', compact('category'));
    }

    public function add()
    {
        return view('category.add');
    }

    public function export()
    {
        $categories = $this->category
            ->orderBy('position', 'asc')
            ->orderBy('id', 'asc')
            ->get(['name', 'status']);

        $fileName = 'categories_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($categories) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Category Name', 'Status']);

            foreach ($categories as $index => $category) {
                fputcsv($file, [
                    $index + 1,
                    $category->name,
                    (int) $category->status === 1 ? 'Active' : 'InActive',
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function save(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required',
        ]);

        $category = $this->category;
        $category->name = $request->name;

        if ($request->hasFile('image')) {

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('category'), $imageName);

            $category->image = 'category/' . $imageName;
        }

        $save = $category->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function edit($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $category = $this->category->find($id);

        if (!$category) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('category.edit', compact('category'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:category,id',
            'name'  => 'required|string|max:255',
            'image' => 'nullable',
        ]);

        $category = $this->category->find($request->id);

        if (!$category) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $category->name = $request->name;
        $category->slug = $request->slug;

        if ($request->hasFile('image')) {

            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('category'), $imageName);

            $category->image = 'category/' . $imageName;
        }

        if ($category->save()) {
            return redirect()->back()->with('success', 'Category updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }

    public function updatePosition(Request $request)
    {
        foreach ($request->positions as $index => $id) {
            Category::where('id', $id)->update([
                'position' => $index + 1
            ]);
        }

        return response()->json([
            'message' => 'Category order updated successfully'
        ]);
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

        $category = $this->category->find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }
}
