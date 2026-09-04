@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="max-w-5xl mx-auto">

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

                                <span
                                    class="px-3 py-1.5 rounded-full
                                           bg-emerald-50 text-[#128C7E]
                                           text-xs font-semibold"
                                >
                                    {{ $order->details->count() }} Items
                                </span>

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
                                        <div class="w-20 text-center">

                                            <span
                                                class="inline-flex items-center
                                                       justify-center min-w-8 h-8
                                                       px-2 rounded-lg
                                                       bg-slate-100
                                                       text-sm font-semibold
                                                       text-slate-700"
                                            >
                                                {{ $detail->quantity }}
                                            </span>

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

                                    </div>

                                @endforeach

                            </div>

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

                                    <span class="text-sm font-medium text-slate-700">
                                        ₹{{ number_format($order->subtotal, 2) }}
                                    </span>

                                </div>


                                <div class="flex justify-between mb-3">

                                    <span class="text-sm text-slate-500">
                                        Discount
                                    </span>

                                    <span class="text-sm font-medium text-slate-700">
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

                                    <span
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
