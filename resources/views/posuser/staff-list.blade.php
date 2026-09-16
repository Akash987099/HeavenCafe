@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="m-0">Staff List — {{ $pos->name }}</h6>
                        <p class="text-xs text-secondary mb-0 mt-1">POS ID: {{ $pos->staff_id }} · {{ $staffs->total() }} staff members</p>
                    </div>
                    <a href="{{ route('pos_user.index') }}" class="btn btn-outline-secondary btn-sm">Back to POS Users</a>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Sr No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Staff ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mobile</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                    <th class="text-secondary opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($staffs as $key => $staff)
                                    <tr>
                                        <td class="ps-3"><p class="text-xs font-weight-bold mb-0">{{ $staffs->firstItem() + $key }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $staff->staff_id }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $staff->name }}</p><p class="text-xs text-secondary mb-0">{{ $staff->email }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $staff->mobile }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ optional($staff->roleMaster)->role_name ?? '-' }}</p></td>
                                        <td>
                                            <a href="{{ route('pos_user.edit', $staff->id) }}" class="btn btn-outline-primary btn-sm mb-1">Update</a>
                                            <a href="{{ route('pos_user.offer-letter', $staff->id) }}" target="_blank" class="btn btn-primary btn-sm mb-1">Offer Letter</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-secondary py-4">No staff found for this POS user.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($staffs->hasPages())
                        <div class="mt-4 px-3">{{ $staffs->links('shared.pagination') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
