@extends('layout.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header pb-0">
                    <h6>Add Recommended Products</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-3">

                    <form action="{{ route('recommended.save') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Select Product</label>
                                <select name="product_id" id="main_product" class="form-control select2-single" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Recommended Products</label>
                                <select name="recommended_products[]" id="recommended_products"
                                    class="form-control select2-multiple" multiple required>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                Save
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            $('.select2-single').select2({
                placeholder: "Select Product",
                width: '100%'
            });

            $('.select2-multiple').select2({
                placeholder: "Select Recommended Products",
                width: '100%'
            });

            $('#main_product').on('change', function() {

                let selectedId = $(this).val();

                $('#recommended_products option').prop('disabled', false);

                if (selectedId) {
                    $('#recommended_products option[value="' + selectedId + '"]')
                        .prop('disabled', true)
                        .prop('selected', false);
                }

                $('#recommended_products').trigger('change');
            });

        });
    </script>
@endsection
