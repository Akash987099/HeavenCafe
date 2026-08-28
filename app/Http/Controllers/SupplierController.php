<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplier;

    public function __construct()
    {
        $this->supplier = new Supplier();
    }

    public function index()
    {
        $supplier = $this->supplier
            ->newQuery()
            ->latest('id')
            ->paginate(config('pagination_limit', 15));

        return view('supplier.index', compact('supplier'));
    }

    public function add()
    {
        return view('supplier.add');
    }

    public function export()
    {
        $suppliers = $this->supplier->latest('id')->get(['name', 'phone', 'email', 'address', 'type']);
        $fileName = 'suppliers_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response()->streamDownload(function () use ($suppliers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Sr No.', 'Role', 'Name', 'Phone', 'Email', 'Address']);

            foreach ($suppliers as $index => $supplier) {
                $role = (int) $supplier->type === 2 ? 'Delivery Boy' : 'Supplier';
                fputcsv($file, [$index + 1, $role, $supplier->name, $supplier->phone, $supplier->email, $supplier->address]);
            }

            fclose($file);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $supplier = new Supplier();
        $supplier->name = $validated['name'];
        $supplier->email = $validated['email'];
        $supplier->phone = $validated['phone'];
        $supplier->address = $validated['address'];

        if ($supplier->save()) {
            return redirect()->back()->with('success', 'Supplier saved successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to save supplier.');
    }

    public function edit($id)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'ID not found!');
        }

        $supplier = $this->supplier->find($id);

        if (!$supplier) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $supplier = $this->supplier->find($validated['id']);

        if (!$supplier) {
            return redirect()->back()->withInput()->with('error', 'Record not found!');
        }

        $supplier->name = $validated['name'];
        $supplier->email = $validated['email'];
        $supplier->phone = $validated['phone'];
        $supplier->address = $validated['address'];

        if ($supplier->save()) {
            return redirect()->back()->with('success', 'Supplier updated successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update supplier.');
    }

    public function assignRole(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:suppliers,id',
        ]);

        $supplier = $this->supplier->find($validated['id']);

        if (!$supplier) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ], 404);
        }

        // suppliers table has "type" column, not "status".
        $supplier->type = ((int) $supplier->type);
        $saved = $supplier->save();

        if (!$saved) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update supplier type',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'type' => (int) $supplier->type,
        ], 200);
    }

    public function delete($id)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'ID not found!');
        }

        $supplier = $this->supplier->find($id);

        if (!$supplier) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        if ($supplier->delete()) {
            return redirect()->back()->with('success', 'Supplier deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete supplier.');
    }
}
