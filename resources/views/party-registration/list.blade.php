<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Celebrate With Us | The Heaven Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:Inter,sans-serif}.display{font-family:'DM Serif Display',serif}.party-card{transition:transform .22s ease,box-shadow .22s ease}.party-card:hover{transform:translateY(-6px);box-shadow:0 24px 40px rgba(15,23,42,.13)}</style>
</head>
<body class="min-h-screen bg-[#fffaf5] text-slate-800">
    <header class="border-b border-orange-100 bg-white/90"><div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4"><a href="{{ route('home') }}" class="text-lg font-extrabold tracking-tight">The Heaven <span class="text-orange-600">Cafe</span></a><span class="rounded-full bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700">Plan a celebration</span></div></header>
    <main class="mx-auto max-w-7xl px-5 py-10 lg:py-14">
        <section class="mx-auto max-w-3xl text-center"><p class="text-sm font-bold uppercase tracking-[.22em] text-orange-600">The Heaven Cafe celebrations</p><h1 class="display mt-3 text-4xl leading-tight text-slate-900 sm:text-6xl">Every occasion deserves a beautiful gathering.</h1><p class="mx-auto mt-5 max-w-2xl text-sm leading-7 text-slate-500 sm:text-base">Choose your party type and tell us about the day you want to create. We’ll take care of the food, warmth and celebration.</p></section>
        <section class="mt-10"><div class="mb-5 flex items-center justify-between"><h2 class="text-lg font-bold text-slate-800">Choose an occasion</h2><span class="text-sm text-slate-400">{{ $partyTypes->count() }} available</span></div><div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($partyTypes as $party)
                <a href="{{ route('booking.show', $party) }}" class="party-card group overflow-hidden rounded-3xl border border-orange-100 bg-white shadow-sm"><div class="relative h-52 overflow-hidden bg-gradient-to-br from-orange-100 to-amber-50">@if($party->image)<img src="{{ asset($party->image) }}" alt="{{ $party->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">@else<div class="flex h-full items-center justify-center text-6xl">{{ $loop->index === 0 ? '🎂' : ($loop->index === 1 ? '💍' : ($loop->index === 2 ? '🎓' : '✨')) }}</div>@endif<div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/40 to-transparent"></div></div><div class="p-5"><div class="flex items-center justify-between gap-3"><h3 class="text-lg font-bold text-slate-800">{{ $party->name }}</h3><span class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-50 text-orange-600 transition group-hover:bg-orange-600 group-hover:text-white">→</span></div><p class="mt-2 text-sm leading-6 text-slate-500">{{ \Illuminate\Support\Str::limit($party->description, 110) ?: 'Start planning your celebration with The Heaven Cafe.' }}</p></div></a>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-orange-200 bg-white p-12 text-center text-sm text-slate-500">Party options will be available soon.</div>
            @endforelse
        </div></section>
    </main>
</body></html>
