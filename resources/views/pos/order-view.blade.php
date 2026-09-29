@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="mx-auto">

        <form
            method="POST"
            action="{{ route('pos.order.payment', $order->id) }}"
            id="paymentForm"
        >

            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

                {{-- =====================================================
                    LEFT SIDE
                ====================================================== --}}
                <div class="lg:col-span-8 space-y-5">


                    {{-- Customer Details --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">

                        <div class="p-5 border-b border-slate-200">

                            <h2 class="text-lg font-bold text-slate-800">
                                Customer Details
                            </h2>

                            <p class="text-xs text-slate-400 mt-1">
                                Enter customer information
                            </p>

                        </div>


                        <div class="p-5">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                {{-- Name --}}
                                <div>

                                    <label
                                        for="customer_name"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                    >
                                        Customer Name
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="customer_name"
                                        id="customer_name"
                                        required
                                        value="{{ old('customer_name') }}"
                                        placeholder="Enter customer name"
                                        class="w-full h-11 px-4 rounded-xl border border-slate-200
                                               bg-slate-50 text-sm text-slate-700
                                               focus:outline-none focus:ring-2
                                               focus:ring-[#128C7E]/20
                                               focus:border-[#128C7E]"
                                    >

                                </div>


                                {{-- Email --}}
                                <div>

                                    <label
                                        for="customer_email"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                    >
                                        Email
                                        <span class="text-xs text-slate-400">
                                            (Optional)
                                        </span>
                                    </label>

                                    <input
                                        type="email"
                                        name="customer_email"
                                        id="customer_email"
                                        value="{{ old('customer_email') }}"
                                        placeholder="customer@email.com"
                                        class="w-full h-11 px-4 rounded-xl border border-slate-200
                                               bg-slate-50 text-sm text-slate-700
                                               focus:outline-none focus:ring-2
                                               focus:ring-[#128C7E]/20
                                               focus:border-[#128C7E]"
                                    >

                                </div>


                                {{-- Phone --}}
                                <div>

                                    <label
                                        for="customer_phone"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                    >
                                        Phone
                                        <span class="text-xs text-slate-400">
                                            (Optional)
                                        </span>
                                    </label>

                                    <input
                                        type="text"
                                        name="customer_phone"
                                        id="customer_phone"
                                        value="{{ old('customer_phone') }}"
                                        placeholder="Enter phone number"
                                        class="w-full h-11 px-4 rounded-xl border border-slate-200
                                               bg-slate-50 text-sm text-slate-700
                                               focus:outline-none focus:ring-2
                                               focus:ring-[#128C7E]/20
                                               focus:border-[#128C7E]"
                                    >

                                </div>

                            </div>

                            {{-- Fulfillment Type --}}
                            <div class="mt-4">
                                <label for="fulfillment_type" class="block text-sm font-medium text-slate-700 mb-2">
                                    Order Type <span class="text-red-500">*</span>
                                </label>
                                <select name="fulfillment_type" id="fulfillment_type" required class="w-full h-11 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]">
                                    <option value="dine_in" @selected(old('fulfillment_type', $order->fulfillment_type ?? 'dine_in') === 'dine_in')>Dine In</option>
                                    <option value="packing" @selected(old('fulfillment_type', $order->fulfillment_type ?? 'dine_in') === 'packing')>Packing / Takeaway</option>
                                </select>
                            </div>

                        </div>

                    </div>


                    {{-- Products --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">

                        <div class="p-5 border-b border-slate-200">

                            <div class="flex items-center justify-between">

                                <div>

                                    <h2 class="text-lg font-bold text-slate-800">
                                        Products
                                    </h2>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Products added to this bill
                                    </p>

                                </div>

                                <div class="flex items-center gap-3">
                                <button type="button" id="addItemButton"
                                    class="mr-3 inline-flex items-center gap-2 rounded-lg border border-[#128C7E] px-3 py-1.5 text-xs font-semibold text-[#128C7E] hover:bg-emerald-50">
                                    <i class="fas fa-plus"></i> Add item
                                </button>
                                <span id="itemCount"
                                    class="px-3 py-1.5 rounded-full
                                           bg-emerald-50 text-[#128C7E]
                                           text-xs font-semibold"
                                >
                                    {{ $order->details->count() }} Items
                                </span>
                                </div>

                            </div>

                        </div>


                        {{-- Product Table --}}
                        <div class="overflow-x-auto">

                            <div class="min-w-[600px]">

                                {{-- Header --}}
                                <div
                                    class="flex items-center px-5 py-3
                                           bg-slate-50 border-b border-slate-200
                                           text-xs font-semibold text-slate-500"
                                >

                                    <div class="flex-1">
                                        Product
                                    </div>

                                    <div class="w-20 text-center">
                                        Qty
                                    </div>

                                    <div class="w-28 text-right">
                                        Price
                                    </div>

                                    <div class="w-28 text-right">
                                        Total
                                    </div>

                                </div>


                                {{-- Products --}}
                                <div id="orderItemsList">
                                @foreach($order->details as $detail)

                                    <div
                                        class="flex items-center px-5 py-4
                                               border-b border-slate-100
                                               last:border-b-0"
                                    >

                                        {{-- Product --}}
                                        <div class="flex-1 min-w-0">

                                            <p
                                                class="text-sm font-semibold
                                                       text-slate-800 truncate"
                                            >
                                                {{ $detail->product_name }}
                                            </p>

                                        </div>


                                        {{-- Quantity --}}
                                        <div class="w-20 flex items-center justify-center gap-1">
                                            <button type="button" class="quantity-change w-7 h-7 rounded bg-slate-100 text-slate-700 hover:bg-slate-200" data-change="-1" title="Decrease quantity">−</button>
                                            <span class="item-quantity min-w-5 text-center text-sm font-semibold text-slate-700">{{ $detail->quantity }}</span>
                                            <button type="button" class="quantity-change w-7 h-7 rounded bg-slate-100 text-slate-700 hover:bg-slate-200" data-change="1" title="Increase quantity">+</button>
                                        </div>


                                        {{-- Price --}}
                                        <div class="w-28 text-right">

                                            <span class="text-sm text-slate-600">
                                                ₹{{ number_format($detail->price, 2) }}
                                            </span>

                                        </div>

                                        {{-- Total --}}
                                        <div class="w-28 text-right">

                                            <span
                                                class="text-sm font-bold
                                                       text-[#128C7E]"
                                            >
                                                ₹{{ number_format($detail->total, 2) }}
                                            </span>

                                        </div>

                                        <div class="w-12 text-right">
                                            <button type="button" class="remove-item text-red-500 hover:text-red-700" title="Remove item">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>

                                    </div>

                                @endforeach
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div id="productPicker" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                    <div class="flex max-h-[85vh] w-full max-w-5xl flex-col rounded-2xl bg-white shadow-xl">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-5">
                            <div><h3 class="text-lg font-bold text-slate-800">Add product</h3><p class="mt-1 text-xs text-slate-400">Select a product to add to this bill</p></div>
                            <div class="flex items-center gap-3">
                                <select id="productPickerCategory" class="h-10 min-w-40 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 focus:border-[#128C7E] focus:outline-none">
                                    <option value="">All categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="closeProductPicker" class="text-slate-400 hover:text-slate-700"><i class="fas fa-times text-lg"></i></button>
                            </div>
                        </div>
                        <div id="productPickerResults" class="min-h-0 flex-1 overflow-y-auto p-5">
                            <div id="productPickerGrid" class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4"></div>
                            <div id="productPickerLoadMore" class="hidden py-4 text-center text-xs text-slate-400"><i class="fas fa-spinner fa-spin mr-1"></i> Loading more products...</div>
                            <div id="productPickerEmpty" class="py-10 text-center text-sm text-slate-400">Loading products...</div>
                            <div id="productPickerSentinel" class="h-px"></div>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-t border-slate-200 p-4">
                            <span id="selectedProductCount" class="text-sm text-slate-500">No products selected</span>
                            <button type="button" id="addSelectedProducts" disabled class="h-11 rounded-xl bg-[#128C7E] px-5 text-sm font-semibold text-white transition hover:bg-[#0f766e] disabled:cursor-not-allowed disabled:opacity-50">
                                <i class="fas fa-plus mr-1"></i> Add selected items
                            </button>
                        </div>
                    </div>
                </div>


                {{-- =====================================================
                    RIGHT SIDE : PAYMENT
                ====================================================== --}}
                <div class="lg:col-span-4">

                    <div
                        class="bg-white rounded-2xl border border-slate-200
                               shadow-sm sticky top-5 overflow-hidden"
                    >

                        {{-- Payment Header --}}
                        <div class="p-5 border-b border-slate-200">

                            <h2 class="text-lg font-bold text-slate-800">
                                Payment
                            </h2>

                            <p class="text-xs text-slate-400 mt-1">
                                Select payment method
                            </p>

                        </div>


                        <div class="p-5">


                            {{-- Amount --}}
                            <div class="mb-5">

                                <label
                                    class="block text-sm font-medium
                                           text-slate-600 mb-2"
                                >
                                    Amount to Pay
                                </label>

                                <div class="relative">

                                    <span
                                        class="absolute left-4 top-1/2
                                               -translate-y-1/2
                                               text-slate-500 font-semibold"
                                    >
                                        ₹
                                    </span>

                                    <input
                                        type="text"
                                        id="paymentAmount"
                                        name="amount"
                                        value="{{ number_format($order->grand_total, 2, '.', '') }}"
                                        readonly
                                        class="w-full h-14 pl-9 pr-4 rounded-xl
                                               border border-slate-200
                                               bg-slate-50
                                               text-xl font-bold
                                               text-slate-800"
                                    >

                                </div>

                            </div>


                            {{-- Payment Methods --}}
                            <div class="mb-5">

                                <label
                                    class="block text-sm font-medium
                                           text-slate-600 mb-3"
                                >
                                    Payment Method
                                </label>


                                <div class="grid grid-cols-3 gap-2">


                                    {{-- Cash --}}
                                    <label class="cursor-pointer">

                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="cash"
                                            class="peer sr-only"
                                            checked
                                        >

                                        <div
                                            class="h-20 rounded-xl border
                                                   border-slate-200
                                                   flex flex-col items-center
                                                   justify-center gap-2
                                                   text-slate-500
                                                   peer-checked:border-[#128C7E]
                                                   peer-checked:bg-emerald-50
                                                   peer-checked:text-[#128C7E]
                                                   transition"
                                        >

                                            <i class="fas fa-money-bill-wave text-lg"></i>

                                            <span class="text-xs font-semibold">
                                                Cash
                                            </span>

                                        </div>

                                    </label>


                                    {{-- Card --}}
                                    <label class="cursor-pointer">

                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="card"
                                            class="peer sr-only"
                                        >

                                        <div
                                            class="h-20 rounded-xl border
                                                   border-slate-200
                                                   flex flex-col items-center
                                                   justify-center gap-2
                                                   text-slate-500
                                                   peer-checked:border-[#128C7E]
                                                   peer-checked:bg-emerald-50
                                                   peer-checked:text-[#128C7E]
                                                   transition"
                                        >

                                            <i class="fas fa-credit-card text-lg"></i>

                                            <span class="text-xs font-semibold">
                                                Card
                                            </span>

                                        </div>

                                    </label>


                                    {{-- UPI --}}
                                    <label class="cursor-pointer">

                                        <input
                                            type="radio"
                                            name="payment_method"
                                            value="upi"
                                            class="peer sr-only"
                                        >

                                        <div
                                            class="h-20 rounded-xl border
                                                border-slate-200
                                                flex flex-col items-center
                                                justify-center gap-2
                                                text-slate-500
                                                peer-checked:border-[#128C7E]
                                                peer-checked:bg-emerald-50
                                                peer-checked:text-[#128C7E]
                                                transition"
                                        >

                                            <i class="fas fa-qrcode text-lg"></i>

                                            <span class="text-xs font-semibold">
                                                UPI
                                            </span>

                                        </div>

                                    </label>

                                </div>

                            </div>


                            {{-- Summary --}}
                            <div
                                class="border-t border-dashed
                                       border-slate-200 pt-4"
                            >

                                <div class="flex justify-between mb-3">

                                    <span class="text-sm text-slate-500">
                                        Subtotal
                                    </span>

                                    <span id="subtotalAmount" class="text-sm font-medium text-slate-700">
                                        ₹{{ number_format($order->subtotal, 2) }}
                                    </span>

                                </div>


                                <div class="flex justify-between mb-3">

                                    <span class="text-sm text-slate-500">
                                        Discount
                                    </span>

                                    <span id="discountAmount" class="text-sm font-medium text-slate-700">
                                        ₹{{ number_format($order->discount, 2) }}
                                    </span>

                                </div>


                                <div
                                    class="flex items-center justify-between
                                           pt-3 border-t border-slate-200"
                                >

                                    <span
                                        class="text-base font-bold
                                               text-slate-700"
                                    >
                                        Total
                                    </span>

                                    <span id="grandTotalAmount"
                                        class="text-2xl font-bold
                                               text-[#128C7E]"
                                    >
                                        ₹{{ number_format($order->grand_total, 2) }}
                                    </span>

                                </div>

                            </div>


                            {{-- Pay Button --}}
                            <button
                                type="submit"
                                id="completePayment"
                                class="w-full h-12 mt-6 rounded-xl
                                       bg-[#128C7E] text-white
                                       font-semibold
                                       flex items-center justify-center
                                       gap-2
                                       hover:bg-[#0f766e]
                                       transition"
                            >

                                <i class="fas fa-check-circle"></i>

                                Complete Payment

                            </button>


                            {{-- Cancel --}}
                            <a
                                href="{{ route('pos.order') }}"
                                class="w-full h-11 mt-3 rounded-xl
                                       border border-slate-200
                                       text-slate-600 font-semibold
                                       flex items-center justify-center
                                       hover:bg-slate-50 transition"
                            >
                                Cancel
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const upiRadio = document.querySelector(
        'input[name="payment_method"][value="upi"]'
    );

    const paymentAmount =
        document.getElementById('paymentAmount');

    if (!upiRadio) {
        return;
    }


    upiRadio.addEventListener('change', function () {

        if (!this.checked) {
            return;
        }

        openRazorpay();

    });


    function openRazorpay() {

        const fulfillmentType = document.getElementById('fulfillment_type');

        if (!fulfillmentType.value) {
            alert('Please select an order type first.');
            upiRadio.checked = false;
            fulfillmentType.focus();
            return;
        }

        const amount = parseFloat(
            paymentAmount.value
        );

        if (!amount || amount <= 0) {

            alert('Invalid payment amount.');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Disable UPI while creating Razorpay order
        |--------------------------------------------------------------------------
        */

        upiRadio.disabled = true;


        fetch(
            "{{ route('pos.order.razorpay', $order->id) }}",
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN':
                        "{{ csrf_token() }}",

                    'Accept': 'application/json'
                }
            }
        )
        .then(response => response.json())

        .then(data => {

            if (!data.success) {

                throw new Error(
                    data.message || 'Unable to create Razorpay order.'
                );
            }


            const options = {

                key: data.key,

                amount: data.amount,

                currency: data.currency,

                name: 'The Heaven Cafe',

                description:
                    'POS Order {{ $order->order_number }}',

                order_id:
                    data.razorpay_order_id,


                prefill: {

                    name:
                        document.getElementById('customer_name').value,

                    email:
                        document.getElementById('customer_email').value,

                    contact:
                        document.getElementById('customer_phone').value

                },


                theme: {
                    color: '#C2410C'
                },


                handler: function (response) {

                    verifyPayment(response);

                },


                modal: {

                    ondismiss: function () {

                        upiRadio.disabled = false;

                        upiRadio.checked = false;

                    }

                }

            };


            const razorpay =
                new Razorpay(options);


            razorpay.on(
                'payment.failed',
                function (response) {

                    console.error(
                        'Razorpay Payment Failed:',
                        response.error
                    );

                    alert(
                        response.error.description ||
                        'Payment failed.'
                    );

                    upiRadio.disabled = false;

                    upiRadio.checked = false;

                }
            );


            razorpay.open();

        })

        .catch(error => {

            console.error(error);

            alert(
                error.message ||
                'Unable to open Razorpay.'
            );

            upiRadio.disabled = false;

            upiRadio.checked = false;

        });

    }


    function verifyPayment(response) {

        /*
        |--------------------------------------------------------------------------
        | Verify Payment on Laravel server
        |--------------------------------------------------------------------------
        */

        fetch(
            "{{ route('pos.order.razorpay.verify') }}",
            {

                method: 'POST',

                headers: {

                    'Content-Type':
                        'application/json',

                    'X-CSRF-TOKEN':
                        "{{ csrf_token() }}",

                    'Accept':
                        'application/json'

                },

                body: JSON.stringify({

                    order_id: "{{ $order->id }}",

                    customer_name:
                        document.getElementById('customer_name').value.trim(),

                    customer_email:
                        document.getElementById('customer_email').value.trim(),

                    customer_phone:
                        document.getElementById('customer_phone').value.trim(),

                    fulfillment_type:
                        document.getElementById('fulfillment_type').value,

                    razorpay_payment_id:
                        response.razorpay_payment_id,

                    razorpay_order_id:
                        response.razorpay_order_id,

                    razorpay_signature:
                        response.razorpay_signature
                })

            }
        )

        .then(response => response.json())

        .then(data => {

            if (!data.success) {

                throw new Error(
                    data.message ||
                    'Payment verification failed.'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Payment successful
            |--------------------------------------------------------------------------
            */

            window.location.href =
                data.redirect;

        })

        .catch(error => {

            console.error(error);

            alert(
                error.message ||
                'Payment verification failed.'
            );

            upiRadio.disabled = false;

            upiRadio.checked = false;

        });

    }

});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemsList = document.getElementById('orderItemsList');
    const picker = document.getElementById('productPicker');
    const pickerResults = document.getElementById('productPickerResults');
    const pickerGrid = document.getElementById('productPickerGrid');
    const pickerLoadMore = document.getElementById('productPickerLoadMore');
    const pickerEmpty = document.getElementById('productPickerEmpty');
    const pickerSentinel = document.getElementById('productPickerSentinel');
    const pickerCategory = document.getElementById('productPickerCategory');
    const selectedProductCount = document.getElementById('selectedProductCount');
    const addSelectedProducts = document.getElementById('addSelectedProducts');
    const addButton = document.getElementById('addItemButton');
    const currency = new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    let items = @json($editableItems);
    let isSaving = false;
    let pickerPage = 1;
    let pickerHasMore = false;
    let pickerLoading = false;
    let selectedProducts = new Map();

    const escapeHtml = (value) => String(value).replace(/[&<>'"]/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
    })[character]);

    function renderItems() {
        itemsList.innerHTML = items.map((item, index) => `
            <div class="flex items-center px-5 py-4 border-b border-slate-100 last:border-b-0">
                <div class="flex-1 min-w-0"><p class="truncate text-sm font-semibold text-slate-800">${escapeHtml(item.product_name)}</p></div>
                <div class="w-20 flex items-center justify-center gap-1">
                    <button type="button" class="quantity-change w-7 h-7 rounded bg-slate-100 text-slate-700 hover:bg-slate-200" data-index="${index}" data-change="-1" title="Decrease quantity">−</button>
                    <span class="min-w-5 text-center text-sm font-semibold text-slate-700">${item.quantity}</span>
                    <button type="button" class="quantity-change w-7 h-7 rounded bg-slate-100 text-slate-700 hover:bg-slate-200" data-index="${index}" data-change="1" title="Increase quantity">+</button>
                </div>
                <div class="w-28 text-right text-sm text-slate-600">₹${currency.format(item.price)}</div>
                <div class="w-28 text-right text-sm font-bold text-[#128C7E]">₹${currency.format(item.total)}</div>
                <div class="w-12 text-right"><button type="button" class="remove-item text-red-500 hover:text-red-700" data-index="${index}" title="Remove item"><i class="fas fa-trash-alt"></i></button></div>
            </div>`).join('');
        document.getElementById('itemCount').textContent = `${items.length} ${items.length === 1 ? 'Item' : 'Items'}`;
    }

    function updateTotals(order) {
        document.getElementById('paymentAmount').value = Number(order.grand_total).toFixed(2);
        document.getElementById('subtotalAmount').textContent = `₹${currency.format(order.subtotal)}`;
        document.getElementById('discountAmount').textContent = `₹${currency.format(order.discount)}`;
        document.getElementById('grandTotalAmount').textContent = `₹${currency.format(order.grand_total)}`;
    }

    renderItems();

    async function saveItems(nextItems) {
        if (!nextItems.length) {
            alert('At least one product is required in an order.');
            return false;
        }
        if (isSaving) return false;
        isSaving = true;
        try {
            const response = await fetch("{{ route('pos.order.items.update', $order->id) }}", {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                body: JSON.stringify({ items: nextItems.map(item => ({ product_id: item.product_id, quantity: item.quantity })) })
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Unable to update order items.');
            items = data.items;
            renderItems();
            updateTotals(data.order);
            return true;
        } catch (error) {
            alert(error.message || 'Unable to update order items.');
            return false;
        } finally {
            isSaving = false;
        }
    }

    itemsList.addEventListener('click', async function (event) {
        const quantityButton = event.target.closest('.quantity-change');
        const removeButton = event.target.closest('.remove-item');
        if (quantityButton) {
            const index = Number(quantityButton.dataset.index);
            const quantity = items[index].quantity + Number(quantityButton.dataset.change);
            if (quantity < 1) return;
            const nextItems = items.map((item, itemIndex) => itemIndex === index ? { ...item, quantity } : item);
            await saveItems(nextItems);
        }
        if (removeButton) {
            const index = Number(removeButton.dataset.index);
            if (!confirm(`Remove ${items[index].product_name} from this bill?`)) return;
            await saveItems(items.filter((_, itemIndex) => itemIndex !== index));
        }
    });

    function showPicker() {
        picker.classList.remove('hidden');
        picker.classList.add('flex');
        selectedProducts = new Map();
        updateSelectedProductButton();
        loadPickerProducts();
    }
    function hidePicker() { picker.classList.add('hidden'); picker.classList.remove('flex'); }

    function updateSelectedProductButton() {
        const count = selectedProducts.size;
        selectedProductCount.textContent = count ? `${count} ${count === 1 ? 'product' : 'products'} selected` : 'No products selected';
        addSelectedProducts.disabled = count === 0;
        addSelectedProducts.innerHTML = `<i class="fas fa-plus mr-1"></i> Add selected items${count ? ` (${count})` : ''}`;
    }
    addButton.addEventListener('click', showPicker);
    document.getElementById('closeProductPicker').addEventListener('click', hidePicker);
    picker.addEventListener('click', event => { if (event.target === picker) hidePicker(); });
    pickerCategory.addEventListener('change', () => loadPickerProducts());

    async function loadPickerProducts(page = 1, append = false) {
        if (pickerLoading || (append && !pickerHasMore)) return;
        pickerLoading = true;
        if (!append) {
            pickerPage = 1;
            pickerHasMore = false;
            pickerGrid.innerHTML = '';
            pickerEmpty.textContent = 'Loading products...';
            pickerEmpty.classList.remove('hidden');
        } else {
            pickerLoadMore.classList.remove('hidden');
        }
        try {
            const response = await fetch(`{{ route('pos.search') }}?page=${page}&category_id=${encodeURIComponent(pickerCategory.value)}`, { headers: { Accept: 'application/json' } });
            const data = await response.json();
            const products = data.products || [];
            if (!response.ok || !data.success) throw new Error('Unable to load products.');
            pickerHasMore = Boolean(data.pagination?.has_more_pages);
            pickerPage = page + 1;
            pickerEmpty.classList.toggle('hidden', products.length > 0 || append);
            if (!products.length && !append) pickerEmpty.textContent = 'No active products found.';
            products.forEach(product => {
                const card = document.createElement('button');
                card.type = 'button';
                card.className = 'add-product w-full rounded-2xl border border-slate-200 bg-white p-3 text-left transition hover:border-[#128C7E] hover:bg-emerald-50';
                card.dataset.product = JSON.stringify(product);
                if (selectedProducts.has(String(product.id))) {
                    card.classList.add('border-[#128C7E]', 'bg-emerald-50');
                }
                const imagePath = String(product.image || '').trim();
                const image = !imagePath
                    ? `{{ asset('images/no-product.png') }}`
                    : (/^(https?:)?\/\//i.test(imagePath) || imagePath.startsWith('data:'))
                        ? imagePath
                        : `{{ asset('') }}${imagePath.replace(/^\/+/, '')}`;
                card.innerHTML = `<div class="h-32 overflow-hidden rounded-xl bg-slate-100 p-2"><img src="${image}" alt="${escapeHtml(product.name)}" class="h-full w-full object-contain" onerror="this.onerror=null;this.src='{{ asset('images/no-product.png') }}';"></div><div class="pt-3"><p class="min-h-10 text-sm font-semibold leading-5 text-slate-800">${escapeHtml(product.name)}</p><p class="mt-1 truncate text-xs text-slate-400">SKU: ${escapeHtml(product.sku_product_id || product.barcode_base || 'N/A')}</p></div><div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3"><span class="text-xs font-medium text-slate-400">Price</span><span class="text-sm font-bold text-[#128C7E]">₹${currency.format(product.price)}</span></div>`;
                pickerGrid.appendChild(card);
            });
        } catch (_) {
            if (!append) { pickerEmpty.textContent = 'Products could not be loaded. Please try again.'; pickerEmpty.classList.remove('hidden'); }
        } finally {
            pickerLoading = false;
            pickerLoadMore.classList.add('hidden');
        }
    }

    new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) loadPickerProducts(pickerPage, true);
    }, { root: pickerResults, rootMargin: '160px 0px' }).observe(pickerSentinel);

    pickerResults.addEventListener('click', function (event) {
        const button = event.target.closest('.add-product');
        if (!button) return;
        const product = JSON.parse(button.dataset.product);
        const productId = String(product.id);
        if (selectedProducts.has(productId)) {
            selectedProducts.delete(productId);
            button.classList.remove('border-[#128C7E]', 'bg-emerald-50');
        } else {
            selectedProducts.set(productId, product);
            button.classList.add('border-[#128C7E]', 'bg-emerald-50');
        }
        updateSelectedProductButton();
    });

    addSelectedProducts.addEventListener('click', async function () {
        if (!selectedProducts.size) return;
        const nextItems = items.map(item => ({ ...item }));
        selectedProducts.forEach(product => {
            const existingIndex = nextItems.findIndex(item => Number(item.product_id) === Number(product.id));
            if (existingIndex >= 0) nextItems[existingIndex].quantity += 1;
            else nextItems.push({ product_id: product.id, product_name: product.name, price: Number(product.price), quantity: 1, total: Number(product.price) });
        });
        const originalContent = addSelectedProducts.innerHTML;
        addSelectedProducts.disabled = true;
        addSelectedProducts.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Adding...';
        if (await saveItems(nextItems)) hidePicker();
        else { addSelectedProducts.disabled = false; addSelectedProducts.innerHTML = originalContent; }
    });
});
</script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('paymentForm');

    const button = document.getElementById('completePayment');

    form.addEventListener('submit', function (event) {

        const customerName =
            document.getElementById('customer_name').value.trim();

        if (!customerName) {

            event.preventDefault();

            alert('Please enter customer name.');

            document
                .getElementById('customer_name')
                .focus();

            return;

        }

        button.disabled = true;

        button.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i>
            Processing Payment...
        `;

    });

});

</script>

@endsection
