@extends('pos.layout.app')

@section('content')
    <style>
        @media print {
            .pos-sidebar, .pos-mobile-sidebar, .no-print, header, footer { display: none !important; }
            body, main { background: #fff !important; }
            .print-area { padding: 0 !important; }
            .shadow-sm { box-shadow: none !important; }
        }
    </style>

    <div class="print-area flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
        <div class="mx-auto max-w-7xl">
            <div class="no-print mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">All Staff Tasks</h1>
                    <p class="mt-1 text-sm text-slate-400">Tasks assigned to every employee</p>
                </div>
                <a href="{{ route('pos.staff.tasks.print') }}" target="_blank" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#128C7E] px-5 text-sm font-semibold text-white hover:bg-[#0f766e]">
                    <i class="fas fa-print"></i> Print Tasks
                </a>
            </div>

            <div class="mb-6 hidden print:block">
                <h1 class="text-xl font-bold text-slate-800">All Staff Tasks</h1>
                <p class="mt-1 text-sm text-slate-500">Printed on {{ now()->format('d M Y, h:i A') }}</p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4"><h2 class="font-bold text-slate-800">{{ $tasks->total() }} Task(s)</h2></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                            <tr><th class="px-5 py-3">Employee</th><th class="px-5 py-3">Task</th><th class="px-5 py-3">Image</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($tasks as $task)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-4"><p class="font-semibold text-slate-800">{{ $task->staff?->name ?: '-' }}</p><p class="mt-1 text-xs text-slate-400">{{ $task->staff?->staff_id ?: '-' }}</p></td>
                                    <td class="px-5 py-4"><p class="font-semibold text-slate-700">{{ $task->title }}</p>@if ($task->description)<p class="mt-1 max-w-sm text-xs text-slate-500">{{ $task->description }}</p>@endif</td>
                                    <td class="px-5 py-4">@if ($task->task_image)<a href="{{ asset($task->task_image) }}" target="_blank"><img src="{{ asset($task->task_image) }}" alt="{{ $task->title }}" class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200"></a>@else <span class="text-slate-400">-</span> @endif</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-5 py-16 text-center text-sm text-slate-400">No staff tasks found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($tasks->hasPages())<div class="no-print border-t border-slate-200 px-5 py-4">{{ $tasks->links() }}</div>@endif
            </div>
        </div>
    </div>
@endsection
