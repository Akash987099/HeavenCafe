@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header pb-0">
                    <h6>Lead Details</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">CRN</label>
                            <input type="text" class="form-control" value="{{ $lead->crn }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">User Name</label>
                            <input type="text" class="form-control" value="{{ $lead->name }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" value="{{ $lead->phone }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="{{ $lead->email }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Card Type</label>
                            <input type="text" class="form-control" value="{{ $lead->cardType->name ?? '' }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" value="{{ ucfirst($lead->status) }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Created At</label>
                            <input type="text" class="form-control" value="{{ $lead->created_at }}" readonly>
                        </div>

                    </div>

                    <hr>

                    @if ($lead->status !== 'completed')
                        <form action="{{ route('leads.status', $lead->id) }}" method="POST">
                            @csrf

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Update Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-success mt-3">
                            This lead is completed.
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
@endsection
