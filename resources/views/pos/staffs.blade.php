@extends('pos.layout.app')

@section('content')
    <div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="mb-6">

                <div class="flex items-center justify-between gap-4">

                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-slate-800">
                            Staffs
                        </h1>
                    </div>

                    <a href="{{ route('pos.staff.add') }}"
                        class="inline-flex items-center gap-2
                           px-4 py-2.5 rounded-xl
                           bg-[#128C7E] text-white
                           text-sm font-semibold
                           hover:bg-[#0f766e] transition">
                        <i class="fas fa-plus"></i>
                        New Staff
                    </a>

                </div>

            </div>


            {{-- Bills Card --}}
            <div
                class="bg-white rounded-2xl
                   border border-slate-200
                   shadow-sm overflow-hidden">

                {{-- Table Header --}}
                <div
                    class="px-5 py-4
                       border-b border-slate-200
                       flex items-center justify-between">

                    <div>

                        <p class="text-xs text-slate-400 mt-1">
                            {{ $users->total() }} total Staffs
                        </p>
                    </div>

                </div>


                {{-- Desktop Table --}}
                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-slate-50">

                            <tr>

                                <th
                                    class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase">
                                    #
                                </th>

                                <th
                                    class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase">
                                    StaffID
                                </th>

                                <th
                                    class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase">
                                    Name
                                </th>

                                <th
                                    class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase">
                                    Phone
                                </th>

                                <th
                                    class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase">
                                    Role
                                </th>

                                <th
                                    class="px-5 py-3 text-center
                                       text-xs font-semibold
                                       text-slate-500 uppercase">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse($users as $key => $pos)
                                <tr class="hover:bg-slate-50 transition">

                                    {{-- Number --}}
                                    <td class="px-5 py-4 text-slate-500">

                                        {{ $users->firstItem() + $key }}

                                    </td>

                                    <td class="px-5 py-4">

                                        <span
                                            class="font-semibold
                                               text-slate-800">
                                            {{ $pos->staff_id }}
                                        </span>

                                    </td>


                                    {{-- Customer --}}
                                    <td class="px-5 py-4">

                                        <div>

                                            <p class="font-semibold text-slate-800">
                                                {{ $pos->name }}
                                            </p>

                                            @if ($pos->email)
                                                <p class="text-xs text-slate-400 mt-1">
                                                    {{ $pos->email }}
                                                </p>
                                            @endif

                                        </div>

                                    </td>


                                    {{-- Phone --}}
                                    <td class="px-5 py-4 text-slate-600">

                                        {{ $pos->mobile ?: '-' }}

                                    </td>

                                    <td class="px-5 py-4 text-slate-600">
                                        {{ optional($pos->roleMaster)->role_name ?? '-' }}
                                    </td>

                                    {{-- Action --}}
                                    <td class="px-5 py-4 text-center">
                                        <a href="{{ route('pos.staff.view', $pos->id) }}"
                                            class="inline-flex
                                               w-9 h-9
                                               items-center
                                               justify-center
                                               rounded-lg
                                               bg-slate-100
                                               text-slate-600
                                               hover:bg-[#128C7E]
                                               hover:text-white
                                               transition"
                                            title="View Bill">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('pos.staff.edit', $pos->id) }}"
                                            class="inline-flex w-9 h-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-[#128C7E] hover:text-white transition"
                                            title="Edit Staff">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="10" class="px-5 py-16 text-center">

                                        <div
                                            class="w-16 h-16 mx-auto
                                               rounded-2xl
                                               bg-slate-100
                                               flex items-center
                                               justify-center">
                                            <i
                                                class="fas fa-users
                                                   text-2xl
                                                   text-slate-400"></i>
                                        </div>

                                        <h3
                                            class="mt-4
                                               text-lg
                                               font-semibold
                                               text-slate-700">
                                            No Staff Found
                                        </h3>

                                        <p
                                            class="text-sm
                                               text-slate-400
                                               mt-1">
                                            No POS transactions have been created yet.
                                        </p>

                                        <a href="{{ route('pos.order') }}"
                                            class="inline-flex
                                               items-center
                                               gap-2
                                               mt-5
                                               px-4 py-2.5
                                               rounded-xl
                                               bg-[#128C7E]
                                               text-white
                                               text-sm
                                               font-semibold">
                                            <i class="fas fa-plus"></i>
                                            Create Staff
                                        </a>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div
                        class="px-5 py-4
                           border-t border-slate-200
                           flex items-center
                           justify-between">

                        <p class="text-xs text-slate-400">

                            Showing
                            {{ $users->firstItem() }}
                            -
                            {{ $users->lastItem() }}
                            of
                            {{ $users->total() }}

                        </p>

                        <div>

                            {{ $users->links() }}

                        </div>

                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection
