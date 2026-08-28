@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('points.save') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Reward %</label>
                                <input type="number" name="reward_percent" class="form-control" placeholder="e.g. 10"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Point Value</label>
                                <input type="number" name="point_value" class="form-control"
                                    placeholder="e.g. 1 Point = ₹1" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Max Redeem %</label>
                                <input type="number" name="max_redeem_percent" class="form-control" placeholder="e.g. 50"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Min Order Amount</label>
                                <input type="number" name="min_order_amount" class="form-control" placeholder="e.g. 200"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_days" class="form-control" min="{{ date('Y-m-d') }}"
                                    required>
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
@endsection
