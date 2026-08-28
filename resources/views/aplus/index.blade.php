@extends('layout.app')

@section('content')

<div class="container-fluid">

    <div class="card mb-4 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">A+ Content Manager</h5>
        </div>

        {{-- FORM --}}
        <div class="card-body border-bottom">
            <form action="{{ route('aplus.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="row align-items-end">

                    <div class="col-md-3">
                        <label class="form-label">Layout</label>
                        <select name="section_type" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="single">1 Image</option>
                            <option value="two">2 Images</option>
                            <option value="three">3 Images</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Upload Images</label>
                        <input type="file" name="images[]" class="form-control" multiple required>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-50">Add</button>
                    </div>

                </div>
            </form>
        </div>

        {{-- A+ SECTIONS --}}
        <div class="card-body">

            @forelse ($aplus as $section)

                {{-- SINGLE --}}
                @if ($section->section_type == 'single')
                    <div class="aplus-box mb-4 p-2 border rounded">
                        <img src="{{ asset('aplus/' . $section->images[0]->image) }}"
                             class="img-fluid w-100 rounded">
                    </div>
                @endif

                {{-- TWO --}}
                @if ($section->section_type == 'two')
                    <div class="aplus-box mb-4 p-2 border rounded">
                        <div class="row">
                            @foreach ($section->images as $img)
                                <div class="col-md-6">
                                    <img src="{{ asset('aplus/' . $img->image) }}"
                                         class="img-fluid rounded w-100">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- THREE --}}
                @if ($section->section_type == 'three')
                    <div class="aplus-box mb-4 p-2 border rounded">
                        <div class="row">
                            @foreach ($section->images as $img)
                                <div class="col-md-4">
                                    <img src="{{ asset('aplus/' . $img->image) }}"
                                         class="img-fluid rounded w-100">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @empty
                <div class="text-center text-muted py-5">
                    No A+ Content Added Yet
                </div>
            @endforelse

        </div>

    </div>

</div>

@endsection