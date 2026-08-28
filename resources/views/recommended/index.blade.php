@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <!-- Header -->
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Recommended</h6>

                    <div class="d-flex gap-2">
                        <a href="{{ route('recommended.export') }}" class="btn btn-success btn-sm">
                            Export
                        </a>
                        <a href="{{ route('recommended.add') }}" class="btn btn-primary btn-sm">
                            + Add
                        </a>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Name
                                    </th>

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($recommended as $key => $item)
                                    <tr>

                                        <td class="text-xs">
                                            {{ $recommended->firstItem() + $key }}
                                        </td>

                                        <td class="text-xs font-weight-bold">
                                            {{ $item->name }}
                                        </td>

                                        <td>
                                            <a href="{{ route('recommended.edit', $item->id) }}" class="text-primary me-2">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            <button class="btn btn-link text-danger p-0 delete-btn"
                                                data-id="{{ $item->id }}"
                                                data-url="{{ route('recommended.delete', $item->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No Record found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4 px-3">
                            {{ $recommended->links('shared.pagination') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
