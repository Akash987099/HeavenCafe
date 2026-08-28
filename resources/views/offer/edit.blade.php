@extends('layout.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mb-4">

            <div class="card-header pb-0">
                <h6>Update Offer</h6>
            </div>

            <div class="card-body px-4 pt-4 pb-2">
                <form action="{{ route('offer.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="id" value="{{ $offer->id }}">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" value="{{ $offer->title }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Coupon Code</label>
                            <input type="text" name="code" value="{{ $offer->code }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="coupon" {{ $offer->type == 'coupon' ? 'selected' : '' }}>Coupon</option>
                                <option value="auto" {{ $offer->type == 'auto' ? 'selected' : '' }}>Auto Apply</option>
                                <option value="card" {{ $offer->type == 'card' ? 'selected' : '' }}>Card Offer</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-control" required>
                                <option value="flat" {{ $offer->discount_type == 'flat' ? 'selected' : '' }}>Flat</option>
                                <option value="percent" {{ $offer->discount_type == 'percent' ? 'selected' : '' }}>Percent</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Discount Value</label>
                            <input type="number" name="discount_value" value="{{ $offer->discount_value }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Min Order Amount</label>
                            <input type="number" name="min_order_amount" value="{{ $offer->min_order_amount }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Max Discount</label>
                            <input type="number" name="max_discount" value="{{ $offer->max_discount }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Usage Limit</label>
                            <input type="number" name="usage_limit" value="{{ $offer->usage_limit }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Per User Limit</label>
                            <input type="number" name="per_user_limit" value="{{ $offer->per_user_limit }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" name="start_date"
                                value="{{ $offer->start_date ? \Carbon\Carbon::parse($offer->start_date)->format('Y-m-d\TH:i') : '' }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Expiry Date</label>
                            <input type="datetime-local" name="expiry_date"
                                value="{{ $offer->expiry_date ? \Carbon\Carbon::parse($offer->expiry_date)->format('Y-m-d\TH:i') : '' }}"
                                class="form-control">
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