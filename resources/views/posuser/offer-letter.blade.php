<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter - {{ $pos->name }}</title>
    <style>
        @page { size: A4; margin: 12mm; } * { box-sizing: border-box; }
        body { margin:0; background:#edf0f2; color:#292521; font:10.7pt/1.62 Cambria,Georgia,'Times New Roman',serif; }
        .toolbar { width:210mm; margin:15px auto 8px; text-align:right; } .print-btn { border:0; padding:9px 18px; border-radius:4px; background:#5c3820; color:#fff; font-weight:700; cursor:pointer; }
        .letter { width:210mm; min-height:297mm; margin:0 auto 18px; padding:15mm 18mm 18mm; background:#fff; box-shadow:0 2px 16px rgba(42,30,21,.08); } .top { height:5px; margin:-15mm -18mm 16px; background:linear-gradient(90deg,#4e2e1b 0 76%,#c99a36 76%); }
        .brand { display:flex; min-height:57px; align-items:center; justify-content:space-between; border-bottom:1px solid #d9d0c7; padding:0 0 12px; } .brand-left { display:flex; align-items:center; } .logo { width:92px; max-height:52px; object-fit:contain; object-position:left center; }
        .confidential { margin:0; color:#78553d; font-size:7.8pt; font-weight:700; letter-spacing:1px; line-height:1.55; text-align:right; text-transform:uppercase; }
        .title { margin:28px 0 18px; color:#4e2e1b; font:700 20pt Georgia,'Times New Roman',serif; letter-spacing:1.6px; text-align:center; } .meta { display:flex; justify-content:space-between; margin-bottom:19px; padding:8px 10px; border-top:1px solid #ebe3db; border-bottom:1px solid #ebe3db; color:#423932; font:9.2pt Arial,Helvetica,sans-serif; } .employee { display:grid; grid-template-columns:1.15fr .85fr; gap:24px; padding:0 0 16px; border-bottom:1px solid #ece6e0; } .employee p { margin:3px 0; } .employee .to { grid-column:1/-1; margin-bottom:5px; color:#5c3820; font:700 9.4pt Arial,Helvetica,sans-serif; letter-spacing:.7px; text-transform:uppercase; } .field-label { display:block; margin-bottom:1px; color:#806d60; font:7.6pt Arial,Helvetica,sans-serif; font-weight:700; letter-spacing:.8px; text-transform:uppercase; } p { margin:0 0 13px; }
        .subject { margin:19px 0 16px; padding:10px 13px; border-left:3px solid #b8842c; background:#faf7f3; font-size:10.7pt; } h3 { margin:22px 0 10px; color:#4e2e1b; font:700 13pt Georgia,'Times New Roman',serif; }
        .details { width:100%; margin:11px 0 19px; border-collapse:collapse; font:9.5pt Arial,Helvetica,sans-serif; } .details th,.details td { padding:8px 11px; border:1px solid #d8d0c8; text-align:left; } .details th { width:35%; background:#f7f2ec; color:#5c3820; } .salary { color:#52321e; font-weight:700; }
        .terms-page { page-break-before:always; break-before:page; padding-top:4mm; } .terms { margin:6px 0 0; padding-left:20px; } .terms li { margin:0 0 7px; } .acceptance { margin-top:21px; padding:13px 14px; border:1px solid #ddcfbd; background:#fcf9f5; } .acceptance h3 { margin-top:0; }
        .signatures { display:grid; grid-template-columns:1fr 1fr; gap:38px; margin-top:28px; page-break-inside:avoid; } .signature-title { margin-bottom:23px; color:#5c3820; font:700 12pt Georgia,'Times New Roman',serif; } .sign-row { min-height:24px; margin:7px 0; border-bottom:1px solid #777; font-size:9.6pt; } .sign-row span { background:#fff; padding-bottom:2px; } .accept-sign .sign-row span { background:#fcf9f5; } .footer { margin-top:25px; padding-top:8px; border-top:1px solid #d8cfc7; color:#887b70; font-size:8pt; text-align:center; }
        .legacy { display:none; }
        @media print { body{background:#fff}.toolbar{display:none}.letter{width:auto;min-height:0;margin:0;padding:0 3mm 4mm}.top{margin:0 -3mm 16px} }
    </style>
</head>
<body>
    @php
        $companyName = 'HEAVEN CAFE';
        $joiningDate = $pos->date_of_joining ? $pos->date_of_joining->format('d F Y') : '________________________';
        $designation = $pos->designation ?: '________________________';
        $address = $pos->address ?: '____________________________________________________________';
        $salary = $pos->salary !== null ? '&#8377; ' . number_format((float) $pos->salary, 2) . ' per month' : '&#8377; ________________________';
    @endphp

    <div class="toolbar"><button type="button" class="print-btn" onclick="window.print()">Print Offer Letter</button></div>

    <main class="letter">
        <div class="top"></div>
        <header class="brand">
            <div class="brand-left">
                @if (!empty($company?->image))
                    <img class="logo" src="{{ asset($company->image) }}" alt="Heaven Cafe Logo">
                @endif
            </div>
            <p class="confidential">Private &amp; Confidential<br>Employment Offer</p>
        </header>

        <h2 class="title">OFFER LETTER</h2>

        <div class="meta">
            <span><strong>Date:</strong> {{ now()->format('d F Y') }}</span>
            <span><strong>Offer Letter No.:</strong> HC/OL/{{ $pos->staff_id }}/{{ now()->format('Y') }}</span>
        </div>

        <section class="employee">
            <p class="to">To</p>
            <div><span class="field-label">Employee Name</span><strong>{{ $pos->name }}</strong></div>
            <div><span class="field-label">Contact Number</span>{{ $pos->mobile }}</div>
            <div><span class="field-label">Address</span>{{ $address }}</div>
        </section>
        <div class="subject"><strong>Subject:</strong> Offer of Employment</div>
        <p>Dear <strong>{{ $pos->name }}</strong>,</p>
        <p>We are pleased to offer you employment with <strong>{{ $companyName }}</strong> for the position of <strong>{{ $designation }}</strong>. Based on your profile and our discussion, we believe that your skills and abilities will be a valuable addition to our team.</p>

        <h3>Employment Details</h3>
        <table class="details">
            <tr><th>Designation</th><td>{{ $designation }}</td></tr><tr><th>Department</th><td>________________________</td></tr>
            <tr><th>Joining Date</th><td>{{ $joiningDate }}</td></tr><tr><th>Work Location</th><td>{{ $pos->store->name ?? 'Heaven Cafe' }}</td></tr>
            <tr><th>Working Hours</th><td>As per company schedule</td></tr><tr><th>Monthly Salary</th><td class="salary">{!! $salary !!}</td></tr>
            <tr><th>Reporting To</th><td>________________________</td></tr><tr><th>Probation Period</th><td>______ months</td></tr>
        </table>
        <section class="terms-page">
        <h3>Terms &amp; Conditions</h3>
        <ol class="terms">
            <li>You are expected to report to work on time and follow the working hours and attendance requirements of {{ $companyName }}.</li>
            <li>You must perform your assigned duties responsibly and maintain the required standards of work, service, and customer handling.</li>
            <li>You are required to follow all company policies, workplace rules, safety procedures, and instructions issued by management.</li>
            <li>Confidential company, customer, employee, financial, and operational information must not be disclosed to unauthorized persons.</li>
            <li>Company property, equipment, systems, documents, and resources must be used responsibly and only for authorized purposes.</li>
            <li>Any unauthorized absence, repeated lateness, misconduct, negligence, or violation of company policies may result in appropriate disciplinary action.</li>
            <li>Your employment will be subject to the company's Employee Working &amp; Conduct Policy and other applicable company policies.</li>
            <li>During the probation period, your performance, attendance, conduct, and suitability for the role will be reviewed by management.</li>
            <li>Employment may be terminated by either party in accordance with the applicable notice period, company policy, employment terms, and applicable law.</li>
            <li>The company reserves the right to amend its policies and employment terms when required, subject to applicable law.</li>
        </ol>
        <section class="acceptance"><h3>Acceptance</h3><p>We welcome you to <strong>{{ $companyName }}</strong> and look forward to your contribution to the growth and success of the organization.</p><p>Please sign below as confirmation that you have read, understood, and accepted the above offer and terms of employment.</p></section>
        <section class="signatures">
            <div><div class="signature-title">For {{ $companyName }}</div><div class="sign-row"><span>Authorized Signatory:</span></div><div class="sign-row"><span>Name:</span></div><div class="sign-row"><span>Designation:</span></div><div class="sign-row"><span>Signature &amp; Stamp:</span></div><div class="sign-row"><span>Date:</span></div></div>
            <div class="accept-sign"><div class="signature-title">Employee Acceptance</div><p>I, <strong>{{ $pos->name }}</strong>, accept the employment offer and agree to comply with the terms and policies of {{ $companyName }}.</p><div class="sign-row"><span>Employee Signature:</span></div><div class="sign-row"><span>Date:</span></div></div>
        </section>
        <div class="footer">This is an official employment offer issued by {{ $companyName }}.</div>
        </section>
        <div class="legacy">
        <table class="terms">
            <tr><th>Employee Name</th><td>{{ $pos->name }}</td></tr>
            <tr><th>Designation</th><td>{{ $pos->designation ?: 'As assigned by management' }}</td></tr>
            <tr><th>Department / Store</th><td>{{ $pos->store->name ?? 'Company Office' }}</td></tr>
            <tr><th>Date of Joining</th><td>{{ $joiningDate }}</td></tr>
            <tr><th>Monthly Gross Salary</th><td class="highlight">{{ $pos->salary !== null ? '₹ ' . number_format((float) $pos->salary, 2) : 'As mutually agreed' }}</td></tr>
            <tr><th>Employment Type</th><td>Full-time</td></tr>
        </table>

        <p>Your appointment is subject to the company’s policies, rules, and procedures as amended from time to time. You are expected to perform your duties diligently, maintain confidentiality of company information, and comply with all applicable workplace standards.</p>

        <p>Please sign and return a copy of this letter as your acceptance of the above terms. We wish you a successful and rewarding association with us.</p>

        <p class="closing">Sincerely,<br><strong>For {{ $companyName }}</strong></p>

        <div class="signature">
            <div class="signature-box">
                <div class="signature-name">Authorized</div>
                <div class="signature-role">Authorized Signatory</div>
            </div>
        </div>

        <div class="footer-note">This is an official offer letter generated by {{ $companyName }}.</div>
        </div>
    </main>
</body>
</html>
