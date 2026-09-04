@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="max-w-7xl mx-auto">

        {{-- Dashboard Header --}}
        <div class="mb-6">

            <p class="text-slate-500 mt-1">
                Manage your orders and sales from one place.
            </p>

        </div>


        {{-- Order Summary --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">


            {{-- Today's Orders --}}
            <div
                class="glass rounded-2xl p-5
                       border border-white/70
                       shadow-lg shadow-slate-200/40"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Today's Orders
                        </p>

                        <h3
                            class="text-3xl font-bold
                                   text-[#0F172A] mt-2"
                        >
                            {{ $todayorder }}
                        </h3>

                    </div>


                    <div
                        class="w-12 h-12 rounded-xl
                               bg-emerald-100
                               text-[#128C7E]
                               flex items-center justify-center"
                    >
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </div>

                </div>


                <p class="text-xs text-slate-400 mt-4">
                    Orders created today
                </p>

            </div>


            {{-- This Week --}}
            <div
                class="glass rounded-2xl p-5
                       border border-white/70
                       shadow-lg shadow-slate-200/40"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            This Week
                        </p>

                        <h3
                            class="text-3xl font-bold
                                   text-[#0F172A] mt-2"
                        >
                            {{ $thisweek }}
                        </h3>

                    </div>


                    <div
                        class="w-12 h-12 rounded-xl
                               bg-blue-100
                               text-blue-600
                               flex items-center justify-center"
                    >
                        <i class="fas fa-calendar-week text-xl"></i>
                    </div>

                </div>


                <p class="text-xs text-slate-400 mt-4">
                    Orders created this week
                </p>

            </div>


            {{-- This Month --}}
            <div
                class="glass rounded-2xl p-5
                       border border-white/70
                       shadow-lg shadow-slate-200/40"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            This Month
                        </p>

                        <h3
                            class="text-3xl font-bold
                                   text-[#0F172A] mt-2"
                        >
                            {{ $thismonth }}
                        </h3>

                    </div>


                    <div
                        class="w-12 h-12 rounded-xl
                               bg-purple-100
                               text-purple-600
                               flex items-center justify-center"
                    >
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>

                </div>


                <p class="text-xs text-slate-400 mt-4">
                    Orders created this month
                </p>

            </div>


            {{-- Total Orders --}}
            <div
                class="glass rounded-2xl p-5
                       border border-white/70
                       shadow-lg shadow-slate-200/40"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Total Orders
                        </p>

                        <h3
                            class="text-3xl font-bold
                                   text-[#0F172A] mt-2"
                        >
                            {{ $totalorder }}
                        </h3>

                    </div>


                    <div
                        class="w-12 h-12 rounded-xl
                               bg-orange-100
                               text-orange-600
                               flex items-center justify-center"
                    >
                        <i class="fas fa-receipt text-xl"></i>
                    </div>

                </div>


                <p class="text-xs text-slate-400 mt-4">
                    All POS orders
                </p>

            </div>

        </div>


        {{-- Store Stock --}}
        <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Store Product Stock</h2>
                    <p class="text-sm text-slate-400 mt-1">Products are sorted by lowest quantity first.</p>
                </div>
                <span class="px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold">
                    {{ $lowStockCount }} low-stock item{{ $lowStockCount === 1 ? '' : 's' }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Product</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">SKU</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Store Qty</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Stock Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($storeProducts as $storeProduct)
                            @php($product = $storeProduct->product)
                            @php($quantity = (int) $storeProduct->qty)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ optional($product)->image ? asset($product->image) : asset('images/no-product.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/no-product.png') }}';" class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-200" alt="{{ optional($product)->name ?? 'Product' }}">
                                        <span class="font-semibold text-slate-800">{{ optional($product)->name ?? 'Product removed' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ optional($product)->sku_product_id ?: '-' }}</td>
                                <td class="px-5 py-3 text-right font-bold {{ $quantity <= 5 ? 'text-rose-600' : 'text-slate-800' }}">{{ $quantity }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($quantity === 0)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">Out of Stock</span>
                                    @elseif($quantity <= 5)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">Low Stock</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-12 text-center text-slate-400">No store products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($storeProducts->hasPages())
                <div class="px-5 py-4 border-t border-slate-200">{{ $storeProducts->links() }}</div>
            @endif
        </div>


        {{-- Quick Actions --}}
        <div class="mt-6">

            <div
                class="bg-white rounded-2xl
                       border border-slate-200
                       shadow-sm p-5"
            >

                <h2 class="text-lg font-bold text-slate-800">
                    Quick Actions
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Quickly manage your POS operations.
                </p>


                <div
                    class="grid grid-cols-1 sm:grid-cols-2
                           gap-3 mt-5"
                >

                    <a
                        href="{{ route('pos.order') }}"
                        class="flex items-center gap-3
                               p-4 rounded-xl
                               border border-slate-200
                               hover:border-[#128C7E]
                               hover:bg-emerald-50/50
                               transition"
                    >

                        <div
                            class="w-10 h-10 rounded-lg
                                   bg-emerald-100
                                   text-[#128C7E]
                                   flex items-center justify-center"
                        >
                            <i class="fas fa-plus"></i>
                        </div>

                        <div>

                            <p class="text-sm font-semibold text-slate-800">
                                Create New Order
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                Create a new POS bill
                            </p>

                        </div>

                    </a>


                    <a
                        href="{{ route('pos.bills') }}"
                        class="flex items-center gap-3
                               p-4 rounded-xl
                               border border-slate-200
                               hover:border-[#128C7E]
                               hover:bg-emerald-50/50
                               transition"
                    >

                        <div
                            class="w-10 h-10 rounded-lg
                                   bg-blue-100
                                   text-blue-600
                                   flex items-center justify-center"
                        >
                            <i class="fas fa-receipt"></i>
                        </div>

                        <div>

                            <p class="text-sm font-semibold text-slate-800">
                                View Bills
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                View all POS transactions
                            </p>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
