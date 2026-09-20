@extends('layout.app')

@section('content')
<div class="row"><div class="col-12"><div class="card mb-4">
    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
        <div><h6 class="m-0">Store Gallery</h6><p class="text-xs text-secondary mb-0 mt-1">Images are saved and shown store-wise.</p></div>
        <a href="{{ route('store_gallery.create') }}" class="btn btn-primary btn-sm mb-0">+ Add Gallery Image</a>
    </div>
    @if(session('success'))<div class="alert alert-success mx-3 mt-3 mb-0 text-sm">{{ session('success') }}</div>@endif
    <div class="card-body px-0 pt-3 pb-2"><div class="table-responsive p-0">
        <table class="table align-items-center mb-0"><thead><tr><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">#</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Store</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Position</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th><th class="text-secondary opacity-7">Action</th></tr></thead>
        <tbody>@forelse($galleries as $key => $gallery)<tr><td class="ps-3 text-xs">{{ $galleries->firstItem() + $key }}</td><td><img src="{{ asset($gallery->image) }}" onerror="this.src='{{ asset('images/no-product.png') }}'" class="rounded border" style="height:52px;width:90px;object-fit:cover;"></td><td><p class="text-xs font-weight-bold mb-0">{{ optional($gallery->store)->name ?? '-' }}</p></td><td><p class="text-xs mb-0">{{ $gallery->title ?: '-' }}</p></td><td class="text-xs">{{ $gallery->sort_order }}</td><td><span class="badge badge-sm {{ $gallery->status ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">{{ $gallery->status ? 'Active' : 'Inactive' }}</span></td><td><a href="{{ route('store_gallery.edit', $gallery) }}" class="text-primary font-weight-bold text-xs">Edit</a></td></tr>@empty<tr><td colspan="7" class="text-center py-4 text-secondary">No gallery images added yet.</td></tr>@endforelse</tbody>
        </table><div class="mt-4 px-3">{{ $galleries->links('shared.pagination') }}</div>
    </div></div>
</div></div></div>
@endsection
