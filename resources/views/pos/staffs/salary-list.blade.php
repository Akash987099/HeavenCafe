@extends('pos.layout.app')

@section('content')
    <div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
        <div class="mx-auto">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Salary List</h1>
                    <p class="mt-1 text-sm text-slate-400">All staff salary details for {{ $monthStart->format('F Y') }}</p>
                </div>
                <a href="{{ route('pos.staff') }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 hover:bg-slate-100"><i class="fas fa-arrow-left"></i> Staffs</a>
            </div>

            @if (session('success')) <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div> @endif

            <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <form method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                        <div><label class="mb-2 block text-sm font-semibold text-slate-700">Salary month</label><input type="month" name="month" value="{{ $selectedMonth }}" class="h-11 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700"></div>
                        <button type="submit" class="h-11 rounded-xl bg-slate-800 px-5 text-sm font-semibold text-white hover:bg-slate-700">View List</button>
                    </form>
                    @if ($isMonthClosed)
                        <form action="{{ route('pos.salary.list.send') }}" method="POST">@csrf <input type="hidden" name="month" value="{{ $selectedMonth }}"><button type="submit" onclick="return confirm('Send this salary list to Akash Kumar and Abhishek Singh?');" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 text-sm font-semibold text-white hover:bg-sky-700"><i class="fas fa-envelope"></i> Email Salary List</button></form>
                    @else
                        <p class="text-sm text-amber-700">The list can be emailed after this month ends.</p>
                    @endif
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500"><tr><th class="px-5 py-3">Staff</th><th class="px-5 py-3 text-right">Payable days</th><th class="px-5 py-3 text-right">Gross salary</th><th class="px-5 py-3 text-right">Leave deduction</th><th class="px-5 py-3 text-right">Advances</th><th class="px-5 py-3 text-right">Final payable</th></tr></thead><tbody class="divide-y divide-slate-100">
                    @forelse ($salaries as $salary)
                        <tr><td class="px-5 py-4"><p class="font-semibold text-slate-800">{{ $salary['staff']->name }}</p><p class="mt-1 text-xs text-slate-400">{{ $salary['staff']->staff_id }} &middot; {{ $salary['staff']->designation ?: 'Staff' }}</p></td><td class="px-5 py-4 text-right">{{ $salary['payable_days'] }}</td><td class="px-5 py-4 text-right">&#8377;{{ number_format($salary['gross_salary'], 2) }}</td><td class="px-5 py-4 text-right text-rose-600">- &#8377;{{ number_format($salary['leave_deduction'], 2) }}</td><td class="px-5 py-4 text-right text-rose-600">- &#8377;{{ number_format($salary['advance_total'], 2) }}</td><td class="px-5 py-4 text-right font-bold text-emerald-700">&#8377;{{ number_format($salary['final_salary'], 2) }}</td></tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No staff found.</td></tr>
                    @endforelse
                </tbody><tfoot class="border-t-2 border-slate-200 bg-slate-50 font-bold text-slate-800"><tr><td class="px-5 py-4" colspan="5">Total final payable</td><td class="px-5 py-4 text-right text-emerald-700">&#8377;{{ number_format($salaries->sum('final_salary'), 2) }}</td></tr></tfoot></table></div>
            </div>
        </div>
    </div>
@endsection
