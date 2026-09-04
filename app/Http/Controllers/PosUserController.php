<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pos;
use App\Models\Store;
use Illuminate\Validation\Rule;
use App\Models\PosOrder;
use App\Models\PosOrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use App\Models\StoreOrder;
use App\Models\StoreOrderItem;
use App\Models\StoreProduct;
use Illuminate\Support\Facades\DB;

class PosUserController extends Controller
{
    protected $pos;
    protected $store;
    protected $product;
    protected $order;
    protected $orderdetails;

    public function __construct(){
        $this->pos = new Pos();
        $this->store = new Store();
        $this->product = new Product();
        $this->order   = new PosOrder();
        $this->orderdetails = new PosOrderDetail();
    }

    public function index(){
        $posuser = $this->pos
        ->with('store')
        ->where('role', 1)
        ->orderBy('id', 'desc')
        ->paginate(config('constants.pagination_limit'));
        return view('posuser.index', compact('posuser'));
    }

    public function add(){
        $store = $this->store->all();
        return view('posuser.add', compact('store'));
    }

    public function save(Request $request){
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:pos,mobile',
            'email'  => 'required|email|max:255|unique:pos,email',
            'store'  => 'required',
            'password' => 'required|string|min:6',
        ]);

        $pos = $this->pos;
        $pos->name = $request->name;
        $pos->mobile = $request->mobile;
        $pos->email = $request->email;
        $pos->store_id = $request->store;
        $pos->role = 1;
        $pos->staff_id = generateStaffId();
        $pos->password = Hash::make($request->password);

        $save = $pos->save();

        if ($save) {
            return redirect()->back()->with('success', 'Successfully!');
        }
        return redirect()->back()->with('error', 'Failed!');
    }

    public function edit($id){
        if (!$id) {
            return redirect()->back()->with('error', 'id not found!');
        }

        $pos = $this->pos->find($id);

        if (!$pos) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $store = $this->store->all();

        return view('posuser.edit', compact('pos', 'store'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:pos,id',
            'name'   => 'required|string|max:255',

            'mobile' => [
                'required',
                'digits:10',
                Rule::unique('pos', 'mobile')->ignore($request->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('pos', 'email')->ignore($request->id),
            ],

            'store'  => 'required|exists:store,id',
        ]);

        $pos = $this->pos->find($request->id);

        if (!$pos) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $pos->name = $request->name;
        $pos->mobile = $request->mobile;
        $pos->email = $request->email;
        $pos->store_id = $request->store;
        $pos->role = 1;


        if ($pos->save()) {
            return redirect()->back()->with('success', 'Category updated successfully!');
        }

        return redirect()->back()->with('error', 'Update failed!');
    }

    public function orders(){
        $orders = PosOrder::with('details')
                ->orderBy('id', 'desc')
                ->paginate(config('constants.pagination_limit'));

        return view('posuser.orders', compact('orders'));
    }

    public function orderView($id){
        $order = PosOrder::with('details')
            ->where('id', $id)
            ->firstOrFail();


        return view(
            'posuser.order-bill',
            compact('order')
        );
    }

    public function storeOrder(){
        $orders = StoreOrder::query()
            ->with(['store', 'posUser'])
            ->withCount('items')
            ->latest('id')
            ->paginate(config('constants.pagination_limit'));

        return view('posuser.store_order', compact('orders'));
    }

    public function storeOrderView(StoreOrder $order)
    {
        $order->load(['items.product', 'store', 'posUser']);

        return view('posuser.store_order_view', compact('order'));
    }

    public function updateStoreOrderStatus(Request $request, StoreOrder $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:2'],
        ]);

        try {
            DB::transaction(function () use ($order, $validated) {
                $order = StoreOrder::query()
                    ->with('items')
                    ->lockForUpdate()
                    ->findOrFail($order->id);

                // A delivered order must never be processed twice.
                if ((int) $order->status === 2) {
                    return;
                }

                foreach ($order->items as $item) {
                    $product = Product::query()
                        ->lockForUpdate()
                        ->findOrFail($item->product_id);

                    if ((int) $product->store_qty < $item->quantity) {
                        throw new \RuntimeException($product->name . ' has insufficient company stock for delivery.');
                    }

                    $storeProduct = StoreProduct::query()
                        ->where('store_id', $order->store_id)
                        ->where('product_id', $item->product_id)
                        ->lockForUpdate()
                        ->first();

                    if ($storeProduct) {
                        $storeProduct->increment('qty', $item->quantity);
                    } else {
                        StoreProduct::create([
                            'store_id' => $order->store_id,
                            'product_id' => $item->product_id,
                            'qty' => $item->quantity,
                        ]);
                    }

                    $product->decrement('store_qty', $item->quantity);
                }

                $order->update(['status' => $validated['status']]);
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Store order could not be marked as delivered.');
        }

        return back()->with('success', 'Store order delivered and stock added to the store successfully.');
    }

    public function downloadStoreOrderInvoice(StoreOrder $order)
    {
        abort_unless((int) $order->status === 2, 404);

        $order->load(['items.product', 'store', 'posUser']);

        return response()
            ->view('pos.product.orders.bill', compact('order'))
            ->header('Content-Disposition', 'attachment; filename="' . $order->order_number . '.html"');
    }
}
