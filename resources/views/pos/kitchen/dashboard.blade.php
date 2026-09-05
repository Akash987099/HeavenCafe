@extends('pos.layout.app')

@section('content')
    <div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
        <div class="max-w-7xl mx-auto">
            @if ($latestOrder)
                <div class="mb-6 rounded-2xl border border-orange-200 bg-orange-50 p-4 flex items-center gap-4"><div class="w-11 h-11 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0 animate-pulse"><i class="fas fa-bell"></i></div><div class="flex-1"><p class="text-sm font-bold text-orange-800">New order alert: {{ $latestOrder->order_number }}</p><p class="text-xs text-orange-700 mt-1">{{ $latestOrder->details->pluck('product_name')->implode(', ') }}</p></div><a data-kitchen-order-id="{{ $latestOrder->id }}" href="{{ route('pos.kitchen.orders.view', $latestOrder->id) }}" class="text-sm font-semibold text-orange-700">Open</a></div>
            @else
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">No pending kitchen orders right now.</div>
            @endif
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"><div class="px-5 py-4 border-b border-slate-200 flex justify-between"><h2 class="font-bold text-slate-800">Pending Orders</h2><span class="text-xs text-slate-400">{{ $pendingOrders->total() }} orders</span></div><div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Order</th><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Items</th><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Time</th><th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Action</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse ($pendingOrders as $order)<tr class="hover:bg-slate-50"><td class="px-5 py-4 font-semibold text-slate-800">{{ $order->order_number }}</td><td class="px-5 py-4 text-slate-600">{{ $order->details->pluck('product_name')->implode(', ') }}</td><td class="px-5 py-4 text-slate-600">{{ $order->created_at?->format('d M, h:i A') }}</td><td class="px-5 py-4 text-right"><a data-kitchen-order-id="{{ $order->id }}" href="{{ route('pos.kitchen.orders.view', $order->id) }}" class="text-sm font-semibold text-[#128C7E]">Prepare</a></td></tr>@empty<tr><td colspan="4" class="px-5 py-16 text-center text-slate-400">No pending orders.</td></tr>@endforelse</tbody></table></div>@if ($pendingOrders->hasPages())<div class="p-4 border-t border-slate-200">{{ $pendingOrders->links() }}</div>@endif</div>
        </div>
    </div>
@endsection
