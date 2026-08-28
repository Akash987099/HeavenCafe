@extends('layout.app')

@section('title', 'User Management')
@section('page_title', 'User Management')

@section('content')
    <div class="datatable-shell overflow-hidden rounded-[20px] border border-slate-200 bg-white shadow-sm">
        <div class="datatable-toolbar flex flex-col gap-4 border-b border-slate-200 px-4 py-4 sm:px-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                <h2 class="text-[1.05rem] font-semibold text-slate-800">Users</h2>

                <label class="flex items-center gap-2 text-[13px] text-slate-500">
                    <span>Show</span>
                    <select class="datatable-select h-9 rounded-[10px] border border-slate-300 bg-white px-3 text-[13px] text-slate-700 outline-none">
                        <option>10</option>
                    </select>
                    <span>entries</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                    <input
                        id="users-search"
                        type="search"
                        placeholder="Search..."
                        class="table-search-input h-10 w-full rounded-[10px] border border-slate-300 bg-white px-4 text-[13px] text-slate-700 outline-none transition focus:border-sky-400 lg:w-44 xl:w-52"
                        data-search-input="users-table"
                    >
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="datatable-table min-w-full" data-search-table="users-table">
                <thead>
                    <tr>
                        <th class="w-16">Sr No.</th>
                        <th class="w-44">Name</th>
                        <th>Email</th>
                        <th class="w-36">Phone</th>
                        <th class="w-32">Status</th>
                        <th class="w-40">Provider</th>
                        <th class="w-36">Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $key => $user)
                        <tr data-search-row>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="datatable-row-grip" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round">
                                            <path d="M5 7h14" />
                                            <path d="M5 12h14" />
                                            <path d="M5 17h14" />
                                        </svg>
                                    </span>
                                    <span>{{ $users->firstItem() + $key }}</span>
                                </div>
                            </td>
                            <td class="font-medium text-slate-800">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->mobile ?? $user->phone ?? '-' }}</td>
                            <td>
                                <span class="datatable-badge">
                                    {{ $user->status ?? 'Active' }}
                                </span>
                            </td>
                            <td>
                                @if ($user->socialAccounts->isNotEmpty())
                                    <span class="datatable-provider">
                                        {{ ucfirst($user->socialAccounts->first()->provider) }}
                                    </span>
                                @else
                                    <span class="datatable-provider datatable-provider-neutral">Manual</span>
                                @endif
                            </td>
                            <td>{{ optional($user->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr class="admin-table-empty-row">
                            <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-500">No registered users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="datatable-footer flex flex-col gap-3 border-t border-slate-200 px-4 py-4 sm:px-5 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-[13px] text-slate-500">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
            </p>
            <div>
                {{ $users->links('shared.pagination') }}
            </div>
        </div>
    </div>
@endsection
