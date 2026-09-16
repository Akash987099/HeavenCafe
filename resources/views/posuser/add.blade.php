@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('pos_user.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="Enter Name" required>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <h6 class="mb-0">Employment Details <small class="text-muted">(Optional)</small></h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_joining" class="form-label">Date of Joining</label>
                                    <input type="date" class="form-control" id="date_of_joining" name="date_of_joining" value="{{ old('date_of_joining') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="designation" class="form-label">Designation</label>
                                    <select class="form-control" id="designation" name="designation">
                                        <option value="">---- Select Designation -----</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->role_name }}" {{ old('designation') === $role->role_name ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salary" class="form-label">Monthly Salary</label>
                                    <input type="number" class="form-control" id="salary" name="salary" value="{{ old('salary') }}" min="0" step="0.01" placeholder="Enter salary">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="">---- Select Gender -----</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Enter contact name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_mobile" class="form-label">Emergency Contact Mobile</label>
                                    <input type="text" class="form-control" id="emergency_contact_mobile" name="emergency_contact_mobile" value="{{ old('emergency_contact_mobile') }}" maxlength="10" placeholder="Enter mobile number">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" placeholder="Enter bank name">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_account_number" class="form-label">Bank Account Number</label>
                                    <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}" placeholder="Enter account number">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_ifsc_code" class="form-label">IFSC Code</label>
                                    <input type="text" class="form-control" id="bank_ifsc_code" name="bank_ifsc_code" value="{{ old('bank_ifsc_code') }}" placeholder="Enter IFSC code">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="documents" class="form-label">Documents</label>
                                    <input type="file" class="form-control" id="documents" name="documents[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                    <small class="text-muted">Optional. Up to 10 files, 10 MB each (JPG, PNG, PDF, DOC, DOCX).</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter address">{{ old('address') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile"
                                        value="{{ old('mobile') }}" placeholder="Enter Mobile" maxlength="10" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" placeholder="Enter Email" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        value="{{ old('password') }}" placeholder="Enter password" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store" class="form-label">Store</label>

                                    <select class="form-control" id="store" name="store" required>

                                        <option value="">---- Select Store -----</option>

                                        @foreach ($store as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('store') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
