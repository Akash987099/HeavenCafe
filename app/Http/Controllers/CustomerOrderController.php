<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Mail\CustomerOrderConfirmation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CustomerOrderController extends Controller
{
    public function selectStore()
    {
        $stores = Store::query()->orderBy('name')->get(['id', 'name', 'city', 'address', 'image']);

        return view('customer-order.select-store', compact('stores'));
    }

    public function start(Request $request)
    {
        $validated = $request->validate(['store_id' => ['required', 'integer', 'exists:store,id']]);

        $request->session()->put('customer_order_store_id', (int) $validated['store_id']);
        $request->session()->forget('customer_order_id');

        return redirect()->route('customer-order.menu');
    }

    public function menu(Request $request)
    {
        $store = $this->selectedStore($request);
        if (!$store) {
            return redirect()->route('customer-order.select-store')
                ->with('error', 'Please select a store first.');
        }

        $categories = Category::query()
            ->where('status', 1)
            ->whereIn('id', Product::query()
                ->where('status', 'active')
                ->whereIn('id', StoreProduct::query()
                    ->where('store_id', $store->id)
                    ->where('qty', '>', 0)
                    ->pluck('product_id'))
                ->pluck('category')
                ->filter())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('customer-order.menu', compact('store', 'categories'));
    }

    public function products(Request $request)
    {
        $store = $this->selectedStore($request);
        abort_unless($store, 403);

        $validated = $request->validate([
            'category' => ['nullable', 'integer'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $products = StoreProduct::query()
            ->join('products', 'products.id', '=', 'store_products.product_id')
            ->where('store_products.store_id', $store->id)
            ->where('store_products.qty', '>', 0)
            ->where('products.status', 'active')
            ->when($validated['category'] ?? null, fn ($query, $category) => $query->where('products.category', $category))
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('products.name', 'like', '%' . $search . '%')
                        ->orWhere('products.sku_product_id', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('products.name')
            ->select([
                'products.id', 'products.name', 'products.image', 'products.sku_product_id',
                'products.price', 'store_products.qty as available_qty',
            ])
            ->paginate(20);

        return response()->json([
            'products' => $products->items(),
            'next_page' => $products->hasMorePages() ? $products->currentPage() + 1 : null,
        ]);
    }

    public function place(Request $request)
    {
        $store = $this->selectedStore($request);
        if (!$store) {
            return redirect()->route('customer-order.select-store');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_mobile' => ['nullable', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email:rfc,dns', 'max:191'],
            'fulfillment_type' => ['required', 'in:packing,dine_in'],
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.id' => ['required', 'integer'],
            'cart.*.qty' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $cart = collect($validated['cart'])->groupBy('id')
            ->map(fn ($items, $id) => ['id' => (int) $id, 'qty' => $items->sum('qty')])
            ->values();

        try {
            $order = DB::transaction(function () use ($cart, $store, $validated) {
                $stockRows = StoreProduct::query()
                    ->where('store_id', $store->id)
                    ->whereIn('product_id', $cart->pluck('id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('product_id');

                $products = Product::query()
                    ->where('status', 'active')
                    ->whereIn('id', $cart->pluck('id'))
                    ->lockForUpdate()
                    ->get(['id', 'name', 'price'])
                    ->keyBy('id');

                if ($stockRows->count() !== $cart->count() || $products->count() !== $cart->count()) {
                    throw new \RuntimeException('One or more selected products are no longer available.');
                }

                foreach ($cart as $item) {
                    $stock = $stockRows->get($item['id']);
                    if ((int) $stock->qty < $item['qty']) {
                        throw new \RuntimeException($products->get($item['id'])->name . ' has only ' . (int) $stock->qty . ' item(s) left.');
                    }
                }

                $subtotal = $cart->sum(fn ($item) => (float) $products->get($item['id'])->price * $item['qty']);
                $order = CustomerOrder::create([
                    'store_id' => $store->id,
                    'order_number' => 'WEB-' . now()->format('YmdHis') . '-' . random_int(100, 999),
                    'customer_name' => $validated['customer_name'],
                    'customer_mobile' => $validated['customer_mobile'] ?? null,
                    'customer_email' => $validated['customer_email'] ?? null,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'fulfillment_type' => $validated['fulfillment_type'],
                    'subtotal' => $subtotal,
                    'grand_total' => $subtotal,
                ]);

                foreach ($cart as $item) {
                    $product = $products->get($item['id']);
                    CustomerOrderItem::create([
                        'customer_order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item['qty'],
                        'total' => (float) $product->price * $item['qty'],
                    ]);
                    $stockRows->get($item['id'])->decrement('qty', $item['qty']);
                }

                return $order;
            });
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Your order could not be placed. Please try again.');
        }

        if ($order->customer_email) {
            try {
                $order->load('store', 'items');
                Mail::to($order->customer_email)->send(new CustomerOrderConfirmation($order));
                $request->session()->flash('order_email_sent', $order->customer_email);
            } catch (\Throwable $exception) {
                // An email delivery error must not undo a successfully saved order.
                report($exception);
            }
        }

        $request->session()->put('customer_order_id', $order->id);
        return redirect()->route('customer-order.success', $order);
    }

    public function success(Request $request, CustomerOrder $order)
    {
        abort_unless(
            (int) $request->session()->get('customer_order_store_id') === (int) $order->store_id
            && (int) $request->session()->get('customer_order_id') === (int) $order->id,
            404
        );

        $order->load('store', 'items');
        return view('customer-order.success-v2', compact('order'));
    }

    public function receipt(Request $request, CustomerOrder $order)
    {
        $this->ensureOrderBelongsToSession($request, $order);
        $order->load('store', 'items');

        return view('customer-order.receipt-v2', [
            'order' => $order,
            'autoPrint' => $request->boolean('print'),
        ]);
    }

    public function downloadReceipt(Request $request, CustomerOrder $order)
    {
        $this->ensureOrderBelongsToSession($request, $order);
        $order->load('store', 'items');

        return Pdf::loadView('customer-order.receipt-pdf-v2', compact('order'))
            ->setPaper('a5')
            ->download($order->order_number . '-receipt.pdf');
    }

    private function selectedStore(Request $request): ?Store
    {
        $storeId = $request->session()->get('customer_order_store_id');
        return $storeId ? Store::find($storeId) : null;
    }

    private function ensureOrderBelongsToSession(Request $request, CustomerOrder $order): void
    {
        abort_unless(
            (int) $request->session()->get('customer_order_store_id') === (int) $order->store_id
            && (int) $request->session()->get('customer_order_id') === (int) $order->id,
            404
        );
    }
}
