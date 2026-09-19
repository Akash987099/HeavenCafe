@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="max-w-7xl mx-auto">

        {{-- Dashboard Header --}}
        <div class="mb-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-orange-600">Live POS dashboard</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-800">Today’s business overview</h1>
                </div>
                <a href="{{ route('pos.order') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-700"><i class="fas fa-plus"></i> New order</a>
            </div>
            <p class="text-slate-500 mt-1">
                {{ now()->format('l, d M Y') }} · Manage your orders and sales from one place.
            </p>
        </div>

        <form method="GET" action="{{ route('pos.index') }}" class="mb-5 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-end">
            <div class="flex-1"><label for="from" class="mb-1 block text-xs font-semibold text-slate-500">From date</label><input id="from" name="from" type="date" value="{{ $selectedFrom }}" max="{{ today()->toDateString() }}" class="h-10 w-full rounded-lg border border-slate-200 px-3 text-sm text-slate-700 outline-none focus:border-orange-500"></div>
            <div class="flex-1"><label for="to" class="mb-1 block text-xs font-semibold text-slate-500">To date</label><input id="to" name="to" type="date" value="{{ $selectedTo }}" max="{{ today()->toDateString() }}" class="h-10 w-full rounded-lg border border-slate-200 px-3 text-sm text-slate-700 outline-none focus:border-orange-500"></div>
            <button type="submit" class="h-10 rounded-lg bg-orange-600 px-5 text-sm font-semibold text-white transition hover:bg-orange-700"><i class="fas fa-filter mr-2"></i>Apply filter</button>
            <a href="{{ route('pos.index') }}" class="h-10 rounded-lg border border-slate-200 px-4 py-2.5 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
        </form>

        {{-- Date-wise revenue and sales BI --}}
        @php($chartLinePoints = implode(' ', $dailyChartPoints))
        <section class="mb-6">
            <div id="salesMetrics" class="grid grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm"><div class="flex justify-between gap-3"><div><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Selected revenue</p><p class="mt-2 text-2xl font-bold text-slate-800">₹{{ number_format($todayRevenue, 2) }}</p></div><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-600"><i class="fas fa-indian-rupee-sign"></i></span></div><p class="mt-4 text-xs {{ $revenueChange !== null && $revenueChange < 0 ? 'text-rose-500' : 'text-emerald-600' }}"><i class="fas {{ $revenueChange !== null && $revenueChange < 0 ? 'fa-arrow-trend-down' : 'fa-arrow-trend-up' }} mr-1"></i>{{ $revenueChange === null ? 'No previous-range comparison yet' : number_format(abs($revenueChange), 1) . '% vs previous range' }}</p></div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex justify-between gap-3"><div><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Paid sales</p><p class="mt-2 text-2xl font-bold text-slate-800">{{ $todaySales }}</p></div><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i class="fas fa-bag-shopping"></i></span></div><p class="mt-4 text-xs text-slate-400">Completed orders in this range</p></div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex justify-between gap-3"><div><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Average bill</p><p class="mt-2 text-2xl font-bold text-slate-800">₹{{ number_format($todayAverageSale, 2) }}</p></div><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i class="fas fa-chart-pie"></i></span></div><p class="mt-4 text-xs text-slate-400">Revenue per paid order</p></div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex justify-between gap-3"><div><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Awaiting payment</p><p class="mt-2 text-2xl font-bold text-slate-800">{{ $pendingOrders }}</p></div><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fas fa-clock"></i></span></div><p class="mt-4 text-xs text-slate-400">Open bills in this range</p></div>
            </div>

            <div class="mt-5 grid grid-cols-1 xl:grid-cols-3 gap-5">
                <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between"><div><h2 class="font-bold text-slate-800">Date-wise revenue trend</h2><p class="mt-1 text-xs text-slate-400">Completed sales for the selected date range</p></div><span class="rounded-lg bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700">{{ \Illuminate\Support\Carbon::parse($selectedFrom)->format('d M') }} - {{ \Illuminate\Support\Carbon::parse($selectedTo)->format('d M') }}</span></div>
                    <div class="mt-4 overflow-x-auto"><svg viewBox="0 0 700 250" class="h-56 min-w-[620px] w-full" role="img" aria-label="Date-wise revenue line chart"><defs><linearGradient id="revenueFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#2563eb" stop-opacity=".25"/><stop offset="100%" stop-color="#2563eb" stop-opacity="0"/></linearGradient></defs><line x1="36" y1="40" x2="664" y2="40" stroke="#e2e8f0"/><line x1="36" y1="125" x2="664" y2="125" stroke="#e2e8f0"/><line x1="36" y1="210" x2="664" y2="210" stroke="#e2e8f0"/><polygon points="36,210 {{ $chartLinePoints }} 664,210" fill="url(#revenueFill)"/><polyline points="{{ $chartLinePoints }}" fill="none" stroke="#2563eb" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>@foreach ($dailySales as $date => $amount) @php($point = $dailyChartPoints[$loop->index]) @php([$pointX, $pointY] = explode(',', $point)) <circle cx="{{ $pointX }}" cy="{{ $pointY }}" r="4" fill="#2563eb" stroke="white" stroke-width="2"><title>{{ \Illuminate\Support\Carbon::parse($date)->format('d M') }}: ₹{{ number_format($amount, 2) }}</title></circle><text x="{{ $pointX }}" y="234" text-anchor="middle" fill="#64748b" font-size="10">{{ \Illuminate\Support\Carbon::parse($date)->format('d M') }}</text>@endforeach</svg></div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><div><h2 class="font-bold text-slate-800">Recent paid sales</h2><p class="mt-1 text-xs text-slate-400">Latest completed transactions</p></div><a href="{{ route('pos.bills') }}" class="text-xs font-bold text-orange-600">All bills</a></div><div class="mt-3 divide-y divide-slate-100">@forelse ($recentSales as $sale)<div class="flex items-center justify-between py-2.5"><div><p class="text-xs font-semibold text-slate-700">{{ $sale->order_number }}</p><p class="mt-0.5 text-[11px] text-slate-400">{{ $sale->created_at->format('h:i A') }} · {{ ucfirst($sale->payment_method ?? 'Payment') }}</p></div><p class="text-sm font-bold text-slate-800">₹{{ number_format($sale->grand_total, 2) }}</p></div>@empty<div class="py-6 text-center text-xs text-slate-400"><i class="fas fa-chart-line mb-2 block text-lg text-slate-300"></i>No paid sales yet today.</div>@endforelse</div></div>
            </div>
        </section>

        <section class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between"><div><h2 class="font-bold text-slate-800">Payment mix</h2><p class="mt-1 text-xs text-slate-400">Revenue split by payment method</p></div><span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">Pie chart</span></div>
                <div class="mt-5 flex flex-col items-center gap-5 sm:flex-row sm:justify-center">
                    <div class="relative h-40 w-40 shrink-0 rounded-full" style="background: conic-gradient({{ $paymentGradient }});"><div class="absolute inset-7 flex flex-col items-center justify-center rounded-full bg-white text-center"><span class="text-[10px] font-semibold uppercase text-slate-400">Revenue</span><span class="mt-1 text-sm font-bold text-slate-800">₹{{ number_format($todayRevenue, 0) }}</span></div></div>
                    <div class="w-full max-w-xs space-y-3">@forelse ($paymentBreakdown as $method)<div class="flex items-center justify-between gap-3"><div class="flex min-w-0 items-center gap-2"><span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $method['color'] }}"></span><span class="truncate text-sm font-medium text-slate-600">{{ $method['label'] }}</span></div><span class="text-sm font-bold text-slate-800">{{ number_format($method['percent'], 1) }}%</span></div>@empty<div class="py-5 text-center text-sm text-slate-400">No paid sales in this date range.</div>@endforelse</div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between"><div><h2 class="font-bold text-slate-800">Range summary</h2><p class="mt-1 text-xs text-slate-400">Performance for the filtered period</p></div><i class="fas fa-chart-column text-xl text-orange-500"></i></div>
                <div class="mt-5 grid grid-cols-2 gap-3"><div class="rounded-xl bg-orange-50 p-4"><p class="text-xs font-semibold text-orange-700">Total revenue</p><p class="mt-1 text-xl font-bold text-slate-800">₹{{ number_format($todayRevenue, 2) }}</p></div><div class="rounded-xl bg-blue-50 p-4"><p class="text-xs font-semibold text-blue-700">Completed bills</p><p class="mt-1 text-xl font-bold text-slate-800">{{ $todaySales }}</p></div><div class="rounded-xl bg-emerald-50 p-4"><p class="text-xs font-semibold text-emerald-700">Average bill</p><p class="mt-1 text-xl font-bold text-slate-800">₹{{ number_format($todayAverageSale, 2) }}</p></div><div class="rounded-xl bg-amber-50 p-4"><p class="text-xs font-semibold text-amber-700">Pending bills</p><p class="mt-1 text-xl font-bold text-slate-800">{{ $pendingOrders }}</p></div></div>
            </div>
        </section>

        {{-- Order Summary --}}
        <div id="orderSummary" class="mt-5 grid grid-cols-2 xl:grid-cols-4 gap-4">


            {{-- Today's Orders --}}
            <div
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Today's Orders
                        </p>

                        <h3
                            class="text-2xl font-bold text-slate-800 mt-2"
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
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            This Week
                        </p>

                        <h3
                            class="text-2xl font-bold text-slate-800 mt-2"
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
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            This Month
                        </p>

                        <h3
                            class="text-2xl font-bold text-slate-800 mt-2"
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
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Total Orders
                        </p>

                        <h3
                            class="text-2xl font-bold text-slate-800 mt-2"
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


        <script>
            // Keep the existing order-count cards directly below the top sales metrics.
            document.addEventListener('DOMContentLoaded', function () {
                const salesMetrics = document.getElementById('salesMetrics');
                const orderSummary = document.getElementById('orderSummary');

                if (salesMetrics && orderSummary) {
                    salesMetrics.insertAdjacentElement('afterend', orderSummary);
                }
            });
        </script>

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
