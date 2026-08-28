@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Orders</h6>
                    <a href="{{ route('order.export') }}" class="btn btn-success btn-sm">Export</a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0 datatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Sr No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Order Number</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Barcode</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Invoice</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Order Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Delhivery Boy</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Discount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Total Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Payment Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        payment method</th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Payment Id</th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Date</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                @foreach ($orders as $key => $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            <i class="fas fa-bars text-secondary me-2 drag-handle" style="cursor:move"></i>
                                            {{ $orders->firstItem() + $key }}
                                        </td>

                                        <td>
                                            <a href="{{ route('users.order_details', $item->id) }}"
                                                class="text-info font-weight-bold text-xs">
                                                {{ $item->order_no }}
                                            </a>
                                        </td>

                                        <td>
                                            @if ($item->barcode != null)
                                                <img src="{{ $item->barcode }}" alt="Barcode" width="150">
                                            @else
                                                <a href="{{ route('order.barcode', $item->id) }}"
                                                    class="text-info font-weight-bold text-xs">
                                                    Generate
                                                </a>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('order.invoice', $item->id) }}"
                                                class="text-info font-weight-bold text-xs">
                                                Invoice
                                            </a>
                                        </td>

                                        <td>
                                            @if($item->status !== 'Cancelled' && $item->status !== 'Delivered')
                                            <select name="status"
                                                class="form-control text-xs font-weight-bold select_stock">
                                                <option value="">Select</option>
                                                @foreach ($status as $val)
                                                    <option value="{{ $val->name }}" data-id="{{ $item->id }}"
                                                        {{ $item->status == $val->name ? 'selected' : '' }}>
                                                        {{ $val->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @elseif($item->status === 'Delivered')
                                            <span class="text-success font-weight-bold text-xs">Delivered</span>
                                            @else
                                            <span class="text-danger font-weight-bold text-xs">Cancelled</span>
                                            @endif
                                        </td>

                                        <td>
                                            <select name="status"
                                                class="form-control text-xs font-weight-bold delhivery_boy_assign">
                                                <option value="">Select</option>
                                                @foreach ($supplier as $val)
                                                    <option value="{{ $val->id }}" data-id="{{ $item->id }}"
                                                        {{ $item->delhivery_boy_id == $val->id ? 'selected' : '' }}>
                                                        {{ $val->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->total_amount }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->total_discount ?? '-' }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->final_amount }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->status }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->payment_status }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->payment_method }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->payment_id }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ date_formet($item->created_at) }}
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

    <script>
        $(document).ready(function() {

            $('.select_stock').on('change', function() {

                var product_id = $(this).find(':selected').data('id');
                var value = $(this).val();

                $.ajax({
                    url: "{{ route('order.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: product_id,
                        status: value,
                    },
                    success: function(res) {
                        console.log(res.message);
                        showNotification('success', res.message || 'Status updated successfully');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        showNotification('danger', 'Something went wrong');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $('.delhivery_boy_assign').on('change', function() {

                var product_id = $(this).find(':selected').data('id');
                var value = $(this).val();

                $.ajax({
                    url: "{{ route('order.delivery_boy') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: product_id,
                        status: value,
                    },
                    success: function(res) {
                        console.log(res.message);
                        showNotification('success', res.message || 'Status updated successfully');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        showNotification('danger', 'Something went wrong');
                    }
                });
            });
        });
    </script>
@endsection
