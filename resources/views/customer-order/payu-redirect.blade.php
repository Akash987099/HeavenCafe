<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting to PayU…</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-orange-50 font-sans text-slate-800 grid place-items-center p-5">
    <main class="w-full max-w-sm rounded-2xl border border-orange-100 bg-white p-7 text-center shadow-sm">
        <div class="mx-auto h-12 w-12 animate-spin rounded-full border-4 border-orange-100 border-t-orange-600"></div>
        <h1 class="mt-5 text-xl font-bold">Opening secure payment</h1>
        <p class="mt-2 text-sm text-slate-500">You are being redirected to PayU. Please do not refresh or close this page.</p>
        <form id="payuPaymentForm" method="POST" action="{{ $paymentUrl }}" class="mt-6">
            @foreach($payload as $name => $value)
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endforeach
            <button type="submit" class="rounded-xl bg-orange-600 px-5 py-3 text-sm font-semibold text-white">Continue to payment</button>
        </form>
    </main>
    <script>document.getElementById('payuPaymentForm').submit();</script>
</body>
</html>
