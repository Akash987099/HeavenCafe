<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotional;

class PromotionalController extends Controller
{
    protected $promo;

    public function __construct()
    {
        $this->promo = new Promotional();
    }
    public function index()
    {
        $promo = $this->promo->orderBy('position', 'asc')->paginate(config('constants.pagination_limit'));
        return view('promotional.index', compact('promo'));
    }

    public function add()
    {
        return view('promotional.add');
    }

    public function export()
    {
        $promotions = $this->promo->orderBy('position', 'asc')->get(['name', 'url_link', 'status']);
        $fileName = 'promotionals_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($promotions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name', 'Link', 'Status']);

            foreach ($promotions as $index => $promotion) {
                fputcsv($file, [$index + 1, $promotion->name, $promotion->url_link, (int) $promotion->status === 1 ? 'Active' : 'Inactive']);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'link' => 'required',
            'image' => 'required',
        ]);

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('promotional'), $imageName);

        $promo = $this->promo;
        $promo->name = $request->name;
        $promo->url_link = $request->link;
        $promo->status = 1;
        $promo->image = 'promotional/' . $imageName;
        $save = $promo->save();

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

        $promo = $this->promo->find($id);

        if (!$promo) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('promotional.edit', compact('promo'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:promotionals,id',
            'name'  => 'required',
            // 'image' => 'nullable',
        ]);

        $promo = $this->promo->find($request->id);

        if (!$promo) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $promo->name = $request->name;
        $promo->url_link = $request->link;
        if ($request->hasFile('image')) {

            if ($promo->image && file_exists(public_path($promo->image))) {
                unlink(public_path($promo->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('promotional'), $imageName);

            $promo->image = 'promotional/' . $imageName;
        }

        if ($promo->save()) {
            return redirect()->back()->with('success', 'Updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
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

        $promo = $this->promo->find($id);

        if (!$promo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $promo->status = $request->status == '1' ? '0' : '1';
        $promo->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }
}
