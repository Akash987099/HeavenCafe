@extends('layout.app')

@section('content')
    <div class="row"><div class="col-12"><div class="card mb-4"><div class="card-header pb-0"><h6 class="m-0">Update Role</h6></div><div class="card-body px-4 pt-4 pb-2">
        <form action="{{ route('role.update') }}" method="POST">@csrf<input type="hidden" name="id" value="{{ $role->id }}">
            <div class="row g-3">
                <div class="col-md-6"><div class="form-group"><label for="role_name" class="form-label">Role Name</label><input type="text" class="form-control" id="role_name" name="role_name" value="{{ old('role_name', $role->role_name) }}" required>@error('role_name')<small class="text-danger">{{ $message }}</small>@enderror</div></div>
                <div class="col-md-6"><div class="form-group"><label for="status" class="form-label">Status</label><select class="form-control" id="status" name="status" required><option value="1" {{ old('status', $role->status) == '1' ? 'selected' : '' }}>Active</option><option value="0" {{ old('status', $role->status) == '0' ? 'selected' : '' }}>Inactive</option></select></div></div>
            </div><div class="mt-4"><button type="submit" class="btn btn-primary">Update</button></div>
        </form>
    </div></div></div></div>
@endsection
