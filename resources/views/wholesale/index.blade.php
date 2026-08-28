@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Users</h6>
                    <a href="{{ route('users.export') }}" class="btn btn-success btn-sm">Export</a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0 datatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Sr No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Action</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Wallet Amounts</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Phone</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                @foreach ($users as $key => $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            <i class="fas fa-bars text-secondary me-2 drag-handle" style="cursor:move"></i>
                                            {{ $users->firstItem() + $key }}
                                        </td>

                                        <td>
                                            <a href="{{ route('users.cart', $item->id) }}"
                                                class="text-success font-weight-bold text-xs">
                                                Cart
                                            </a>
                                            &nbsp;
                                            <a href="{{ route('users.order', $item->id) }}"
                                                class="text-info font-weight-bold text-xs">
                                                Order
                                            </a>
                                        </td>

                                        <td>
                                            <select name="status" class="form-control text-xs font-weight-bold select_top">
                                                <option value="">Select</option>
                                                <option value="active" data-id="{{ $item->id }}"
                                                    {{ $item->status == 'active' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="inactive" data-id="{{ $item->id }}"
                                                    {{ $item->status == 'inactive' ? 'selected' : '' }}>
                                                    InActive
                                                </option>
                                            </select>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->email }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->wallet_points }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->phone }}</p>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $users->links('shared.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select_top').on('change', function() {

                var product_id = $(this).find(':selected').data('id');
                var value = $(this).val();

                // console.log(product_id, value);

                $.ajax({
                    url: "{{ route('users.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: product_id,
                        status: value,
                    },
                    success: function(res) {
                        // console.log(res.message);
                        showNotification('success', res.message || 'Status updated successfully');
                    },
                    error: function(xhr) {
                        // console.log(xhr.responseText);
                        showNotification('danger', 'Something went wrong');
                    }
                });
            });
        });
    </script>
@endsection
