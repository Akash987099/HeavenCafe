<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::paginate(config('constants.pagination_limit'));
        return view('payment-methods.index', compact('paymentMethods'));
    }

    public function add()
    {
        return view('payment-methods.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        PaymentMethod::create($request->all());
        return redirect()->route('payment_method.index')->with('success', 'Payment method added successfully.');
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update($request->all());
        return redirect()->route('payment_method.index')->with('success', 'Payment method updated successfully.');
    }

    public function delete($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();
        return redirect()->route('payment_method.index')->with('success', 'Payment method deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $paymentMethod = PaymentMethod::findOrFail($request->id);
        $paymentMethod->status = $request->status;
        $paymentMethod->save();
        return response()->json(['success' => true]);
    }
}
