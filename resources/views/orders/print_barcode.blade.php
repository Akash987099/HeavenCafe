<!DOCTYPE html>
<html>

<head>

    <title>Print Barcode</title>

    <style>
        body {
            text-align: center;
        }

        .barcode-box {
            display: inline-block;
            margin: 20px;
        }
    </style>

</head>

<body onload="window.print()">

    @foreach ($orders as $order)
        <div class="barcode-box">

            <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $order->order_no }}&code=Code128">

        </div>
    @endforeach

</body>

</html>
