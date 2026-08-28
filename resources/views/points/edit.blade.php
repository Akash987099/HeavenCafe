@extends('layout.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0">Edit</h6>
            </div>

            <div class="card-body px-4 pt-4 pb-2">
                <form action="{{ route('points.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="id" value="{{ $point->id }}">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Reward %</label>
                            <input type="number" name="reward_percent" class="form-control"
                                value="{{ $point->reward_percent }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Point Value</label>
                            <input type="number" name="point_value" class="form-control"
                                value="{{ $point->point_value }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Max Redeem %</label>
                            <input type="number" name="max_redeem_percent" class="form-control"
                                value="{{ $point->max_redeem_percent }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Min Order Amount</label>
                            <input type="number" name="min_order_amount" class="form-control"
                                value="{{ $point->min_order_amount }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="expiry_days" class="form-control"
                                value="{{ \Carbon\Carbon::parse($point->expiry_days)->format('Y-m-d') }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection