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
                    <div class="px-4 pt-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label for="bulk_brand" class="form-label text-xs font-weight-bold">Bulk Brand</label>
                                <select id="bulk_brand" class="form-control">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary w-100 mb-0" id="applyBulkBrand">Update
                                    Brand</button>
                            </div>
                            <div class="col-md-4">
                                <label for="bulk_summer" class="form-label text-xs font-weight-bold">Bulk Summer</label>
                                <select id="bulk_summer" class="form-control">
                                    <option value="">Select Summer</option>
                                    @foreach ($summer as $summerItem)
                                        <option value="{{ $summerItem->id }}">{{ $summerItem->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-info text-white w-100 mb-0"
                                    id="applyBulkSummer">Update Summer</button>
                            </div>

                            <div class="col-md-4">
                                <label for="bulk_tax" class="form-label text-xs font-weight-bold">Bulk Tax</label>
                                <select id="bulk_tax" class="form-control">
                                    <option value="">Select Tax</option>
                                    @foreach ($tax as $item)
                                        <option value="{{ $item->tax_value }}">{{ $item->tax_value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-info text-white w-100 mb-0" id="applyBulkTax">Update
                                    Summer</button>
                            </div>
                        </div>
                        <p class="text-xs text-muted mt-2 mb-0">Checkbox se products select karke brand ya summer ek saath
                            update kar sakte hain.</p>
                    </div>
                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0 datatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">
                                        <input type="checkbox" id="select-all-products">
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Sr No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Product Id</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Name</th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Barcode</th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Image</th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Summer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">In
                                        Stock</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Stock</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Tax</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Add
                                        Combo</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Gallery</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Plateform</th>
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
                                        <td class="ps-3">
                                            <input type="checkbox" class="product-checkbox" value="{{ $item->id }}">
                                        </td>
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
                                            <img src="{{ $item->barcode_base }}" alt="Barcode" width="150"
                                                class="barcode-preview cursor-pointer rounded border"
                                                style="cursor: pointer;">
                                        </td>
                                        
                                        <td>
                                            <img src="{{ asset($item->image) }}" alt="Barcode" width="150"
                                                class="cursor-pointer rounded border"
                                                style="cursor: pointer;">
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
                                            <select name="status"
                                                class="form-control text-xs font-weight-bold select_top">
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
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->tax }}</p>
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
                                            <a href="{{ route('product.plateform', $item->id) }}"
                                                class="text-xs font-weight-bold mb-0 text-info">
                                                Platform
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
                <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data"
                    id="importForm">
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

    <div class="modal fade" id="apiImportModal" tabindex="-1" aria-labelledby="apiImportModalLabel"
        aria-hidden="true">
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
                                    <option value="{{ $client->id }}">
                                        {{ $client->name }}{{ $client->company_name ? ' - ' . $client->company_name : '' }}
                                    </option>
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

    <div id="barcodeModal"
        style="
        display:none;
        position:fixed;
        inset:0;
        z-index:99999;
        background:rgba(0,0,0,0.75);
        align-items:center;
        justify-content:center;
        padding:20px;
    ">
        <div
            style="
            position:relative;
            background:#fff;
            padding:35px 40px;
            border-radius:15px;
            width:650px;
            max-width:95vw;
            min-height:300px;
            display:flex;
            align-items:center;
            justify-content:center;
            box-shadow:0 10px 40px rgba(0,0,0,0.3);
        ">

            <!-- Close -->
            <button type="button" id="closeBarcodeModal"
                style="
                position:absolute;
                top:-15px;
                right:-15px;
                width:40px;
                height:40px;
                border:0;
                border-radius:50%;
                background:#fff;
                color:#333;
                font-size:22px;
                cursor:pointer;
                box-shadow:0 2px 10px rgba(0,0,0,.3);
                z-index:2;
            ">
                &times;
            </button>

            <!-- Barcode -->
            <img id="barcodeLargeImage" src="" alt="Barcode"
                style="
                width:700px;
                height:300px;
                max-width:90vw;
                object-fit:contain;
                image-rendering:auto;
            ">

        </div>
    </div>

    <script>
        $(document).on('click', '.barcode-preview', function() {

            let imageSrc = $(this).attr('src');

            console.log('Barcode clicked:', imageSrc);

            if (!imageSrc) {
                return;
            }

            $('#barcodeLargeImage').attr('src', imageSrc);

            $('#barcodeModal').css('display', 'flex');
        });


        $('#closeBarcodeModal').on('click', function() {

            $('#barcodeModal').css('display', 'none');

            $('#barcodeLargeImage').attr('src', '');

        });


        $('#barcodeModal').on('click', function(e) {

            if (e.target === this) {

                $(this).css('display', 'none');

                $('#barcodeLargeImage').attr('src', '');
            }

        });


        $(document).on('keydown', function(e) {

            if (e.key === 'Escape') {

                $('#barcodeModal').css('display', 'none');

                $('#barcodeLargeImage').attr('src', '');
            }

        });
    </script>

    <script>
        $(document).ready(function() {
            function getSelectedProductIds() {
                return $('.product-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
            }

            function bulkUpdateProducts(field, value) {
                var ids = getSelectedProductIds();

                if (!ids.length) {
                    showNotification('warning', 'Please select at least one product.');
                    return;
                }

                if (!value) {
                    showNotification('warning', 'Please select a value first.');
                    return;
                }

                $.ajax({
                    url: "{{ route('product.bulk_update') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: ids,
                        field: field,
                        value: value
                    },
                    success: function(res) {
                        if (field === 'summer') {
                            $('.product-checkbox:checked').each(function() {
                                $(this).closest('tr').find('.select_summer').val(value);
                            });
                        }

                        $('#select-all-products').prop('checked', false);
                        $('.product-checkbox').prop('checked', false);
                        showNotification('success', res.message || 'Successfully updated');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        showNotification('danger', 'Something went wrong');
                    }
                });
            }

            $('#select-all-products').on('change', function() {
                $('.product-checkbox').prop('checked', this.checked);
            });

            $('.product-checkbox').on('change', function() {
                $('#select-all-products').prop(
                    'checked',
                    $('.product-checkbox').length === $('.product-checkbox:checked').length
                );
            });

            $('#applyBulkBrand').on('click', function() {
                bulkUpdateProducts('brand', $('#bulk_brand').val());
            });

            $('#applyBulkSummer').on('click', function() {
                bulkUpdateProducts('summer', $('#bulk_summer').val());
            });

            $('#applyBulkTax').on('click', function() {
                bulkUpdateProducts('tax', $('#bulk_tax').val());
            });

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
