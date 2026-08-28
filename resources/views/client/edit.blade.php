@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6 class="m-0">Edit Client</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('client.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $client->id }}">

                        @include('client.form', ['client' => $client])

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Client</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
