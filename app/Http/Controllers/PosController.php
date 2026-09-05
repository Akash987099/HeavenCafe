<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PosOrder;
use App\Models\Pos;
use App\Models\Role;
use App\Models\PosOrderDetail;
use App\Models\Policy;
use App\Models\StoreProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PosController extends Controller
{
    protected $product;
    protected $order;
    protected $orderdetails;
    protected $pos;
    protected $user;
    protected $policy;

    public function __construct(){
        $this->product = new Product();
        $this->order   = new PosOrder();
        $this->orderdetails = new PosOrderDetail();
        $this->pos = new Pos();
        $this->policy = new Policy();
        $this->user = Auth::guard('pos')->user();
    }
    
    public function index()
    {
        $user = Auth::guard('pos')->user();

        // Manager ke staff IDs
        $staffIDs = $this->staffID($user->id);

        // Manager + uske staff
        if ($user->role == 1) {

            $userIDs = array_merge(
                [$user->id],
                $staffIDs
            );

        } else {

            // Staff sirf apne orders dekhe
            $userIDs = [$user->id];
        }


        // Today's Orders
        $todayorder = $this->order
            ->whereIn('pos_user_id', $userIDs)
            ->whereDate('created_at', today())
            ->count();


        // This Week Orders
        $thisweek = $this->order
            ->whereIn('pos_user_id', $userIDs)
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();


        // This Month Orders
        $thismonth = $this->order
            ->whereIn('pos_user_id', $userIDs)
            ->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])
            ->count();


        // Total Orders
        $totalorder = $this->order
            ->whereIn('pos_user_id', $userIDs)
            ->count();

        // Store stock is shown in ascending quantity order so low-stock products appear first.
        $storeProducts = StoreProduct::query()
            ->with(['product:id,name,image,sku_product_id'])
            ->where('store_id', $user->store_id)
            ->orderBy('qty')
            ->paginate(20, ['*'], 'stock_page');

        $lowStockCount = StoreProduct::query()
            ->where('store_id', $user->store_id)
            ->where('qty', '<=', 5)
            ->count();


        return view(
            'pos.index',
            compact(
                'todayorder',
                'thisweek',
                'thismonth',
                'totalorder',
                'storeProducts',
                'lowStockCount'
            )
        );
    }

    public function order(){
        return view('pos/order');
    }

    public function search(Request $request)
    {
        $search = trim($request->sku_product_id ?? '');

        if ($search === '') {

            return response()->json([
                'success' => false,
                'products' => [],
                'message' => 'Search value is required.',
            ]);
        }


        $products = $this->product
            ->where('status', 'active')
            ->where(function ($query) use ($search) {

                $query->where(
                    'sku_product_id',
                    'LIKE',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'barcode_base',
                    'LIKE',
                    '%' . $search . '%'
                );

            })
            ->select([
                'id',
                'sku_product_id',
                'barcode_base',
                'name',
                'image',
                'price',
            ])
            ->limit(20)
            ->get();


        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function save(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Products From URL
        |--------------------------------------------------------------------------
        */

        $cart = $request->input('cart');


        if (empty($cart)) {

            return redirect()
                ->route('pos.order')
                ->with('error', 'No products selected.');

        }


        /*
        |--------------------------------------------------------------------------
        | Decode Cart
        |--------------------------------------------------------------------------
        */

        $cart = json_decode(
            base64_decode($cart),
            true
        );


        if (!is_array($cart) || empty($cart)) {

            return redirect()
                ->route('pos.order')
                ->with('error', 'Invalid order data.');

        }


        /*
        |--------------------------------------------------------------------------
        | POS User
        |--------------------------------------------------------------------------
        */

        $posUser = Auth::guard('pos')->user();


        if (!$posUser) {

            return redirect()
                ->route('pos.login');

        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $subtotal = 0;


        foreach ($cart as $item) {

            $price = (float) ($item['price'] ?? 0);

            $quantity = (int) ($item['qty'] ?? 1);

            $subtotal += $price * $quantity;

        }


        $discount = 0;

        $grandTotal = $subtotal - $discount;


        /*
        |--------------------------------------------------------------------------
        | Save Order
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();


        try {

            /*
            |--------------------------------------------------------------------------
            | Generate Order Number
            |--------------------------------------------------------------------------
            */

            $orderNumber =
                'POS-' .
                date('YmdHis') .
                '-' .
                random_int(100, 999);


            /*
            |--------------------------------------------------------------------------
            | Main Order
            |--------------------------------------------------------------------------
            */

            $order = PosOrder::create([

                'pos_user_id' => $posUser->id,

                'order_number' => $orderNumber,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'grand_total' => $grandTotal,

                'status' => 'completed',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Order Details
            |--------------------------------------------------------------------------
            */

            foreach ($cart as $item) {

                $price = (float) ($item['price'] ?? 0);

                $quantity = (int) ($item['qty'] ?? 1);

                $total = $price * $quantity;


                PosOrderDetail::create([

                    'pos_order_id' => $order->id,

                    'product_id' => $item['id'],

                    'product_name' => $item['name'],

                    'price' => $price,

                    'quantity' => $quantity,

                    'total' => $total,

                ]);

            }


            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Go To Order View
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('pos.order.view', [
                    'id' => $order->id
                ]);

        } catch (\Throwable $e) {

            DB::rollBack();


            return redirect()
                ->route('pos.order')
                ->with(
                    'error',
                    'Order save failed: ' . $e->getMessage()
                );
        }
    }

     public function orderView($id)
    {
        $order = PosOrder::with('details')
            ->where('id', $id)
            ->where(
                'pos_user_id',
                Auth::guard('pos')->id()
            )
            ->firstOrFail();


        return view(
            'pos.order-view',
            compact('order')
        );
    }

    public function orderbill($id)
    {
        $user = Auth::guard('pos')->user();

        $query = PosOrder::with('details')
            ->where('id', $id);

        // Staff apna hi order dekh sakta hai
        if ($user->role == 2) {
            $query->where('pos_user_id', $user->id);
        }

        // IMPORTANT: result ko $order mein assign karo
        $order = $query->firstOrFail();

        return view('pos.order-bill', compact('order'));
    }

    public function payment(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Payment Data
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'customer_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'customer_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'required',
                'in:cash,card,upi',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Logged-in POS User
        |--------------------------------------------------------------------------
        */

        $posUserId = Auth::guard('pos')->id();

        if (!$posUserId) {

            return redirect()
                ->route('pos.login')
                ->with('error', 'POS session expired. Please login again.');
        }


        /*
        |--------------------------------------------------------------------------
        | Get Current Order
        |--------------------------------------------------------------------------
        |
        | Important:
        | Order sirf current logged-in POS user ka hi milega.
        |
        */

        $order = PosOrder::where('id', $id)
            ->where('pos_user_id', $posUserId)
            ->first();


        if (!$order) {

            return redirect()
                ->route('pos.order.bill', $id)
                ->with('error', 'Order not found.');
        }


        /*
        |--------------------------------------------------------------------------
        | Check Order Already Paid
        |--------------------------------------------------------------------------
        */

        if (
            isset($order->payment_status) &&
            $order->payment_status === 'paid'
        ) {

            return redirect()
                ->route('pos.order.view', $order->id)
                ->with('error', 'This order has already been paid.');
        }


        /*
        |--------------------------------------------------------------------------
        | Check Amount
        |--------------------------------------------------------------------------
        */

        $amount = (float) $validated['amount'];

        $grandTotal = (float) $order->grand_total;


        if (round($amount, 2) != round($grandTotal, 2)) {

            return back()
                ->withInput()
                ->withErrors([
                    'amount' => 'Payment amount must be ₹' .
                        number_format($grandTotal, 2),
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update Order
        |--------------------------------------------------------------------------
        */

        $order->customer_name = $validated['customer_name'];

        $order->customer_email =
            $validated['customer_email'] ?? null;

        $order->customer_phone =
            $validated['customer_phone'] ?? null;

        $order->payment_method =
            $validated['payment_method'];

        $order->payment_status = 'paid';

        $order->status = 'completed';

        $order->save();


        /*
        |--------------------------------------------------------------------------
        | Redirect To Bill
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('pos.order.bill', $order->id)
            ->with('success', 'Payment completed successfully.');
    }

    private function staffID($managerID)
    {
        return $this->pos
            ->where('user_id', $managerID)
            ->pluck('id')
            ->toArray();
    }

    public function bills()
    {
        $user = Auth::guard('pos')->user();

        $orders = PosOrder::with('details');

        if ($user->role == 1) {

            $staffIDs = $this->staffID($user->id);

            $userIDs = array_merge(
                [$user->id],
                $staffIDs
            );

            $orders->whereIn('pos_user_id', $userIDs);

        } else {

            $orders->where('pos_user_id', $user->id);
        }

        $orders = $orders
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('pos.bills', compact('orders'));
    }

    public function createRazorpayOrder($id)
    {
        $order = PosOrder::findOrFail($id);

        $amount = (float) $order->grand_total;

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment amount.'
            ], 422);
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int) round($amount * 100),
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);

        $order->update([
            'razorpay_order_id' => $razorpayOrder['id'],
            'payment_method' => 'upi',
        ]);

        return response()->json([
            'success' => true,
            'key' => config('services.razorpay.key'),
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'order_id' => $order->id,
        ]);
    }

    public function verifyRazorpayPayment(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'order_id' => 'required|integer',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $order = PosOrder::find($request->order_id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
                'order_id' => $request->order_id,
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Razorpay Order ID
        |--------------------------------------------------------------------------
        */

        if (
            empty($order->razorpay_order_id) ||
            $order->razorpay_order_id !== $request->razorpay_order_id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Razorpay order.',
            ], 400);
        }


        /*
        |--------------------------------------------------------------------------
        | Razorpay API
        |--------------------------------------------------------------------------
        */

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );


        /*
        |--------------------------------------------------------------------------
        | Verify Signature
        |--------------------------------------------------------------------------
        */

        try {

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $order->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'   => $request->razorpay_signature,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed.',
                'error' => $e->getMessage(),
            ], 400);
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Verified
        |--------------------------------------------------------------------------
        */

        $order->customer_name =
        $request->customer_name;

        $order->customer_email =
            $request->customer_email;

        $order->customer_phone =
            $request->customer_phone;

        $order->payment_method = 'upi';

        $order->razorpay_payment_id =
            $request->razorpay_payment_id;

        $order->razorpay_signature =
            $request->razorpay_signature;

        $order->status = 'completed';

        $order->payment_status = 'paid';


        /*
        |--------------------------------------------------------------------------
        | Save Order
        |--------------------------------------------------------------------------
        */

        $saved = $order->save();


        if (!$saved) {

            return response()->json([
                'success' => false,
                'message' => 'Payment verified but order update failed.',
            ], 500);
        }


        /*
        |--------------------------------------------------------------------------
        | Return Success
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'message' => 'Payment successful.',
            'redirect' => route('pos.order.bill', $order->id),
            'order_id' => $order->id,
            'payment_status' => $order->payment_status,
        ]);
    }


    // Sttafs

    public function staff(){
        $users = $this->pos
            ->with('roleMaster')
            ->where('user_id', Auth::guard('pos')->user()->id)
            ->paginate(20);
        return view('pos.staffs', compact('users'));
        
    }

    public function staffAdd(){
        $roles = Role::query()
            ->where('status', 1)
            ->where('id', '!=', 1)
            ->orderBy('role_name')
            ->get();

        return view('pos.staffs.add', compact('roles'));
    }

    public function staffSave(Request $request){
        // dd($request->all());
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:pos,mobile',
            'email'  => 'required|email|max:255|unique:pos,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', 'integer', 'not_in:1', Rule::exists('roles', 'id')->where('status', 1)],
        ]);

        $pos = $this->pos;
        $pos->name = $request->name;
        $pos->mobile = $request->mobile;
        $pos->email = $request->email;
        $pos->store_id = Auth::guard('pos')->user()->store_id;
        $pos->user_id = Auth::guard('pos')->user()->id;
        $pos->role = $request->role;
        $pos->staff_id = generateStaffId();
        $pos->password = Hash::make($request->password);

        $save = $pos->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function staffView($id)
    {
        $staff = Pos::with(['store', 'roleMaster'])
            ->where('user_id', Auth::guard('pos')->id())
            ->findOrFail($id);

        return view('pos.staffs.view', compact('staff'));
    }

    public function staffEdit($id)
    {
        $staff = Pos::query()
            ->where('user_id', Auth::guard('pos')->id())
            ->findOrFail($id);
        $roles = Role::query()
            ->where('status', 1)
            ->where('id', '!=', 1)
            ->orderBy('role_name')
            ->get();

        return view('pos.staffs.edit', compact('staff', 'roles'));
    }

    public function staffUpdate(Request $request, $id)
    {
        $staff = Pos::query()
            ->where('user_id', Auth::guard('pos')->id())
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:pos,mobile,' . $staff->id,
            'email' => 'required|email|max:255|unique:pos,email,' . $staff->id,
            'role' => ['required', 'integer', 'not_in:1', Rule::exists('roles', 'id')->where('status', 1)],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $staff->name = $request->name;
        $staff->mobile = $request->mobile;
        $staff->email = $request->email;
        $staff->role = $request->role;

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        return redirect()->route('pos.staff')->with('success', 'Staff updated successfully.');
    }

    public function kitchenOrders()
    {
        $this->ensureKitchenUser();

        $confirmedOrders = PosOrder::query()
            ->with('details')
            ->where('status', 'completed')
            ->where('payment_status', 'paid');

        $latestOrder = (clone $confirmedOrders)->latest('id')->first();
        $orders = $confirmedOrders->latest('id')->paginate(20);

        return view('pos.kitchen.index', compact('orders', 'latestOrder'));
    }

    public function kitchenOrderView($id)
    {
        $this->ensureKitchenUser();

        $order = PosOrder::query()
            ->with('details')
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->findOrFail($id);

        return view('pos.kitchen.view', compact('order'));
    }

    private function ensureKitchenUser(): void
    {
        $roleName = strtolower(trim((string) optional(Auth::guard('pos')->user()->roleMaster)->role_name));

        abort_unless(in_array($roleName, ['kitchen', 'rasoi'], true), 403);
    }

    public function policy(){
        $policy = $this->policy->paginate(20);
        return view('pos.policy', compact('policy'));
    }

}
