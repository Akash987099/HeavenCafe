<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    protected $wishlist;

    public function __construct()
    {
        $this->wishlist = new Wishlist();
    }

    public function index(Request $request)
    {
        $user_id = auth()->id();

        $wishlist = $this->wishlist->join('products', 'wishlists.product_id', 'products.id')->where('wishlists.user_id', $user_id)->select('wishlists.id as wishlist_id', 'wishlists.product_id', 'products.name', 'products.image')->get();

        if (!$wishlist) {
            return response()->json([
                'status' => false,
                'message' => 'no record found!',
            ]);
        }

        $wishlist->each(function ($product) {
            $product->url = Str::slug($product->name) . '-' . $product->product_id;
        });

        return response()->json([
            'status' => true,
            'message' => 'Success',
            "data" => $wishlist
        ]);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'type'       => 'required|in:add,remove',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->id();

        $wishlist = Wishlist::where('user_id', $user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($request->type === 'add') {

            if ($wishlist) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Product already in wishlist',
                ]);
            }

            Wishlist::create([
                'user_id'    => $user_id,
                'product_id' => $request->product_id,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Product added to wishlist',
            ]);
        }

        if ($request->type === 'remove') {

            if (!$wishlist) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Product not found in wishlist',
                ]);
            }

            $wishlist->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Product removed from wishlist',
            ]);
        }
    }
}
