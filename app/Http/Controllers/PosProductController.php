<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreOrder;
use App\Models\StoreOrderItem;
use App\Models\StoreProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosProductController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->whereIn('id', Product::query()->where('is_store', 1)->pluck('category')->filter())
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('pos.product.index', compact('categories'));
    }

    public function products(Request $request)
    {
        $validated = $request->validate([
            'category' => ['nullable', 'integer'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $products = Product::query()
            ->where('is_store', 1)
            ->where('status', 'active')
            ->when($validated['category'] ?? null, function ($query, $category) {
                $query->where('category', $category);
            })
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('sku_product_id', 'like', '%' . $search . '%')
                        ->orWhere('barcode_base', 'like', '%' . $search . '%');
                });
            })
            ->latest('id')
            ->select(['id', 'name', 'image', 'sku_product_id', 'category', 'price', 'store_qty'])
            ->paginate(20);

        return response()->json([
            'products' => $products->items(),
            'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null,
        ]);
    }

    public function stock()
    {
        $storeProducts = StoreProduct::query()
            ->with(['product:id,name,image,sku_product_id'])
            ->where('store_id', Auth::guard('pos')->user()->store_id)
            ->orderBy('qty')
            ->paginate(20);

        return view('pos.product.stock', compact('storeProducts'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.id' => ['required', 'integer'],
            'cart.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $cart = collect($validated['cart'])
            ->groupBy('id')
            ->map(fn ($items, $id) => ['id' => (int) $id, 'qty' => $items->sum('qty')])
            ->values();

        try {
            $order = DB::transaction(function () use ($cart) {
                // Lock rows so two POS users cannot sell the same store quantity.
                $products = Product::query()
                    ->where('is_store', 1)
                    ->where('status', 'active')
                    ->whereIn('id', $cart->pluck('id'))
                    ->lockForUpdate()
                    ->get(['id', 'name', 'price', 'store_qty'])
                    ->keyBy('id');

                if ($products->count() !== $cart->count()) {
                    throw new \RuntimeException('One or more selected products are no longer available.');
                }

                foreach ($cart as $item) {
                    $product = $products->get($item['id']);

                    if ((int) $product->store_qty < $item['qty']) {
                        throw new \RuntimeException($product->name . ' only has ' . (int) $product->store_qty . ' item(s) available.');
                    }
                }

                $posUser = Auth::guard('pos')->user();
                $subtotal = $cart->sum(function ($item) use ($products) {
                    return ((float) $products->get($item['id'])->price) * $item['qty'];
                });

                $order = StoreOrder::create([
                    'pos_user_id' => Auth::guard('pos')->id(),
                    'store_id' => $posUser->store_id,
                    'order_number' => 'STORE-' . now()->format('YmdHis') . '-' . random_int(100, 999),
                    'status' => 1,
                    'subtotal' => $subtotal,
                    'grand_total' => $subtotal,
                ]);

                foreach ($cart as $item) {
                    $product = $products->get($item['id']);

                    StoreOrderItem::create([
                        'store_order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['qty'],
                        'price' => $product->price,
                        'total' => (float) $product->price * $item['qty'],
                    ]);

                }

                return $order;
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Store order could not be placed. Please verify the store order database columns.');
        }

        return redirect()->route('pos_product.orders.view', $order);
    }

    public function orders()
    {
        $orders = StoreOrder::query()
            ->withCount('items')
            ->where('store_id', Auth::guard('pos')->user()->store_id)
            ->latest('id')
            ->paginate(20);

        return view('pos.product.orders.index', compact('orders'));
    }

    public function orderView(StoreOrder $order)
    {
        abort_unless($order->store_id === Auth::guard('pos')->user()->store_id, 404);

        $this->loadInvoiceAmounts($order);

        return view('pos.product.orders.view', compact('order'));
    }

    public function downloadBill(StoreOrder $order)
    {
        abort_unless($order->store_id === Auth::guard('pos')->user()->store_id, 404);
        abort_unless((int) $order->status === 2, 404);

        $this->loadInvoiceAmounts($order);

        return response()
            ->view('pos.product.orders.bill', compact('order'))
            ->header('Content-Disposition', 'attachment; filename="' . $order->order_number . '.html"');
    }

    private function loadInvoiceAmounts(StoreOrder $order): void
    {
        $order->load(['items.product', 'store', 'posUser']);

        // Invoice values are read from the saved order-item columns, not products.price.
        $order->grand_total = $order->items->sum('total');
    }

}
