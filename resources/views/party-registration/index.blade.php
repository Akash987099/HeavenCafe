<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Party Booking | The Heaven Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: Inter, sans-serif
        }

        .display {
            font-family: 'DM Serif Display', serif
        }

        .party-card {
            transition: .2s ease
        }

        .party-card:hover {
            transform: translateX(4px)
        }

        .party-card.selected {
            border-color: #f97316;
            background: #fff7ed;
            box-shadow: 0 12px 28px rgba(249, 115, 22, .15)
        }

        .party-image {
            animation: party-slide .35s ease-out
        }

        @keyframes party-slide {
            from {
                opacity: 0;
                transform: translateX(18px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }
    </style>
    <style>
        .party-card>div.relative {
            height: 110px;
            margin: 0;
            border-radius: 0
        }

        .party-card>div.p-4 {
            min-height: 82px;
            padding: 14px
        }

        .party-card>div.p-4 p {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden
        }

        @media (min-width: 1024px) {
            #booking-layout { grid-template-columns: 280px minmax(0, 1fr); }
            #booking-layout.party-selected { grid-template-columns: 280px 440px minmax(0, 1fr); }
        }
    </style>
</head>

<body class="min-h-screen bg-[#fffaf5] text-slate-800">
    <header class="border-b border-orange-100 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-3"><a href="{{ route('home') }}"
                class="text-base font-extrabold tracking-tight text-slate-800">The Heaven <span
                    class="text-orange-600">Cafe</span></a><button type="button" id="change-store"
                class="rounded-full border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-bold text-orange-700">Store:
                <span
                    id="header-store-name">{{ $stores->firstWhere('id', $selectedStoreId)->name ?? 'Choose store' }}</span></button>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-5 py-5 lg:py-6">
        @if (session('success'))
            <div
                class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                ✓ {{ session('success') }}</div>
        @endif
        <div class="mt-3">
            <p class="text-xs font-extrabold uppercase tracking-[.18em] text-orange-600">Party booking</p>
            <h1 class="display mt-1 text-3xl text-slate-950 sm:text-4xl">Choose an occasion. Make it yours.</h1>
            <p class="mt-2 max-w-2xl text-sm leading-5 text-slate-500">Select a party type, explore its details and send
                your requirements in one place.</p>
        </div>
        <div id="booking-layout" class="mt-5 grid gap-5">
            <section>
                <div class="mb-5 flex items-end justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wider text-orange-600">Step 1</p>
                        <h2 class="display mt-1 text-3xl text-slate-900">Pick your occasion</h2>
                    </div><span class="text-xs text-slate-400">{{ $partyTypes->count() }} options</span>
                </div>
                <div class="grid gap-3">
                    @forelse($partyTypes as $party)
                        <a href="{{ route('booking.show', $party) }}" data-party-id="{{ $party->id }}"
                            class="party-card group overflow-hidden rounded-2xl border-2 border-transparent bg-white text-left shadow-sm">
                            <div class="relative h-36 overflow-hidden bg-gradient-to-br from-orange-100 to-amber-50">
                                @if ($party->image)
                                    <img src="{{ asset($party->image) }}" alt="{{ $party->name }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105">@else
                                    <div class="flex h-full items-center justify-center text-5xl">
                                        {{ $loop->index === 0 ? '🎂' : ($loop->index === 1 ? '💍' : ($loop->index === 2 ? '🎓' : '✨')) }}
                                    </div>
                                @endif
                                <div
                                    class="absolute right-3 top-3 hidden h-6 w-6 items-center justify-center rounded-full bg-orange-600 text-xs font-bold text-white selected-mark">
                                    ✓</div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-slate-800">{{ $party->name }}</h3>
                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                    {{ $party->description ?: 'Tell us what you have in mind and we will help create the perfect celebration.' }}
                                </p>
                            </div>
                    </a>@empty<div class="rounded-2xl bg-white p-6 text-sm text-slate-500">Party options will be
                            available shortly.</div>
                    @endforelse
                </div>
            </section>
            <aside id="registration-form"
                class="h-fit rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-lg shadow-orange-100/50 sm:p-7 lg:sticky lg:top-5">
                <p class="text-sm font-bold uppercase tracking-wider text-orange-600">Step 2</p>
                <h2 class="display mt-1 text-3xl text-slate-900">Plan your party</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Share the details and we’ll get back to you.</p>
                <form method="POST" action="{{ route('booking.store') }}" class="mt-6 space-y-4">@csrf<input
                        id="party_master_id" type="hidden" name="party_master_id"
                        value="{{ old('party_master_id', $selectedParty->id ?? '') }}">
                    @error('party_master_id')
                        <p class="rounded-lg bg-rose-50 p-3 text-xs text-rose-600">Please select a party type.</p>
                    @enderror
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-600">Preferred cafe store <span
                                class="text-rose-500">*</span></label><select required id="booking_store_id"
                            name="store_id"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:border-orange-500">
                            <option value="">Select a store</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" @selected(old('store_id', $selectedStoreId) == $store->id)>
                                    {{ $store->name }}{{ $store->city ? ' — ' . $store->city : '' }}</option>
                            @endforeach
                        </select>
                        <p id="store-session-status" class="mt-1 hidden text-xs font-medium text-emerald-600">✓ Store
                            saved for this booking session</p>
                        @error('store_id')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-600">Your name <span
                                class="text-rose-500">*</span></label><input required name="customer_name"
                            value="{{ old('customer_name') }}"
                            class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm outline-none focus:border-orange-500"
                            placeholder="Enter your name">
                        @error('customer_name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label class="mb-1.5 block text-xs font-semibold text-slate-600">Mobile <span
                                    class="text-rose-500">*</span></label><input required name="mobile"
                                value="{{ old('mobile') }}"
                                class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm outline-none focus:border-orange-500"
                                placeholder="9876543210"></div>
                        <div><label class="mb-1.5 block text-xs font-semibold text-slate-600">Guests <span
                                    class="text-rose-500">*</span></label><input required min="1" type="number"
                                name="guest_count" value="{{ old('guest_count') }}"
                                class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm outline-none focus:border-orange-500"
                                placeholder="50"></div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label class="mb-1.5 block text-xs font-semibold text-slate-600">Event date <span
                                    class="text-rose-500">*</span></label><input required type="date"
                                name="event_date" value="{{ old('event_date') }}"
                                class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm outline-none focus:border-orange-500">
                        </div>
                        <div><label class="mb-1.5 block text-xs font-semibold text-slate-600">Time</label><input
                                type="time" name="event_time" value="{{ old('event_time') }}"
                                class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm outline-none focus:border-orange-500">
                        </div>
                    </div>
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-600">Email</label><input
                            type="email" name="email" value="{{ old('email') }}"
                            class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm outline-none focus:border-orange-500"
                            placeholder="you@example.com"></div>
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-600">Venue /
                            locality</label><input name="venue" value="{{ old('venue') }}"
                            class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm outline-none focus:border-orange-500"
                            placeholder="Where is the party planned?"></div>
                    <div><label class="mb-1.5 block text-xs font-semibold text-slate-600">Special requirements</label>
                        <textarea name="requirements" rows="3"
                            class="w-full rounded-xl border border-slate-200 p-3 text-sm outline-none focus:border-orange-500"
                            placeholder="Menu, decoration, cake or anything else...">{{ old('requirements') }}</textarea>
                    </div>
                    <button
                        class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-orange-600 text-sm font-bold text-white shadow-lg shadow-orange-200 transition hover:bg-orange-700"><span>Submit
                            party enquiry</span><span>→</span></button>
                    <p class="text-center text-xs leading-5 text-slate-400">Our team will contact you to confirm
                        availability and arrangements.</p>
                </form>
            </aside>
        </div>
    </main>
    <div id="store-first-modal"
        class="{{ $selectedStoreId ? 'hidden' : 'flex' }} fixed inset-0 z-50 items-center justify-center bg-slate-950/70 p-5 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-3xl bg-white p-7 shadow-2xl">
            <p class="text-xs font-extrabold uppercase tracking-[.18em] text-orange-600">Welcome</p>
            <h2 class="display mt-2 text-4xl text-slate-950">Choose your cafe store</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">Store select karne ke baad aap party type choose karke
                registration complete kar sakte hain.</p>
            <div class="mt-6 space-y-3">
                @forelse($stores as $store)
                    <button type="button" data-store-id="{{ $store->id }}"
                        data-store-name="{{ $store->name }}"
                        class="store-choice flex w-full items-center justify-between rounded-2xl border border-slate-200 p-4 text-left transition hover:border-orange-400 hover:bg-orange-50"><span><span
                                class="block font-bold text-slate-800">{{ $store->name }}</span><span
                                class="mt-0.5 block text-xs text-slate-500">{{ $store->city ?: $store->address ?: 'Cafe store' }}</span></span><span
                        class="text-lg text-orange-600">→</span></button>@empty<p
                        class="rounded-xl bg-rose-50 p-4 text-sm text-rose-600">No cafe store is available right now.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
    <script>
        const partyDetails = @json($partyTypes);
        const bookingBaseUrl = @json(url('booking'));
        const assetBaseUrl = @json(url('/'));
        const registrationPanel = document.getElementById('registration-form');
        registrationPanel.insertAdjacentHTML('beforebegin',
            '<div id="selected-party-panel" class="h-fit overflow-hidden rounded-3xl bg-slate-950 shadow-lg shadow-slate-200 lg:sticky lg:top-5"><div id="party-image-frame" class="party-image relative h-72 bg-gradient-to-br from-orange-400 to-rose-500"><img id="selected-party-image" class="hidden h-full w-full object-cover" alt=""><div id="selected-party-emoji" class="flex h-full items-center justify-center text-7xl">✨</div><div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-5 pt-16"><p class="text-xs font-bold uppercase tracking-wider text-orange-200">Selected celebration</p><h2 id="selected-party-name" class="display mt-1 text-3xl text-white">Choose your party</h2></div></div><div class="p-5"><p id="selected-party-description" class="text-sm leading-6 text-slate-300">Choose a party type from the left to see its details and register.</p><a id="selected-party-link" href="#" class="mt-4 inline-block text-xs font-bold text-orange-300">Open dedicated party page →</a></div></div>'
            );
        const bookingLayout = document.getElementById('booking-layout');
        const selectedPartyPanel = document.getElementById('selected-party-panel');
        selectedPartyPanel.classList.add('hidden');

        function updatePartyPreview(id) {
            const party = partyDetails.find(item => item.id === Number(id));
            if (!party) return;
            document.getElementById('party_master_id').value = party.id;
            selectedPartyPanel.classList.remove('hidden');
            bookingLayout.classList.add('party-selected');
            document.getElementById('selected-party-name').textContent = party.name;
            document.getElementById('selected-party-description').textContent = party.description;
            document.getElementById('selected-party-link').href = bookingBaseUrl + '/' + party.slug;
            const image = document.getElementById('selected-party-image'),
                emoji = document.getElementById('selected-party-emoji'),
                frame = document.getElementById('party-image-frame');
            frame.classList.remove('party-image');
            void frame.offsetWidth;
            frame.classList.add('party-image');
            if (party.image) {
                image.src = assetBaseUrl + '/' + party.image.replace(/^\/+/, '');
                image.alt = party.name;
                image.classList.remove('hidden');
                emoji.classList.add('hidden');
            } else {
                image.classList.add('hidden');
                emoji.classList.remove('hidden');
            }
        }
        document.querySelectorAll('[data-party-id]').forEach(card => card.addEventListener('click', () =>
            updatePartyPreview(card.dataset.partyId)));
        const selectedPartyId = document.getElementById('party_master_id').value;
        if (selectedPartyId) updatePartyPreview(selectedPartyId);
        const storeModal = document.getElementById('store-first-modal'),
            bookingStore = document.getElementById('booking_store_id');
        document.querySelectorAll('.store-choice').forEach(button => button.addEventListener('click', () => {
            bookingStore.value = button.dataset.storeId;
            bookingStore.dispatchEvent(new Event('change'));
            document.getElementById('header-store-name').textContent = button.dataset.storeName;
            storeModal.classList.add('hidden');
        }));
        document.getElementById('change-store').addEventListener('click', () => storeModal.classList.remove('hidden'));
    </script>
    <script>
        const partyInput = document.getElementById('party_master_id');
        const cards = document.querySelectorAll('[data-party-id]');
        const storeSelect = document.getElementById('booking_store_id');
        const storeStatus = document.getElementById('store-session-status');

        function selectParty(id) {
            partyInput.value = id;
            cards.forEach(card => {
                const selected = card.dataset.partyId === String(id);
                card.classList.toggle('selected', selected);
                card.querySelector('.selected-mark').classList.toggle('hidden', !selected);
            });
        }
        cards.forEach(card => card.addEventListener('click', event => {
            event.preventDefault();
            selectParty(card.dataset.partyId);
            document.getElementById('registration-form').scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }));
        if (partyInput.value) {
            selectParty(partyInput.value);
        }
        if (storeSelect.value) {
            storeStatus.classList.remove('hidden');
        }
        storeSelect.addEventListener('change', async () => {
            if (!storeSelect.value) {
                storeStatus.classList.add('hidden');
                return;
            }
            const data = new FormData();
            data.append('_token', document.querySelector('input[name="_token"]').value);
            data.append('store_id', storeSelect.value);
            try {
                const response = await fetch('{{ route('booking.store.select') }}', {
                    method: 'POST',
                    body: data,
                    headers: {
                        Accept: 'application/json'
                    }
                });
                if (response.ok) {
                    storeStatus.classList.remove('hidden');
                }
            } catch (error) {
                storeStatus.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
