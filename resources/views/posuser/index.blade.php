@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center category-card-header">
                    <div class="category-card-header-top">
                        <h6 class="m-0">Pos User</h6>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('pos_user.add') }}" class="btn btn-primary btn-sm category-card-add-btn">
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Store</th>
                                    <th class="text-secondary opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posuser as $key => $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            <i class="fas fa-bars text-secondary me-2 drag-handle" style="cursor:move"></i>
                                            {{ $posuser->firstItem() + $key }}
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->staff_id }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->name }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->email }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->store->name }}</p>
                                        </td>
                                        <td>
                                            <a href="{{ route('pos_user.edit', $item->id) }}"
                                                class="text-secondary font-weight-bold text-xs">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <div class="mt-4">
                            {{ $posuser->links('shared.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
