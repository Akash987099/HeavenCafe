@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('status.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bg_color" class="form-label">Background Color</label>
                                    <input type="text" class="form-control" id="bg_color" name="bg_color"
                                        value="bg-blue-100" placeholder="bg-blue-100" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="text_color" class="form-label">Text Color</label>
                                    <input type="text" class="form-control" id="text_color" name="text_color"
                                        value="text-blue-800" placeholder="text-blue-800" required>
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
