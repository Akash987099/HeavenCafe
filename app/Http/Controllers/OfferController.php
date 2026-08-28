<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;

class OfferController extends Controller
{
    protected $offer;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function index()
    {
        $offers = $this->offer->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('offer.index', compact('offers'));
    }

    public function add()
    {
        return view('offer.add');
    }

    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'code' => 'nullable|string|max:50|unique:offers,code',
            'type' => 'required|in:coupon,auto,card',
            'discount_type' => 'required|in:flat,percent',
            'discount_value' => 'required|numeric',
            'min_order_amount' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'per_user_limit' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $this->offer->create([
            'title' => $request->title,
            'code' => $request->code,
            'type' => $request->type,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'per_user_limit' => $request->per_user_limit,
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
            'status' => 1,
        ]);

        return redirect()->route('offer.index')->with('success', 'Offer created successfully');
    }

    public function edit($id)
    {
        $offer = $this->offer->findOrFail($id);
        return view('offer.edit', compact('offer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:offers,id',
            'title' => 'required|string|max:150',
            'code' => 'nullable|string|max:50|unique:offers,code,' . $request->id,
            'type' => 'required|in:coupon,auto,card',
            'discount_type' => 'required|in:flat,percent',
            'discount_value' => 'required|numeric',
            'min_order_amount' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'per_user_limit' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $offer = $this->offer->findOrFail($request->id);

        $offer->update([
            'title' => $request->title,
            'code' => $request->code,
            'type' => $request->type,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'per_user_limit' => $request->per_user_limit,
            'start_date' => $request->start_date,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('offer.index')->with('success', 'Offer updated successfully');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:offers,id',
            'status' => 'required|in:0,1',
        ]);

        $offer = $this->offer->findOrFail($request->id);
        $offer->status = $request->status;
        $offer->save();

        return response()->json(['success' => true, 'message' => 'Offer status updated successfully']);
    }
}
