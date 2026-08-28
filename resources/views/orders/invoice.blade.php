<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @font-face {
            font-family: 'NotoSansDevanagari';
            font-style: normal;
            font-weight: 400;
            src: url('{{ public_path("fonts/NotoSansDevanagari-Regular.ttf") }}') format("truetype");
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'NotoSansDevanagari', Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            color: #1f2937;
            padding: 16px;
        }

        .invoice-box {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #d6dee6;
            border-radius: 6px;
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: #1a4b63;
            color: #fff;
        }

        .header-right {
            text-align: right;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .invoice-number {
            font-size: 13px;
            opacity: 0.95;
            margin-top: 4px;
        }

        .company-name {
            font-size: 18px;
            font-weight: 700;
            white-space: nowrap;
        }

        .content {
            padding: 16px 20px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .info-table td {
            width: 33.33%;
            vertical-align: top;
            padding-right: 14px;
        }

        .info-table td:last-child {
            padding-right: 0;
        }

        .info-heading {
            font-size: 12px;
            font-weight: 700;
            color: #1a4b63;
            border-bottom: 1px solid #dce6ee;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }

        .info-content {
            font-size: 13px;
            line-height: 1.55;
            word-break: break-word;
        }

        .summary-section {
            margin-bottom: 14px;
            background: #f6fafc;
            border-left: 3px solid #1a4b63;
            padding: 10px 12px;
        }

        .summary-heading {
            color: #1a4b63;
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .summary-text {
            font-size: 13px;
            line-height: 1.45;
            color: #374151;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dce6ee;
            font-size: 13px;
            table-layout: fixed;
            margin-bottom: 16px;
        }

        .items-table th,
        .items-table td {
            border-bottom: 1px solid #e6edf3;
            padding: 10px 8px;
            word-break: break-word;
        }

        .items-table th {
            background: #eaf3f8;
            color: #1a4b63;
            text-transform: uppercase;
            font-size: 12px;
            text-align: left;
        }

        .items-table th:nth-child(2),
        .items-table td:nth-child(2),
        .items-table th:nth-child(3),
        .items-table td:nth-child(3),
        .items-table th:nth-child(4),
        .items-table td:nth-child(4) {
            text-align: right;
            white-space: nowrap;
        }

        .section-row td {
            background: #f5f9fc;
            color: #1a4b63;
            font-weight: 700;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            background: #f5f9fc;
            border-top: 1px solid #d6dee6;
            padding: 14px 20px;
        }

        .footer-text {
            font-size: 12px;
            color: #435a6d;
        }

        .total-amount {
            font-size: 18px;
            font-weight: 700;
            color: #1a4b63;
            white-space: nowrap;
        }

        .total-amount span {
            font-size: 12px;
            font-weight: 400;
            margin-right: 6px;
            color: #62788a;
        }

        .currency {
            font-family: "DejaVu Sans", "NotoSansDevanagari", Arial, Helvetica, sans-serif;
        }

        @media (max-width: 760px) {
            .header {
                padding: 14px;
            }

            .content {
                padding: 14px 14px 0;
            }

            .footer {
                padding: 12px 14px;
            }

            .info-table,
            .info-table tr,
            .info-table td {
                display: block;
                width: 100%;
            }

            .info-table td {
                padding-right: 0;
                margin-bottom: 10px;
            }

            .info-table td:last-child {
                margin-bottom: 0;
            }
        }

        @media print {
            @page {
                size: A4;
                margin: 8mm;
            }

            body {
                background: #fff;
                padding: 0;
            }

            .invoice-box {
                max-width: 100%;
                border: none;
                border-radius: 0;
                box-shadow: none;
            }

            .header,
            .items-table th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <div class="company-name">Heaven Store</div>
            <div class="header-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">#{{ $order->order_no }}</div>
            </div>
        </div>

        <div class="content">
            <table class="info-table">
                <tr>
                    <td>
                        <div class="info-heading">FROM</div>
                        <div class="info-content">
                            <strong>Heaven Store</strong><br>
                            Akash Kumar<br>
                            info@heavenstore.com <br>
                            Barthra kotla narkhi road firozabad <br>
                            Uttar Pradesh <br>
                            283206 <br>
                        </div>
                    </td>
                    <td>
                        <div class="info-heading">TO</div>
                        <div class="info-content">
                            <strong>{{ $address->person ?? $user->name }}</strong><br>
                            {{ $address->address ?? '' }}<br>
                            {{ $address->city ?? '' }}, {{ $address->state ?? '' }} {{ $address->pincode ?? '' }}<br>
                            +91 {{ $address->phone ?? $user->phone }}<br>
                            {{ $user->email }}
                        </div>
                    </td>
                    <td>
                        <div class="info-heading">INVOICE INFO</div>
                        <div class="info-content">
                            <strong>Invoice date:</strong> {{ now()->format('d M Y h:i A') }}<br>
                            <strong>Payment Mode:</strong> {{ $order->payment_method }}
                        </div>
                    </td>
                </tr>
            </table>

            <div class="summary-section">

                <div class="summary-text">
                    Thank you for shopping with <strong>Heaven Store</strong></div>

                <div style="margin-top:10px;">
                    <img src="{{ $order->barcode }}" style="height:80px;">
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 52%;">Description</th>
                        <th style="width: 12%;">QTY</th>
                        <th style="width: 18%;">Unit Price</th>
                        <th style="width: 18%;">Discount</th>
                        <th style="width: 18%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $key => $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->discount }}</td>
                            <td><strong>{{ $item->final_price }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div class="total-amount"><span>TOTAL Amount</span>INR {{$order->final_amount}}</div>
            <br>
            <div class="footer-text">
                This is a computer generated invoice and does not require a signature.
            </div>
        </div>
    </div>
</body>

</html>
