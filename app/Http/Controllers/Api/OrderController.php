<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transcation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Address;
use App\Models\TruckOrder;
use App\Models\Review;
use App\Models\OrderRating;
use App\Models\Table;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use App\Models\CardTransaction;
use App\Models\UserCard;
use App\Models\CardType;
use App\Models\Notification;
use App\Models\Status;

class OrderController extends Controller
{
    protected $order;
    protected $orderItems;
    protected $carts;
    protected $product;
    protected $transcation;
    protected $address;
    protected $track;
    protected $wallet;
    protected $card;
    protected $cardtype;
    protected $cardtransaction;
    protected $notification;

    public function __construct()
    {
        $this->order = new Order();
        $this->orderItems = new OrderItem();
        $this->carts = new Cart();
        $this->product = new Product();
        $this->transcation = new Transcation();
        $this->address = new Address();
        $this->track = new TruckOrder();
        $this->wallet = new Wallet();
        $this->card = new UserCard();
        $this->cardtype = new CardType();
        $this->cardtransaction = new CardTransaction();
        $this->notification = new Notification();
    }

    public function placeOrder(Request $request)
    {

        $delhivery_charge = 0;
        $distance = 0;
        $time = 0;

        $validator = Validator::make($request->all(), [
            'order_type' => 'required|in:token,delivery,takeway',
            'table_no'   => 'nullable|integer',
            'payment_method'   => 'required|in:cod,online,wallet,card,Razorpay',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        if ($request->order_type == 'token') {
            if (!$request->table_no) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Table number is required for token order',
                ], 400);
            }

            $table_no = $request->table_no;
            $table = Table::where('table_no', $table_no)->first();
            // dd($table);

            if (!$table) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Table no not found',
                ], 404);
            }
        }

        $user_id = auth()->id();

        // dd(auth()->user());

        if ($request->order_type == 'delivery') {
            $address = $this->address
            ->where('user_id', $user_id)
            ->where('is_default', 1)
            ->first();

            if (!$address) {
                return response()->json([
                    'status' => false,
                    'message' => 'Default address not found'
                ], 400);
            }

            if ($request->delhivery_charge) {
                return response()->json([
                    'status' => false,
                    'message' => 'Delhivery charge cannot be applied for delivery order',
                ], 400);
            }
            
            $delhivery_charge = number_format($address ? ($address->distance * 5) : 0, 2);
            $time = $address ? $address->time : 0;
            $distance = $address ? $address->distance : 0;

        }else{
            $address = 0;
        }

        $carts = $this->carts
            ->with('product')
            ->where('user_id', $user_id)
            ->whereNull('order_id')
            ->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        try {
            return DB::transaction(function () use ($carts, $user_id, $request, $address, $delhivery_charge) {

                $totalAmount = 0;
                $totalDiscount = 0;

                foreach ($carts as $cart) {

                    if (!$cart->product) {
                        throw new \Exception("Product missing for cart ID {$cart->id}");
                    }

                    $availableStock = (int) ($cart->product->stock ?? 0);
                    if ($availableStock < (int) $cart->qty) {
                        throw new \Exception("Insufficient stock for {$cart->product->name}");
                    }

                    $totalAmount += $cart->price;
                    $totalDiscount += $cart->discount;
                }

                $finalAmount = $totalAmount - $totalDiscount + ($request->order_type == 'delivery' ? $delhivery_charge : 0);

                if($request->payment_method == 'wallet'){
                    $wallet_points = auth()->user()->wallet_points;

                    if($wallet_points < $finalAmount){
                        return response()->json([
                            'status' => false,
                            'message' => 'Insufficient wallet points'
                        ], 400);
                    }

                    $user = auth()->user();
                    $user->wallet_points = $wallet_points - $finalAmount;
                    $user->save();

                    $payment_status = 'paid';
                }

                if($request->payment_method == 'card'){
                    if (!$request->card_number) {
                        return response()->json([
                            'status'  => false,
                            'message' => 'Card number is required for card payment',
                        ], 400);
                    }
                    $card = $this->card->where('user_id', $user_id)
                        ->where('card_number', $request->card_number)
                        ->first();
                    if (!$card) {
                        return response()->json([
                            'status'  => false,
                            'message' => 'Card not found',
                        ], 404);
                    }

                    if($card->status != 1){
                        return response()->json([
                            'status'  => false,
                            'message' => 'Card is not active',
                        ], 400);
                    }

                    if (\Carbon\Carbon::parse($card->expiry_date)->isPast()) {
                        return response()->json([
                            'status'  => false,
                            'message' => 'Card is expired',
                        ], 400);
                    }

                    if($card->balance < $finalAmount){
                        return response()->json([
                            'status'  => false,
                            'message' => 'Insufficient card balance',
                        ], 400);
                    }

                    $cardtype = $this->cardtype->find($card->card_type_id);
                    $discountPercent = $cardtype->discount_percent ?? 0;

                    $discountAmount = ($finalAmount * $discountPercent) / 100;
                    $card->balance = $card->balance - $finalAmount;
                    $card->save();
                }

                $orderNo = $this->generateOrderNo();

                $order = $this->order->create([
                    'user_id' => $user_id,
                    'address_id' => $address->id ?? 0,
                    'order_no' => $orderNo,
                    'total_amount' => $totalAmount,
                    'total_discount' => $totalDiscount,
                    'delhivery_charge' => $delhivery_charge,
                    'final_amount' => $finalAmount,
                    'order_type' => $request->order_type,
                    'table_no' => $request->table_no ?? null,
                    'payment_method' => $request->payment_method ?? 'cod',
                    'status' => 'Confirm Order',
                    'description' => $request->description ?? null,
                    // 'payment_status' => $request->payment_method == 'cod' ? 'pending' : 'paid',
                    'payment_status' => in_array($request->payment_method, ['cod', 'Razorpay']) ? 'pending' : 'paid',
                ]);

                if($request->payment_method == 'card') {
                    $this->cardtransaction->create([
                        'card_id' => $card->id,
                        'user_id' => $user_id,
                        'order_id' => $orderNo,
                        'amount' => $finalAmount,
                        'type' => 'debit',
                        'description' => "Payment for order, Card No: **** **** **** " . substr($card->card_number, -4),
                    ]);

                    $this->notification->create([
                        'user_id' => $user_id,
                        'title' => 'Card Payment Successful',
                        'description' => "Your card ending with " . substr($card->card_number, -4) . " has been charged ₹{$finalAmount} for order {$orderNo}."
                    ]);

                    $this->wallet->create([
                        'user_id' => $user_id,
                        'order_id' => $order->id,
                        'type' => 'credit',
                        'points' => $discountAmount,
                        'description' => "Wallet points credited for card payment, Order No: {$orderNo}",
                    ]);
                }

                foreach ($carts as $cart) {

                    $this->orderItems->create([
                        'order_id' => $order->id,
                        'product_id' => $cart->product_id,
                        'qty' => $cart->qty,
                        'price' => $cart->price,
                        'discount' => $cart->discount,
                        'final_price' => $cart->price - $cart->discount,
                    ]);

                    $cart->product->decrement('stock', $cart->qty);

                    $cart->update([
                        'order_id' => $order->id
                    ]);
                }

                $this->transcation->create([
                    'user_id' => $user_id,
                    'order_id' => $order->id,
                    'payment_id' => $request->payment_id ?? null,
                    'amount' => $finalAmount,
                    'currency' => 'INR',
                    'payment_method' => $request->payment_method,
                    'transaction_type' => 'debit',
                    'gateway' => $request->payment_method == 'Razorpay' ? 'Razorpay' : ($request->payment_method == 'wallet' ? 'Wallet' : ($request->payment_method == 'card' ? 'Card' : 'COD')),
                    'status' => 'success',
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);

                add_reward_points($user_id, $order->id, $finalAmount);

                $this->notification->create([
                    'user_id' => $user_id,
                    'title' => 'Order Placed Successfully',
                    'description' => "Your order {$orderNo} has been placed successfully. Total amount: ₹{$finalAmount}."
                ]);

                // if($request->order_type == 'delivery') {
                //     $data = $this->createshipment($order, $address, $delhivery_charge);
                //     dd($data);
                // }

                return response()->json([
                    'status' => true,
                    'message' => 'Order placed successfully',
                    'order_id' => $order->id,
                    'order_no' => $orderNo,
                    'order_type' => $order->order_type,
                    'table_no' => $order->table_no,
                    'final_amount' => $finalAmount,
                    'total_items' => $carts->count()
                ]);

            });
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function success(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,order_no',
            'payment_id' => 'required|string',
            'status' => 'required|in:success,failed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $order = $this->order->where('order_no', $request->order_id)->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->payment_status == 'paid') {
            return response()->json([
                'status' => false,
                'message' => 'Payment already marked as successful for this order'
            ], 400);
        }

        $order->update([
            'payment_status' => $request->status === 'success' ? 'paid' : 'failed',
        ]);

        $this->transcation->where('id', $order->id)->update([
            'payment_status' => $request->status === 'success' ? 'paid' : 'failed',
            'status' => $request->status === 'success' ? 'success' : 'failed',
            'paid_at' => now()
        ]);

        add_reward_points($order->user_id, $order->id, $order->final_amount);

        $this->notification->create([
            'user_id' => $order->user_id,
            'title' => 'Payment Successful',
            'description' => "Your payment for order {$order->order_no} has been received successfully."
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Payment marked as successful'
        ]);
    }

    // public function createshipment($order, $address, $delhivery_charge)
    // {
        
    // }

    public function index($status = null)
    {
        $user_id = auth()->id();

        $statusMap = Status::get(['name', 'bg_color', 'text_color'])
            ->keyBy(function ($status) {
                return strtolower(trim($status->name));
            });

        $query  = Order::with([
            'products:id,name,image',
            'orderRating'
        ])
            ->where('user_id', $user_id);
         if (!empty($status)) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($status)]);
        }
            $orders = $query->latest()
            ->get([
                'id',
                'order_no',
                'status',
                'total_amount',
                'final_amount',
                'payment_method',
                'created_at'
            ]);

        $orders->transform(function ($order) use ($statusMap) {
            $statusDetails = $this->resolveStatusColors($order->status, $statusMap);

            $order->bg_color = $statusDetails['bg_color'];
            $order->text_color = $statusDetails['text_color'];

            return $order;
        });

        $statusCounts = Order::where('user_id', $user_id)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusCounts = $statusCounts->map(function ($total, $status) use ($statusMap) {
            $statusDetails = $this->resolveStatusColors($status, $statusMap);

            return [
                'total' => $total,
                'bg_color' => $statusDetails['bg_color'],
                'text_color' => $statusDetails['text_color'],
            ];
        });

        $totalOrders = Order::where('user_id', $user_id)->count();

        return response()->json([
            'status' => true,
            'message' => 'Order list',  
            'count' => $statusCounts,
            'total_orders' => $totalOrders,
            'data' => $orders,
        ]);
    }

    private function resolveStatusColors($status, $statusMap)
    {
        $normalizedStatus = strtolower(trim((string) $status));
        $statusDetails = $statusMap->get($normalizedStatus);

        if (!empty($statusDetails?->bg_color) && !empty($statusDetails?->text_color)) {
            return [
                'bg_color' => $statusDetails->bg_color,
                'text_color' => $statusDetails->text_color,
            ];
        }

        $fallbackColors = [
            'confirm order' => [
                'bg_color' => 'bg-blue-100',
                'text_color' => 'text-blue-800',
            ],
            'packing' => [
                'bg_color' => 'bg-yellow-100',
                'text_color' => 'text-yellow-800',
            ],
            'shipped' => [
                'bg_color' => 'bg-indigo-100',
                'text_color' => 'text-indigo-800',
            ],
            'out for delivery' => [
                'bg_color' => 'bg-orange-100',
                'text_color' => 'text-orange-800',
            ],
            'delivered' => [
                'bg_color' => 'bg-green-100',
                'text_color' => 'text-green-800',
            ],
            'completed' => [
                'bg_color' => 'bg-emerald-100',
                'text_color' => 'text-emerald-800',
            ],
            'cancelled' => [
                'bg_color' => 'bg-red-100',
                'text_color' => 'text-red-800',
            ],
        ];

        return $fallbackColors[$normalizedStatus] ?? [
            'bg_color' => 'bg-gray-100',
            'text_color' => 'text-gray-800',
        ];
    }

    public function show($id)
    {
        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'Id is required'
            ], 404);
        }

        $user_id = auth()->id();

        $order = $this->order
            ->with(['items.product'])
            ->where('user_id', $user_id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->items->each(function ($item) use ($user_id) {

            if ($item->product) {
                // product url
                $item->product->url = Str::slug($item->product->name) . '-' . $item->product->id;

                // product rating (user rating)
                $rating = Review::where('product_id', $item->product->id)
                    ->where('order_id', $item->order_id)
                    ->where('user_id', $user_id)
                    ->select('rating', 'review')
                    ->first();

                $item->product->rating = $rating ? $rating->rating : null;
                $item->product->review = $rating ? $rating->review : null;
            }
        });

        return response()->json([
            'status' => true,
            'message' => 'Order details',
            'data' => $order
        ]);
    }

    public function cancel($id)
    {
        $user_id = auth()->id();

        $order = $this->order
            ->with('items')
            ->where('user_id', $user_id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'status' => false,
                'message' => 'Order already cancelled'
            ]);
        }

        if ($order->status === 'delivered') {
            return response()->json([
                'status' => false,
                'message' => 'Delivered order cannot be cancelled'
            ]);
        }

        DB::transaction(function () use ($order) {

            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->qty);
            }

            $order->update([
                'status' => 'cancelled'
            ]);

            DB::table('transactions')
                ->where('order_id', $order->id)
                ->update([
                    'status' => 'cancelled'
                ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Order cancelled successfully'
        ]);
    }

    private function generateOrderNo()
    {
        do {
            $orderNo = 'AWC' . mt_rand(10000, 99999);
        } while (Order::where('order_no', $orderNo)->exists());

        return $orderNo;
    }

    public function invoice($id)
    {
        $user_id = auth()->id();

        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'Id is required'
            ], 404);
        }

        $order = $this->order
            ->with([
                'items' => function ($query) {
                    $query->select('id', 'order_id', 'product_id', 'qty', 'price', 'discount', 'final_price');
                },
                'items.product' => function ($query) {
                    $query->select('id', 'name', 'image', 'sku_code', 'hsn_code', 'barcode_base');
                }
            ])
            ->where('user_id', $user_id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $transaction = $this->transcation
            ->where('order_id', $order->id)
            ->latest('id')
            ->first();

        $user = auth()->user();

        $defaultAddress = $this->address
            ->where('id', $user->address_id)
            ->where('is_default', 1)
            ->first();

        if (!$defaultAddress) {
            $defaultAddress = $this->address
                ->where('user_id', $user_id)
                ->where('is_default', 1)
                ->first();
        }

        return response()->json([
            'status' => true,
            'message' => 'Invoice details',
            'data' => [
                'order' => [
                    'order_id' => $order->id,
                    'order_no' => $order->order_no,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'total_amount' => $order->total_amount,
                    'total_discount' => $order->total_discount,
                    'final_amount' => $order->final_amount,
                    'created_at' => $order->created_at,
                ],
                'items' => $order->items,
                'transaction' => $transaction,
                'address' => $defaultAddress,
            ]
        ]);
    }

    public function track($order_no)
    {
        if (empty($order_no)) {
            return response()->json([
                'status' => false,
                'message' => 'Order Number required'
            ], 400);
        }

        $order = $this->order
            ->where('order_no', $order_no)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $track = $this->track
            ->where('order_id', $order->id)
            ->orderBy('id', 'ASC')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Tracking details fetched successfully',
            'order' => $order,
            'tracking' => $track
        ], 200);
    }
}
