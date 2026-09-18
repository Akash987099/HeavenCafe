@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center category-card-header">
                    <div class="category-card-header-top">
                        <h6 class="m-0">Policy</h6>

                        <div class="d-flex gap-2 flex-wrap">

                            <a href="{{ route('policy.add') }}" class="btn btn-primary btn-sm category-card-add-btn">
                                + Add
                            </a>
                        </div>
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
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        File</th>
                                    <th class="text-secondary opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policy as $key => $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            <i class="fas fa-bars text-secondary me-2 drag-handle"></i>
                                            {{ $policy->firstItem() + $key }}
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->name }}</p>
                                        </td>

                                        <td>
                                            <a href="{{ asset($item->pdf) }}" target="_blank">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $item->pdf }}
                                                </p>
                                            </a>
                                        </td>

                                        <td>
                                            <button class="btn btn-link text-danger p-0 delete-btn"
                                                data-id="{{ $item->id }}"
                                                data-url="{{ route('policy.delete', $item->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <div class="mt-4">
                            {{ $policy->links('shared.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
       
        $("#sortable-category").sortable({
            handle: '.drag-handle',
            update: function() {

                let positions = [];

                $("#sortable-category tr").each(function() {
                    positions.push($(this).data('id'));
                });

                $.ajax({
                    url: "{{ route('category.updatePosition') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        positions: positions
                    },
                    success: function(res) {
                        console.log(res.message);
                        showNotification('success', res.message || 'Position updated successfully');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        showNotification('danger', 'Failed to update position');
                    }
                });
            }
        });

        $('.select_top').on('change', function() {

                var product_id = $(this).find(':selected').data('id');
                var value = $(this).val();

                // console.log(product_id, value);

                $.ajax({
                    url: "{{ route('category.status') }}",
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
