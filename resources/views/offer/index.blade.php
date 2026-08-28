@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Offers</h6>

                    <a href="{{ route('offer.add') }}" class="btn btn-primary btn-sm">
                        + Add
                    </a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Title</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Discount</th>
                                    <th>Min Order</th>
                                    <th>Expiry</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($offers as $key => $item)
                                    <tr>
                                        <td>{{ $offers->firstItem() + $key }}</td>

                                        <td>{{ $item->title }}</td>

                                        <td>
                                            {{ $item->code ?? '-' }}
                                        </td>

                                        <td>
                                            {{ ucfirst($item->type) }}
                                        </td>

                                        <td>
                                            @if ($item->discount_type == 'percent')
                                                {{ $item->discount_value }}%
                                            @else
                                                ₹{{ $item->discount_value }}
                                            @endif
                                        </td>

                                        <td>₹{{ $item->min_order_amount }}</td>

                                        <td>
                                            {{ $item->expiry_date ?? '-' }}
                                        </td>

                                        <td>
                                            <select name="status" class="form-control text-xs font-weight-bold select_top">
                                                <option value="">Select</option>
                                                <option value="1" data-id="{{ $item->id }}"
                                                    {{ $item->status == 1 ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="0" data-id="{{ $item->id }}"
                                                    {{ $item->status == 0 ? 'selected' : '' }}>
                                                    InActive
                                                </option>
                                            </select>
                                        </td>

                                        <td>
                                            <a href="{{ route('offer.edit', $item->id) }}"
                                                class="text-secondary font-weight-bold text-xs">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                        <div class="mt-4">
                            {{ $offers->links('shared.pagination') }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $('.select_top').on('change', function() {

            var product_id = $(this).find(':selected').data('id');
            var value = $(this).val();

            // console.log(product_id, value);

            $.ajax({
                url: "{{ route('offer.status') }}",
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
    </script>
@endsection
