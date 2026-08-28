<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductAplus;
use App\Models\ProductAplusImage;

class AplusController extends Controller
{
    protected $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    // 🔹 PAGE LOAD
    public function index($id)
    {
        if (!$id) {
            return back()->with('error', 'ID not found');
        }

        $product = $this->product->find($id);

        if (!$product) {
            return back()->with('error', 'Record not found!');
        }

        // APLUS DATA WITH IMAGES
        $aplus = ProductAplus::with('images')
                    ->where('product_id', $id)
                    ->latest()
                    ->get();

        return view('aplus.index', compact('product', 'aplus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required',
            'section_type' => 'required',
            'images'       => 'required'
        ]);

        $type  = $request->section_type;
        $files = $request->file('images');
        $count = count($files);

        if ($type == 'single' && $count != 1) {
            return back()->with('error', '1 image required');
        }

        if ($type == 'two' && $count != 2) {
            return back()->with('error', '2 images required');
        }

        if ($type == 'three' && $count != 3) {
            return back()->with('error', '3 images required');
        }

        $aplus = ProductAplus::create([
            'product_id'   => $request->product_id,
            'section_type' => $type
        ]);

        foreach ($files as $file) {

            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('aplus'), $name);

            ProductAplusImage::create([
                'aplus_id' => $aplus->id,
                'image'    => $name
            ]);
        }

        return back()->with('success', 'A+ Section Added Successfully');
    }

    // 🔹 DELETE SECTION
    public function destroy($id)
    {
        $aplus = ProductAplus::with('images')->find($id);

        if (!$aplus) {
            return back()->with('error', 'Record not found');
        }

        // DELETE IMAGES FROM FOLDER
        foreach ($aplus->images as $img) {
            $path = public_path('aplus/' . $img->image);

            if (file_exists($path)) {
                unlink($path);
            }
        }

        // DELETE FROM DB
        $aplus->delete();

        return back()->with('success', 'Section Deleted');
    }
}