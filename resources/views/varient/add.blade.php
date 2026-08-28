@extends('layout.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Add Product Variant</h6>
            </div>

            <div class="card-body px-4 pt-4 pb-2">

                <form action="{{route('varient.save')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="product_id" value="{{$product->id}}">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control" placeholder="Enter SKU">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Price</label>
                            <input type="number" min="0" step="0.01" value="{{$product->price}}" name="price" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stock</label>
                            <input type="number" min="0" value="{{$product->stock}}" name="stock" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Variant Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        @foreach($attribute as $attr)

                        <div class="col-md-4">
                            <label class="form-label">{{$attr->name}}</label>

                            <select name="attributes[{{$attr->id}}]" class="form-control">
                                <option value="">Select {{$attr->name}}</option>

                                @foreach($attribute_value->where('attribute_id',$attr->id) as $val)

                                <option value="{{$val->id}}">
                                    {{$val->value}}
                                </option>

                                @endforeach

                            </select>
                        </div>

                        @endforeach

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Add Variant</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Existing Variants</h6>
            </div>

            <div class="card-body px-0 pt-3 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SKU</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stock</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Attributes</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($varient as $key => $item)
                                <tr>
                                    <td class="align-middle text-sm ps-3">
                                        {{ $varient->firstItem() + $key }}
                                    </td>
                                    <td class="align-middle text-sm">
                                        {{ $item->sku ?: '-' }}
                                    </td>
                                    <td class="align-middle text-sm">
                                        {{ $item->price }}
                                    </td>
                                    <td class="align-middle text-sm">
                                        {{ $item->stock }}
                                    </td>
                                    <td class="align-middle text-sm" style="white-space: normal;">
                                        {{ $item->attribute_summary ?: '-' }}
                                    </td>
                                    <td class="align-middle text-sm">
                                        @if(!empty($item->image))
                                            <img src="{{ asset('variant/' . $item->image) }}" alt="Variant Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <form action="{{ route('varient.delete', $item->id) }}" method="POST" onsubmit="return confirm('Delete this variant?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger mb-0">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-sm py-4">No variants found for this product.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($varient->hasPages())
                    <div class="px-3 pt-3">
                        {{ $varient->links('shared.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
