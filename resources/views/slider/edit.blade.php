@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Update</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('slider.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$slider->id}}">
                        <div class="row g-3">

                            <!-- Slider Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Slider Title</label>
                                    <input type="text" class="form-control" name="title" value="{{$slider->name}}"
                                        placeholder="Enter slider title" required>
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Slider Image</label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Http URL / Link</label>
                                    <input type="text" class="form-control" name="link" value="{{$slider->link}}"
                                        placeholder="Enter URL Link" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label fw-semibold mb-2">
                                        Old Image
                                    </label>

                                    <div class="border rounded-3 p-3 text-center bg-light position-relative">
                                        <img src="{{ asset($slider->image) }}" alt="Old Image"
                                            class="img-fluid rounded-3 shadow-sm"
                                            style="max-height: 200px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>

                            <a href="{{ route('slider.index') }}" class="btn btn-secondary ms-2">
                                Cancel
                            </a>
                        </div>

                    </form>
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
@endsection
