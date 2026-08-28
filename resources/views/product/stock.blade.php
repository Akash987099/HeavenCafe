@extends('layout.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Stock</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{route('product.stock_save')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$product->id}}">
                        <div class="row g-3">
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock" class="form-label">Qty</label>
                                    <input type="number" class="form-control" value="{{$product->stock}}" id="image" name="stock" required>
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

@endsection