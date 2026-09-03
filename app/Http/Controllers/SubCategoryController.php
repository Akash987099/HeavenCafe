<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    protected $category;
    protected $subcategory;

    public function __construct()
    {
        $this->category = new Category();
        $this->subcategory = new SubCategory();
    }

    public function index()
    {
        $category = $this->subcategory->with('categories')->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        // dd($category);
        return view('sub_category.index', compact('category'));
    }

    public function add()
    {
        $category = $this->category->all();
        return view('sub_category.add', compact('category'));
    }

    public function export()
    {
        $subcategories = $this->subcategory
            ->with('categories')
            ->orderBy('id', 'desc')
            ->get();

        $fileName = 'subcategories_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($subcategories) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Category Name', 'Sub Category Name']);

            foreach ($subcategories as $index => $subcategory) {
                fputcsv($file, [
                    $index + 1,
                    $subcategory->categories->pluck('name')->implode(', ') ?: 'N/A',
                    $subcategory->name,
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function save(Request $request)
    {
        $request->validate([
            'category'    => 'required|array|min:1',
            'category.*'  => 'exists:category,id',
            'name' => 'required|string|max:255',
            'image' => 'required|image',
        ]);

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('subcategory'), $imageName);

        $save = DB::transaction(function () use ($request, $imageName) {
            $categoryIds = array_values(array_unique($request->category));
            $subcategory = new SubCategory();
            $subcategory->name = $request->name;
            $subcategory->description = $request->description;
            // Keep the first ID for older integrations that still read category_id directly.
            $subcategory->category_id = $categoryIds[0];
            $subcategory->image = 'subcategory/' . $imageName;
            $subcategory->save();
            $subcategory->categories()->sync($categoryIds);

            return true;
        });

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

        $subcategory = $this->subcategory->find($id);

        if (!$subcategory) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $subcategory->load('categories');
        $category = $this->category->all();
        return view('sub_category.edit', compact('subcategory', 'category'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:sub_category,id',
            'category'    => 'required|array|min:1',
            'category.*'  => 'exists:category,id',
            'name'  => 'required|string|max:255',
            'image' => 'nullable',
        ]);

        $subcategory = $this->subcategory->find($request->id);

        if (!$subcategory) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $subcategory->name = $request->name;
        $subcategory->description = $request->description;
        $categoryIds = array_values(array_unique($request->category));
        $subcategory->category_id = $categoryIds[0];

        if ($request->hasFile('image')) {

            if ($subcategory->image && file_exists(public_path($subcategory->image))) {
                unlink(public_path($subcategory->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('subcategory'), $imageName);

            $subcategory->image = 'subcategory/' . $imageName;
        }

        if ($subcategory->save()) {
            $subcategory->categories()->sync($categoryIds);

            return redirect()->back()->with('success', 'Category updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }

    public function delete($id)
    {
        $subcategory = $this->subcategory->withCount('products')->find($id);

        if (!$subcategory) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        if ($subcategory->products_count > 0) {
            return redirect()->back()->with('error', 'This subcategory is used by products and cannot be deleted.');
        }

        DB::transaction(function () use ($subcategory) {
            $subcategory->categories()->detach();
            $subcategory->delete();
        });

        if ($subcategory->image && file_exists(public_path($subcategory->image))) {
            unlink(public_path($subcategory->image));
        }

        return redirect()->back()->with('success', 'Subcategory deleted successfully!');
    }

}
