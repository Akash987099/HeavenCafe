@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="m-0">Employee Profile</h6>
                        <p class="text-xs text-secondary mb-0 mt-1">Staff ID: {{ $pos->staff_id }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pos_user.offer-letter', $pos->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file-alt me-1"></i> Offer Letter
                        </a>
                        <a href="{{ route('pos_user.edit', $pos->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Update
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 pt-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-6 col-xl-4">
                            <div class="border rounded p-3 h-100">
                                <p class="text-xs text-secondary mb-1">Employee Name</p>
                                <p class="font-weight-bold mb-0">{{ $pos->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="border rounded p-3 h-100">
                                <p class="text-xs text-secondary mb-1">Mobile Number</p>
                                <p class="font-weight-bold mb-0">{{ $pos->mobile }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="border rounded p-3 h-100">
                                <p class="text-xs text-secondary mb-1">Email Address</p>
                                <p class="font-weight-bold mb-0">{{ $pos->email }}</p>
                            </div>
                        </div>

                        <div class="col-12 mt-4"><h6 class="mb-0">Employment Details</h6></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Store</p><p class="font-weight-bold mb-0">{{ $pos->store->name ?? '-' }}</p></div></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Designation</p><p class="font-weight-bold mb-0">{{ $pos->designation ?: '-' }}</p></div></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Date of Joining</p><p class="font-weight-bold mb-0">{{ $pos->date_of_joining ? $pos->date_of_joining->format('d M Y') : '-' }}</p></div></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Monthly Salary</p><p class="font-weight-bold mb-0">{{ $pos->salary !== null ? '₹ ' . number_format((float) $pos->salary, 2) : '-' }}</p></div></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Date of Birth</p><p class="font-weight-bold mb-0">{{ $pos->date_of_birth ? $pos->date_of_birth->format('d M Y') : '-' }}</p></div></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Gender</p><p class="font-weight-bold mb-0 text-capitalize">{{ $pos->gender ?: '-' }}</p></div></div>
                        <div class="col-12"><div class="border rounded p-3"><p class="text-xs text-secondary mb-1">Address</p><p class="font-weight-bold mb-0">{{ $pos->address ?: '-' }}</p></div></div>

                        <div class="col-12 mt-4"><h6 class="mb-0">Emergency &amp; Bank Details</h6></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Emergency Contact</p><p class="font-weight-bold mb-0">{{ $pos->emergency_contact_name ?: '-' }}</p></div></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Emergency Mobile</p><p class="font-weight-bold mb-0">{{ $pos->emergency_contact_mobile ?: '-' }}</p></div></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Bank Name</p><p class="font-weight-bold mb-0">{{ $pos->bank_name ?: '-' }}</p></div></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">Account Number</p><p class="font-weight-bold mb-0">{{ $pos->bank_account_number ?: '-' }}</p></div></div>
                        <div class="col-md-6 col-xl-4"><div class="border rounded p-3 h-100"><p class="text-xs text-secondary mb-1">IFSC Code</p><p class="font-weight-bold mb-0">{{ $pos->bank_ifsc_code ?: '-' }}</p></div></div>

                        <div class="col-12 mt-4"><h6 class="mb-0">Documents</h6></div>
                        <div class="col-12">
                            <div class="border rounded p-3">
                                @forelse ($pos->documents ?? [] as $document)
                                    <a href="{{ asset($document) }}" target="_blank" class="btn btn-outline-secondary btn-sm me-2 mb-2">
                                        <i class="fas fa-paperclip me-1"></i> Document {{ $loop->iteration }}
                                    </a>
                                @empty
                                    <p class="text-secondary text-sm mb-0">No documents uploaded.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
