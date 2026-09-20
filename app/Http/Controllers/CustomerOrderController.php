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

        if (!$this->payuIsConfigured()) {
            return back()->withInput()->with('error', 'Online payment is not configured yet. Please contact the store.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_mobile' => ['required', 'string', 'max:20'],
            'customer_email' => ['required', 'email:rfc,dns', 'max:191'],
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
                    'payment_gateway' => 'payu',
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

                $order->update([
                    // PayU txnid must be unique, short, and must not contain spaces.
                    'payu_txnid' => 'CO' . now()->format('ymdHis') . $order->id,
                ]);

                return $order;
            });
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', $exception instanceof \RuntimeException
                ? $exception->getMessage()
                : 'Your order could not be placed. Please try again.');
        }

        $request->session()->put('customer_order_id', $order->id);
        return view('customer-order.payu-redirect', [
            'paymentUrl' => rtrim(config('services.payu.base_url'), '/') . '/_payment',
            'payload' => $this->payuRequestPayload($order),
        ]);
    }

    public function payuCallback(Request $request)
    {
        $response = $request->all();
        $order = CustomerOrder::query()->where('payu_txnid', $request->input('txnid'))->firstOrFail();
        $isValid = $this->isValidPayuResponse($response)
            && hash_equals(number_format((float) $order->grand_total, 2, '.', ''), number_format((float) $request->input('amount'), 2, '.', ''))
            && hash_equals((string) $order->id, (string) $request->input('udf1'));
        $isSuccessful = $isValid && strtolower((string) $request->input('status')) === 'success';
        $sendEmail = false;

        DB::transaction(function () use ($order, $response, $isValid, $isSuccessful, &$sendEmail) {
            $order = CustomerOrder::query()->with('items')->lockForUpdate()->findOrFail($order->id);

            // Never change stock or payment state for a forged callback.
            if (!$isValid) {
                return;
            }

            if ((string) $order->payment_status === 'completed') {
                return;
            }

            $paymentData = [
                'payu_payment_id' => $response['mihpayid'] ?? null,
                'payment_method' => $response['mode'] ?? null,
                'payu_response' => $response,
            ];

            if ($isSuccessful) {
                $order->update($paymentData + ['payment_status' => 'completed']);
                $sendEmail = true;
                return;
            }

            // A verified failed payment must not leave stock reserved.
            if ((string) $order->payment_status === 'pending') {
                foreach ($order->items as $item) {
                    $stock = StoreProduct::query()
                        ->where('store_id', $order->store_id)
                        ->where('product_id', $item->product_id)
                        ->lockForUpdate()
                        ->first();
                    if ($stock) {
                        $stock->increment('qty', $item->quantity);
                    }
                }
                $order->update($paymentData + [
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);
            }
        });

        $order->refresh();

        $request->session()->put('customer_order_store_id', $order->store_id);
        $request->session()->put('customer_order_id', $order->id);

        if ($sendEmail && $order->customer_email) {
            try {
                $order->load('store', 'items');
                Mail::to($order->customer_email)->send(new CustomerOrderConfirmation($order));
                $request->session()->flash('order_email_sent', $order->customer_email);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

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

    private function payuIsConfigured(): bool
    {
        $key = (string) config('services.payu.key');
        $salt = (string) config('services.payu.salt');
        return $key !== '' && $salt !== '' && !str_starts_with($key, 'your_') && !str_starts_with($salt, 'your_');
    }

    private function payuRequestPayload(CustomerOrder $order): array
    {
        $key = (string) config('services.payu.key');
        $amount = number_format((float) $order->grand_total, 2, '.', '');
        $firstname = str_replace('|', ' ', trim($order->customer_name));
        $email = str_replace('|', ' ', trim($order->customer_email));
        $productInfo = 'Order ' . $order->order_number;
        $udf1 = (string) $order->id;
        $hashString = implode('|', [
            $key, $order->payu_txnid, $amount, $productInfo, $firstname, $email,
            // udf2-udf5, followed by the five empty fields in PayU's
            // `udf5||||||SALT` sequence.
            $udf1, '', '', '', '', '', '', '', '', '', config('services.payu.salt'),
        ]);

        return [
            'key' => $key,
            'txnid' => $order->payu_txnid,
            'amount' => $amount,
            'productinfo' => $productInfo,
            'firstname' => $firstname,
            'email' => $email,
            'phone' => $order->customer_mobile,
            'surl' => route('customer-order.payu.callback'),
            'furl' => route('customer-order.payu.callback'),
            'udf1' => $udf1,
            'udf2' => '', 'udf3' => '', 'udf4' => '', 'udf5' => '',
            'hash' => hash('sha512', $hashString),
        ];
    }

    private function isValidPayuResponse(array $response): bool
    {
        if (!$this->payuIsConfigured() || empty($response['hash'])) {
            return false;
        }

        $prefix = !empty($response['additionalCharges'])
            ? $response['additionalCharges'] . '|' : '';
        $reverseHash = $prefix . config('services.payu.salt') . '|' . ($response['status'] ?? '') . '||||||'
            . ($response['udf5'] ?? '') . '|' . ($response['udf4'] ?? '') . '|' . ($response['udf3'] ?? '') . '|'
            . ($response['udf2'] ?? '') . '|' . ($response['udf1'] ?? '') . '|' . ($response['email'] ?? '') . '|'
            . ($response['firstname'] ?? '') . '|' . ($response['productinfo'] ?? '') . '|'
            . ($response['amount'] ?? '') . '|' . ($response['txnid'] ?? '') . '|' . ($response['key'] ?? '');

        return hash_equals(hash('sha512', $reverseHash), (string) $response['hash']);
    }
}
