@extends('layout.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{route('product.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$product->id}}">
                        <div class="row g-3">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" value="{{$product->name}}" name="name" placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{$product->slug}}"
                                        placeholder="Enter Details" oninput="this.value = this.value.replace(/\s+/g, '')"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Brand Name</label>
                                    <input type="text" class="form-control" id="brand_name" name="brand_name" value="{{$product->brand_name}}" placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image" placeholder="Enter Details">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label fw-semibold mb-2">
                                        Old Image
                                    </label>

                                    <div class="border rounded-3 p-3 text-center bg-light position-relative">
                                        <img src="{{ asset($product->image) }}" alt="Old Image"
                                            class="img-fluid rounded-3 shadow-sm"
                                            style="max-height: 50px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Status</label>
                                    <select type="text" class="form-control" id="status" name="status" placeholder="Enter Details">
                                        <option value="active">Active</option>
                                        <option value="in_active">In Active</option>
                                    </select>    
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" value="{{$product->price}}" name="price" placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Actual Price</label>
                                    <input type="text" class="form-control" id="ac_price" name="ac_price" value="{{$product->ac_price}}" placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">SKU Code</label>
                                    <input type="text" class="form-control" id="sku_code" name="sku_code" value="{{$product->sku_code}}" placeholder="Enter Details">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">HSN Code</label>
                                    <input type="text" class="form-control" id="hsn_code" name="hsn_code" value="{{$product->hsn_code}}" placeholder="Enter Details">
                                </div>
                            </div>

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Tags</label>
                                    <input type="text" class="form-control" id="tags" name="tags" value="{{$product->tags}}" placeholder="Enter Details">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Meta Tag</label>
                                    <input type="text" class="form-control" id="meta_tag" name="meta_tag" value="{{$product->meta_tag}}" placeholder="Enter Details">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Category</label>
                                    <select type="text" class="form-control" id="category" name="category" placeholder="Enter Details">
                                        <option value="">Select Category</option>
                                        @foreach ($category as $key => $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Sub Category</label>
                                    <select type="text" class="form-control" id="sub_category" name="sub_category" placeholder="Enter Details">
                                        <option value="">------Select sub Category------</option>
                                        @foreach ($sub_category as $key => $item)
                                            <option value="{{$item->id}}" data-id="{{$item->category_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Child Category</label>
                                    <select type="text" class="form-control" id="child_category" name="child_category" placeholder="Enter Details">
                                        <option value="">------Select sub Category------</option>
                                        @foreach ($child_category as $key => $item)
                                            <option value="{{$item->id}}" data-id="{{$item->sub_category_id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Discount</label>
                                    <select type="text" class="form-control" id="discount" name="discount" placeholder="Enter Details">
                                        <option value="">------Select Discount------</option>
                                        @foreach ($discount as $key => $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Brands</label>
                                    <select type="text" class="form-control" id="brand" name="brand" placeholder="Enter Details">
                                        <option value="">------Select Brand------</option>
                                        @foreach ($brand as $key => $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Types</label>
                                    <select type="text" class="form-control" id="type" name="type">
                                        <option value="">------Select Types------</option>
                                        @foreach ($type as $key => $item)
                                            <option value="{{$item->name}}" data-id="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Type Value</label>
                                    <input type="text" class="form-control" id="type_value" name="type_value" value="{{$product->type_value}}" placeholder="Enter Details">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Tax</label>
                                    <select type="text" class="form-control" id="tax" name="tax">
                                        <option value="">------Select Types------</option>
                                        @foreach ($tax as $key => $item)
                                            <option value="{{$item->tax_value}}" data-id="{{$item->id}}">{{$item->tax_value}}</option>
                                        @endforeach
                                    </select>    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Assign Store</label>
                                    <select type="text" class="form-control" id="assign_store" name="assign_store">
                                        <option value="0">------Select Types------</option>
                                        <option value="1">------Assign Store------</option>
                                    </select>    
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Store QTY</label>
                                    <input type="text" class="form-control" id="store_qty" name="store_qty" value="{{$product->store_qty}}" placeholder="Enter Details">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Short Description</label>
                                    <textarea type="text" class="form-control" id="short_description" name="short_description" placeholder="Enter Details" required>{!! $product->short_description !!}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Description</label>
                                    <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter Details" required>{!! $product->description !!}</textarea>
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
    CKEDITOR.replace('description', {
        height: 300,
        removeButtons: 'PasteFromWord'
    });
</script>


<script>
    CKEDITOR.replace('short_description', {
        height: 200,
        removeButtons: 'PasteFromWord'
    });
</script>

<script>
            $(document).ready(function() {
                $('#status').val('{{ $product->status }}');
                $('#category').val('{{ $product->category }}');
                $('#sub_category').val('{{ $product->sub_category }}');
                $('#child_category').val('{{ $product->child_category }}');
                $('#store').val('{{ $product->store }}');
                $('#discount').val('{{ $product->discount }}');
                $('#summer').val('{{ $product->summer_sale }}');
                $('#brand').val('{{ $product->brands }}');
                $('#assign_store').val('{{ $product->is_store }}');
            });
    </script>

@endsection
