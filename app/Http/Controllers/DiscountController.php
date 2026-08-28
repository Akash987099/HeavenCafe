<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    protected $discount;

    public function __construct()
    {
        $this->discount = new Discount();
    }

    public function index()
    {
        $discount = $this->discount->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('discount.index', compact('discount'));
    }

    public function add()
    {
        return view('discount.add');
    }

    public function export()
    {
        $discounts = $this->discount->orderBy('id', 'desc')->get(['name', 'amount']);
        $fileName = 'discounts_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($discounts) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Name', 'Amount']);

            foreach ($discounts as $index => $discount) {
                fputcsv($file, [$index + 1, $discount->name, $discount->amount]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required',
        ]);

        $discount = $this->discount;
        $discount->name = $request->name;
        $discount->amount = $request->amount;

        $save = $discount->save();

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

        $discount = $this->discount->find($id);

        if (!$discount) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('discount.edit', compact('discount'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required',
        ]);

        $discount = $this->discount->find($request->id);

        if (!$discount) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $discount->name = $request->name;
        $discount->amount = $request->amount;

        if ($discount->save()) {
            return redirect()->back()->with('success', 'Updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }
}
