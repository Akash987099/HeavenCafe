@extends('layout.app')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0"><h6 class="mb-0">{{ $editingParty ? 'Update Party Type' : 'Add Party Type' }}</h6><p class="text-xs text-secondary mb-0 mt-1">Used later in frontend party registration.</p></div>
            <div class="card-body">
                <form action="{{ $editingParty ? route('party_master.update', $editingParty) : route('party_master.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($editingParty) @method('PUT') @endif
                    <div class="mb-3"><label class="form-label">Party Name <span class="text-danger">*</span></label><input required type="text" name="name" maxlength="150" value="{{ old('name', $editingParty->name ?? '') }}" class="form-control" placeholder="e.g. Birthday Party">@error('name')<p class="text-danger text-xs mt-1 mb-0">{{ $message }}</p>@enderror</div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" rows="4" maxlength="5000" class="form-control" placeholder="Short description for this party type">{{ old('description', $editingParty->description ?? '') }}</textarea>@error('description')<p class="text-danger text-xs mt-1 mb-0">{{ $message }}</p>@enderror</div>
                    <div class="mb-3"><label class="form-label">Image <span class="text-secondary text-xs">(JPG, PNG or WEBP)</span></label><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="form-control">@error('image')<p class="text-danger text-xs mt-1 mb-0">{{ $message }}</p>@enderror
                        @if ($editingParty?->image)<img src="{{ asset($editingParty->image) }}" alt="{{ $editingParty->name }}" class="mt-2 rounded border" style="width:86px;height:64px;object-fit:cover">@endif
                    </div>
                    <div class="mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1" @selected(old('status', $editingParty->status ?? 1) == 1)>Active</option><option value="0" @selected(old('status', $editingParty->status ?? 1) == 0)>Inactive</option></select></div>
                    <div class="d-flex gap-2"><button class="btn btn-primary mb-0">{{ $editingParty ? 'Update' : 'Save Party Type' }}</button>@if($editingParty)<a href="{{ route('party_master.index') }}" class="btn btn-outline-secondary mb-0">Cancel</a>@endif</div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8 mb-4">
        <div class="card h-100"><div class="card-header pb-0 d-flex justify-content-between align-items-center"><div><h6 class="mb-0">Party Master</h6><p class="text-xs text-secondary mb-0 mt-1">{{ $parties->total() }} party type(s)</p></div></div>
            <div class="card-body px-0 pt-3 pb-2">
                @if(session('success'))<div class="alert alert-success mx-3 text-white">{{ session('success') }}</div>@endif
                <div class="table-responsive p-0"><table class="table align-items-center mb-0"><thead><tr><th class="ps-3">Image</th><th>Name &amp; Description</th><th>Status</th><th>Action</th></tr></thead><tbody>
                    @forelse($parties as $party)
                        <tr><td class="ps-3">@if($party->image)<img src="{{ asset($party->image) }}" alt="{{ $party->name }}" class="rounded" style="width:54px;height:42px;object-fit:cover">@else<span class="text-secondary text-xs">No image</span>@endif</td><td><p class="text-xs font-weight-bold mb-1">{{ $party->name }}</p><p class="text-xs text-secondary mb-0">{{ \Illuminate\Support\Str::limit($party->description, 80) ?: '-' }}</p></td><td><span class="badge badge-sm {{ $party->status ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">{{ $party->status ? 'Active' : 'Inactive' }}</span></td><td><a href="{{ route('party_master.index', ['edit' => $party->id]) }}" class="btn btn-outline-primary btn-sm mb-0">Edit</a><form action="{{ route('party_master.destroy', $party) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button onclick="return confirm('Delete {{ $party->name }}?')" class="btn btn-outline-danger btn-sm mb-0">Delete</button></form></td></tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">No party types found.</td></tr>
                    @endforelse
                </tbody></table></div>
                @if($parties->hasPages())<div class="mt-3 px-3">{{ $parties->links('shared.pagination') }}</div>@endif
            </div>
        </div>
    </div>
</div>
@endsection
