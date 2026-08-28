@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center category-card-header">
                    <div class="category-card-header-top">
                        <h6 class="m-0">Products</h6>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal"
                                data-bs-target="#apiImportModal">Get Products By API</button>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#importModal">Import</button>
                            <a href="{{ route('product.export') }}" class="btn btn-success btn-sm">Export</a>
                            <a href="{{ route('product.add') }}" class="btn btn-primary btn-sm category-card-add-btn">
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
                                        Product Id</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Name</th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Summer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">In
                                        Stock</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Stock</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Add
                                        Combo</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Gallery</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Similar</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">A
                                        Plus</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Varient</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Action</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">SKU
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">HSN
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Price</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Actual Price</th>

                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                @foreach ($products as $key => $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            <i class="fas fa-bars text-secondary me-2 drag-handle" style="cursor:move"></i>
                                            {{ $products->firstItem() + $key }}
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->sku_product_id }}</p>
                                        </td>

                                        <td>
                                            <a href="{{ route('product.edit', $item->id) }}"
                                                class="text-xs font-weight-bold mb-0 text-info">
                                                {{ $item->name }}
                                            </a>
                                            {{-- <p class="text-xs font-weight-bold mb-0">{{ $item->name }}</p> --}}
                                        </td>

                                        <td>
                                            <select name="status"
                                                class="form-control text-xs font-weight-bold select_summer">
                                                <option value="">Select</option>
                                                @foreach ($summer as $key => $val)
                                                    <option value="{{ $val->id }}" data-id="{{ $item->id }}"
                                                        {{ $item->summer_id == $val->id ? 'selected' : '' }}>
                                                        {{ $val->name }}
                                                    </option>
                                                @endforeach
                                            </select>
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
                                            <select name="status"
                                                class="form-control text-xs font-weight-bold select_stock">
                                                <option value="">Select</option>
                                                <option value="active" data-id="{{ $item->id }}"
                                                    {{ $item->in_stock == '1' ? 'selected' : '' }}>
                                                    Stock
                                                </option>
                                                <option value="inactive" data-id="{{ $item->id }}"
                                                    {{ $item->in_stock == '0' ? 'selected' : '' }}>
                                                    out of stock
                                                </option>
                                            </select>
                                        </td>

                                        <td>
                                            <a href="{{ route('product.stock', $item->id) }}"
                                                class="text-xs font-weight-bold mb-0 text-info">
                                                Stock
                                            </a>
                                        </td>

                                        <td>
                                            @if ($item->product_type === 'single')
                                                <select name="status"
                                                    class="form-control text-xs font-weight-bold select_product_type">
                                                    <option value="single" data-id="{{ $item->id }}"
                                                        {{ $item->product_type == 'single' ? 'selected' : '' }}>
                                                        Single
                                                    </option>
                                                    <option value="combo" data-id="{{ $item->id }}"
                                                        {{ $item->product_type == 'combo' ? 'selected' : '' }}>
                                                        Combo
                                                    </option>
                                                </select>
                                            @else
                                                <a href="{{ route('combo.add', $item->id) }}"
                                                    class="text-xs font-weight-bold mb-0 text-info">
                                                    Combo
                                                </a>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('product.gallery', $item->id) }}"
                                                class="text-xs font-weight-bold mb-0 text-info">
                                                Gallery
                                            </a>
                                        </td>

                                        <td>
                                            <a href="{{ route('product.similar', $item->id) }}"
                                                class="text-xs font-weight-bold mb-0 text-info">
                                                Similar
                                            </a>
                                        </td>

                                        <td>
                                            <a href="{{ route('aplus.index', $item->id) }}"
                                                class="text-xs font-weight-bold mb-0 text-info">
                                                A Plus
                                            </a>
                                        </td>

                                        <td>
                                            <a href="{{ route('varient.add', $item->id) }}"
                                                class="text-xs font-weight-bold mb-0 text-info">
                                                Varient
                                            </a>
                                        </td>

                                        <td>
                                            <a href="{{ route('product.edit', $item->id) }}"
                                                class="text-xs font-weight-bold mb-0 text-info">
                                                Edit
                                            </a>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->sku_code }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->hsn_code }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->price }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->ac_price }}</p>
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $products->links('shared.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 border-bottom pb-3">
                            <label class="form-label d-block">Download Sample Format</label>
                            <p class="text-xs text-muted mb-2">Download this sample file to understand the correct data
                                format for importing.</p>
                            <a href="{{ route('product.sample.download') }}"
                                class="btn btn-info btn-sm text-white mb-0">Download Sample File</a>
                        </div>
                        <div class="mb-3 pt-2">
                            <label for="importFile" class="form-label">Upload Data File</label>
                            <input class="form-control" type="file" id="importFile" name="import_file" required
                                accept=".csv, .xlsx, .xls">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="apiImportModal" tabindex="-1" aria-labelledby="apiImportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="apiImportModalLabel">Get Products By API</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('product.import_api_products') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="client_id" class="form-label">Select Client</label>
                            <select name="client_id" id="client_id" class="form-control" required>
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}{{ $client->company_name ? ' - ' . $client->company_name : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-xs text-muted mb-0">
                            Selected client ke saath remote DDesire API products current products table me sync ho jayenge.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Fetch & Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('.select_summer').on('change', function() {

                var product_id = $(this).find(':selected').data('id');
                var value = $(this).val();

                // console.log(product_id, value);

                $.ajax({
                    url: "{{ route('product.summer_status') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: product_id,
                        status: value,
                    },
                    success: function(res) {
                        console.log(res.message);
                        showNotification('success', res.message || 'Successfully');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        showNotification('danger', 'Something went wrong');
                    }
                });
            });

            $('.select_top').on('change', function() {

                var product_id = $(this).find(':selected').data('id');
                var value = $(this).val();

                // console.log(product_id, value);

                $.ajax({
                    url: "{{ route('product.status') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: product_id,
                        status: value,
                    },
                    success: function(res) {
                        console.log(res.message);
                        showNotification('success', res.message || 'Successfully');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        showNotification('danger', 'Something went wrong');
                    }
                });
            });

            $('.select_stock').on('change', function() {

                var product_id = $(this).find(':selected').data('id');
                var value = $(this).val();

                // console.log(product_id, value);

                $.ajax({
                    url: "{{ route('product.select_stock') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: product_id,
                        status: value,
                    },
                    success: function(res) {
                        console.log(res.message);
                        showNotification('success', res.message || 'Successfully');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        showNotification('danger', 'Something went wrong');
                    }
                });
            });

            $('.select_product_type').on('change', function() {

                var product_id = $(this).find(':selected').data('id');
                var value = $(this).val();

                // console.log(product_id, value);

                $.ajax({
                    url: "{{ route('product.product_type') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: product_id,
                        status: value,
                    },
                    success: function(res) {
                        console.log(res.message);
                        showNotification('success', res.message || 'Successfully');
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
