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
        <div class="mx-auto">
            <div class="no-print mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">{{ $isOwnTasks ? 'My Tasks' : 'All Staff Tasks' }}</h1>
                    <p class="mt-1 text-sm text-slate-400">{{ $isOwnTasks ? 'Tasks assigned to you' : 'Tasks assigned to every employee' }}</p>
                </div>
                <a href="{{ route('pos.staff.tasks.print') }}" target="_blank" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#128C7E] px-5 text-sm font-semibold text-white hover:bg-[#0f766e]">
                    <i class="fas fa-print"></i> Print Tasks
                </a>
            </div>

            <div class="mb-6 hidden print:block">
                <h1 class="text-xl font-bold text-slate-800">{{ $isOwnTasks ? 'My Tasks' : 'All Staff Tasks' }}</h1>
                <p class="mt-1 text-sm text-slate-500">Printed on {{ now()->format('d M Y, h:i A') }}</p>
            </div>

            <div class="mb-4 text-sm font-semibold text-slate-500">{{ $tasks->total() }} Task(s)</div>

            @forelse ($tasks->groupBy('staff_id') as $staffTasks)
                @php($employee = $staffTasks->first()->staff)
                <section class="mb-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600"><i class="fas fa-user"></i></div>
                        <div>
                            <h2 class="font-bold text-slate-800">{{ $employee?->name ?: '-' }}</h2>
                            <p class="mt-0.5 text-xs text-slate-400">{{ $employee?->staff_id ?: '-' }} &middot; {{ $staffTasks->count() }} task(s)</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 p-4 lg:grid-cols-4">
                        @foreach ($staffTasks as $task)
                            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white p-3">
                                <a href="{{ $task->task_image ? asset($task->task_image) : '#' }}" {{ $task->task_image ? 'target=_blank' : '' }} class="block">
                                    @if ($task->task_image)
                                        <img src="{{ asset($task->task_image) }}" alt="{{ $task->title }}"
                                            class="h-36 w-full rounded-lg bg-slate-100 object-contain"
                                            onerror="this.onerror=null;this.src='{{ asset('images/no-product.png') }}';">
                                    @else
                                        <div class="flex h-36 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                            <i class="fas fa-image text-2xl"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="mt-3">
                                    <h3 class="truncate text-sm font-semibold text-slate-800">{{ $task->title }}</h3>
                                    @if ($task->description)
                                        <p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $task->description }}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-16 text-center text-sm text-slate-400">No staff tasks found.</div>
            @endforelse

            @if ($tasks->hasPages())<div class="no-print mt-5">{{ $tasks->links() }}</div>@endif
        </div>
    </div>
@endsection
