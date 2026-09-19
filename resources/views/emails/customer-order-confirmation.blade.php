<!doctype html>
<html lang="en">
<body style="margin:0;background:#fff7ed;color:#1e293b;font-family:Arial,sans-serif;">
    <div style="max-width:600px;margin:24px auto;background:#ffffff;border:1px solid #fed7aa;border-radius:16px;overflow:hidden;">
        <div style="padding:24px;background:#ea580c;color:#ffffff;"><h1 style="margin:0;font-size:22px;">Order confirmed</h1><p style="margin:8px 0 0;opacity:.9;">The Heaven Cafe</p></div>
        <div style="padding:24px;">
            <p style="margin-top:0;">Hi {{ $order->customer_name }}, your order has been received by <strong>{{ $order->store->name }}</strong>.</p>
            <p style="padding:12px;background:#fff7ed;border-radius:8px;"><strong>Order number:</strong> {{ $order->order_number }}<br><strong>Payment status:</strong> Pending</p>
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                @foreach($order->items as $item)
                    <tr><td style="padding:9px 0;border-bottom:1px solid #e2e8f0;">{{ $item->product_name }} × {{ $item->quantity }}</td><td style="padding:9px 0;border-bottom:1px solid #e2e8f0;text-align:right;">₹{{ number_format($item->total, 2) }}</td></tr>
                @endforeach
                <tr><td style="padding-top:14px;font-weight:bold;">Total</td><td style="padding-top:14px;text-align:right;font-weight:bold;color:#c2410c;">₹{{ number_format($order->grand_total, 2) }}</td></tr>
            </table>
            <p style="margin:24px 0 0;color:#64748b;font-size:13px;">Please keep this email for your order reference.</p>
        </div>
    </div>
</body>
</html>
