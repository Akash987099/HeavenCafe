@extends('pos.layout.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div><h1 class="text-2xl font-bold text-slate-800">Store Orders</h1><p class="text-sm text-slate-500 mt-1">Orders sent from the company inventory to this store.</p></div>
            <a href="{{ route('pos_product.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#128C7E] text-white text-sm font-semibold hover:bg-[#0f766e]"><i class="fas fa-plus"></i>New Store Order</a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200"><h2 class="text-lg font-bold text-slate-800">Order History</h2><p class="text-xs text-slate-400 mt-1">{{ $orders->total() }} total store orders</p></div>
            <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">#</th><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Order Number</th><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Items</th><th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Amount</th><th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th><th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Date</th><th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Action</th></tr></thead>
                <tbody class="divide-y divide-slate-100">@forelse($orders as $key => $order)@php($isDelivered = (int) $order->status === 2)<tr class="hover:bg-slate-50"><td class="px-5 py-4 text-slate-500">{{ $orders->firstItem() + $key }}</td><td class="px-5 py-4 font-semibold text-slate-800">{{ $order->order_number }}</td><td class="px-5 py-4 text-slate-600">{{ $order->items_count }} product{{ $order->items_count === 1 ? '' : 's' }}</td><td class="px-5 py-4 text-right font-bold text-[#128C7E]">₹{{ number_format($order->grand_total, 2) }}</td><td class="px-5 py-4 text-center"><span class="px-3 py-1 rounded-full text-xs font-semibold {{ $isDelivered ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $isDelivered ? 'Delivered' : 'Processing' }}</span></td><td class="px-5 py-4 text-center text-slate-600">{{ $order->created_at->format('d M Y, h:i A') }}</td><td class="px-5 py-4 text-center"><a href="{{ route('pos_product.orders.view', $order) }}" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-semibold bg-emerald-50 text-[#128C7E] hover:bg-emerald-100"><i class="fas fa-eye"></i>View</a></td></tr>@empty<tr><td colspan="7" class="px-5 py-14 text-center text-slate-400"><i class="fas fa-box-open text-3xl mb-3 block"></i>No store orders yet.</td></tr>@endforelse</tbody></table></div>
            @if($orders->hasPages())<div class="px-5 py-4 border-t border-slate-200">{{ $orders->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
