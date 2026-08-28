<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    protected $slider;

    public function __construct()
    {
        $this->slider = new Slider();
    }

    public function index()
    {
        $sliders = $this->slider->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('slider.index', compact('sliders'));
    }

    public function add()
    {
        return view('slider.add');
    }

    public function export()
    {
        $sliders = $this->slider->orderBy('id', 'desc')->get(['name', 'link', 'status']);
        $fileName = 'sliders_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($sliders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name', 'Link', 'Status']);

            foreach ($sliders as $index => $slider) {
                fputcsv($file, [$index + 1, $slider->name, $slider->link, (int) $slider->status === 1 ? 'Active' : 'Inactive']);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public  function save(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'title' => 'required',
            'image' => 'required',
        ]);

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('slider'), $imageName);

        $slider = $this->slider;
        $slider->name = $request->title;
        $slider->link = $request->link;
        $slider->status = 1;
        $slider->image = 'slider/' . $imageName;
        $save = $slider->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
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

        $slider = $this->slider->find($id);

        if (!$slider) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $slider->status = $slider->status == '1' ? '0' : '1';
        $slider->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }

    public function edit($id)
    {
        // dd($id);
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $slider = $this->slider->find($id);

        if (!$slider) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('slider.edit', compact('slider'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:sliders,id',
            'title'  => 'required',
            // 'image' => 'nullable',
        ]);

        $slider = $this->slider->find($request->id);

        if (!$slider) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $slider->name = $request->title;
        $slider->link = $request->link;
        if ($request->hasFile('image')) {

            if ($slider->image && file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('slider'), $imageName);

            $slider->image = 'slider/' . $imageName;
        }

        if ($slider->save()) {
            return redirect()->back()->with('success', 'Updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }

    public function delete($id)
    {
        try {
            $slider = $this->slider->findOrFail($id);

            if ($slider->image && file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }

            $slider->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'exceptionError',
                'error'  => $e->getMessage()
            ], 500);
        }
    }
}
