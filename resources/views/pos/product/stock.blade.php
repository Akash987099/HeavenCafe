@extends('pos.layout.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6"><h1 class="text-2xl font-bold text-slate-800">My Store Products</h1><p class="text-sm text-slate-500 mt-1">Products and quantities assigned to your store after delivery.</p></div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50"><tr><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Product</th><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">SKU</th><th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Quantity</th><th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($storeProducts as $storeProduct)@php($product = $storeProduct->product)@php($quantity = (int) $storeProduct->qty)<tr class="hover:bg-slate-50"><td class="px-5 py-3"><div class="flex items-center gap-3"><img src="{{ optional($product)->image ? asset($product->image) : asset('images/no-product.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/no-product.png') }}';" class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-200"><span class="font-semibold text-slate-800">{{ optional($product)->name ?? 'Product removed' }}</span></div></td><td class="px-5 py-3 text-slate-500">{{ optional($product)->sku_product_id ?: '-' }}</td><td class="px-5 py-3 text-right font-bold {{ $quantity <= 5 ? 'text-rose-600' : 'text-slate-800' }}">{{ $quantity }}</td><td class="px-5 py-3 text-center">@if($quantity === 0)<span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">Out of Stock</span>@elseif($quantity <= 5)<span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">Low Stock</span>@else<span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">In Stock</span>@endif</td></tr>@empty<tr><td colspan="4" class="px-5 py-12 text-center text-slate-400">No products have been delivered to this store yet.</td></tr>@endforelse</tbody></table></div>
            @if($storeProducts->hasPages())<div class="px-5 py-4 border-t border-slate-200">{{ $storeProducts->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
