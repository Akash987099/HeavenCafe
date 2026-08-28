@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center category-card-header">
                    <div class="category-card-header-top">
                        <h6 class="m-0">Point Setting</h6>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('points.add') }}" class="btn btn-primary btn-sm category-card-add-btn">
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
                                    <th>Sr No.</th>
                                    <th>Reward %</th>
                                    <th>Point Value</th>
                                    <th>Max Redeem %</th>
                                    <th>Min Order Amount</th>
                                    <th>Expiry Days</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="sortable-category">
                                @foreach ($points as $key => $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            {{ $points->firstItem() + $key }}
                                        </td>

                                        <td>{{ $item->reward_percent }}%</td>
                                        <td>{{ $item->point_value }}</td>
                                        <td>{{ $item->max_redeem_percent }}%</td>
                                        <td>₹{{ $item->min_order_amount }}</td>
                                        <td>{{ $item->expiry_days }} days</td>

                                        <td>
                                            <a href="{{ route('points.edit', $item->id) }}"
                                                class="text-secondary font-weight-bold text-xs">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                        <div class="mt-4">
                            {{ $points->links('shared.pagination') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection