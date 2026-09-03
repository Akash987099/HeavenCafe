@extends('layout.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Add</h6>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{route('sub_category.save')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Details" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-label">Category</label>
                                    <div class="subcategory-category-control">
                                        <select class="form-control category-select" id="category" name="category[]" multiple required>
                                            @foreach ($category as $key => $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle_number" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image" placeholder="Enter vehicle number" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="vehicle_name" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Description" required></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.4.9/sumoselect.min.css">
<style>
    .subcategory-category-control .SumoSelect {
        width: 100%;
    }

    .subcategory-category-control .CaptionCont {
        min-height: 40px;
        padding: 8px 40px 8px 13px;
        border: 1px solid #d5dfeb;
        border-radius: 10px;
        background: #fff;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .subcategory-category-control .CaptionCont:focus,
    .subcategory-category-control .SumoSelect.open > .CaptionCont {
        border-color: #8cb4e8;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .subcategory-category-control .CaptionCont > span {
        color: #7c8da3;
        font-size: .82rem;
        font-weight: 500;
        line-height: 22px;
    }

    .subcategory-category-control .CaptionCont > label {
        width: 38px;
        border-left: 1px solid #e4ebf3;
    }

    .subcategory-category-control .CaptionCont > label > i {
        border-color: #64748b transparent transparent;
    }

    .subcategory-category-control .optWrapper {
        top: calc(100% + 6px);
        border: 1px solid #d5dfeb;
        border-radius: 10px;
        box-shadow: 0 14px 30px rgba(15, 23, 42, .14);
        overflow: hidden;
    }

    .subcategory-category-control .options {
        padding: 5px;
    }

    .subcategory-category-control .options li.opt,
    .subcategory-category-control .options li.group > label,
    .subcategory-category-control .select-all {
        min-height: 36px;
        padding: 9px 10px 9px 34px;
        border: 0;
        border-radius: 7px;
        color: #334155;
        font-size: .8rem;
        font-weight: 600;
    }

    .subcategory-category-control .options li.opt:hover,
    .subcategory-category-control .options li.opt.sel,
    .subcategory-category-control .options li.opt.selected,
    .subcategory-category-control .select-all:hover {
        background: #eff6ff;
    }

    .subcategory-category-control .options li.opt span i,
    .subcategory-category-control .select-all > span i {
        top: 10px;
        left: 10px;
        width: 16px;
        height: 16px;
        border: 1px solid #b8c5d5;
        border-radius: 4px;
        box-shadow: none;
    }

    .subcategory-category-control .options li.opt.selected span i,
    .subcategory-category-control .select-all.selected > span i {
        border-color: #2563eb;
        background-color: #2563eb;
    }

    .subcategory-category-control .btnOk,
    .subcategory-category-control .btnCancel {
        padding: 9px 16px;
        border: 0;
        font-size: .78rem;
        font-weight: 700;
    }

    .subcategory-category-control .btnOk {
        background: #2563eb;
        color: #fff;
    }

    .subcategory-category-control .btnCancel {
        background: #f1f5f9;
        color: #64748b;
    }

    html[data-theme="dark"] .subcategory-category-control .CaptionCont,
    body.dark-mode .subcategory-category-control .CaptionCont {
        border-color: #334155;
        background: #0f172a;
    }

    html[data-theme="dark"] .subcategory-category-control .CaptionCont > span,
    html[data-theme="dark"] .subcategory-category-control .options li.opt,
    html[data-theme="dark"] .subcategory-category-control .select-all,
    body.dark-mode .subcategory-category-control .CaptionCont > span,
    body.dark-mode .subcategory-category-control .options li.opt,
    body.dark-mode .subcategory-category-control .select-all {
        color: #e2e8f0;
    }

    html[data-theme="dark"] .subcategory-category-control .optWrapper,
    body.dark-mode .subcategory-category-control .optWrapper {
        border-color: #334155;
        background: #111827;
    }

    html[data-theme="dark"] .subcategory-category-control .options li.opt:hover,
    html[data-theme="dark"] .subcategory-category-control .options li.opt.sel,
    html[data-theme="dark"] .subcategory-category-control .options li.opt.selected,
    html[data-theme="dark"] .subcategory-category-control .select-all:hover,
    body.dark-mode .subcategory-category-control .options li.opt:hover,
    body.dark-mode .subcategory-category-control .options li.opt.sel,
    body.dark-mode .subcategory-category-control .options li.opt.selected,
    body.dark-mode .subcategory-category-control .select-all:hover {
        background: #1e3a5f;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.4.9/jquery.sumoselect.min.js"></script>
<script>
    $(function () {
        const $category = $('#category');

        $category.SumoSelect({
            placeholder: '----Select categories-----',
            selectAll: true,
            selectAllPartialCheck: true,
            okCancelInMulti: true,
            csvDispCount: 3
        });
    });
</script>
@endpush
