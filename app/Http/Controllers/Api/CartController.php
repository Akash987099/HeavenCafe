<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Address;
use App\Models\Discount;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CartController extends Controller
{
    protected $cart;
    protected $address;

    public function __construct()
    {
        $this->cart = new Cart();
        $this->address = new Address();
    }

    public function index(Request $request)
    {
        $user_id = auth()->id();

        $address = $this->address->where('user_id', $user_id)->where('is_default', 1)->first();

        $carts = $this->cart
            ->join('products', 'carts.product_id', 'products.id')
            ->where('carts.user_id', $user_id)
            ->whereNull('order_id')
            ->select(
                'carts.id as cart_id',
                'carts.price',
                'carts.qty',
                'carts.product_id',
                'carts.discount',
                'products.price as product_price',
                'products.ac_price',
                'products.stock',
                'products.in_stock',
                'products.name',
                'products.image'
            )->get();

        $total = $carts->sum('qty');
        $price = $carts->sum('price');
        $discount = $carts->sum('discount');


        $carts->each(function ($product) {
            $product->url = Str::slug($product->name) . '-' . $product->product_id;
        });

        return response()->json([
            'status' => true,
            'message' => 'Success!',
            'total_qty' => $total,
            'totalPrice' => $price,
            'distance' => $address ? $address->distance : 0,
            'time' => $address ? $address->time : 0,
            'delhivery_charge' => number_format($address ? ($address->distance * 5) : 0, 2),
            'discount' => $discount,
            'data' => $carts
        ]);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'qty'        => 'required|integer|min:0',
            'price'      => 'nullable|numeric|min:0',
            'type'       => 'required|in:add,remove,custom',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->id();

        $cart = Cart::where('user_id', $user_id)
            ->where('product_id', $request->product_id)
            ->whereNull('order_id')
            ->first();

        $product = Product::find($request->product_id);

        if ($product->stock == 0 || !$product->in_stock) {
            return response()->json([
                'status' => false,
                'message' => 'Product is out of stock'
            ], 404);
        }

        $discountprice = 0;

        if ($product && $product->discount) {
            $discount = Discount::where('id', $product->discount)->first();
            $discountprice = $discount->amount ?? 0;
        }

        $totalPrice = $product->price - $discountprice;

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // ================= ADD =================
        if ($request->type === 'add') {

            if ($cart) {
                $cart->qty += $request->qty;
                $cart->discount  = $discountprice ?? 0;
                $cart->price += ($totalPrice * $request->qty);
                $cart->save();
            } else {
                $cart = Cart::create([
                    'user_id' => $user_id,
                    'product_id' => $request->product_id,
                    'qty' => $request->qty,
                    'discount' => $discountprice ?? 0,
                    'price' => $totalPrice * $request->qty,
                ]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Product quantity increased',
                'data'    => $this->cartResponse($cart, $product, $discountprice)
            ], 200);
        }

        // ================= REMOVE =================
        if ($request->type === 'remove') {

            if (!$cart) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Cart item not found',
                ], 404);
            }

            $cart->qty -= $request->qty;

            if ($cart->qty <= 0) {
                $cart->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Product removed from cart',
                    'data'    => [
                        'qty'   => 0,
                        'price' => 0,
                    ]
                ], 200);
            }

            $cart->price = $cart->qty * $totalPrice;
            $cart->save();

            return response()->json([
                'status'  => true,
                'message' => 'Product quantity decreased',
                'data'    => $this->cartResponse($cart, $product, $discountprice)
            ], 200);
        }

        // ================= CUSTOM =================
        if ($request->type === 'custom') {

            if ($request->qty == 0) {

                if ($cart) {
                    $cart->delete();
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Cart item removed',
                    'data'    => [
                        'qty'   => 0,
                        'price' => 0,
                    ]
                ], 200);
            }

            if ($cart) {
                $cart->qty = $request->qty;
                $cart->discount = $discountprice ?? 0;
                $cart->price = $totalPrice * $request->qty;
                $cart->save();
            } else {
                $cart = Cart::create([
                    'user_id' => $user_id,
                    'product_id' => $request->product_id,
                    'qty' => $request->qty,
                    'discount' => $discountprice ?? 0,
                    'price' => $totalPrice * $request->qty,
                ]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Cart updated',
                'data'    => $this->cartResponse($cart, $product, $discountprice)
            ], 200);
        }
    }

    private function cartResponse($cart, $product, $discountprice = 0)
    {
        return [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'qty' => $cart->qty,
            'price' => $cart->price,

            // ✅ Added fields
            'product_price' => $product->price,
            'ac_price' => $product->ac_price,
            'stock' => $product->stock,
            'discount' => $discountprice,
            'in_stock' => $product->in_stock,
            'image' => $product->image,
        ];
    }

    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required|integer|exists:users,id',
            'cart_id' => 'required|integer|exists:carts,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user_id = auth()->id();

        $cart = Cart::where('id', $request->cart_id)
            ->where('user_id', $user_id)
            ->whereNull('order_id')
            ->first();

        if (!$cart) {
            return response()->json([
                'status'  => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $cart->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Cart item removed successfully',
            'remove_cart_id' => $request->cart_id,
        ], 200);
    }

    public function clear()
    {
        $user_id = auth()->id();

        Cart::where('user_id', $user_id)->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Cart cleared successfully',
        ], 200);
    }
}
