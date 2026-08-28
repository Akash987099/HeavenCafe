@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('setting.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug"
                                        placeholder="Enter Details" oninput="this.value = this.value.replace(/\s+/g, '')"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Media Link</label>
                                    <input type="url" class="form-control" id="media_link" name="media_link"
                                        placeholder="Enter Details"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image"
                                        placeholder="Enter vehicle number">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Description</label>
                                    <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter Details" required></textarea>
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
@endsection
