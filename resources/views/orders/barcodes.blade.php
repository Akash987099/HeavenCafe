@extends('layout.app')

@section('content')
    <form action="{{ route('order.barcode_print') }}" method="POST" target="_blank">
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">

                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="m-0">Barcodes</h6>

                        <button type="submit" class="btn btn-primary btn-sm">
                            Print Selected
                        </button>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">

                            <table class="table align-items-center mb-0 datatable">

                                <thead>
                                    <tr>

                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            <input type="checkbox" id="select-all">
                                        </th>

                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sr No.</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order Number</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Barcode</th>

                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($barcodes as $key => $item)
                                        <tr>

                                            <td>
                                                <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                    class="row-checkbox">
                                            </td>

                                            <td>{{ $barcodes->firstItem() + $key }}</td>

                                            <td>{{ $item->order_no }}</td>

                                            <td>

                                                <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $item->order_no }}&code=Code128"
                                                    width="150">

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                            <div class="mt-4">
                                {{ $barcodes->links('shared.pagination') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

<script>
    document.getElementById('select-all').onclick = function() {

        var checkboxes = document.getElementsByClassName('row-checkbox');

        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }

    };
</script>
