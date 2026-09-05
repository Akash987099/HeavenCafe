@extends('pos.layout.app')

@section('content')
    <div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
        <div class="mx-auto max-w-4xl">
            <div class="mb-6"><h1 class="text-2xl font-bold text-slate-800">Edit Staff</h1><p class="text-sm text-slate-400 mt-1">Update staff account details</p></div>
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <form action="{{ route('pos.staff.update', $staff->id) }}" method="POST">@csrf
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Staff Name <span class="text-red-500">*</span></label><input type="text" name="name" value="{{ old('name', $staff->name) }}" required class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm"></div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Mobile <span class="text-red-500">*</span></label><input type="text" name="mobile" value="{{ old('mobile', $staff->mobile) }}" maxlength="10" required class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm"></div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Email <span class="text-red-500">*</span></label><input type="email" name="email" value="{{ old('email', $staff->email) }}" required class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm"></div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Role <span class="text-red-500">*</span></label><select name="role" required class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm"><option value="">Select role</option>@foreach ($roles as $role)<option value="{{ $role->id }}" {{ old('role', $staff->role) == $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>@endforeach</select></div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label><input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm"></div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-2">Confirm New Password</label><input type="password" name="password_confirmation" class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm"></div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3"><a href="{{ route('pos.staff') }}" class="h-11 px-5 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-semibold flex items-center">Cancel</a><button type="submit" class="h-11 px-6 rounded-xl bg-[#128C7E] text-white text-sm font-semibold">Update Staff</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection
