@extends('pos.layout.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('pos.bills') }}" class="text-sm font-semibold text-orange-700 hover:underline">← Back to bills</a>
                <h1 class="mt-2 text-2xl font-bold text-slate-800">Customer Self Order</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $order->order_number }} · {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a target="_blank" href="{{ route('pos.customer-orders.receipt', ['order' => $order, 'print' => 1]) }}" class="rounded-xl border border-orange-200 bg-white px-4 py-2.5 text-sm font-semibold text-orange-700 hover:bg-orange-50">Print receipt</a>
                <a href="{{ route('pos.customer-orders.receipt.download', $order) }}" class="rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-orange-700">Download receipt</a>
            </div>
        </div>

        @if(session('success'))<div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>@endif

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <section class="lg:col-span-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-bold text-slate-800">Order items</h2></div>
                <div class="divide-y divide-slate-100">@foreach($order->items as $item)<div class="flex items-center justify-between gap-4 px-5 py-4"><div><p class="font-semibold text-slate-800">{{ $item->product_name }}</p><p class="mt-1 text-xs text-slate-400">₹{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p></div><strong class="text-orange-700">₹{{ number_format($item->total, 2) }}</strong></div>@endforeach</div>
                <div class="flex justify-between border-t border-slate-200 px-5 py-4 text-lg font-bold"><span>Grand total</span><span class="text-orange-700">₹{{ number_format($order->grand_total, 2) }}</span></div>
            </section>

            <aside class="space-y-5">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><h2 class="font-bold text-slate-800">Customer</h2><p class="mt-3 text-sm font-semibold">{{ $order->customer_name }}</p>@if($order->customer_mobile)<p class="mt-1 text-sm text-slate-500">{{ $order->customer_mobile }}</p>@endif@if($order->customer_email)<p class="mt-1 break-all text-sm text-slate-500">{{ $order->customer_email }}</p>@endif</section>
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><h2 class="font-bold text-slate-800">Status</h2><div class="mt-3 flex justify-between text-sm"><span class="text-slate-500">Payment</span><span class="rounded-full bg-amber-50 px-2.5 py-1 font-semibold text-amber-700">{{ ucfirst($order->payment_status ?? 'pending') }}</span></div><div class="mt-3 flex justify-between text-sm"><span class="text-slate-500">Order type</span><span class="rounded-full bg-blue-50 px-2.5 py-1 font-semibold text-blue-700">{{ ($order->fulfillment_type ?? 'packing') === 'dine_in' ? 'Dine In' : 'Packing' }}</span></div><div class="mt-3 flex justify-between text-sm"><span class="text-slate-500">Order</span><span class="rounded-full {{ strtolower($order->status) === 'delivered' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }} px-2.5 py-1 font-semibold">{{ ucfirst($order->status) }}</span></div>@if(strtolower($order->status) !== 'delivered')<form method="POST" action="{{ route('pos.customer-orders.delivered', $order) }}" class="mt-5">@csrf<button onclick="return confirm('Mark this customer order as delivered?')" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">Mark Delivered</button></form>@endif</section>
            </aside>
        </div>
    </div>
</div>
@endsection
