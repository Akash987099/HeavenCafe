<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    protected $brand;

    public function __construct()
    {
        $this->brand = new Brand();
    }
    public function index(){
        $brands = $this->brand->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('brand.index', compact('brands'));
    }

    public function add(){
        return view('brand.add');
    }

    public function export()
    {
        $brands = $this->brand
            ->orderBy('id', 'desc')
            ->get(['name']);

        $fileName = 'brands_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($brands) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Brand Name']);

            foreach ($brands as $index => $brand) {
                fputcsv($file, [
                    $index + 1,
                    $brand->name,
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
            'image' => 'required|image',
        ]);

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('brand'), $imageName);

        $brand = $this->brand;
        $brand->name = $request->name;
        $brand->image = 'brand/' . $imageName;
        $save = $brand->save();

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

        $brand = $this->brand->find($id);

        if (!$brand) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('brand.edit', compact('brand'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:brands,id',
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $brand = $this->brand->find($request->id);

        if (!$brand) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $brand->name = $request->name;

        if ($request->hasFile('image')) {

            if ($brand->image && file_exists(public_path($brand->image))) {
                unlink(public_path($brand->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('brand'), $imageName);

            $brand->image = 'brand/' . $imageName;
        }

        if ($brand->save()) {
            return redirect()->back()->with('success', 'Updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }
}
