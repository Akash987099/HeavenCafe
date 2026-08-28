@extends('layout.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Gallery</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{route('product.gallery_save')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$product->id}}">
                        <div class="row g-3">
                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Images</label>
                                    <input type="file" class="form-control" id="image" name="image[]" multiple required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0 datatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Sr No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Image</th>
                                    
                                    <th class="text-secondary opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                @php $series = 1 @endphp
                                @foreach ($gallery as $key => $item)
                                    <tr>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $series++ }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">  <img src="{{ asset($item->image) }}" alt="" style="height:40px; width:auto;"></p>
                                        </td>

                                        <td>
                                            <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs delete-btn"
                                            data-id="{{ $item->id }}"
                                            data-url="{{ route('product.gallery_delete', $item->id) }}">
                                            Delete
                                        </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

            </div>
        </div>
    </div>
</div>

@endsection