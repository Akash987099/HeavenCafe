@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="mx-auto">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">
                Create Staff
            </h1>

            <p class="text-sm text-slate-400 mt-1">
                Create a new staff account
            </p>
        </div>


        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200
                        text-emerald-700 text-sm">
                {{ session('success') }}
            </div>
        @endif


        {{-- Error Message --}}
        @if(session('error'))
            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200
                        text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif


        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Form Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-xl bg-emerald-50
                                text-[#128C7E] flex items-center justify-center">

                        <i class="fas fa-user-plus text-lg"></i>

                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Staff Information
                        </h2>

                        <p class="text-xs text-slate-400 mt-1">
                            Enter staff details below
                        </p>
                    </div>

                </div>

            </div>


            {{-- Form --}}
            <form action="{{ route('pos.staff.save') }}" method="POST">

                @csrf

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Name --}}
                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Staff Name
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <span class="absolute left-4 top-1/2
                                             -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-user"></i>
                                </span>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="Enter staff name"
                                    class="w-full h-12 pl-11 pr-4 rounded-xl
                                           border border-slate-200 bg-slate-50
                                           text-sm text-slate-700
                                           focus:outline-none focus:ring-2
                                           focus:ring-[#128C7E]/20
                                           focus:border-[#128C7E]"
                                >

                            </div>

                        </div>


                        {{-- Mobile --}}
                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Mobile
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <span class="absolute left-4 top-1/2
                                             -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-phone"></i>
                                </span>

                                <input
                                    type="text"
                                    name="mobile"
                                    value="{{ old('mobile') }}"
                                    maxlength="10"
                                    required
                                    placeholder="Enter 10 digit mobile"
                                    class="w-full h-12 pl-11 pr-4 rounded-xl
                                           border border-slate-200 bg-slate-50
                                           text-sm text-slate-700
                                           focus:outline-none focus:ring-2
                                           focus:ring-[#128C7E]/20
                                           focus:border-[#128C7E]"
                                >

                            </div>

                        </div>


                        {{-- Email --}}
                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Email
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <span class="absolute left-4 top-1/2
                                             -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-envelope"></i>
                                </span>

                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="Enter email address"
                                    class="w-full h-12 pl-11 pr-4 rounded-xl
                                           border border-slate-200 bg-slate-50
                                           text-sm text-slate-700
                                           focus:outline-none focus:ring-2
                                           focus:ring-[#128C7E]/20
                                           focus:border-[#128C7E]"
                                >

                            </div>

                        </div>


                    


                        {{-- Password --}}
                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Password
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <span class="absolute left-4 top-1/2
                                             -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>

                                <input
                                    type="password"
                                    name="password"
                                    required
                                    placeholder="Enter password"
                                    class="w-full h-12 pl-11 pr-4 rounded-xl
                                           border border-slate-200 bg-slate-50
                                           text-sm text-slate-700
                                           focus:outline-none focus:ring-2
                                           focus:ring-[#128C7E]/20
                                           focus:border-[#128C7E]"
                                >

                            </div>

                        </div>


                        {{-- Confirm Password --}}
                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Confirm Password
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <span class="absolute left-4 top-1/2
                                             -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    placeholder="Confirm password"
                                    class="w-full h-12 pl-11 pr-4 rounded-xl
                                           border border-slate-200 bg-slate-50
                                           text-sm text-slate-700
                                           focus:outline-none focus:ring-2
                                           focus:ring-[#128C7E]/20
                                           focus:border-[#128C7E]"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- Staff ID Information --}}
                    <div class="mt-6 p-4 rounded-xl bg-emerald-50
                                border border-emerald-100">

                        <div class="flex items-start gap-3">

                            <div class="w-9 h-9 rounded-lg bg-white
                                        text-[#128C7E] flex items-center
                                        justify-center shrink-0">

                                <i class="fas fa-id-card"></i>

                            </div>

                            <div>

                                <p class="text-sm font-semibold text-emerald-800">
                                    Staff ID
                                </p>

                                <p class="text-xs text-emerald-600 mt-1">
                                    Staff ID will be automatically generated
                                    after creating the staff account.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Footer --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200
                            flex items-center justify-end gap-3">

                    <a
                        href="{{ url()->previous() }}"
                        class="h-11 px-5 rounded-xl border border-slate-200
                               bg-white text-slate-600 text-sm font-semibold
                               flex items-center justify-center
                               hover:bg-slate-100 transition"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="h-11 px-6 rounded-xl bg-[#128C7E]
                               text-white text-sm font-semibold
                               flex items-center justify-center gap-2
                               hover:bg-[#0f766e] transition"
                    >

                        <i class="fas fa-user-plus"></i>

                        Create Staff

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection