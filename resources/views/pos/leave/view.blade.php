@extends('pos.layout.app')

@section('content')

    <div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

        <div class="mx-auto">

            {{-- Header --}}
            <div
                class="flex flex-col sm:flex-row
                    sm:items-center sm:justify-between
                    gap-4 mb-6">

                <div>

                    <h1 class="text-2xl font-bold text-slate-800">
                        Leave Details
                    </h1>

                    <p class="text-sm text-slate-400 mt-1">
                        View complete leave request details
                    </p>

                </div>

                <a href="{{ route('leave.index') }}"
                    class="h-10 px-4 rounded-xl
                       bg-white border border-slate-200
                       text-slate-600 text-sm font-semibold
                       flex items-center justify-center
                       gap-2 hover:bg-slate-50 transition">

                    <i class="fas fa-arrow-left"></i>

                    Back

                </a>

            </div>


            {{-- Main Card --}}
            <div
                class="bg-white rounded-2xl
                   border border-slate-200
                   shadow-sm overflow-hidden">

                {{-- Staff Header --}}
                <div
                    class="p-6 bg-gradient-to-r
                       from-emerald-50 to-white
                       border-b border-slate-200">

                    <div class="flex items-center gap-4">

                        {{-- Avatar --}}
                        <div
                            class="w-16 h-16 rounded-2xl
                               bg-[#128C7E] text-white
                               flex items-center justify-center
                               text-xl font-bold shrink-0">

                            {{ strtoupper(substr(optional($leave->pos)->name ?? 'S', 0, 1)) }}

                        </div>


                        <div class="flex-1">

                            <h2 class="text-xl font-bold text-slate-800">

                                {{ optional($leave->pos)->name ?? 'N/A' }}

                            </h2>

                            <p class="text-sm text-slate-500 mt-1">

                                {{ optional($leave->pos)->designation ?? 'Staff' }}

                            </p>

                            <div class="flex flex-wrap items-center
                                    gap-2 mt-3">

                                <span
                                    class="px-3 py-1.5 rounded-lg
                                       bg-white border border-slate-200
                                       text-xs font-semibold
                                       text-[#128C7E]">

                                    {{ optional($leave->pos)->staff_id ?? 'N/A' }}

                                </span>

                                @if ($leave->status == 'approved')
                                    <span
                                        class="px-3 py-1.5 rounded-lg
                                           bg-emerald-100
                                           text-emerald-700
                                           text-xs font-semibold">
                                        <i class="fas fa-check mr-1"></i>
                                        Approved
                                    </span>
                                @elseif($leave->status == 'rejected')
                                    <span
                                        class="px-3 py-1.5 rounded-lg
                                           bg-red-100 text-red-700
                                           text-xs font-semibold">
                                        <i class="fas fa-times mr-1"></i>
                                        Rejected
                                    </span>
                                @elseif($leave->status == 'cancelled')
                                    <span
                                        class="px-3 py-1.5 rounded-lg
                                           bg-slate-100 text-slate-600
                                           text-xs font-semibold">
                                        Cancelled
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1.5 rounded-lg
                                           bg-orange-100 text-orange-700
                                           text-xs font-semibold">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending
                                    </span>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Leave Information --}}
                <div class="p-6">

                    <h3 class="text-base font-bold
                           text-slate-800 mb-5">
                        Leave Information
                    </h3>


                    <div class="grid grid-cols-1
                            md:grid-cols-2 gap-5">


                        {{-- Leave Type --}}
                        <div
                            class="p-4 rounded-xl
                               bg-slate-50
                               border border-slate-100">

                            <p class="text-xs text-slate-400 mb-1">
                                Leave Type
                            </p>

                            <p class="text-sm font-semibold text-slate-700">

                                {{ $leave->leave_type }}

                            </p>

                        </div>


                        {{-- Total Days --}}
                        <div
                            class="p-4 rounded-xl
                               bg-slate-50
                               border border-slate-100">

                            <p class="text-xs text-slate-400 mb-1">
                                Total Days
                            </p>

                            <p class="text-sm font-semibold text-slate-700">

                                {{ $leave->total_days }}

                                {{ $leave->total_days == 1 ? 'Day' : 'Days' }}

                            </p>

                        </div>


                        {{-- Start Date --}}
                        <div
                            class="p-4 rounded-xl
                               bg-slate-50
                               border border-slate-100">

                            <p class="text-xs text-slate-400 mb-1">
                                Start Date
                            </p>

                            <p class="text-sm font-semibold text-slate-700">

                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}

                            </p>

                        </div>


                        {{-- End Date --}}
                        <div
                            class="p-4 rounded-xl
                               bg-slate-50
                               border border-slate-100">

                            <p class="text-xs text-slate-400 mb-1">
                                End Date
                            </p>

                            <p class="text-sm font-semibold text-slate-700">

                                {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}

                            </p>

                        </div>


                        {{-- Reason --}}
                        <div
                            class="md:col-span-2
                               p-4 rounded-xl
                               bg-slate-50
                               border border-slate-100">

                            <p class="text-xs text-slate-400 mb-2">
                                Reason
                            </p>

                            <p class="text-sm text-slate-700 leading-6">

                                {{ $leave->reason ?: 'No reason provided.' }}

                            </p>

                        </div>

                    </div>


                    {{-- Approval Information --}}
                    <div class="mt-8">

                        <h3 class="text-base font-bold
                               text-slate-800 mb-5">
                            Approval Information
                        </h3>


                        <div class="grid grid-cols-1
                                md:grid-cols-2 gap-5">


                            {{-- Approved By --}}
                            <div
                                class="p-4 rounded-xl
                                   bg-slate-50
                                   border border-slate-100">

                                <p class="text-xs text-slate-400 mb-1">
                                    Approved / Rejected By
                                </p>

                                <p class="text-sm font-semibold text-slate-700">

                                    {{ optional($leave->approver)->name ?? '-' }}

                                </p>

                            </div>


                            {{-- Approved At --}}
                            <div
                                class="p-4 rounded-xl
                                   bg-slate-50
                                   border border-slate-100">

                                <p class="text-xs text-slate-400 mb-1">
                                    Action Date
                                </p>

                                <p class="text-sm font-semibold text-slate-700">

                                    @if ($leave->approved_at)
                                        {{ \Carbon\Carbon::parse($leave->approved_at)->format('d M Y, h:i A') }}
                                    @else
                                        -
                                    @endif

                                </p>

                            </div>


                            {{-- Manager Remark --}}
                            <div
                                class="md:col-span-2
                                   p-4 rounded-xl
                                   bg-slate-50
                                   border border-slate-100">

                                <p class="text-xs text-slate-400 mb-2">
                                    Manager Remark
                                </p>

                                <p class="text-sm text-slate-700">

                                    {{ $leave->manager_remark ?: 'No remark added.' }}

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Created Information --}}
                    <div
                        class="mt-8 p-4 rounded-xl
                           bg-blue-50
                           border border-blue-100">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-9 h-9 rounded-lg
                                   bg-white text-blue-600
                                   flex items-center justify-center">

                                <i class="fas fa-info-circle"></i>

                            </div>

                            <div>

                                <p class="text-sm font-semibold
                                      text-blue-800">

                                    Leave Request Created

                                </p>

                                <p class="text-xs text-blue-600 mt-1">

                                    {{ $leave->created_at ? $leave->created_at->format('d M Y, h:i A') : '-' }}

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                @if (Auth::guard('pos')->user()->role == 1)
                    <div class="mt-8 p-5 rounded-2xl
                bg-slate-50 border border-slate-200">

                        <div class="flex items-center gap-3 mb-5">

                            <div
                                class="w-10 h-10 rounded-xl
                       bg-emerald-100 text-[#128C7E]
                       flex items-center justify-center">
                                <i class="fas fa-edit"></i>
                            </div>

                            <div>

                                <h3 class="text-base font-bold text-slate-800">
                                    Update Leave Status
                                </h3>

                                <p class="text-xs text-slate-400 mt-1">
                                    Approve or reject this leave request
                                </p>

                            </div>

                        </div>


                        <form action="{{ route('leave.status', $leave->id) }}" method="POST">

                            @csrf


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                                {{-- Status --}}
                                <div>

                                    <label
                                        class="block text-sm font-semibold
                               text-slate-700 mb-2">
                                        Leave Status
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative">

                                        <span
                                            class="absolute left-4 top-1/2
                                   -translate-y-1/2
                                   text-slate-400">
                                            <i class="fas fa-tasks"></i>
                                        </span>

                                        <select name="status" required
                                            class="w-full h-12 pl-11 pr-4
                                   rounded-xl
                                   border border-slate-200
                                   bg-white
                                   text-sm text-slate-700
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-[#128C7E]/20
                                   focus:border-[#128C7E]">

                                            <option value="pending" {{ $leave->status == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>

                                            <option value="approved" {{ $leave->status == 'approved' ? 'selected' : '' }}>
                                                Approved
                                            </option>

                                            <option value="rejected" {{ $leave->status == 'rejected' ? 'selected' : '' }}>
                                                Rejected
                                            </option>

                                            <option value="cancelled"
                                                {{ $leave->status == 'cancelled' ? 'selected' : '' }}>
                                                Cancelled
                                            </option>

                                        </select>

                                    </div>

                                </div>


                                {{-- Current Status --}}
                                <div>

                                    <label
                                        class="block text-sm font-semibold
                               text-slate-700 mb-2">
                                        Current Status
                                    </label>

                                    <div
                                        class="h-12 px-4 rounded-xl
                               bg-white border border-slate-200
                               flex items-center">

                                        @if ($leave->status == 'approved')
                                            <span
                                                class="text-sm font-semibold
                                         text-emerald-600">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Approved
                                            </span>
                                        @elseif($leave->status == 'rejected')
                                            <span
                                                class="text-sm font-semibold
                                         text-red-600">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                Rejected
                                            </span>
                                        @elseif($leave->status == 'cancelled')
                                            <span
                                                class="text-sm font-semibold
                                         text-slate-600">
                                                <i class="fas fa-ban mr-2"></i>
                                                Cancelled
                                            </span>
                                        @else
                                            <span
                                                class="text-sm font-semibold
                                         text-orange-600">
                                                <i class="fas fa-clock mr-2"></i>
                                                Pending
                                            </span>
                                        @endif

                                    </div>

                                </div>


                                {{-- Manager Remark --}}
                                <div class="md:col-span-2">

                                    <label
                                        class="block text-sm font-semibold
                               text-slate-700 mb-2">
                                        Manager Remark
                                    </label>

                                    <textarea name="manager_remark" rows="3" placeholder="Enter remark..."
                                        class="w-full p-4 rounded-xl
                               border border-slate-200
                               bg-white text-sm text-slate-700
                               resize-none
                               focus:outline-none
                               focus:ring-2
                               focus:ring-[#128C7E]/20
                               focus:border-[#128C7E]">{{ old('manager_remark', $leave->manager_remark) }}</textarea>

                                </div>

                            </div>


                            {{-- Submit --}}
                            <div class="flex justify-end mt-5">

                                <button type="submit"
                                    class="h-11 px-6 rounded-xl
                           bg-[#128C7E]
                           text-white text-sm font-semibold
                           flex items-center gap-2
                           hover:bg-[#0f766e]
                           transition">

                                    <i class="fas fa-save"></i>

                                    Update Status

                                </button>

                            </div>

                        </form>

                    </div>
                @endif


                {{-- Footer --}}
                <div
                    class="px-6 py-4 bg-slate-50
                       border-t border-slate-200
                       flex flex-wrap items-center
                       justify-end gap-3">

                    <a href="{{ route('leave.index') }}"
                        class="h-10 px-5 rounded-xl
                           border border-slate-200
                           bg-white text-slate-600
                           text-sm font-semibold
                           flex items-center justify-center
                           gap-2 hover:bg-slate-100 transition">

                        <i class="fas fa-arrow-left"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>

    </div>

@endsection
