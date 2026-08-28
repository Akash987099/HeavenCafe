<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderRating;
use App\Models\Review;

class ReviewController extends Controller
{
    public function orderRating(Request $request)
    {
        $user_id = auth()->id();

        $request->validate([
            'order_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string'
        ]);

        $check = OrderRating::where('user_id', $user_id)
            ->where('order_id', $request->order_id)
            ->first();

        if ($check) {
            return response()->json([
                'status' => false,
                'message' => 'You already rated this order'
            ]);
        }

        $review = OrderRating::create([
            'user_id' => $user_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Order rating submitted successfully',
            'data' => $review
        ]);
    }

    public function productRating(Request $request)
    {
        $user_id = auth()->id();

        $request->validate([
            'order_id' => 'required',
            'product_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string'
        ]);

        $check = Review::where('user_id', $user_id)
            ->where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($check) {
            return response()->json([
                'status' => false,
                'message' => 'You already rated this order'
            ]);
        }

        $review = Review::create([
            'user_id' => $user_id,
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'review' => $request->review
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Order rating submitted successfully',
            'data' => $review
        ]);
    }
}
