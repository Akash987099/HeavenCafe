@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Update</h6>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('category.index') }}" class="btn btn-primary btn-sm">
                            Back
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('category.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $category->name }}" placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Category Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label fw-semibold mb-2">
                                        Old Image
                                    </label>

                                    <div class="border rounded-3 p-3 text-center bg-light position-relative">
                                        <img src="{{ asset($category->image) }}" alt="Old Image"
                                            class="img-fluid rounded-3 shadow-sm"
                                            style="max-height: 200px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
