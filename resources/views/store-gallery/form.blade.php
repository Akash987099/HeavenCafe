@extends('layout.app')

@section('content')
<div class="row"><div class="col-12"><div class="card mb-4">
    <div class="card-header pb-0"><h6 class="m-0">{{ isset($gallery) ? 'Edit Store Gallery Image' : 'Add Store Gallery Image' }}</h6></div>
    <div class="card-body px-4 pt-4 pb-2">
        @if($errors->any())<div class="alert alert-danger text-sm"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form action="{{ isset($gallery) ? route('store_gallery.update', $gallery) : route('store_gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if(isset($gallery)) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Store</label><select name="store_id" class="form-control" required><option value="">Select store</option>@foreach($stores as $store)<option value="{{ $store->id }}" @selected(old('store_id', $gallery->store_id ?? '') == $store->id)>{{ $store->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Image</label><input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control" {{ isset($gallery) ? '' : 'required' }}><small class="text-secondary">JPG, PNG or WEBP; maximum 5 MB.</small></div>
                <div class="col-md-6"><label class="form-label">Title <span class="text-secondary">(optional)</span></label><input type="text" name="title" maxlength="255" value="{{ old('title', $gallery->title ?? '') }}" class="form-control" placeholder="Example: Store ambience"></div>
                <div class="col-md-3"><label class="form-label">Position</label><input type="number" name="sort_order" min="0" value="{{ old('sort_order', $gallery->sort_order ?? 0) }}" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-control"><option value="1" @selected(old('status', $gallery->status ?? 1) == 1)>Active</option><option value="0" @selected(old('status', $gallery->status ?? 1) == 0)>Inactive</option></select></div>
                @if(isset($gallery))<div class="col-md-12"><img src="{{ asset($gallery->image) }}" class="rounded border" style="height:110px;max-width:220px;object-fit:cover;"></div>@endif
            </div>
            <div class="mt-4"><button class="btn btn-primary">{{ isset($gallery) ? 'Update' : 'Save' }}</button><a href="{{ route('store_gallery.index') }}" class="btn btn-secondary ms-2">Cancel</a></div>
        </form>
    </div>
</div></div></div>
@endsection
