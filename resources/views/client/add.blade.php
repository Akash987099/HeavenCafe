@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6 class="m-0">Add Client</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('client.save') }}" method="POST">
                        @csrf

                        @include('client.form')

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save Client</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
