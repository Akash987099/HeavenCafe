<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;background:#f8fafc;color:#1e293b;font-family:Arial,sans-serif;">
    <div style="max-width:620px;margin:32px auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;">
        <div style="background:#128C7E;color:#ffffff;padding:24px 30px;">
            <h1 style="margin:0;font-size:22px;">Salary Confirmation</h1>
            <p style="margin:8px 0 0;font-size:14px;opacity:.9;">{{ $salary['month_name'] }}</p>
        </div>
        <div style="padding:28px 30px;">
            <p style="margin-top:0;font-size:15px;">Hello <strong>{{ $staff->name }}</strong>,</p>
            <p style="font-size:14px;line-height:1.6;color:#475569;">Please review your salary calculation for <strong>{{ $salary['month_name'] }}</strong>. Your Staff ID is <strong>{{ $staff->staff_id }}</strong>.</p>

            <table style="width:100%;border-collapse:collapse;margin:22px 0;font-size:14px;">
                <tr><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;color:#64748b;">Monthly salary</td><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;text-align:right;font-weight:bold;">₹{{ number_format($salary['monthly_salary'], 2) }}</td></tr>
                <tr><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;color:#64748b;">Payable days</td><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;text-align:right;font-weight:bold;">{{ $salary['payable_days'] }} day(s)</td></tr>
                <tr><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;color:#64748b;">Gross salary</td><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;text-align:right;font-weight:bold;">₹{{ number_format($salary['gross_salary'], 2) }}</td></tr>
                <tr><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;color:#64748b;">Unpaid leave deduction ({{ $salary['unpaid_leave_days'] }} day(s))</td><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;text-align:right;color:#e11d48;font-weight:bold;">- ₹{{ number_format($salary['leave_deduction'], 2) }}</td></tr>
                <tr><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;color:#64748b;">Salary advances</td><td style="padding:11px 0;border-bottom:1px solid #e2e8f0;text-align:right;color:#e11d48;font-weight:bold;">- ₹{{ number_format($salary['advance_total'], 2) }}</td></tr>
                <tr><td style="padding:16px 0 0;font-size:16px;font-weight:bold;color:#047857;">Final payable salary</td><td style="padding:16px 0 0;text-align:right;font-size:18px;font-weight:bold;color:#047857;">₹{{ number_format($salary['final_salary'], 2) }}</td></tr>
            </table>

            <div style="border-left:4px solid #128C7E;background:#f0fdfa;padding:14px 16px;color:#115e59;font-size:14px;line-height:1.5;">
                Please reply to this email with <strong>Confirmed</strong> if these details are correct. If you find any issue, reply with <strong>Correction Required</strong> and mention the detail that needs correction.
            </div>
            <p style="margin:24px 0 0;font-size:14px;color:#475569;">Thank you,<br>The Heaven Cafe</p>
        </div>
    </div>
</body>
</html>
