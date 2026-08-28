<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;

class OfferController extends Controller
{
    protected $offer;

    public function __construct()
    {
        $this->offer = new Offer();
    }

    public function index()
    {
        $offers = $this->offer->where('status', 1)->select('id', 'title', 'code', 'type', 'discount_value', 'min_order_amount', 'max_discount')->get();
        return response()->json(['status' => 'success', 'data' => $offers]);
    }

    public function show($id)
    {
        $offer = $this->offer->where('id', $id)->where('status', 1)->first();
        if ($offer) {
            return response()->json(['status' => 'success', 'data' => $offer]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Offer not found'], 404);
        }
    }
}
