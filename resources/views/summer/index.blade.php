@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header pb-0 d-flex justify-content-between align-items-center category-card-header">
                    <div class="category-card-header-top">
                        <h6 class="m-0">Summer</h6>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('summer.export') }}" class="btn btn-success btn-sm">Export</a>
                            <a href="{{ route('summer.add') }}" class="btn btn-primary btn-sm category-card-add-btn">
                                + Add
                            </a>
                        </div>
                    </div>

                    <input type="text" id="searchInput" placeholder="Search..."
                        class="form-control form-control-sm card-header-search">
                </div>

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
                                        Title
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="sortable-table">
                                @forelse ($summer as $key => $item)
                                    <tr data-id="{{ $item->id }}">

                                        <td class="text-xs">
                                            <i class="fas fa-bars text-secondary me-2 drag-handle" style="cursor:move"></i>
                                            {{ $summer->firstItem() + $key }}
                                        </td>

                                        <td class="text-xs font-weight-bold">
                                            {{ $item->name }}
                                        </td>

                                        <td class="text-xs">
                                            {{ $item->sub_name }}
                                        </td>

                                        <td>
                                            <select name="status" class="form-control text-xs font-weight-bold select_top">
                                                <option value="">Select</option>
                                                <option value="1" data-id="{{ $item->id }}"
                                                    {{ $item->status == '1' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="0" data-id="{{ $item->id }}"
                                                    {{ $item->status == '0' ? 'selected' : '' }}>
                                                    InActive
                                                </option>
                                            </select>
                                        </td>

                                        <td>
                                            <a href="{{ route('summer.edit', $item->id) }}" class="text-primary me-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No data found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4 px-3">
                            {{ $summer->links('shared.pagination') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#sortable-table").sortable({
            handle: '.drag-handle',
            update: function() {
                let positions = [];

                $("#sortable-table tr").each(function() {
                    positions.push($(this).data('id'));
                });

                $.ajax({
                    url: "{{ route('summer.updatePosition') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        positions: positions
                    },
                    success: function(res) {
                        console.log(res.message);
                    }
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select_top').on('change', function() {

                var product_id = $(this).find(':selected').data('id');
                var value = $(this).val();

                // console.log(product_id, value);

                $.ajax({
                    url: "{{ route('summer.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: product_id,
                        status: value,
                    },
                    success: function(res) {
                        console.log(res.message);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Something went wrong');
                    }
                });
            });
        });
    </script>
@endsection
