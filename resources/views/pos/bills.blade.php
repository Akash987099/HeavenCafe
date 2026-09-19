@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800">
                        Bills
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        View all POS transactions and generated bills.
                    </p>
                </div>

                <form method="GET" action="{{ route('pos.bills') }}" class="flex flex-1 max-w-md items-center gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="search" name="search" value="{{ $search }}" placeholder="Search order, customer or product..." class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-sm outline-none focus:border-[#128C7E]">
                    </div>
                    <button type="submit" class="h-10 rounded-xl bg-slate-800 px-3 text-xs font-semibold text-white hover:bg-slate-700">Search</button>
                    @if($search)
                        <a href="{{ route('pos.bills') }}" class="text-xs font-semibold text-slate-500 hover:text-rose-600">Clear</a>
                    @endif
                </form>

                <a
                    href="{{ route('pos.order') }}"
                    class="inline-flex items-center gap-2
                           px-4 py-2.5 rounded-xl
                           bg-[#128C7E] text-white
                           text-sm font-semibold
                           hover:bg-[#0f766e] transition"
                >
                    <i class="fas fa-plus"></i>
                    New Bill
                </a>

            </div>

        </div>


        {{-- Customer self-orders --}}
        <div class="mb-6 bg-white rounded-2xl border border-orange-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-orange-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Customer Self Orders</h2>
                    <p class="text-xs text-slate-400 mt-1">Orders placed directly by customers for this store.</p>
                </div>
                <span class="inline-flex px-3 py-1.5 rounded-full bg-orange-50 text-orange-700 text-xs font-semibold">{{ $customerOrders->total() }} orders</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-orange-50"><tr><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Order no.</th><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Customer</th><th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Items</th><th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Amount</th><th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Payment</th><th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Order type</th><th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Order status</th><th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Date</th><th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Action</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($customerOrders as $customerOrder)
                            <tr class="hover:bg-orange-50/40 transition">
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $customerOrder->order_number }}<p class="mt-1 text-[11px] font-normal text-orange-600">Customer self order</p></td>
                                <td class="px-5 py-4"><p class="font-semibold text-slate-800">{{ $customerOrder->customer_name }}</p><p class="mt-1 text-xs text-slate-400">{{ $customerOrder->customer_mobile ?: ($customerOrder->customer_email ?: '-') }}</p></td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-1">
                                        @foreach($customerOrder->items->take(2) as $item)
                                            <span class="text-xs text-slate-600 max-w-[220px] truncate">{{ $item->product_name }} × {{ $item->quantity }}</span>
                                        @endforeach
                                        @if($customerOrder->items->count() > 2)
                                            <span class="text-xs text-orange-600 font-medium">+ {{ $customerOrder->items->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right font-bold text-orange-700">₹{{ number_format($customerOrder->grand_total, 2) }}</td>
                                <td class="px-5 py-4 text-center"><span class="inline-flex px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold">{{ ucfirst($customerOrder->payment_status ?? 'pending') }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="inline-flex px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">{{ ($customerOrder->fulfillment_type ?? 'packing') === 'dine_in' ? 'Dine In' : 'Packing' }}</span></td>
                                <td class="px-5 py-4 text-center"><span class="inline-flex px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">{{ ucfirst($customerOrder->status) }}</span></td>
                                <td class="px-5 py-4 text-center whitespace-nowrap"><p class="text-sm text-slate-600">{{ $customerOrder->created_at->format('d M Y') }}</p><p class="mt-1 text-xs text-slate-400">{{ $customerOrder->created_at->format('h:i A') }}</p></td>
                                <td class="px-5 py-4 text-center"><a href="{{ route('pos.customer-orders.view', $customerOrder) }}" class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-orange-600 hover:text-white">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-5 py-8 text-center text-sm text-slate-400">No customer self-orders for this store yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($customerOrders->hasPages())
                <div class="border-t border-slate-100 px-5 py-3">{{ $customerOrders->links('shared.pagination') }}</div>
            @endif
        </div>

        {{-- Bills Card --}}
        <div
            class="bg-white rounded-2xl
                   border border-slate-200
                   shadow-sm overflow-hidden"
        >

            {{-- Table Header --}}
            <div
                class="px-5 py-4
                       border-b border-slate-200
                       flex items-center justify-between"
            >

                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        POS Transactions
                    </h2>

                    <p class="text-xs text-slate-400 mt-1">
                        {{ $orders->total() }} total bills
                    </p>
                </div>

            </div>


            {{-- Desktop Table --}}
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th
                                class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                #
                            </th>

                            <th
                                class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Bill Number
                            </th>

                            <th
                                class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Customer
                            </th>

                            <th
                                class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Phone
                            </th>

                            <th
                                class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Products
                            </th>

                            <th
                                class="px-5 py-3 text-right
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Amount
                            </th>

                            <th
                                class="px-5 py-3 text-center
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Payment
                            </th>

                            <th
                                class="px-5 py-3 text-center
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Order Type
                            </th>

                            <th
                                class="px-5 py-3 text-center
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Status
                            </th>

                            <th
                                class="px-5 py-3 text-center
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Date
                            </th>

                            <th
                                class="px-5 py-3 text-center
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($orders as $key => $order)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- Number --}}
                                <td class="px-5 py-4 text-slate-500">

                                    {{ $orders->firstItem() + $key }}

                                </td>


                                {{-- Bill Number --}}
                                <td class="px-5 py-4">

                                    <span
                                        class="font-semibold
                                               text-slate-800"
                                    >
                                        {{ $order->order_number }}
                                    </span>

                                </td>


                                {{-- Customer --}}
                                <td class="px-5 py-4">

                                    <div>

                                        <p class="font-semibold text-slate-800">
                                            {{ $order->customer_name ?: 'Walk-in Customer' }}
                                        </p>

                                        @if($order->customer_email)

                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $order->customer_email }}
                                            </p>

                                        @endif

                                    </div>

                                </td>


                                {{-- Phone --}}
                                <td class="px-5 py-4 text-slate-600">

                                    {{ $order->customer_phone ?: '-' }}

                                </td>


                                {{-- Products --}}
                                <td class="px-5 py-4">

                                    <div class="flex flex-col gap-1">

                                        @foreach($order->details->take(2) as $detail)

                                            <span
                                                class="text-xs text-slate-600
                                                       max-w-[220px] truncate"
                                            >
                                                {{ $detail->product_name }}
                                                × {{ $detail->quantity }}
                                            </span>

                                        @endforeach


                                        @if($order->details->count() > 2)

                                            <span
                                                class="text-xs
                                                       text-[#128C7E]
                                                       font-medium"
                                            >
                                                + {{ $order->details->count() - 2 }}
                                                more
                                            </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- Amount --}}
                                <td class="px-5 py-4 text-right">

                                    <span
                                        class="font-bold
                                               text-[#128C7E]"
                                    >
                                        ₹{{ number_format($order->grand_total, 2) }}
                                    </span>

                                </td>


                                {{-- Payment --}}
                                <td class="px-5 py-4 text-center">

                                    @php
                                        $paymentClass = match(strtolower($order->payment_method ?? '')) {
                                            'cash' => 'bg-emerald-50 text-emerald-600',
                                            'card' => 'bg-blue-50 text-blue-600',
                                            'upi' => 'bg-purple-50 text-purple-600',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp

                                    <span
                                        class="inline-flex
                                               px-3 py-1.5
                                               rounded-full
                                               text-xs font-semibold
                                               {{ $paymentClass }}"
                                    >
                                        {{ ucfirst($order->payment_method ?? 'N/A') }}
                                    </span>

                                </td>


                                {{-- Dine In / Takeaway --}}
                                <td class="px-5 py-4 text-center">

                                    @if(($order->fulfillment_type ?? 'dine_in') === 'packing')
                                        <span class="inline-flex px-3 py-1.5 rounded-full bg-orange-50 text-orange-600 text-xs font-semibold">
                                            Packing / Takeaway
                                        </span>
                                    @else
                                        <span class="inline-flex px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold">
                                            Dine In
                                        </span>
                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-4 text-center">

                                    @if(strtolower($order->status) === 'completed')

                                        <span
                                            class="inline-flex
                                                   px-3 py-1.5
                                                   rounded-full
                                                   bg-emerald-50
                                                   text-emerald-600
                                                   text-xs font-semibold"
                                        >
                                            Completed
                                        </span>

                                    @elseif(strtolower($order->status) === 'pending')

                                        <span
                                            class="inline-flex
                                                   px-3 py-1.5
                                                   rounded-full
                                                   bg-amber-50
                                                   text-amber-600
                                                   text-xs font-semibold"
                                        >
                                            Pending
                                        </span>

                                    @else

                                        <span
                                            class="inline-flex
                                                   px-3 py-1.5
                                                   rounded-full
                                                   bg-slate-100
                                                   text-slate-600
                                                   text-xs font-semibold"
                                        >
                                            {{ ucfirst($order->status ?? 'Unknown') }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Date --}}
                                <td
                                    class="px-5 py-4
                                           text-center
                                           whitespace-nowrap"
                                >

                                    <p class="text-sm text-slate-600">

                                        {{ $order->created_at->format('d M Y') }}

                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">

                                        {{ $order->created_at->format('h:i A') }}

                                    </p>

                                </td>


                                {{-- Action --}}
                                <td class="px-5 py-4 text-center">
                                @if(empty($order->payment_method))
                                    <a
                                        href="{{ route('pos.order.view', $order->id) }}"
                                        class="inline-flex
                                               w-9 h-9
                                               items-center
                                               justify-center
                                               rounded-lg
                                               bg-slate-100
                                               text-slate-600
                                               hover:bg-[#128C7E]
                                               hover:text-white
                                               transition"
                                        title="View Bill"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @else
                                
                                <a
                                        href="{{ route('pos.order.bill', $order->id) }}"
                                        class="inline-flex
                                               w-9 h-9
                                               items-center
                                               justify-center
                                               rounded-lg
                                               bg-slate-100
                                               text-slate-600
                                               hover:bg-[#128C7E]
                                               hover:text-white
                                               transition"
                                        title="View Bill"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="px-5 py-16 text-center"
                                >

                                    <div
                                        class="w-16 h-16 mx-auto
                                               rounded-2xl
                                               bg-slate-100
                                               flex items-center
                                               justify-center"
                                    >
                                        <i
                                            class="fas fa-receipt
                                                   text-2xl
                                                   text-slate-400"
                                        ></i>
                                    </div>

                                    <h3
                                        class="mt-4
                                               text-lg
                                               font-semibold
                                               text-slate-700"
                                    >
                                        No Bills Found
                                    </h3>

                                    <p
                                        class="text-sm
                                               text-slate-400
                                               mt-1"
                                    >
                                        No POS transactions have been created yet.
                                    </p>

                                    <a
                                        href="{{ route('pos.order') }}"
                                        class="inline-flex
                                               items-center
                                               gap-2
                                               mt-5
                                               px-4 py-2.5
                                               rounded-xl
                                               bg-[#128C7E]
                                               text-white
                                               text-sm
                                               font-semibold"
                                    >
                                        <i class="fas fa-plus"></i>
                                        Create First Bill
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($orders->hasPages())

                <div
                    class="px-5 py-4
                           border-t border-slate-200
                           flex items-center
                           justify-between"
                >

                    <p class="text-xs text-slate-400">

                        Showing
                        {{ $orders->firstItem() }}
                        -
                        {{ $orders->lastItem() }}
                        of
                        {{ $orders->total() }}

                    </p>

                    <div>

                        {{ $orders->links() }}

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
