@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Update Supplier</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('supplier.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" value="{{$supplier->id}}">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" value="{{$supplier->name}}" name="name" placeholder="Enter name"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="{{$supplier->email}}" name="email" placeholder="Enter Details"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="number" class="form-control" value="{{$supplier->phone}}" name="phone" placeholder="Enter Details"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" value="{{$supplier->address}}" name="address" placeholder="Enter Details"
                                        required>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>

                            <a href="{{ route('supplier.index') }}" class="btn btn-secondary ms-2">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
