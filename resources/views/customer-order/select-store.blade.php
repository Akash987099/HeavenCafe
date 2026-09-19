<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose a Store | The Heaven Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: Inter, sans-serif; }</style>
</head>
<body class="min-h-screen bg-orange-50 text-slate-800">
    <main class="max-w-5xl mx-auto px-4 py-10 sm:py-16">
        <div class="text-center max-w-xl mx-auto mb-10">
            <p class="text-orange-700 font-semibold text-sm tracking-wide uppercase">The Heaven Cafe</p>
            <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold">Choose your store</h1>
            <p class="mt-3 text-slate-500">Select the store you are visiting to see its available menu and place your order.</p>
        </div>

        @if(session('error'))
            <div class="max-w-xl mx-auto mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{{ session('error') }}</div>
        @endif

        @if($stores->isEmpty())
            <div class="rounded-2xl bg-white p-10 text-center shadow-sm border border-orange-100 text-slate-500">No stores are available right now.</div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($stores as $store)
                    <form method="POST" action="{{ route('customer-order.start') }}" class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        @csrf
                        <input type="hidden" name="store_id" value="{{ $store->id }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="w-11 h-11 shrink-0 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center text-lg font-bold">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($store->name, 0, 1)) }}</div>
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Open menu</span>
                        </div>
                        <h2 class="mt-5 font-bold text-lg">{{ $store->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $store->city ?: 'Store location' }}</p>
                        @if($store->address)<p class="mt-1 text-xs text-slate-400 line-clamp-2">{{ $store->address }}</p>@endif
                        <button class="mt-5 w-full rounded-xl bg-orange-600 py-3 text-sm font-semibold text-white hover:bg-orange-700">Order from this store</button>
                    </form>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
