@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('cms.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Page Category</label>
                                    <select name="category" required id="" class="form-control">
                                        <option value="Company">Company</option>
                                        <option value="Legal">Legal</option>
                                        <option value="Policy">Policy</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter Name" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Url</label>
                                    <input type="text" class="form-control" name="url"
                                        placeholder="Enter URL Name" required>
                                </div>
                            </div>

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
                                    <label class="form-label">description</label>
                                    <textarea type="file" id="description" class="form-control" name="description" required></textarea>
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
