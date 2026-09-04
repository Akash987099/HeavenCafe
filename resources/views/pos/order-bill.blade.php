@extends('pos.layout.app')

@section('content')

<style>

    /* =========================================================
       SCREEN INVOICE
    ========================================================= */

    .invoice-page {
        background: #f4f6f8;
        width: 100%;
        min-height: 100%;
        padding: 20px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .invoice-box {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #d6dee6;
        border-radius: 8px;
        overflow: hidden;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;

        padding: 20px 25px;

        background: #C2410C;
        color: #ffffff;
    }

    .company-name {
        font-size: 22px;
        font-weight: 700;
    }

    .company-subtitle {
        font-size: 12px;
        margin-top: 3px;
        opacity: .9;
    }

    .invoice-header-right {
        text-align: right;
    }

    .invoice-title {
        font-size: 26px;
        font-weight: 700;
        letter-spacing: .5px;
    }

    .invoice-number {
        font-size: 13px;
        margin-top: 4px;
        opacity: .95;
    }

    /* =========================================================
       CONTENT
    ========================================================= */

    .invoice-content {
        padding: 22px 25px 0;
    }

    /* =========================================================
       INFORMATION
    ========================================================= */

    .invoice-info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .invoice-info-table td {
        width: 33.33%;
        vertical-align: top;
        padding-right: 20px;
    }

    .invoice-info-table td:last-child {
        padding-right: 0;
    }

    .info-heading {
        font-size: 12px;
        font-weight: 700;
        color: #C2410C;

        border-bottom: 1px solid #dce6ee;

        padding-bottom: 6px;
        margin-bottom: 8px;
    }

    .info-content {
        font-size: 13px;
        line-height: 1.6;
        color: #374151;
        word-break: break-word;
    }

    .info-content strong {
        color: #111827;
    }

    /* =========================================================
       THANK YOU
    ========================================================= */

    .payment-box {
        margin-bottom: 20px;

        padding: 11px 13px;

        background: #f5faf9;

        border-left: 3px solid #C2410C;
    }

    .payment-box span {
        font-size: 12px;
        color: #64748b;
    }

    .payment-box strong {
        margin-left: 4px;

        font-size: 13px;

        color: #C2410C;

        text-transform: uppercase;
    }

    /* =========================================================
       PRODUCTS
    ========================================================= */

    .items-scroll {
        width: 100%;
        overflow-x: auto;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;

        border: 1px solid #dce6ee;

        font-size: 13px;

        table-layout: fixed;

        margin-bottom: 20px;
    }

    .items-table th,
    .items-table td {
        border-bottom: 1px solid #e6edf3;

        padding: 11px 9px;

        word-break: break-word;
    }

    .items-table th {
        background: #eaf8f5;

        color: #C2410C;

        text-transform: uppercase;

        font-size: 11px;

        text-align: left;
    }

    .items-table tr:last-child td {
        border-bottom: none;
    }

    .items-table th:nth-child(n+3),
    .items-table td:nth-child(n+3) {
        text-align: right;
    }

    .items-table th:nth-child(1),
    .items-table td:nth-child(1) {
        width: 5%;
        text-align: center;
    }

    .items-table th:nth-child(2),
    .items-table td:nth-child(2) {
        width: 43%;
        text-align: left;
    }

    .items-table th:nth-child(3),
    .items-table td:nth-child(3) {
        width: 12%;
    }

    .items-table th:nth-child(4),
    .items-table td:nth-child(4) {
        width: 20%;
    }

    .items-table th:nth-child(5),
    .items-table td:nth-child(5) {
        width: 20%;
    }

    /* =========================================================
       SUMMARY
    ========================================================= */

    .summary-box {
        width: 340px;
        max-width: 100%;

        margin-left: auto;
        margin-bottom: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;

        padding: 7px 0;

        font-size: 13px;

        color: #64748b;
    }

    .summary-row strong {
        color: #334155;
    }

    .grand-total {
        display: flex;
        justify-content: space-between;
        align-items: center;

        margin-top: 7px;
        padding-top: 12px;

        border-top: 2px solid #C2410C;

        font-size: 18px;
        font-weight: 700;

        color: #C2410C;
    }

    /* =========================================================
       FOOTER
    ========================================================= */

    .invoice-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;

        gap: 15px;

        flex-wrap: wrap;

        background: #f5f9fc;

        border-top: 1px solid #d6dee6;

        padding: 16px 25px;
    }

    .footer-text {
        font-size: 12px;
        color: #64748b;
    }

    .footer-total {
        font-size: 19px;
        font-weight: 700;

        color: #C2410C;

        white-space: nowrap;
    }

    .footer-total span {
        font-size: 11px;
        font-weight: 400;

        color: #64748b;

        margin-right: 5px;
    }

    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .bill-actions {
        width: 100%;
        max-width: 900px;

        margin: 0 auto 15px;

        display: flex;

        justify-content: flex-end;

        gap: 10px;

        flex-wrap: wrap;
    }

    .bill-action {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 5px;

        border: 0;
        border-radius: 8px;

        padding: 10px 18px;

        font-size: 13px;
        font-weight: 600;

        cursor: pointer;

        text-decoration: none;

        transition: .2s ease;
    }

    .new-order-btn {
        background: #ffffff;

        border: 1px solid #d6dee6;

        color: #475569;
    }

    .new-order-btn:hover {
        background: #f8fafc;
    }

    .print-a4-btn {
        background: #2563eb;
        color: #ffffff;
    }

    .print-a4-btn:hover {
        background: #1d4ed8;
    }

    .print-thermal-btn {
        background: #C2410C;
        color: #ffffff;
    }

    .print-thermal-btn:hover {
        background: #B91C1C;
    }


    /* =========================================================
       MOBILE SCREEN
    ========================================================= */

    @media (max-width: 700px) {

        .invoice-page {
            padding: 10px;
        }

        .bill-actions {
            justify-content: stretch;
        }

        .bill-action {
            flex: 1;
            min-width: 120px;
        }

        .invoice-header {
            padding: 16px;
        }

        .invoice-content {
            padding: 16px 14px 0;
        }

        .invoice-info-table,
        .invoice-info-table tbody,
        .invoice-info-table tr,
        .invoice-info-table td {
            display: block;
            width: 100%;
        }

        .invoice-info-table td {
            padding-right: 0;
            margin-bottom: 15px;
        }

        .invoice-info-table td:last-child {
            margin-bottom: 0;
        }

        .invoice-header {
            align-items: flex-start;
        }

        .invoice-title {
            font-size: 21px;
        }

        .company-name {
            font-size: 18px;
        }

        .items-table {
            min-width: 650px;
        }

        .summary-box {
            width: 100%;
        }

        .invoice-footer {
            padding: 14px 16px;
        }

    }


    /* =========================================================
       PRINT - COMMON
    ========================================================= */

    @media print {

        body * {
            visibility: hidden !important;
        }

        .invoice-box,
        .invoice-box * {
            visibility: visible !important;
        }

        .bill-actions {
            display: none !important;
        }

        .invoice-page {
            position: absolute !important;

            left: 0 !important;
            top: 0 !important;

            margin: 0 !important;

            background: #ffffff !important;

            min-height: auto !important;

            padding: 0 !important;

            overflow: visible !important;
        }

        .invoice-box {
            overflow: visible !important;

            box-shadow: none !important;
        }

        .items-scroll {
            overflow: visible !important;
        }

        .invoice-header,
        .items-table th,
        .payment-box,
        .invoice-footer {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

    }


    /* =========================================================
       A4 PRINT
    ========================================================= */

    @media print {

        body.a4-print {
            width: 210mm !important;

            margin: 0 !important;
            padding: 0 !important;

            background: #ffffff !important;
        }

        body.a4-print .invoice-page {
            width: 100% !important;
        }

        body.a4-print .invoice-box {
            width: 190mm !important;
            max-width: 190mm !important;

            margin: 0 auto !important;

            border: 1px solid #d6dee6 !important;

            border-radius: 0 !important;
        }

        body.a4-print .invoice-header {
            padding: 20px 25px !important;

            background: #C2410C !important;
            color: #ffffff !important;
        }

        body.a4-print .invoice-content {
            padding: 22px 25px 0 !important;
        }

        body.a4-print .items-table {
            width: 100% !important;

            min-width: 0 !important;

            font-size: 13px !important;
        }

        body.a4-print .items-table th,
        body.a4-print .items-table td {
            padding: 11px 9px !important;
        }

    }


    /* =========================================================
       THERMAL 80MM PRINT
    ========================================================= */

    @media print {

        body.thermal-print {
            width: 80mm !important;

            margin: 0 !important;
            padding: 0 !important;

            background: #ffffff !important;
        }

        body.thermal-print .invoice-page {
            width: 80mm !important;
        }

        body.thermal-print .invoice-box {
            width: 80mm !important;
            max-width: 80mm !important;

            margin: 0 !important;

            border: none !important;

            border-radius: 0 !important;

            box-shadow: none !important;
        }

        /* Header */

        body.thermal-print .invoice-header {
            padding: 8px 5px !important;

            background: #ffffff !important;

            color: #000000 !important;

            border-bottom: 1px dashed #000000 !important;
        }

        body.thermal-print .company-name {
            font-size: 17px !important;
            color: #000000 !important;
        }

        body.thermal-print .company-subtitle {
            font-size: 9px !important;
            color: #000000 !important;
        }

        body.thermal-print .invoice-title {
            font-size: 17px !important;
            color: #000000 !important;
        }

        body.thermal-print .invoice-number {
            font-size: 9px !important;
            color: #000000 !important;
        }


        /* Content */

        body.thermal-print .invoice-content {
            padding: 7px 5px 0 !important;
        }


        /* Customer information */

        body.thermal-print .invoice-info-table {
            margin-bottom: 8px !important;
        }

        body.thermal-print .invoice-info-table,
        body.thermal-print .invoice-info-table tbody,
        body.thermal-print .invoice-info-table tr,
        body.thermal-print .invoice-info-table td {
            display: block !important;

            width: 100% !important;
        }

        body.thermal-print .invoice-info-table td {
            padding: 0 !important;

            margin-bottom: 7px !important;
        }

        body.thermal-print .info-heading {
            font-size: 9px !important;

            color: #000000 !important;

            border-bottom: 1px dashed #000000 !important;

            padding-bottom: 2px !important;

            margin-bottom: 3px !important;
        }

        body.thermal-print .info-content {
            font-size: 9px !important;

            line-height: 1.4 !important;

            color: #000000 !important;
        }

        body.thermal-print .info-content strong {
            color: #000000 !important;
        }


        /* Thank you */

        body.thermal-print .payment-box {
            margin-bottom: 8px !important;

            padding: 6px !important;

            background: #ffffff !important;

            border: 1px dashed #000000 !important;

            border-left: 1px dashed #000000 !important;
        }

        body.thermal-print .payment-box span,
        body.thermal-print .payment-box strong {
            font-size: 9px !important;

            color: #000000 !important;
        }


        /* Products */

        body.thermal-print .items-table {
            width: 100% !important;

            min-width: 0 !important;

            table-layout: fixed !important;

            font-size: 8px !important;

            margin-bottom: 8px !important;
        }

        body.thermal-print .items-table th,
        body.thermal-print .items-table td {
            padding: 4px 2px !important;

            color: #000000 !important;

            word-break: break-word !important;
        }

        body.thermal-print .items-table th {
            background: #ffffff !important;

            color: #000000 !important;

            border-bottom: 1px dashed #000000 !important;
        }

        body.thermal-print .items-table th:nth-child(1),
        body.thermal-print .items-table td:nth-child(1) {
            width: 6% !important;
        }

        body.thermal-print .items-table th:nth-child(2),
        body.thermal-print .items-table td:nth-child(2) {
            width: 38% !important;
        }

        body.thermal-print .items-table th:nth-child(3),
        body.thermal-print .items-table td:nth-child(3) {
            width: 12% !important;
        }

        body.thermal-print .items-table th:nth-child(4),
        body.thermal-print .items-table td:nth-child(4) {
            width: 21% !important;
        }

        body.thermal-print .items-table th:nth-child(5),
        body.thermal-print .items-table td:nth-child(5) {
            width: 23% !important;
        }


        /* Summary */

        body.thermal-print .summary-box {
            width: 100% !important;

            margin-bottom: 8px !important;
        }

        body.thermal-print .summary-row {
            font-size: 9px !important;

            padding: 3px 0 !important;

            color: #000000 !important;
        }

        body.thermal-print .summary-row strong {
            color: #000000 !important;
        }

        body.thermal-print .grand-total {
            font-size: 13px !important;

            padding-top: 6px !important;

            margin-top: 4px !important;

            border-top: 1px dashed #000000 !important;

            color: #000000 !important;
        }


        /* Footer */

        body.thermal-print .invoice-footer {
            padding: 7px 5px !important;

            background: #ffffff !important;

            border-top: 1px dashed #000000 !important;
        }

        body.thermal-print .footer-text {
            font-size: 8px !important;

            color: #000000 !important;
        }

        body.thermal-print .footer-total {
            font-size: 13px !important;

            color: #000000 !important;
        }

        body.thermal-print .footer-total span {
            font-size: 8px !important;

            color: #000000 !important;
        }

    }

</style>


<div class="invoice-page">

    {{-- =====================================================
        ACTION BUTTONS
    ====================================================== --}}

    <div class="bill-actions">

        <a
            href="{{ route('pos.order') }}"
            class="bill-action new-order-btn"
        >
            <i class="fas fa-plus"></i>
            New Order
        </a>


        <button
            type="button"
            id="printA4Btn"
            class="bill-action print-a4-btn"
        >
            <i class="fas fa-file-invoice"></i>
            Print A4
        </button>


        <button
            type="button"
            id="printThermalBtn"
            class="bill-action print-thermal-btn"
        >
            <i class="fas fa-receipt"></i>
            Print Thermal
        </button>

    </div>


    {{-- =====================================================
        INVOICE
    ====================================================== --}}

    <div class="invoice-box">


        {{-- HEADER --}}

        <div class="invoice-header">

            <div>

                <div class="company-name">
                    The Heaven Cafe
                </div>

                <div class="company-subtitle">
                    Retail Store / POS
                </div>

            </div>


            <div class="invoice-header-right">

                <div class="invoice-title">
                    INVOICE
                </div>

                <div class="invoice-number">
                    #{{ $order->order_number }}
                </div>

            </div>

        </div>


        <div class="invoice-content">


            {{-- =================================================
                FROM / TO / INVOICE INFO
            ================================================== --}}

            <table class="invoice-info-table">

                <tr>


                    {{-- FROM --}}

                    <td>

                        <div class="info-heading">
                            FROM
                        </div>

                        <div class="info-content">

                            <strong>
                                The Heaven Cafe
                            </strong>

                            <br>

                            Retail Store

                            <br>

                            Uttar Pradesh, India

                            <br>

                            Email:
                            info@heavenkart.online

                        </div>

                    </td>


                    {{-- TO --}}

                    <td>

                        <div class="info-heading">
                            TO
                        </div>

                        <div class="info-content">

                            <strong>
                                {{ $order->customer_name ?: 'Walk-in Customer' }}
                            </strong>


                            @if($order->customer_phone)

                                <br>

                                +91 {{ $order->customer_phone }}

                            @endif


                            @if($order->customer_email)

                                <br>

                                {{ $order->customer_email }}

                            @endif

                        </div>

                    </td>


                    {{-- INVOICE INFO --}}

                    <td>

                        <div class="info-heading">
                            INVOICE INFO
                        </div>

                        <div class="info-content">

                            <strong>
                                Invoice No:
                            </strong>

                            {{ $order->order_number }}

                            <br>


                            <strong>
                                Invoice Date:
                            </strong>

                            {{ $order->created_at->format('d M Y, h:i A') }}

                            <br>


                            <strong>
                                Payment Mode:
                            </strong>

                            {{ strtoupper($order->payment_method ?? 'N/A') }}

                            <br>


                            <strong>
                                Status:
                            </strong>

                            {{ ucfirst($order->status ?? 'Pending') }}

                        </div>

                    </td>

                </tr>

            </table>


            {{-- =================================================
                THANK YOU
            ================================================== --}}

            <div class="payment-box">

                <span>
                    Thank you for shopping with
                </span>

                <strong>
                    The Heaven Cafe
                </strong>

            </div>


            {{-- =================================================
                PRODUCTS
            ================================================== --}}

            <div class="items-scroll">

                <table class="items-table">

                    <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Qty
                            </th>

                            <th>
                                Unit Price
                            </th>

                            <th>
                                Total
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($order->details as $key => $detail)

                            <tr>

                                <td>
                                    {{ $key + 1 }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $detail->product_name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $detail->quantity }}
                                </td>

                                <td>
                                    ₹{{ number_format($detail->price, 2) }}
                                </td>

                                <td>
                                    <strong>
                                        ₹{{ number_format($detail->total, 2) }}
                                    </strong>
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    style="text-align:center;"
                                >
                                    No items found
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                SUMMARY
            ================================================== --}}

            <div class="summary-box">

                <div class="summary-row">

                    <span>
                        Subtotal
                    </span>

                    <strong>
                        ₹{{ number_format($order->subtotal, 2) }}
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Discount
                    </span>

                    <strong>
                        ₹{{ number_format($order->discount ?? 0, 2) }}
                    </strong>

                </div>


                <div class="grand-total">

                    <span>
                        TOTAL
                    </span>

                    <span>
                        ₹{{ number_format($order->grand_total, 2) }}
                    </span>

                </div>

            </div>


        </div>


        {{-- =====================================================
            FOOTER
        ====================================================== --}}

        <div class="invoice-footer">

            <div class="footer-text">

                This is a computer generated invoice
                and does not require a signature.

            </div>


            <div class="footer-total">

                <span>
                    TOTAL AMOUNT
                </span>

                ₹{{ number_format($order->grand_total, 2) }}

            </div>

        </div>


    </div>

</div>


{{-- =========================================================
    PRINT JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const printA4Btn =
        document.getElementById('printA4Btn');

    const printThermalBtn =
        document.getElementById('printThermalBtn');


    /*
    |--------------------------------------------------------------------------
    | A4 PRINT
    |--------------------------------------------------------------------------
    */

    if (printA4Btn) {

        printA4Btn.addEventListener('click', function () {

            document.body.classList.remove(
                'thermal-print'
            );

            document.body.classList.add(
                'a4-print'
            );

            window.print();

        });

    }


    /*
    |--------------------------------------------------------------------------
    | THERMAL PRINT
    |--------------------------------------------------------------------------
    */

    if (printThermalBtn) {

        printThermalBtn.addEventListener('click', function () {

            document.body.classList.remove(
                'a4-print'
            );

            document.body.classList.add(
                'thermal-print'
            );

            window.print();

        });

    }


    /*
    |--------------------------------------------------------------------------
    | AFTER PRINT
    |--------------------------------------------------------------------------
    */

    window.addEventListener('afterprint', function () {

        document.body.classList.remove(
            'a4-print',
            'thermal-print'
        );

    });

});

</script>

@endsection
