@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row
                    md:items-center md:justify-between
                    gap-4 mb-6">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Leave Management
                </h1>

                <p class="text-slate-500 mt-1">
                    Manage staff leaves and leave requests.
                </p>

            </div>


            {{-- Add Leave --}}
            <a
                href="{{ route('leave.add') }}"
                class="h-11 px-5 rounded-xl
                       bg-[#128C7E] text-white
                       text-sm font-semibold
                       flex items-center justify-center
                       gap-2 hover:bg-[#0f766e]
                       transition"
            >

                <i class="fas fa-plus"></i>

                Add Leave

            </a>

        </div>


        {{-- Leave Summary --}}
        <div class="grid grid-cols-1 sm:grid-cols-2
                    lg:grid-cols-4 gap-5">


            {{-- Total --}}
            <div
                class="glass rounded-2xl p-5
                       border border-white/70
                       shadow-lg shadow-slate-200/40"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Total Leaves
                        </p>

                        <h3 class="text-3xl font-bold
                                   text-[#0F172A] mt-2">

                            {{ $totalLeaves }}

                        </h3>

                    </div>

                    <div
                        class="w-12 h-12 rounded-xl
                               bg-blue-100 text-blue-600
                               flex items-center justify-center"
                    >

                        <i class="fas fa-calendar-alt text-xl"></i>

                    </div>

                </div>

                <p class="text-xs text-slate-400 mt-4">
                    All leave requests
                </p>

            </div>


            {{-- Pending --}}
            <div
                class="glass rounded-2xl p-5
                       border border-white/70
                       shadow-lg shadow-slate-200/40"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Pending
                        </p>

                        <h3 class="text-3xl font-bold
                                   text-[#0F172A] mt-2">

                            {{ $pendingLeaves }}

                        </h3>

                    </div>

                    <div
                        class="w-12 h-12 rounded-xl
                               bg-orange-100 text-orange-600
                               flex items-center justify-center"
                    >

                        <i class="fas fa-clock text-xl"></i>

                    </div>

                </div>

                <p class="text-xs text-slate-400 mt-4">
                    Awaiting approval
                </p>

            </div>


            {{-- Approved --}}
            <div
                class="glass rounded-2xl p-5
                       border border-white/70
                       shadow-lg shadow-slate-200/40"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Approved
                        </p>

                        <h3 class="text-3xl font-bold
                                   text-[#0F172A] mt-2">

                            {{ $approvedLeaves }}

                        </h3>

                    </div>

                    <div
                        class="w-12 h-12 rounded-xl
                               bg-emerald-100 text-[#128C7E]
                               flex items-center justify-center"
                    >

                        <i class="fas fa-check-circle text-xl"></i>

                    </div>

                </div>

                <p class="text-xs text-slate-400 mt-4">
                    Approved leave requests
                </p>

            </div>


            {{-- Rejected --}}
            <div
                class="glass rounded-2xl p-5
                       border border-white/70
                       shadow-lg shadow-slate-200/40"
            >

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Rejected
                        </p>

                        <h3 class="text-3xl font-bold
                                   text-[#0F172A] mt-2">

                            {{ $rejectedLeaves }}

                        </h3>

                    </div>

                    <div
                        class="w-12 h-12 rounded-xl
                               bg-red-100 text-red-600
                               flex items-center justify-center"
                    >

                        <i class="fas fa-times-circle text-xl"></i>

                    </div>

                </div>

                <p class="text-xs text-slate-400 mt-4">
                    Rejected leave requests
                </p>

            </div>

        </div>


        {{-- Leave List --}}
        <div class="mt-6">

            <div
                class="bg-white rounded-2xl
                       border border-slate-200
                       shadow-sm overflow-hidden"
            >

                {{-- Header --}}
                <div
                    class="p-5 border-b border-slate-200
                           flex items-center justify-between"
                >

                    <div>

                        <h2 class="text-lg font-bold text-slate-800">
                            Leave Requests
                        </h2>

                        <p class="text-xs text-slate-400 mt-1">
                            Recent leave applications
                        </p>

                    </div>

                    <div
                        class="w-10 h-10 rounded-xl
                               bg-emerald-50 text-[#128C7E]
                               flex items-center justify-center"
                    >

                        <i class="fas fa-calendar-check"></i>

                    </div>

                </div>


                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr
                                class="bg-slate-50
                                       border-b border-slate-200"
                            >

                                <th class="px-5 py-4 text-left
                                           text-xs font-semibold
                                           text-slate-500">
                                    #
                                </th>

                                <th class="px-5 py-4 text-left
                                           text-xs font-semibold
                                           text-slate-500">
                                    Staff
                                </th>

                                <th class="px-5 py-4 text-left
                                           text-xs font-semibold
                                           text-slate-500">
                                    Leave Type
                                </th>

                                <th class="px-5 py-4 text-left
                                           text-xs font-semibold
                                           text-slate-500">
                                    Date
                                </th>

                                <th class="px-5 py-4 text-left
                                           text-xs font-semibold
                                           text-slate-500">
                                    Days
                                </th>

                                <th class="px-5 py-4 text-left
                                           text-xs font-semibold
                                           text-slate-500">
                                    Status
                                </th>

                                <th class="px-5 py-4 text-center
                                           text-xs font-semibold
                                           text-slate-500">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse($leaves as $key => $leave)

                                <tr class="hover:bg-slate-50 transition">

                                    <td class="px-5 py-4 text-sm
                                               text-slate-500">

                                        {{ $leaves->firstItem() + $key }}

                                    </td>


                                    <td class="px-5 py-4">

                                        <div>

                                            <p class="text-sm font-semibold
                                                      text-slate-800">
                                                    
                                                {{ optional($leave->pos)->staff_id ?? 'N/A' }}
                                                <br>
                                                {{ optional($leave->pos)->name ?? 'N/A' }}

                                            </p>

                                            <p class="text-xs text-slate-400 mt-1">

                                                {{ optional($leave->user)->staff_id ?? '' }}

                                            </p>

                                        </div>

                                    </td>


                                    <td class="px-5 py-4">

                                        <span
                                            class="text-sm font-medium
                                                   text-slate-700"
                                        >

                                            {{ $leave->leave_type }}

                                        </span>

                                    </td>


                                    <td class="px-5 py-4">

                                        <p class="text-sm text-slate-700">

                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}

                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">

                                            to

                                            {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}

                                        </p>

                                    </td>


                                    <td class="px-5 py-4">

                                        <span
                                            class="text-sm font-semibold
                                                   text-slate-700"
                                        >

                                            {{ $leave->total_days }}

                                        </span>

                                    </td>


                                    <td class="px-5 py-4">

                                        @if($leave->status == 'approved')

                                            <span
                                                class="inline-flex
                                                       items-center gap-1
                                                       px-3 py-1.5 rounded-lg
                                                       bg-emerald-50
                                                       text-emerald-600
                                                       text-xs font-semibold"
                                            >

                                                <i class="fas fa-check"></i>
                                                Approved

                                            </span>

                                        @elseif($leave->status == 'rejected')

                                            <span
                                                class="inline-flex
                                                       items-center gap-1
                                                       px-3 py-1.5 rounded-lg
                                                       bg-red-50 text-red-600
                                                       text-xs font-semibold"
                                            >

                                                <i class="fas fa-times"></i>
                                                Rejected

                                            </span>

                                        @else

                                            <span
                                                class="inline-flex
                                                       items-center gap-1
                                                       px-3 py-1.5 rounded-lg
                                                       bg-orange-50
                                                       text-orange-600
                                                       text-xs font-semibold"
                                            >

                                                <i class="fas fa-clock"></i>
                                                Pending

                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-5 py-4">

                                        <div class="flex items-center
                                                    justify-center gap-2">

                                            <a
                                                href="{{ route('leave.view', $leave->id) }}"
                                                class="w-9 h-9 rounded-lg
                                                       bg-slate-100
                                                       text-slate-500
                                                       flex items-center
                                                       justify-center
                                                       hover:bg-slate-200"
                                                title="View"
                                            >

                                                <i class="fas fa-eye text-xs"></i>

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-5 py-16 text-center"
                                    >

                                        <div
                                            class="w-16 h-16 mx-auto
                                                   rounded-2xl bg-slate-100
                                                   flex items-center
                                                   justify-center mb-4"
                                        >

                                            <i
                                                class="fas fa-calendar-times
                                                       text-slate-400 text-2xl"
                                            ></i>

                                        </div>

                                        <h3
                                            class="text-sm font-semibold
                                                   text-slate-700"
                                        >
                                            No Leave Requests
                                        </h3>

                                        <p
                                            class="text-xs text-slate-400 mt-1"
                                        >
                                            No leave applications found.
                                        </p>

                                        <a
                                            href="{{ route('leave.add') }}"
                                            class="inline-flex items-center
                                                   gap-2 mt-4 px-4 h-10
                                                   rounded-xl bg-[#128C7E]
                                                   text-white text-xs
                                                   font-semibold"
                                        >

                                            <i class="fas fa-plus"></i>

                                            Add Leave

                                        </a>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($leaves->hasPages())

                    <div class="p-4 border-t border-slate-200">

                        {{ $leaves->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection