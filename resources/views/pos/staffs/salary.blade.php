@extends('pos.layout.app')

@section('content')
    <div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
        <div class="mx-auto max-w-6xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Monthly Salary</h1>
                    <p class="mt-1 text-sm text-slate-400">{{ $staff->name }} &middot; {{ $staff->staff_id }} &middot; {{ $staff->designation ?: 'Staff' }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pos.staff.advances', $staff->id) }}"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#128C7E] px-5 text-sm font-semibold text-white hover:bg-[#0f766e]">
                        <i class="fas fa-hand-holding-usd"></i> Salary Advances
                    </a>
                    <a href="{{ route('pos.staff') }}"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <form method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Salary month</label>
                        <input type="month" name="month" value="{{ $selectedMonth }}"
                            class="h-11 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700 focus:border-[#128C7E] focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20">
                        @error('month') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="h-11 rounded-xl bg-slate-800 px-5 text-sm font-semibold text-white hover:bg-slate-700">View Salary</button>
                </form>
            </div>

            @if (! $isMonthClosed)
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                    <i class="fas fa-clock mt-0.5 text-amber-600"></i>
                    <p><span class="font-semibold">Provisional calculation:</span> {{ $monthStart->format('F Y') }} salary will be finalised on {{ $monthStart->copy()->endOfMonth()->format('d M Y') }}. Advances and approved leaves can still change the final amount.</p>
                </div>
            @endif

            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Monthly salary</p>
                    <p class="mt-2 text-2xl font-bold text-slate-800">&#8377;{{ number_format($monthlySalary, 2) }}</p>
                    <p class="mt-1 text-xs text-slate-400">&#8377;{{ number_format($dailySalary, 2) }} per day</p>
                </div>
                <div class="rounded-2xl border border-orange-100 bg-orange-50 p-5">
                    <p class="text-sm font-medium text-orange-700">Unpaid leave deduction</p>
                    <p class="mt-2 text-2xl font-bold text-orange-900">- &#8377;{{ number_format($leaveDeduction, 2) }}</p>
                    <p class="mt-1 text-xs text-orange-700">{{ $unpaidLeaveDays }} unpaid leave day(s)</p>
                </div>
                <div class="rounded-2xl border border-rose-100 bg-rose-50 p-5">
                    <p class="text-sm font-medium text-rose-700">Salary advances</p>
                    <p class="mt-2 text-2xl font-bold text-rose-900">- &#8377;{{ number_format($advanceTotal, 2) }}</p>
                    <p class="mt-1 text-xs text-rose-700">{{ $monthAdvances->count() }} entry / entries this month</p>
                </div>
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                    <p class="text-sm font-medium text-emerald-700">{{ $isMonthClosed ? 'Final payable salary' : 'Estimated payable salary' }}</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-900">&#8377;{{ number_format($finalSalary, 2) }}</p>
                    <p class="mt-1 text-xs text-emerald-700">{{ $isMonthClosed ? 'Finalised' : 'Finalises at month end' }} &middot; {{ $monthStart->format('F Y') }}</p>
                </div>
            </div>

            <div class="mb-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-1">
                    <h2 class="font-bold text-slate-800">Payroll details</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Working days</dt><dd class="font-semibold text-slate-700">{{ $monthStart->daysInMonth }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Approved paid leave</dt><dd class="font-semibold text-slate-700">{{ $paidLeaveDays }} day(s)</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Approved unpaid leave</dt><dd class="font-semibold text-slate-700">{{ $unpaidLeaveDays }} day(s)</dd></div>
                        <div class="flex justify-between gap-4 border-t border-slate-100 pt-3"><dt class="font-semibold text-slate-700">Total deductions</dt><dd class="font-bold text-rose-600">&#8377;{{ number_format($leaveDeduction + $advanceTotal, 2) }}</dd></div>
                    </dl>
                </div>
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
                    <div class="border-b border-slate-200 px-5 py-4"><h2 class="font-bold text-slate-800">Salary formula</h2></div>
                    <div class="p-5 text-sm text-slate-600">
                        <p>Monthly salary <span class="font-semibold text-slate-800">&#8377;{{ number_format($monthlySalary, 2) }}</span></p>
                        <p class="mt-2">Less unpaid leave deduction <span class="font-semibold text-rose-600">- &#8377;{{ number_format($leaveDeduction, 2) }}</span></p>
                        <p class="mt-2">Less salary advances <span class="font-semibold text-rose-600">- &#8377;{{ number_format($advanceTotal, 2) }}</span></p>
                        <div class="mt-4 border-t border-slate-200 pt-4 text-base font-bold text-emerald-700">{{ $isMonthClosed ? 'Final payable' : 'Estimated payable' }}: &#8377;{{ number_format($finalSalary, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4"><h2 class="font-bold text-slate-800">Approved leaves in {{ $monthStart->format('F Y') }}</h2></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500"><tr><th class="px-5 py-3">Type</th><th class="px-5 py-3">Period</th><th class="px-5 py-3 text-right">Days in month</th><th class="px-5 py-3 text-right">Salary impact</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($approvedLeaves as $leave)
                                <tr><td class="px-5 py-4 font-medium text-slate-700">{{ $leave->leave_type }}</td><td class="px-5 py-4 text-slate-600">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td><td class="px-5 py-4 text-right text-slate-700">{{ $leave->days_in_month }}</td><td class="px-5 py-4 text-right font-semibold {{ $leave->leave_type === 'Unpaid Leave' ? 'text-rose-600' : 'text-emerald-600' }}">{{ $leave->leave_type === 'Unpaid Leave' ? 'Deducted' : 'Paid' }}</td></tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-12 text-center text-sm text-slate-400">No approved leaves for this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4"><h2 class="font-bold text-slate-800">Salary advances in {{ $monthStart->format('F Y') }}</h2></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500"><tr><th class="px-5 py-3">Date</th><th class="px-5 py-3">Note</th><th class="px-5 py-3 text-right">Amount</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($monthAdvances as $advance)
                                <tr><td class="px-5 py-4 font-medium text-slate-700">{{ $advance->advance_date?->format('d M Y') }}</td><td class="px-5 py-4 text-slate-600">{{ $advance->note ?: '-' }}</td><td class="px-5 py-4 text-right font-semibold text-rose-600">- &#8377;{{ number_format($advance->amount, 2) }}</td></tr>
                            @empty
                                <tr><td colspan="3" class="px-5 py-12 text-center text-sm text-slate-400">No salary advances for this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
