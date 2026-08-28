<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax;

class TaxController extends Controller
{
    protected $tax;

    public function __construct()
    {
        $this->tax = new Tax();
    }

    public function index()
    {
        $taxes = $this->tax->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('tax.index', compact('taxes'));
    }

    public function add()
    {
        return view('tax.add');
    }

    public function export()
    {
        $taxes = $this->tax->orderBy('id', 'desc')->get(['name', 'tax_value', 'status']);
        $fileName = 'taxes_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($taxes) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name', 'Tax Value', 'Status']);

            foreach ($taxes as $index => $tax) {
                fputcsv($file, [$index + 1, $tax->name, $tax->tax_value, (int) $tax->status === 1 ? 'Active' : 'Inactive']);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'tax_value' => 'required',
        ]);

        $tax = $this->tax;
        $tax->name = $request->name;
        $tax->tax_value = $request->tax_value;
        $save = $tax->save();

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

        $tax = $this->tax->find($id);

        if (!$tax) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('tax.edit', compact('tax'));
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

        $tax = $this->tax->find($id);

        if (!$tax) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $tax->status = $tax->status == 1 ? 0 : 1;
        $tax->save();

        return response()->json([
            'status'    => 'success',
        ], 200);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'tax_value' => 'required',
        ]);

        $tax = $this->tax->find($request->id);
        $tax->name = $request->name;
        $tax->tax_value = $request->tax_value;
        $save = $tax->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

}
