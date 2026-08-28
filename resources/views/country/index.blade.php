@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center category-card-header">
                    <div class="category-card-header-top">
                        <h6 class="m-0">Countries</h6>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('country.export') }}" class="btn btn-success btn-sm">Export</a>
                            <a href="{{ route('country.add') }}" class="btn btn-primary btn-sm category-card-add-btn">
                                + Add
                            </a>
                        </div>
                    </div>

                    <input type="text" id="searchInput" placeholder="Search..."
                        class="py-2 border border-gray-300 rounded-lg h-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white card-header-search">
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0 datatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Sr No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Code</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Currency SN</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Currency</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">CCO</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Symbol</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rate</th>
                                    <th class="text-secondary opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-table">
                                @foreach ($countries as $key => $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            <i class="fas fa-bars text-secondary me-2 drag-handle" style="cursor:move"></i>
                                            {{ $countries->firstItem() + $key }}
                                        </td>

                                        <td><p class="text-xs font-weight-bold mb-0">{{ $item->country_name }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $item->country_code }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $item->country_short }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $item->currency_name }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $item->currency_code }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $item->currency_symbol }}</p></td>
                                        <td><p class="text-xs font-weight-bold mb-0">{{ $item->currency_rate }}</p></td>

                                        <td>
                                            <a href="{{ route('country.edit', $item->id) }}"
                                                class="text-secondary font-weight-bold text-xs">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $countries->links('shared.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
