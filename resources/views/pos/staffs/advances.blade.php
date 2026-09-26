@extends('pos.layout.app')

@section('content')
    <div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
        <div class="mx-auto max-w-6xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Salary Advances</h1>
                    <p class="mt-1 text-sm text-slate-400">{{ $staff->name }} &middot; {{ $staff->staff_id }}</p>
                </div>
                <a href="{{ route('pos.staff') }}"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    <i class="fas fa-arrow-left"></i> Back to Staffs
                </a>
            </div>

            @if (session('success'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 grid grid-cols-1 gap-5 lg:grid-cols-3">
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                    <p class="text-sm font-medium text-emerald-700">This month&apos;s advances</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-900">&#8377;{{ number_format($currentMonthAdvance, 2) }}</p>
                    <p class="mt-1 text-xs text-emerald-700">Multiple entries are allowed each month.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 lg:col-span-2">
                    <h2 class="text-base font-bold text-slate-800">Add salary advance</h2>
                    <form action="{{ route('pos.staff.advances.store', $staff->id) }}" method="POST" class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                        @csrf
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Amount <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" value="{{ old('amount') }}" min="0.01" step="0.01" required placeholder="0.00"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700 focus:border-[#128C7E] focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20">
                            @error('amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Advance date <span class="text-red-500">*</span></label>
                            <input type="date" name="advance_date" value="{{ old('advance_date', now()->toDateString()) }}" required
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700 focus:border-[#128C7E] focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20">
                            @error('advance_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Note</label>
                            <input type="text" name="note" value="{{ old('note') }}" maxlength="1000" placeholder="Optional note"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700 focus:border-[#128C7E] focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20">
                            @error('note') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-3 flex justify-end">
                            <button type="submit" class="inline-flex h-11 items-center gap-2 rounded-xl bg-[#128C7E] px-5 text-sm font-semibold text-white hover:bg-[#0f766e]">
                                <i class="fas fa-plus"></i> Add Advance
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="font-bold text-slate-800">Advance history</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                            <tr><th class="px-5 py-3">#</th><th class="px-5 py-3">Date</th><th class="px-5 py-3">Note</th><th class="px-5 py-3 text-right">Amount</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($advances as $advance)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-4 text-slate-500">{{ $advances->firstItem() + $loop->index }}</td>
                                    <td class="px-5 py-4 font-medium text-slate-700">{{ $advance->advance_date?->format('d M Y') }}</td>
                                    <td class="px-5 py-4 text-slate-600">{{ $advance->note ?: '-' }}</td>
                                    <td class="px-5 py-4 text-right font-semibold text-slate-800">&#8377;{{ number_format($advance->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-14 text-center text-sm text-slate-400">No salary advances added yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($advances->hasPages())
                    <div class="border-t border-slate-200 px-5 py-4">{{ $advances->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
