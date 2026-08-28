@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <!-- Header -->
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Sliders</h6>

                    <div class="d-flex gap-2">
                        <a href="{{ route('slider.export') }}" class="btn btn-success btn-sm">
                            Export
                        </a>
                        <a href="{{ route('slider.add') }}" class="btn btn-primary btn-sm">
                            + Add Slider
                        </a>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Name
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Image
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($sliders as $key => $item)
                                    <tr>
                                        <!-- Sr No -->
                                        <td class="text-xs">
                                            {{ $sliders->firstItem() + $key }}
                                        </td>

                                        <!-- Name -->
                                        <td class="text-xs font-weight-bold">
                                            {{ $item->name ?? $item->title }}
                                        </td>

                                        <!-- Image -->
                                        <td>
                                            <img src="{{ asset($item->image) }}" class="rounded border"
                                                style="height:50px;width:100px;object-fit:cover;">
                                        </td>

                                        <td>
                                            <select class="form-control text-xs status-change" data-id="{{ $item->id }}"
                                                data-url="{{ route('slider.status') }}">
                                                <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>
                                                    Inactive
                                                </option>
                                            </select>
                                        </td>

                                        <!-- Actions -->
                                        <td>
                                            <a href="{{ route('slider.edit', $item->id) }}" class="text-primary me-2">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button class="btn btn-link text-danger p-0 delete-btn"
                                                data-id="{{ $item->id }}"
                                                data-url="{{ route('slider.delete', $item->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No sliders found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4 px-3">
                            {{ $sliders->links('shared.pagination') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.status-change').on('change', function() {

                var product_id = $(this).attr('data-id');
                var value = $(this).val();

                $.ajax({
                    url: "{{ route('slider.status') }}",
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
