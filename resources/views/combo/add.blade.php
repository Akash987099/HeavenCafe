@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('combo.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $product->id }}" name="combo_product_id">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="name" value="{{ $product->name }}"
                                        readonly name="name" placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" value="{{ $product->price }}"
                                         name="price" placeholder="Enter Price" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Combo Image</label>
                                    <input type="file" class="form-control" id="image" name="image"
                                        placeholder="Enter vehicle number">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Link (Optional)</label>
                                    <input type="text" class="form-control" id="link" name="link"
                                        placeholder="Enter Link">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Combo Products</label>
                                    <select class="form-control select2" name="product_id[]" multiple required>
                                        @foreach ($products as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" cols="5"
                                        placeholder="Enter Description"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Search & Select Products",
                allowClear: true
            });
        });
    </script>
@endsection
