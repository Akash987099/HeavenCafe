@extends('layout.app')

@section('content')

@php
    $isDelivered = (int) $order->status === 2;
@endphp

<div class="row">
    <div class="col-12">
        <div class="card mb-4">

            {{-- Header --}}
            <div class="card-header pb-0 d-flex justify-content-between align-items-start">

                <div>
                    <a href="{{ route('pos_user.store-order') }}" class="text-sm text-primary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Store Orders
                    </a>

                    <h6 class="mt-2 mb-1">
                        {{ $order->order_number }}
                    </h6>

                    <p class="text-xs text-secondary mb-0">
                        Created {{ $order->created_at->format('d M Y, h:i A') }}
                    </p>
                </div>

                <div class="text-end">

                    @if($isDelivered)

                        <span class="badge badge-sm bg-gradient-success">
                            Delivered
                        </span>

                        <br>

                        <a href="{{ route('pos_user.store-order.invoice', $order) }}"
                           class="btn btn-sm btn-outline-success mt-2">
                            <i class="fas fa-download me-1"></i>
                            Download Invoice
                        </a>

                    @else

                        <span class="badge badge-sm bg-gradient-warning">
                            Processing
                        </span>

                    @endif

                </div>

            </div>

            {{-- Body --}}
            <div class="card-body">

                {{-- Error Message --}}
                @if(session('error'))
                    <div class="alert alert-danger text-white">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success text-white">
                        {{ session('success') }}
                    </div>
                @endif


                {{-- Order Information --}}
                <div class="row mb-4">

                    {{-- Destination Store --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">

                            <p class="text-xs text-secondary text-uppercase mb-1">
                                Destination Store
                            </p>

                            <h6 class="mb-1">
                                {{ optional($order->store)->name ?? '-' }}
                            </h6>

                            <p class="text-xs text-secondary mb-0">
                                {{ optional($order->store)->address ?? '' }}
                            </p>

                        </div>
                    </div>


                    {{-- Ordered By --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">

                            <p class="text-xs text-secondary text-uppercase mb-1">
                                Ordered By
                            </p>

                            <h6 class="mb-1">
                                {{ optional($order->posUser)->name ?? '-' }}
                            </h6>

                            <p class="text-xs text-secondary mb-0">
                                {{ optional($order->posUser)->email ?? '' }}
                            </p>

                        </div>
                    </div>


                    {{-- Order Amount --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">

                            <p class="text-xs text-secondary text-uppercase mb-1">
                                Order Amount
                            </p>

                            <h5 class="text-success mb-1">
                                ₹{{ number_format($order->grand_total, 2) }}
                            </h5>

                            <p class="text-xs text-secondary mb-0">
                                {{ $order->items->sum('quantity') }} total quantity
                            </p>

                        </div>
                    </div>

                </div>


                {{-- Order Items Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h6 class="mb-0">
                        Order Items
                    </h6>

                    @if(!$isDelivered)

                        <form method="POST"
                              action="{{ route('pos_user.store-order.status', $order) }}">

                            @csrf

                            <input type="hidden"
                                   name="status"
                                   value="2">

                            <button type="submit"
                                    class="btn btn-sm btn-success mb-0"
                                    onclick="return confirm('Mark this order as delivered and add stock to the store?')">

                                <i class="fas fa-truck me-1"></i>
                                Mark Delivered

                            </button>

                        </form>

                    @endif

                </div>


                {{-- Order Items Table --}}
                <div class="table-responsive">

                    <table class="table align-items-center mb-0">

                        <thead>
                            <tr>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Product
                                </th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    SKU
                                </th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                    Price
                                </th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                    Qty
                                </th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                    Total
                                </th>

                            </tr>
                        </thead>


                        <tbody>

                            @foreach($order->items as $item)

                                <tr>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $item->product_name }}
                                        </p>
                                    </td>

                                    <td>
                                        <p class="text-xs text-secondary mb-0">
                                            {{ optional($item->product)->sku_product_id ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="text-end">
                                        <p class="text-xs mb-0">
                                            ₹{{ number_format($item->price, 2) }}
                                        </p>
                                    </td>

                                    <td class="text-end">
                                        <p class="text-xs mb-0">
                                            {{ $item->quantity }}
                                        </p>
                                    </td>

                                    <td class="text-end">
                                        <p class="text-xs font-weight-bold text-success mb-0">
                                            ₹{{ number_format($item->total, 2) }}
                                        </p>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot>

                            <tr>

                                <td colspan="4" class="text-end">
                                    <strong>Grand Total</strong>
                                </td>

                                <td class="text-end">
                                    <strong class="text-success">
                                        ₹{{ number_format($order->grand_total, 2) }}
                                    </strong>
                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection