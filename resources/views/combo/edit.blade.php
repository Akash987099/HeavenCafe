@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('combo.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $combo->combo_product_id }}" name="combo_product_id">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" value="{{ $combo->price }}"
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

                <div class="table-responsive p-0 mt-4">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp

                            @foreach ($comboItems as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>

                                    <td>{{ $item->product->name ?? '' }}</td>

                                    <td>
                                        @if ($item->image)
                                            <img src="{{ asset($item->image) }}" style="height:40px;">
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('combo.deleteItem', $item->id) }}"
                                            onclick="return confirm('Are you sure?')" class="text-danger">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
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
