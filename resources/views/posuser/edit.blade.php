@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Update</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('pos_user.update') }}" method="POST">
                        @csrf

                        <input type="hidden" value="{{$pos->id}}" name="id">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $pos->name }}" placeholder="Enter Name" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile"
                                        value="{{ $pos->mobile }}" placeholder="Enter Mobile" maxlength="10" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ $pos->email }}" placeholder="Enter Email" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store" class="form-label">Store</label>

                                    <select class="form-control" id="store" name="store" required>

                                        <option value="">---- Select Store -----</option>

                                        @foreach ($store as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('store', $pos->store_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <script>
    $('#store').val($pos->store_id);
    </script>
@endsection
