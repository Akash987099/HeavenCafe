@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Edit FAQ</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('faq.update') }}" method="POST">
                        @csrf

                        <input type="hidden" name="id" value="{{ $faq->id }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Question / Title</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $faq->name) }}" placeholder="Enter FAQ title" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="1" {{ old('status', (string) $faq->status) == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', (string) $faq->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <textarea id="description" class="form-control" name="description" required>{!! old('description', $faq->description) !!}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>

                            <a href="{{ route('faq.index') }}" class="btn btn-secondary ms-2">
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
