@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Clients</h6>

                    <a href="{{ route('client.add') }}" class="btn btn-primary btn-sm">
                        + Add Client
                    </a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Client</th>
                                    <th>Contact</th>
                                    <th>Company</th>
                                    <th>Category</th>
                                    <th>Pickup / Return</th>
                                    <th>Extra Addresses</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $key => $item)
                                    <tr>
                                        <td>{{ $clients->firstItem() + $key }}</td>

                                        <td>
                                            <strong>{{ $item->name }}</strong><br>
                                            <small>{{ $item->client_id ?? 'N/A' }}</small>
                                        </td>

                                        <td>
                                            {{ $item->email }}<br>
                                            <small>{{ $item->phone }}</small>
                                        </td>

                                        <td>
                                            {{ $item->company_name ?? '-' }}<br>
                                            <small>GST: {{ $item->gst_number ?? '-' }}</small>
                                        </td>

                                        <td>{{ $item->category->name ?? '-' }}</td>

                                        <td>
                                            <strong>Pickup:</strong> {{ $item->pickup_city ?? '-' }},
                                            {{ $item->pickup_state ?? '-' }}<br>
                                            <small>{{ $item->pickup_pincode ?? '-' }}</small><br>
                                            <strong>Return:</strong> {{ $item->return_city ?? '-' }},
                                            {{ $item->return_state ?? '-' }}<br>
                                            <small>{{ $item->return_pincode ?? '-' }}</small>
                                        </td>

                                        <td>
                                            @forelse ($item->addresses as $address)
                                                <div class="mb-2">
                                                    <strong>{{ ucfirst($address->type) }}</strong><br>
                                                    <small>
                                                        {{ $address->address ?: '-' }},
                                                        {{ $address->city ?: '-' }},
                                                        {{ $address->state ?: '-' }} -
                                                        {{ $address->pincode ?: '-' }}
                                                    </small>
                                                </div>
                                            @empty
                                                <span>-</span>
                                            @endforelse
                                        </td>

                                        <td>
                                            <select name="status" class="form-control text-xs font-weight-bold client-status">
                                                <option value="1" data-id="{{ $item->id }}"
                                                    {{ $item->status == 1 ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="0" data-id="{{ $item->id }}"
                                                    {{ $item->status == 0 ? 'selected' : '' }}>
                                                    Inactive
                                                </option>
                                            </select>
                                        </td>

                                        <td>
                                            <a href="{{ route('client.edit', $item->id) }}"
                                                class="text-secondary font-weight-bold text-xs me-3">
                                                Edit
                                            </a>
                                            <a href="{{ route('client.delete', $item->id) }}"
                                                onclick="return confirm('Are you sure you want to delete this client?')"
                                                class="text-danger font-weight-bold text-xs">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No clients found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $clients->links('shared.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.client-status').on('change', function() {
            var clientId = $(this).find(':selected').data('id');
            var value = $(this).val();

            $.ajax({
                url: "{{ route('client.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: clientId,
                    status: value,
                },
                success: function(res) {
                    showNotification('success', res.message || 'Client status updated successfully');
                },
                error: function() {
                    showNotification('danger', 'Something went wrong');
                }
            });
        });
    </script>
@endsection
