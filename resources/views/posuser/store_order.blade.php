@extends('layout.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center gap-3 flex-wrap">
                <div><h6 class="m-0">Store Orders</h6><p class="text-xs text-secondary mb-0 mt-1">Orders placed by POS users for their stores.</p></div>
                <div class="d-flex align-items-center gap-2">
                    <span id="selectedOrderCount" class="text-xs text-secondary d-none"></span>
                    <button type="submit" form="bulkDeliveryForm" id="bulkDeliverButton" class="btn btn-success btn-sm mb-0" disabled onclick="return confirm('Mark all selected orders as delivered? Their items will be added to the respective store stock.');">Mark Selected Delivered</button>
                    <input type="text" id="searchInput" placeholder="Search order..." class="py-2 border border-gray-300 rounded-lg h-6 card-header-search">
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2"><form id="bulkDeliveryForm" method="POST" action="{{ route('pos_user.store-order.bulk-deliver') }}">@csrf<div class="table-responsive p-0"><table class="table align-items-center mb-0">
                <thead><tr><th class="ps-3"><input id="selectAllOrders" type="checkbox" aria-label="Select all processing orders"></th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order No.</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Store</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">POS User</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Items</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th><th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th><th class="text-secondary opacity-7">Action</th></tr></thead>
                <tbody>@forelse($orders as $key => $order)@php($isDelivered = (int) $order->status === 2)<tr><td><p class="text-xs font-weight-bold mb-0">{{ $orders->firstItem() + $key }}</p></td><td><p class="text-xs font-weight-bold mb-0">{{ $order->order_number }}</p></td><td><p class="text-xs font-weight-bold mb-0">{{ optional($order->store)->name ?? '-' }}</p></td><td><p class="text-xs font-weight-bold mb-0">{{ optional($order->posUser)->name ?? '-' }}</p></td><td><p class="text-xs font-weight-bold mb-0">{{ $order->items_count }}</p></td><td><p class="text-xs font-weight-bold text-success mb-0">₹{{ number_format($order->grand_total, 2) }}</p></td><td><span class="badge badge-sm {{ $isDelivered ? 'bg-gradient-success' : 'bg-gradient-warning' }}">{{ $isDelivered ? 'Delivered' : 'Processing' }}</span></td><td><p class="text-xs font-weight-bold mb-0">{{ $order->created_at->format('d M Y, h:i A') }}</p></td><td><a href="{{ route('pos_user.store-order.view', $order) }}" class="btn btn-sm btn-outline-primary mb-0">View</a></td></tr>@empty<tr><td colspan="9" class="text-center py-4 text-secondary">No store orders found.</td></tr>@endforelse</tbody>
            </table><div class="mt-4 px-3">{{ $orders->links('shared.pagination') }}</div></div></form></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('#bulkDeliveryForm table');
    const selectAll = document.getElementById('selectAllOrders');
    const button = document.getElementById('bulkDeliverButton');
    const count = document.getElementById('selectedOrderCount');
    if (!table || !selectAll || !button || !count) return;

    // Add a selectable first column only for orders that have not yet been delivered.
    table.querySelectorAll('tbody tr').forEach(function (row) {
        const viewLink = row.querySelector('a[href*="/store/order/"]');
        if (!viewLink) return;

        const firstCell = document.createElement('td');
        firstCell.className = 'ps-3';
        const orderId = (viewLink.href.match(/\/store\/order\/(\d+)(?:\?.*)?$/) || [])[1];
        const isDelivered = row.textContent.includes('Delivered');

        if (!isDelivered && orderId) {
            firstCell.innerHTML = '<input type="checkbox" class="store-order-checkbox" name="order_ids[]" value="' + orderId + '" aria-label="Select this order">';
        }
        row.insertBefore(firstCell, row.firstElementChild);
    });

    const checkboxes = Array.from(document.querySelectorAll('.store-order-checkbox'));
    function updateState() {
        const selected = checkboxes.filter(checkbox => checkbox.checked).length;
        button.disabled = selected === 0;
        count.textContent = selected ? selected + ' selected' : '';
        count.classList.toggle('d-none', selected === 0);
        selectAll.checked = checkboxes.length > 0 && selected === checkboxes.length;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
        updateState();
    });
    checkboxes.forEach(checkbox => checkbox.addEventListener('change', updateState));
    updateState();
});
</script>
@endpush
