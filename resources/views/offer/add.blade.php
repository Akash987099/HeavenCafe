@extends('layout.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mb-4">

            <div class="card-header pb-0">
                <h6>Add Offer</h6>
            </div>

            <div class="card-body px-4 pt-4 pb-2">
                <form action="{{ route('offer.save') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Get 10% OFF" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Coupon Code</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. SAVE50">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="coupon">Coupon</option>
                                <option value="auto">Auto Apply</option>
                                <option value="card">Card Offer</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-control" required>
                                <option value="flat">Flat</option>
                                <option value="percent">Percent</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Discount Value</label>
                            <input type="number" name="discount_value" class="form-control" placeholder="e.g. 50 or 10" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Min Order Amount</label>
                            <input type="number" name="min_order_amount" class="form-control" placeholder="e.g. 300">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Max Discount</label>
                            <input type="number" name="max_discount" class="form-control" placeholder="e.g. 100">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Usage Limit</label>
                            <input type="number" name="usage_limit" class="form-control" placeholder="e.g. 100">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Per User Limit</label>
                            <input type="number" name="per_user_limit" class="form-control" placeholder="e.g. 1">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" name="start_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Expiry Date</label>
                            <input type="datetime-local" name="expiry_date" class="form-control">
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