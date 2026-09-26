@extends('pos.layout.app')

@section('content')
    <div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
        <div class="mx-auto max-w-6xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Staff Tasks</h1>
                    <p class="mt-1 text-sm text-slate-400">{{ $staff->name }} &middot; {{ $staff->staff_id }}</p>
                </div>
                <a href="{{ route('pos.staff') }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    <i class="fas fa-arrow-left"></i> Back to Staffs
                </a>
            </div>

            @if (session('success'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif

            <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-600"><i class="fas fa-tasks text-lg"></i></div>
                        <div><h2 class="text-lg font-bold text-slate-800">Assign New Task</h2><p class="mt-1 text-xs text-slate-400">Add task details and an optional reference image.</p></div>
                    </div>
                </div>
                <form action="{{ route('pos.staff.tasks.store', $staff->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Task title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Enter task title" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm text-slate-700 focus:border-[#128C7E] focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Task description</label>
                            <textarea name="description" rows="3" placeholder="Add task instructions or details" class="w-full rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700 focus:border-[#128C7E] focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20">{{ old('description') }}</textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Task image</label>
                            <input type="file" name="task_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-sky-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-sky-700 hover:file:bg-sky-100">
                            <p class="mt-2 text-xs text-slate-400">Optional reference image. JPG, PNG or WEBP; maximum 4 MB.</p>
                            @error('task_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end"><button type="submit" class="inline-flex h-11 items-center gap-2 rounded-xl bg-[#128C7E] px-5 text-sm font-semibold text-white hover:bg-[#0f766e]"><i class="fas fa-plus"></i> Assign Task</button></div>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4"><h2 class="font-bold text-slate-800">Task History</h2></div>
                @if ($tasks->isNotEmpty())
                    <div class="grid grid-cols-2 gap-3 p-4 lg:grid-cols-3">
                        @foreach ($tasks as $task)
                            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white p-3">
                                <a href="{{ $task->task_image ? asset($task->task_image) : '#' }}" {{ $task->task_image ? 'target=_blank' : '' }} class="block">
                                    @if ($task->task_image)
                                        <img src="{{ asset($task->task_image) }}" alt="{{ $task->title }}" class="h-36 w-full rounded-lg bg-slate-100 object-cover"
                                            onerror="this.onerror=null;this.src='{{ asset('images/no-product.png') }}';">
                                    @else
                                        <div class="flex h-36 items-center justify-center rounded-lg bg-slate-100 text-slate-400"><i class="fas fa-image text-2xl"></i></div>
                                    @endif
                                </a>
                                <div class="mt-3">
                                    <h3 class="truncate text-sm font-semibold text-slate-800">{{ $task->title }}</h3>
                                    @if ($task->description)<p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $task->description }}</p>@endif
                                    <form action="{{ route('pos.staff.tasks.destroy', $task->id) }}" method="POST" class="mt-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this task?');" class="text-xs font-semibold text-rose-600 hover:text-rose-700">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="px-5 py-14 text-center text-sm text-slate-400">No tasks assigned yet.</div>
                @endif
                </div>
                @if ($tasks->hasPages())<div class="border-t border-slate-200 px-5 py-4">{{ $tasks->links() }}</div>@endif
            </div>
        </div>
    </div>
@endsection
