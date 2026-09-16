<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter - {{ $pos->name }}</title>
    <style>
        @page { size: A4; margin: 15mm 17mm; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #e7e7e7; color: #111; font-family: Arial, Helvetica, sans-serif; font-size: 10.5pt; line-height: 1.48; }
        .toolbar { width: 210mm; margin: 14px auto 8px; text-align: right; }
        .print-button { padding: 8px 16px; border: 0; border-radius: 3px; background: #3d2819; color: #fff; cursor: pointer; font-weight: 700; }
        .page { width: 210mm; min-height: 297mm; margin: 0 auto 14px; padding: 15mm 17mm; background: #fff; position: relative; box-shadow: 0 2px 12px rgba(0, 0, 0, .13); }
        .letterhead { height: 62px; border-bottom: 1px solid #bdbdbd; display: flex; align-items: flex-start; }
        .logo { width: 130px; max-height: 54px; object-fit: contain; object-position: left top; }
        .document-title { margin: 30px 0 42px; text-align: center; text-decoration: underline; font-family: 'Times New Roman', Times, serif; font-size: 16pt; font-weight: 700; letter-spacing: .3px; }
        .reference-row { display: flex; justify-content: space-between; gap: 25px; margin-bottom: 34px; font-size: 10pt; }
        .recipient { margin-bottom: 26px; line-height: 1.4; }
        .recipient p, .body-copy p { margin: 0 0 14px; }
        .subject { margin: 21px 0 18px !important; font-weight: 700; }
        .emphasis { font-weight: 700; }
        .key-terms { margin: 21px 0; padding-left: 19px; }
        .key-terms li { margin: 0 0 7px; }
        .signature-area { margin-top: 50px; width: 245px; text-align: center; page-break-inside: avoid; }
        .signature-space { height: 48px; }
        .signature-line { border-top: 1px solid #222; padding-top: 5px; font-size: 9.5pt; font-weight: 700; }
        .sign-company { margin-bottom: 3px; font-weight: 700; }
        .footer { position: absolute; right: 17mm; bottom: 12mm; left: 17mm; border-top: 1px solid #c8c8c8; padding-top: 6px; color: #555; font-size: 8pt; display: flex; justify-content: space-between; }
        .annexure-title { margin: 37px 0 8px; text-align: center; font-family: 'Times New Roman', Times, serif; font-size: 16pt; font-weight: 700; text-decoration: underline; }
        .annexure-subtitle { margin: 0 0 34px; text-align: center; color: #444; font-size: 9.5pt; }
        .annexure-intro { margin-bottom: 24px; }
        .salary-table { width: 100%; border-collapse: collapse; margin: 15px 0 27px; font-size: 10pt; }
        .salary-table th, .salary-table td { border: 1px solid #333; padding: 9px 11px; }
        .salary-table th { background: #f1f1f1; text-align: left; font-weight: 700; }
        .salary-table td:last-child, .salary-table th:last-child { width: 34%; text-align: right; }
        .salary-table .total td { font-weight: 700; background: #f7f7f7; }
        .annexure-list { padding-left: 19px; }
        .annexure-list li { margin: 0 0 9px; }
        .employee-acceptance { margin-top: 32px; padding-top: 16px; border-top: 1px solid #bbb; page-break-inside: avoid; }
        .accept-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 46px; margin-top: 42px; }
        .accept-line { height: 42px; border-bottom: 1px solid #222; }
        .accept-label { margin-top: 5px; font-size: 9.5pt; font-weight: 700; }
        @media print { body { background: #fff; } .toolbar { display: none; } .page { margin: 0; box-shadow: none; } .page + .page { break-before: page; page-break-before: always; } }
    </style>
</head>
<body>
    @php
        $companyName = 'HEAVEN CAFE';
        $letterNumber = 'HC/OL/' . $pos->staff_id . '/' . now()->format('Y');
        $joiningDate = $pos->date_of_joining ? $pos->date_of_joining->format('d F Y') : '________________________';
        $designation = $pos->designation ?: '________________________';
        $salary = (float) ($pos->salary ?? 0);
        $basic = $salary * .50;
        $hra = $salary * .30;
        $allowance = $salary * .20;
        $formatMoney = fn ($amount) => $amount > 0 ? '&#8377; ' . number_format($amount, 2) : '________________';
    @endphp

    <div class="toolbar"><button class="print-button" onclick="window.print()">Print Offer Letter</button></div>

    <section class="page">
        <header class="letterhead">
            @if (!empty($company?->image))
                <img class="logo" src="{{ asset($company->image) }}" alt="Heaven Cafe Logo">
            @endif
        </header>

        <h1 class="document-title">OFFER LETTER</h1>

        <div class="reference-row">
            <div>Ref. No.: <strong>{{ $letterNumber }}</strong></div>
            <div>Date: <strong>{{ now()->format('d M Y') }}</strong></div>
        </div>

        <section class="recipient">
            <p>Mr./Ms. <strong>{{ $pos->name }}</strong><br>
                Mobile: {{ $pos->mobile }}</p>
        </section>

        <section class="body-copy">
            <p>Dear {{ $pos->name }},</p>

            <p>We are pleased to offer you the position of <span class="emphasis">{{ $designation }}</span> with <span class="emphasis">{{ $companyName }}</span>. Based on our discussion and assessment, we believe that your skills and experience will be a valuable addition to our organisation.</p>

            <p>Your employment will commence from <span class="emphasis">{{ $joiningDate }}</span> at <span class="emphasis">{{ $pos->store->name ?? 'Heaven Cafe' }}</span>. Your monthly gross salary will be <span class="emphasis">{!! $formatMoney($salary) !!}</span>; the salary structure is detailed in Annexure A.</p>

            <p class="subject">Terms of Employment</p>
            <ol class="key-terms">
                <li>You will perform the duties assigned to you with diligence and maintain the service standards of {{ $companyName }}.</li>
                <li>You must follow the company’s attendance, working-hours, safety, hygiene, and workplace conduct policies.</li>
                <li>All customer, financial, operational, and company information must remain confidential.</li>
                <li>Your performance, attendance, and conduct will be reviewed during the probation period.</li>
                <li>Employment is subject to company policy and may be ended by either party in accordance with the applicable notice terms and law.</li>
            </ol>

            <p>We welcome you to {{ $companyName }} and look forward to your contribution to our continued growth and success. Please sign the acceptance in Annexure A and return a copy of this letter.</p>
        </section>

        <div class="signature-area">
            <p class="sign-company">For {{ $companyName }}</p>
            <div class="signature-space"></div>
            <div class="signature-line">Authorised Signatory</div>
        </div>

        <footer class="footer"><span>{{ $companyName }} | Employment Offer</span><span>Private &amp; Confidential</span></footer>
    </section>

    <section class="page">
        <header class="letterhead">
            @if (!empty($company?->image))
                <img class="logo" src="{{ asset($company->image) }}" alt="Heaven Cafe Logo">
            @endif
        </header>

        <h1 class="annexure-title">ANNEXURE A</h1>
        <p class="annexure-subtitle">Salary Structure and Employee Acceptance</p>

        <p class="annexure-intro">The following salary structure forms part of the offer made to <strong>{{ $pos->name }}</strong>, Employee ID <strong>{{ $pos->staff_id }}</strong>.</p>

        <table class="salary-table">
            <thead><tr><th>Salary Component</th><th>Monthly Amount</th></tr></thead>
            <tbody>
                <tr><td>Basic Salary</td><td>{!! $formatMoney($basic) !!}</td></tr>
                <tr><td>House Rent Allowance</td><td>{!! $formatMoney($hra) !!}</td></tr>
                <tr><td>Special Allowance</td><td>{!! $formatMoney($allowance) !!}</td></tr>
                <tr class="total"><td>Gross Monthly Salary</td><td>{!! $formatMoney($salary) !!}</td></tr>
                <tr class="total"><td>Annual CTC</td><td>{!! $formatMoney($salary * 12) !!}</td></tr>
            </tbody>
        </table>

        <h2 class="subject">Additional Employment Details</h2>
        <ul class="annexure-list">
            <li><strong>Probation period:</strong> ______ months from the date of joining.</li>
            <li><strong>Working hours:</strong> As per the company schedule and operational requirements.</li>
            <li><strong>Reporting to:</strong> ________________________________.</li>
            <li><strong>Work location:</strong> {{ $pos->store->name ?? 'Heaven Cafe' }}.</li>
            <li>Any statutory deductions, incentives, or benefits, where applicable, will be governed by company policy and applicable law.</li>
        </ul>

        <section class="employee-acceptance">
            <p><strong>Employee Acceptance</strong></p>
            <p>I, <strong>{{ $pos->name }}</strong>, confirm that I have read, understood, and accepted the terms of this offer letter and Annexure A.</p>
            <div class="accept-grid">
                <div><div class="accept-line"></div><div class="accept-label">Employee Signature</div></div>
                <div><div class="accept-line"></div><div class="accept-label">Date</div></div>
            </div>
        </section>

        <footer class="footer"><span>{{ $companyName }} | Annexure A</span><span>Private &amp; Confidential</span></footer>
    </section>
</body>
</html>
