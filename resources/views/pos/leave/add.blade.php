@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Add Leave
                </h1>

                <p class="text-sm text-slate-400 mt-1">
                    Create a new leave request
                </p>

            </div>

            <a
                href="{{ route('leave.index') }}"
                class="h-10 px-4 rounded-xl
                       bg-white border border-slate-200
                       text-slate-600 text-sm font-semibold
                       flex items-center gap-2
                       hover:bg-slate-50 transition"
            >
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

        </div>


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="mb-5 p-4 rounded-xl
                        bg-red-50 border border-red-200">

                <div class="flex items-start gap-3">

                    <i class="fas fa-exclamation-circle
                              text-red-500 mt-0.5"></i>

                    <div>

                        <p class="text-sm font-semibold text-red-700">
                            Please fix the following errors:
                        </p>

                        <ul class="mt-2 text-xs text-red-600 space-y-1">

                            @foreach($errors->all() as $error)

                                <li>
                                    • {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        {{-- Form Card --}}
        <div class="bg-white rounded-2xl
                    border border-slate-200
                    shadow-sm overflow-hidden">


            {{-- Card Header --}}
            <div class="px-6 py-5
                        border-b border-slate-200">

                <div class="flex items-center gap-3">

                    <div
                        class="w-11 h-11 rounded-xl
                               bg-emerald-50
                               text-[#128C7E]
                               flex items-center justify-center"
                    >

                        <i class="fas fa-calendar-plus text-lg"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-bold text-slate-800">
                            Leave Information
                        </h2>

                        <p class="text-xs text-slate-400 mt-1">
                            Enter leave details below
                        </p>

                    </div>

                </div>

            </div>


            {{-- Form --}}
            <form
                action="{{ route('leave.save') }}"
                method="POST"
                id="leaveForm"
            >

                @csrf

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        

                        {{-- Leave Type --}}
                        <div>

                            <label
                                class="block text-sm font-semibold
                                       text-slate-700 mb-2"
                            >
                                Leave Type
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <span
                                    class="absolute left-4 top-1/2
                                           -translate-y-1/2
                                           text-slate-400"
                                >
                                    <i class="fas fa-calendar-check"></i>
                                </span>

                                <select
                                    name="leave_type"
                                    required
                                    class="w-full h-12 pl-11 pr-4
                                           rounded-xl
                                           border border-slate-200
                                           bg-slate-50
                                           text-sm text-slate-700
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-[#128C7E]/20
                                           focus:border-[#128C7E]"
                                >

                                    <option value="">
                                        Select Leave Type
                                    </option>

                                    <option
                                        value="Casual Leave"
                                        {{ old('leave_type') == 'Casual Leave' ? 'selected' : '' }}
                                    >
                                        Casual Leave
                                    </option>

                                    <option
                                        value="Sick Leave"
                                        {{ old('leave_type') == 'Sick Leave' ? 'selected' : '' }}
                                    >
                                        Sick Leave
                                    </option>

                                    <option
                                        value="Earned Leave"
                                        {{ old('leave_type') == 'Earned Leave' ? 'selected' : '' }}
                                    >
                                        Earned Leave
                                    </option>

                                    <option
                                        value="Emergency Leave"
                                        {{ old('leave_type') == 'Emergency Leave' ? 'selected' : '' }}
                                    >
                                        Emergency Leave
                                    </option>

                                    <option
                                        value="Unpaid Leave"
                                        {{ old('leave_type') == 'Unpaid Leave' ? 'selected' : '' }}
                                    >
                                        Unpaid Leave
                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- Total Days --}}
                        <div>

                            <label
                                class="block text-sm font-semibold
                                       text-slate-700 mb-2"
                            >
                                Total Days
                            </label>

                            <div class="relative">

                                <span
                                    class="absolute left-4 top-1/2
                                           -translate-y-1/2
                                           text-slate-400"
                                >
                                    <i class="fas fa-calculator"></i>
                                </span>

                                <input
                                    type="text"
                                    id="totalDays"
                                    name="total_days"
                                    value="{{ old('total_days') }}"
                                    readonly
                                    placeholder="Auto calculated"
                                    class="w-full h-12 pl-11 pr-4
                                           rounded-xl
                                           border border-slate-200
                                           bg-slate-100
                                           text-sm text-slate-700"
                                >

                            </div>

                        </div>


                        {{-- Start Date --}}
                        <div>

                            <label
                                class="block text-sm font-semibold
                                       text-slate-700 mb-2"
                            >
                                Start Date
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <span
                                    class="absolute left-4 top-1/2
                                           -translate-y-1/2
                                           text-slate-400"
                                >
                                    <i class="fas fa-calendar"></i>
                                </span>

                                <input
                                    type="date"
                                    name="start_date"
                                    id="startDate"
                                    value="{{ old('start_date') }}"
                                    required
                                    class="w-full h-12 pl-11 pr-4
                                           rounded-xl
                                           border border-slate-200
                                           bg-slate-50
                                           text-sm text-slate-700
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-[#128C7E]/20
                                           focus:border-[#128C7E]"
                                >

                            </div>

                        </div>


                        {{-- End Date --}}
                        <div>

                            <label
                                class="block text-sm font-semibold
                                       text-slate-700 mb-2"
                            >
                                End Date
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <span
                                    class="absolute left-4 top-1/2
                                           -translate-y-1/2
                                           text-slate-400"
                                >
                                    <i class="fas fa-calendar"></i>
                                </span>

                                <input
                                    type="date"
                                    name="end_date"
                                    id="endDate"
                                    value="{{ old('end_date') }}"
                                    required
                                    class="w-full h-12 pl-11 pr-4
                                           rounded-xl
                                           border border-slate-200
                                           bg-slate-50
                                           text-sm text-slate-700
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-[#128C7E]/20
                                           focus:border-[#128C7E]"
                                >

                            </div>

                        </div>


                        {{-- Reason --}}
                        <div class="md:col-span-2">

                            <label
                                class="block text-sm font-semibold
                                       text-slate-700 mb-2"
                            >
                                Reason
                                <span class="text-slate-400 font-normal">
                                    (Optional)
                                </span>
                            </label>

                            <textarea
                                name="reason"
                                rows="4"
                                placeholder="Enter leave reason..."
                                class="w-full p-4 rounded-xl
                                       border border-slate-200
                                       bg-slate-50
                                       text-sm text-slate-700
                                       resize-none
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-[#128C7E]/20
                                       focus:border-[#128C7E]"
                            >{{ old('reason') }}</textarea>

                        </div>

                    </div>


                    {{-- Status Information --}}
                    <div
                        class="mt-6 p-4 rounded-xl
                               bg-orange-50
                               border border-orange-100"
                    >

                        <div class="flex items-start gap-3">

                            <div
                                class="w-9 h-9 rounded-lg
                                       bg-white text-orange-500
                                       flex items-center
                                       justify-center shrink-0"
                            >

                                <i class="fas fa-clock"></i>

                            </div>

                            <div>

                                <p class="text-sm font-semibold
                                          text-orange-800">

                                    Leave Status

                                </p>

                                <p class="text-xs text-orange-600 mt-1">

                                    This leave request will be created with
                                    <strong>Pending</strong> status and will
                                    require manager approval.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Footer --}}
                <div
                    class="px-6 py-4 bg-slate-50
                           border-t border-slate-200
                           flex items-center justify-end gap-3"
                >

                    <a
                        href="{{ route('leave.index') }}"
                        class="h-11 px-5 rounded-xl
                               border border-slate-200
                               bg-white text-slate-600
                               text-sm font-semibold
                               flex items-center
                               justify-center gap-2
                               hover:bg-slate-100 transition"
                    >

                        <i class="fas fa-times"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="h-11 px-6 rounded-xl
                               bg-[#128C7E]
                               text-white text-sm font-semibold
                               flex items-center
                               justify-center gap-2
                               hover:bg-[#0f766e] transition"
                    >

                        <i class="fas fa-paper-plane"></i>

                        Submit Leave

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- Calculate Days --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const startDate = document.getElementById('startDate');
    const endDate   = document.getElementById('endDate');
    const totalDays = document.getElementById('totalDays');


    function calculateDays()
    {
        if (!startDate.value || !endDate.value) {

            totalDays.value = '';

            return;
        }


        const start = new Date(startDate.value);
        const end   = new Date(endDate.value);


        if (end < start) {

            totalDays.value = '';

            endDate.setCustomValidity(
                'End date cannot be before start date.'
            );

            return;
        }


        endDate.setCustomValidity('');


        const difference =
            end.getTime() - start.getTime();


        const days =
            Math.floor(
                difference / (1000 * 60 * 60 * 24)
            ) + 1;


        totalDays.value = days;

    }


    startDate.addEventListener('change', function () {

        if (endDate.value &&
            endDate.value < startDate.value) {

            endDate.value = '';

        }

        endDate.min = startDate.value;

        calculateDays();

    });


    endDate.addEventListener('change', calculateDays);


    calculateDays();

});

</script>

@endsection