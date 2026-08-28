@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center category-card-header">
                    <div class="category-card-header-top">
                        <h6 class="m-0">FAQ</h6>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('faq.add') }}" class="btn btn-primary btn-sm category-card-add-btn">
                                + Add FAQ
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
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Description</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Status</th>
                                    <th class="text-secondary opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($faqs as $key => $item)
                                    <tr>
                                        <td class="text-xs">
                                            {{ $faqs->firstItem() + $key }}
                                        </td>

                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $item->name }}</p>
                                        </td>

                                        <td>
                                            <p class="text-xs text-muted mb-0">{!! \Illuminate\Support\Str::limit(strip_tags($item->description), 80) !!}</p>
                                        </td>

                                        <td>
                                            <select name="status" class="form-control text-xs font-weight-bold select_top">
                                                <option value="1" data-id="{{ $item->id }}"
                                                    {{ $item->status == 1 ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="0" data-id="{{ $item->id }}"
                                                    {{ $item->status == 0 ? 'selected' : '' }}>
                                                    InActive
                                                </option>
                                            </select>
                                        </td>

                                        <td>
                                            <a href="{{ route('faq.edit', $item->id) }}"
                                                class="text-primary me-2">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button class="btn btn-link text-danger p-0 delete-btn"
                                                data-id="{{ $item->id }}"
                                                data-url="{{ route('faq.delete', $item->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No FAQs found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4 px-3">
                            {{ $faqs->links('shared.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.select_top').on('change', function() {
            var product_id = $(this).find(':selected').data('id');
            var value = $(this).val();

            $.ajax({
                url: "{{ route('faq.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: product_id,
                    status: value,
                },
                success: function(res) {
                    console.log(res.message);
                    showNotification('success', res.message || 'Status updated successfully');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    showNotification('danger', 'Something went wrong');
                }
            });
        });
    </script>
@endsection
