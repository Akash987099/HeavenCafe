@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Staff Details
                </h1>

                <p class="text-sm text-slate-400 mt-1">
                    View staff account information
                </p>
            </div>

            <a
                href="{{ url()->previous() }}"
                class="h-10 px-4 rounded-xl border border-slate-200
                       bg-white text-slate-600 text-sm font-semibold
                       flex items-center gap-2 hover:bg-slate-50 transition"
            >
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
            <a href="{{ route('pos.staff.offer-letter', $staff->id) }}" target="_blank"
               class="h-10 px-4 rounded-xl bg-[#128C7E] text-white text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-file-alt"></i> Offer Letter
            </a>

        </div>


        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl border border-slate-200
                    shadow-sm overflow-hidden">

            {{-- Profile Header --}}
            <div class="p-6 bg-gradient-to-r from-emerald-50 to-white
                        border-b border-slate-200">

                <div class="flex flex-col sm:flex-row
                            sm:items-center gap-4">

                    {{-- Avatar --}}
                    @if ($staff->staff_image)
                        <img src="{{ asset($staff->staff_image) }}" alt="{{ $staff->name }}" class="h-20 w-20 shrink-0 rounded-2xl object-cover ring-2 ring-white shadow-sm">
                    @else
                        <div class="w-20 h-20 rounded-2xl bg-[#128C7E] text-white flex items-center justify-center text-2xl font-bold shrink-0">{{ strtoupper(substr($staff->name, 0, 1)) }}</div>
                    @endif


                    {{-- Name --}}
                    <div class="flex-1">

                        <h2 class="text-xl font-bold text-slate-800">
                            {{ $staff->name }}
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            {{ $staff->designation }}
                        </p>

                        <div class="flex items-center gap-2 mt-3">

                            <span class="px-3 py-1.5 rounded-lg
                                         bg-white border border-slate-200
                                         text-xs font-semibold
                                         text-[#128C7E]">

                                {{ $staff->staff_id }}

                            </span>

                            <span class="px-3 py-1.5 rounded-lg
                                         bg-emerald-100 text-emerald-700
                                         text-xs font-semibold">

                                Active

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <div class="border-b border-slate-200 px-6 pt-4">
                <div class="flex gap-2 overflow-x-auto" role="tablist">
                    <button type="button" data-profile-tab="personal" class="profile-tab whitespace-nowrap border-b-2 border-[#128C7E] px-4 pb-3 text-sm font-semibold text-[#128C7E]">Personal Details</button>
                    <button type="button" data-profile-tab="account" class="profile-tab whitespace-nowrap border-b-2 border-transparent px-4 pb-3 text-sm font-semibold text-slate-500">Account Details</button>
                    <button type="button" data-profile-tab="bank" class="profile-tab whitespace-nowrap border-b-2 border-transparent px-4 pb-3 text-sm font-semibold text-slate-500">Bank &amp; Emergency</button>
                    <button type="button" data-profile-tab="documents" class="profile-tab whitespace-nowrap border-b-2 border-transparent px-4 pb-3 text-sm font-semibold text-slate-500">Documents</button>
                </div>
            </div>

            {{-- Details --}}
            <div class="p-6">

                <section data-profile-panel="personal" class="profile-panel">

                <h3 class="text-base font-bold text-slate-800 mb-5">
                    Personal Information
                </h3>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Name --}}
                    <div class="p-4 rounded-xl bg-slate-50
                                border border-slate-100">

                        <p class="text-xs text-slate-400 mb-1">
                            Staff Name
                        </p>

                        <p class="text-sm font-semibold text-slate-700">
                            {{ $staff->name }}
                        </p>

                    </div>


                    {{-- Staff ID --}}
                    <div class="p-4 rounded-xl bg-slate-50
                                border border-slate-100">

                        <p class="text-xs text-slate-400 mb-1">
                            Staff ID
                        </p>

                        <p class="text-sm font-semibold text-slate-700">
                            {{ $staff->staff_id }}
                        </p>

                    </div>


                    {{-- Mobile --}}
                    <div class="p-4 rounded-xl bg-slate-50
                                border border-slate-100">

                        <p class="text-xs text-slate-400 mb-1">
                            Mobile Number
                        </p>

                        <p class="text-sm font-semibold text-slate-700">
                            {{ $staff->mobile }}
                        </p>

                    </div>


                    {{-- Email --}}
                    <div class="p-4 rounded-xl bg-slate-50
                                border border-slate-100">

                        <p class="text-xs text-slate-400 mb-1">
                            Email Address
                        </p>

                        <p class="text-sm font-semibold text-slate-700">
                            {{ $staff->email }}
                        </p>

                    </div>


                    {{-- Designation --}}
                    <div class="p-4 rounded-xl bg-slate-50
                                border border-slate-100">

                        <p class="text-xs text-slate-400 mb-1">
                            Designation
                        </p>

                        <p class="text-sm font-semibold text-slate-700">
                            {{ $staff->designation }}
                        </p>

                    </div>


                    {{-- Store --}}
                    <div class="p-4 rounded-xl bg-slate-50
                                border border-slate-100">

                        <p class="text-xs text-slate-400 mb-1">
                            Store
                        </p>

                        <p class="text-sm font-semibold text-slate-700">
                            {{ optional($staff->store)->name ?? '-' }}
                        </p>

                    </div>

                </div>
                </section>


                {{-- Account Information --}}
                <section data-profile-panel="account" class="profile-panel hidden">

                    <h3 class="text-base font-bold text-slate-800 mb-5">
                        Account Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div class="p-4 rounded-xl bg-slate-50
                                    border border-slate-100">

                            <p class="text-xs text-slate-400 mb-1">
                                Role
                            </p>

                            <p class="text-sm font-semibold text-slate-700">
                                {{ optional($staff->roleMaster)->role_name ?? '-' }}
                            </p>

                        </div>


                        <div class="p-4 rounded-xl bg-slate-50
                                    border border-slate-100">

                            <p class="text-xs text-slate-400 mb-1">
                                Created At
                            </p>

                            <p class="text-sm font-semibold text-slate-700">
                                {{ $staff->created_at?->format('d M Y, h:i A') }}
                            </p>

                        </div>

                    </div>

                </section>

                <section data-profile-panel="bank" class="profile-panel hidden">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100"><p class="text-xs text-slate-400 mb-1">Emergency Contact</p><p class="text-sm font-semibold text-slate-700">{{ $staff->emergency_contact_name ?: '-' }}</p></div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100"><p class="text-xs text-slate-400 mb-1">Emergency Mobile</p><p class="text-sm font-semibold text-slate-700">{{ $staff->emergency_contact_mobile ?: '-' }}</p></div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100"><p class="text-xs text-slate-400 mb-1">Bank Name</p><p class="text-sm font-semibold text-slate-700">{{ $staff->bank_name ?: '-' }}</p></div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100"><p class="text-xs text-slate-400 mb-1">Account Number</p><p class="text-sm font-semibold text-slate-700">{{ $staff->bank_account_number ?: '-' }}</p></div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100"><p class="text-xs text-slate-400 mb-1">IFSC Code</p><p class="text-sm font-semibold text-slate-700">{{ $staff->bank_ifsc_code ?: '-' }}</p></div>
                    </div>
                </section>

                <section data-profile-panel="documents" class="profile-panel hidden">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        @forelse ($staff->documents ?? [] as $document)
                            <a href="{{ asset($document) }}" target="_blank" class="mr-2 inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm font-medium text-[#128C7E] shadow-sm"><i class="fas fa-paperclip"></i> Document {{ $loop->iteration }}</a>
                        @empty
                            <p class="text-sm text-slate-400">No documents uploaded.</p>
                        @endforelse
                    </div>
                </section>

            </div>


            {{-- Footer --}}
            <div class="px-6 py-4 bg-slate-50
                        border-t border-slate-200
                        flex justify-end gap-3">

                <a
                    href="{{ url()->previous() }}"
                    class="h-10 px-5 rounded-xl
                           border border-slate-200 bg-white
                           text-slate-600 text-sm font-semibold
                           flex items-center justify-center gap-2
                           hover:bg-slate-100 transition"
                >
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>

            </div>

        </div>

        @if (Auth::guard('pos')->id() === $staff->id)
            <div id="change-profile-image" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-800">Change Profile Image</h2><p class="mt-1 text-sm text-slate-400">JPG, PNG or WEBP. Maximum 2 MB.</p>
                <form action="{{ route('pos.profile.image.update') }}" method="POST" enctype="multipart/form-data" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">@csrf
                    <input required type="file" name="staff_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600">
                    <button class="h-10 shrink-0 rounded-xl bg-[#128C7E] px-5 text-sm font-semibold text-white">Save Image</button>
                </form>
                @error('staff_image')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        @endif

    </div>

</div>

<script>
document.querySelectorAll('.profile-tab').forEach(function (tab) {
    tab.addEventListener('click', function () {
        var key = tab.dataset.profileTab;
        document.querySelectorAll('.profile-tab').forEach(function (item) { item.classList.remove('border-[#128C7E]', 'text-[#128C7E]'); item.classList.add('border-transparent', 'text-slate-500'); });
        document.querySelectorAll('.profile-panel').forEach(function (panel) { panel.classList.toggle('hidden', panel.dataset.profilePanel !== key); });
        tab.classList.remove('border-transparent', 'text-slate-500'); tab.classList.add('border-[#128C7E]', 'text-[#128C7E]');
    });
});
</script>
@endsection
