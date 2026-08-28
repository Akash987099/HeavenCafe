<?php

namespace App\Http\Controllers\cafe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cafe\Category;

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
        return view('cafe.category.index', compact('category'));
    }

    public function add(){
        return view('cafe.category.add');
    }

    public function save(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            // 'image' => 'required|image',
        ]);

        $category = $this->category;
        $category->name = $request->name;
        $category->slug = $request->slug;

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
        // dd($id);
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $category = $this->category->find($id);

        if (!$category) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('cafe.category.edit', compact('category'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:category,id',
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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

}
