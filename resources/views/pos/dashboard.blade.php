@extends('layout.app')

@section('content')

<div class="flex-1 overflow-y-auto p-4 md:p-6">

    <!-- Dashboard Header -->
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-[#0F172A]">
                Dashboard
            </h1>
            <p class="text-slate-500 mt-1">
                Manage and publish Savari Golds results from one place.
            </p>
        </div>

        <!-- Dashboard 4 Boxes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Total Contacts -->
            <div class="glass rounded-2xl p-5 border border-white/70 shadow-lg shadow-slate-200/40">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Records
                        </p>
                        <h3 class="text-3xl font-bold text-[#0F172A] mt-2">
                            {{ $totalRecords }}
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#128C7E] flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>

                <p class="text-xs text-slate-400 mt-4">
                            All saved result records
                </p>
            </div>


            <!-- Today's results -->
            <div class="glass rounded-2xl p-5 border border-white/70 shadow-lg shadow-slate-200/40">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Today's Results
                        </p>
                        <h3 class="text-3xl font-bold text-[#0F172A] mt-2">
                            {{ $todayRecords }}
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </div>
                </div>

                <p class="text-xs text-slate-400 mt-4">
                            Records published for today
                </p>
            </div>


            <!-- Games -->
            <div class="glass rounded-2xl p-5 border border-white/70 shadow-lg shadow-slate-200/40">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Games
                        </p>
                        <h3 class="text-3xl font-bold text-[#0F172A] mt-2">
                            {{ $totalGames }}
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-paper-plane text-xl"></i>
                    </div>
                </div>

                <p class="text-xs text-slate-400 mt-4">
                            Unique game names in records
                </p>
            </div>


            <!-- Quick action -->
            <div class="glass rounded-2xl p-5 border border-white/70 shadow-lg shadow-slate-200/40">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Result Board
                        </p>

                        <a href="{{ route('satta-records.create') }}" class="inline-block text-sm font-bold text-emerald-600 mt-3">Add new result</a>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="fas fa-plus text-xl"></i>
                    </div>
                </div>

                <p class="text-xs text-slate-400 mt-4">
                    Create a result record
                </p>
            </div>

        </div>

    </div>

</div>

@endsection