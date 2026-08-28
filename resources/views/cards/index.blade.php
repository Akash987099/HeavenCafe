@extends('layout.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">

            <div class="card-header pb-0">
                <h6>User Cards</h6>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">

                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>User Name</th>
                                <th>Card Type</th>
                                <th>Card Number</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Expiry</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($cards as $key => $item)
                                <tr>
                                    <td>{{ $cards->firstItem() + $key }}</td>

                                    <td>
                                        {{ $item->user->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $item->cardType->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        <strong>{{ $item->card_number }}</strong>
                                    </td>

                                    <td>
                                        ₹{{ $item->balance }}
                                    </td>

                                    <td>
                                        @if($item->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $item->expiry_date }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                    <div class="mt-4">
                        {{ $cards->links('shared.pagination') }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection