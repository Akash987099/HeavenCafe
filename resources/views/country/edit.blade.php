@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('country.update') }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{$country->id}}">
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$country->country_name}}"
                                        placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Code</label>
                                    <input type="text" class="form-control" id="name" name="code" value="{{$country->country_code}}"
                                        placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Country Short Name</label>
                                    <input type="text" class="form-control" id="short_name" name="short_name" value="{{$country->country_short}}"
                                        placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Currency Name</label>
                                    <input type="text" class="form-control" id="currency" name="currency" value="{{$country->currency_name}}"
                                        placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Currency Code</label>
                                    <input type="text" class="form-control" id="currency_code" name="currency_code" value="{{$country->currency_code}}"
                                        placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Currency Symbol</label>
                                    <input type="text" class="form-control" id="sysbol" name="sysbol" value="{{$country->currency_symbol}}"
                                        placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" value="{{$country->currency_rate}}"
                                        placeholder="Enter Details" required>
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
