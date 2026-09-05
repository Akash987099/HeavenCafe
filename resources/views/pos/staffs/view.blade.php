@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="max-w-5xl mx-auto">

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
                    <div class="w-20 h-20 rounded-2xl
                                bg-[#128C7E] text-white
                                flex items-center justify-center
                                text-2xl font-bold shrink-0">

                        {{ strtoupper(substr($staff->name, 0, 1)) }}

                    </div>


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


            {{-- Details --}}
            <div class="p-6">

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


                {{-- Account Information --}}
                <div class="mt-8">

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

                </div>

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

    </div>

</div>

@endsection
