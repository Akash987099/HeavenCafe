<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order placed | The Heaven Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: Inter, sans-serif; }</style>
</head>
<body class="min-h-screen bg-orange-50 text-slate-800">
    <main class="max-w-lg mx-auto px-4 py-10">
        <section class="rounded-3xl bg-white p-7 shadow-sm border border-orange-100 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-3xl font-bold text-emerald-700">✓</div>
            <p class="mt-5 text-sm font-semibold uppercase tracking-wider text-orange-600">Order placed</p>
            <h1 class="mt-2 text-2xl font-extrabold">Thank you, {{ $order->customer_name }}!</h1>
            <p class="mt-3 text-sm text-slate-500">Your order has been sent to <strong>{{ $order->store->name }}</strong>.</p>
            @if(session('order_email_sent'))<p class="mt-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">Order confirmation sent to {{ session('order_email_sent') }}.</p>@endif

            <div class="mt-6 overflow-hidden rounded-2xl border border-dashed border-orange-200 text-left shadow-sm">
                <div class="bg-orange-600 px-5 py-4 text-white"><p class="text-xs font-semibold uppercase tracking-wider text-orange-100">Receipt preview</p><div class="mt-1 flex items-center justify-between"><strong>{{ $order->store->name }}</strong><span class="text-xs">{{ $order->order_number }}</span></div></div>
                <div class="bg-orange-50 p-5"><div class="flex justify-between text-xs text-slate-500"><span>{{ $order->created_at->format('d M Y, h:i A') }}</span><span>{{ $order->customer_name }}</span></div><div class="mt-3 flex justify-between rounded-lg bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-800"><span>Payment status</span><span>{{ strtoupper($order->payment_status ?? 'pending') }}</span></div>@foreach($order->items as $item)<div class="mt-3 flex justify-between text-sm"><span>{{ $item->product_name }} × {{ $item->quantity }}</span><span>₹{{ number_format($item->total, 2) }}</span></div>@endforeach<div class="mt-4 flex justify-between border-t border-orange-200 pt-4 font-bold"><span>Total</span><span class="text-orange-700">₹{{ number_format($order->grand_total, 2) }}</span></div></div>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <a target="_blank" href="{{ route('customer-order.receipt', ['order' => $order, 'print' => 1]) }}" class="rounded-xl border border-orange-200 bg-white px-4 py-3 text-sm font-semibold text-orange-700 hover:bg-orange-50">Print receipt</a>
                <a href="{{ route('customer-order.receipt.download', $order) }}" class="rounded-xl bg-orange-600 px-4 py-3 text-sm font-semibold text-white hover:bg-orange-700">Download receipt</a>
            </div>
            <a href="{{ route('customer-order.menu') }}" class="mt-5 inline-block text-sm font-semibold text-orange-700 hover:underline">Order more items</a>
        </section>
    </main>
</body>
</html>
