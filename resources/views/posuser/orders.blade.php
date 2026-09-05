@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center category-card-header">
                    <div class="category-card-header-top">
                        <h6 class="m-0">Pos Orders</h6>
                    </div>

                    <input type="text" id="searchInput" placeholder="Search..."
                        class="py-2 border border-gray-300 rounded-lg h-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white card-header-search">
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0 datatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Sr No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Bill Number</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Customer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Phone</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Payment</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key => $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            {{ $orders->firstItem() + $key }}
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->order_number; }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->customer_name }}
                                            @if($item->customer_email)

                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $item->customer_email }}
                                            </p>

                                        @endif
                                        </p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->customer_phone }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">₹{{ number_format($item->grand_total, 2) }}</p>
                                        </td>

                                        <td class="px-5 py-4 text-center">

                                    @php
                                        $paymentClass = match(strtolower($item->payment_method ?? '')) {
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
                                        {{ ucfirst($item->payment_method ?? 'N/A') }}
                                    </span>

                                </td>

                                <td class="px-5 py-4 text-center">

                                    @if(strtolower($item->status) === 'completed')

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

                                    @elseif(strtolower($item->status) === 'pending')

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
                                            {{ ucfirst($item->status ?? 'Unknown') }}
                                        </span>

                                    @endif

                                </td>

                                <td
                                    class="px-5 py-4
                                           text-center
                                           whitespace-nowrap"
                                >

                                    <p class="text-sm text-slate-600">

                                        {{ $item->created_at->format('d M Y') }}

                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">

                                        {{ $item->created_at->format('h:i A') }}

                                    </p>

                                </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                            <a
                                        href="{{ route('pos_user.order_view', $item->id) }}"
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
                                    </p>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <div class="mt-4">
                            {{ $orders->links('shared.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
