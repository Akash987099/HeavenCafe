<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $store->name }} Menu | The Heaven Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: Inter, sans-serif; } .cart-open { overflow: hidden; }</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <main class="max-w-7xl mx-auto px-4 py-5 sm:py-8">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div><p class="text-xs font-semibold uppercase tracking-wider text-orange-600">Ordering from</p><h1 class="mt-1 text-2xl font-extrabold">{{ $store->name }}</h1></div>
            <a href="{{ route('customer-order.select-store') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100">Change store</a>
        </div>

        @if(session('error'))<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{{ session('error') }}</div>@endif

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
            <section class="lg:col-span-8 rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-4">
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <input id="productSearch" type="search" placeholder="Search menu..." class="h-11 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none focus:border-orange-500">
                        <select id="categoryFilter" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:border-orange-500"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select>
                    </div>
                    <div class="mt-4 flex items-center justify-between"><div><h2 class="font-bold">Menu</h2><p class="mt-1 text-xs text-slate-400">Only items available at this store are shown.</p></div><span id="productCount" class="rounded-full bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700">Loading...</span></div>
                </div>
                <div id="productGrid" class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 xl:grid-cols-3"></div>
                <div id="loader" class="p-7 text-center text-sm text-slate-400">Loading menu...</div>
                <div id="emptyState" class="hidden p-12 text-center text-sm text-slate-400">No items are available for this store.</div>
                <div id="scrollTarget" class="h-px"></div>
            </section>

            <aside class="lg:col-span-4 rounded-2xl border border-slate-200 bg-white shadow-sm lg:sticky lg:top-5 lg:h-[calc(100vh-3rem)] lg:max-h-[720px] flex flex-col">
                <div class="border-b border-slate-100 p-5"><h2 class="text-lg font-bold">Your order</h2><p class="mt-1 text-xs text-slate-400">Review items before placing your order.</p></div>
                <div id="cartItems" class="min-h-[190px] flex-1 space-y-3 overflow-y-auto p-4"><div class="flex min-h-[170px] items-center justify-center text-center text-sm text-slate-400">Your cart is empty.</div></div>
                <form id="orderForm" method="POST" action="{{ route('customer-order.place') }}" class="border-t border-slate-100 p-5">
                    @csrf
                    <div id="cartInputs"></div>
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Order type</label><select name="fulfillment_type" required class="mb-3 h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm outline-none focus:border-orange-500"><option value="packing" @selected(old('fulfillment_type', 'packing') === 'packing')>Packing / Takeaway</option><option value="dine_in" @selected(old('fulfillment_type') === 'dine_in')>Dine In</option></select>
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Your name</label><input name="customer_name" required maxlength="100" value="{{ old('customer_name') }}" class="mb-3 h-10 w-full rounded-lg border border-slate-200 px-3 text-sm outline-none focus:border-orange-500" placeholder="Enter your name">
                    <label class="mb-1 block text-xs font-semibold text-slate-600">Mobile number</label><input name="customer_mobile" required maxlength="20" value="{{ old('customer_mobile') }}" class="h-10 w-full rounded-lg border border-slate-200 px-3 text-sm outline-none focus:border-orange-500" placeholder="Enter mobile number">
                    <label class="mb-1 mt-3 block text-xs font-semibold text-slate-600">Email <span class="font-normal text-slate-400">(PayU receipt and order confirmation)</span></label><input type="email" name="customer_email" required maxlength="191" value="{{ old('customer_email') }}" class="h-10 w-full rounded-lg border border-slate-200 px-3 text-sm outline-none focus:border-orange-500" placeholder="you@example.com">
                    <div class="mt-4 flex justify-between border-t border-slate-100 pt-4 text-sm"><span class="text-slate-500">Total</span><strong id="cartTotal" class="text-orange-700">₹0.00</strong></div>
                    <button class="mt-4 h-12 w-full rounded-xl bg-orange-600 font-semibold text-white transition hover:bg-orange-700">Place order</button>
                </form>
            </aside>
        </div>
    </main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('productGrid'), loader = document.getElementById('loader'), empty = document.getElementById('emptyState'), count = document.getElementById('productCount');
    const search = document.getElementById('productSearch'), category = document.getElementById('categoryFilter'), cartItems = document.getElementById('cartItems'), cartInputs = document.getElementById('cartInputs'), total = document.getElementById('cartTotal');
    const assetBase = @json(asset('')), fallbackImage = @json(asset('images/no-product.png'));
    let page = 1, nextPage = 1, loading = false, cart = {}, timer;
    const esc = value => String(value || '').replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));
    const money = value => `₹${Number(value || 0).toFixed(2)}`;
    const image = value => {
        if (!value) return fallbackImage;
        const imagePath = String(value).trim();
        if (/^(https?:)?\/\//i.test(imagePath) || imagePath.startsWith('data:')) return imagePath;
        return assetBase.replace(/\/?$/, '/') + imagePath.replace(/^\/+/, '');
    };
    function renderCart() {
        const items = Object.values(cart), sum = items.reduce((a, i) => a + i.price * i.qty, 0);
        total.textContent = money(sum);
        cartInputs.innerHTML = items.map((i, n) => `<input type="hidden" name="cart[${n}][id]" value="${i.id}"><input type="hidden" name="cart[${n}][qty]" value="${i.qty}">`).join('');
        cartItems.innerHTML = items.length ? items.map(i => `<div class="rounded-xl border border-slate-200 bg-slate-50 p-3"><div class="flex items-center gap-3"><img src="${image(i.image)}" onerror="this.src='${fallbackImage}'" class="h-10 w-10 rounded-lg object-cover"><div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold">${esc(i.name)}</p><p class="mt-1 text-xs text-orange-700">${money(i.price)} each</p></div><button type="button" data-action="remove" data-id="${i.id}" class="text-rose-600 text-xs font-semibold">Remove</button></div><div class="mt-3 flex items-center justify-between border-t border-slate-200 pt-3"><div class="flex items-center gap-2"><button type="button" data-action="minus" data-id="${i.id}" class="h-7 w-7 rounded border bg-white">−</button><span class="w-5 text-center text-sm font-semibold">${i.qty}</span><button type="button" data-action="plus" data-id="${i.id}" class="h-7 w-7 rounded border bg-white">+</button></div><strong class="text-sm text-orange-700">${money(i.price * i.qty)}</strong></div></div>`).join('') : '<div class="flex min-h-[170px] items-center justify-center text-center text-sm text-slate-400">Your cart is empty.</div>';
    }
    function add(product) { const id = String(product.id), available = Number(product.available_qty) || 0; if (!available) return; if (cart[id] && cart[id].qty >= cart[id].available) return alert(`Only ${cart[id].available} item(s) are available.`); cart[id] = cart[id] ? {...cart[id], qty: cart[id].qty + 1} : {id: product.id, name: product.name, image: product.image, price: Number(product.price) || 0, available, qty: 1}; renderCart(); }
    cartItems.addEventListener('click', event => { const button = event.target.closest('button[data-action]'); if (!button || !cart[button.dataset.id]) return; const item = cart[button.dataset.id], action = button.dataset.action; if (action === 'remove') delete cart[item.id]; if (action === 'minus') item.qty > 1 ? item.qty-- : delete cart[item.id]; if (action === 'plus') { if (item.qty >= item.available) return alert(`Only ${item.available} item(s) are available.`); item.qty++; } renderCart(); });
    function card(product) { const available = Number(product.available_qty) || 0, node = document.createElement('article'); node.className = 'rounded-xl border border-slate-200 p-3'; node.innerHTML = `<img src="${image(product.image)}" onerror="this.src='${fallbackImage}'" class="h-36 w-full rounded-lg bg-slate-100 object-cover"><h3 class="mt-3 truncate text-sm font-bold">${esc(product.name)}</h3><p class="mt-1 text-xs text-slate-400">${available} available</p><div class="mt-4 flex items-center justify-between"><strong class="text-orange-700">${money(product.price)}</strong><button ${available ? '' : 'disabled'} class="add rounded-lg px-3 py-2 text-xs font-semibold ${available ? 'bg-orange-600 text-white hover:bg-orange-700' : 'bg-slate-100 text-slate-400'}">${available ? '+ Add' : 'Out of stock'}</button></div>`; node.querySelector('.add').addEventListener('click', () => add(product)); return node; }
    async function load(reset = false) {
        // Reset first. Otherwise filtering stops working once the previous list
        // has reached its final page (where nextPage is null).
        if (reset) { page = 1; nextPage = 1; grid.innerHTML = ''; }
        if (loading || !nextPage) return;
        loading = true;
        loader.classList.remove('hidden');
        try {
            const params = new URLSearchParams({page});
            if (search.value.trim()) params.set('search', search.value.trim());
            if (category.value) params.set('category', category.value);
            const response = await fetch(`{{ route('customer-order.products') }}?${params}`, {headers:{Accept:'application/json'}});
            if (!response.ok) throw new Error();
            const data = await response.json();
            data.products.forEach(p => grid.appendChild(card(p)));
            nextPage = data.next_page;
            page = nextPage || page;
            count.textContent = `${grid.children.length} item${grid.children.length === 1 ? '' : 's'}`;
            empty.classList.toggle('hidden', !!grid.children.length);
        } catch {
            loader.textContent = 'Menu could not be loaded. Please refresh.';
        } finally {
            loading = false;
            if (!nextPage) loader.classList.add('hidden');
        }
    }
    const reset = () => load(true); search.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(reset, 300); }); category.addEventListener('change', reset);
    new IntersectionObserver(entries => { if (entries[0].isIntersecting) load(); }, {rootMargin:'300px'}).observe(document.getElementById('scrollTarget'));
    document.getElementById('orderForm').addEventListener('submit', e => { if (!Object.keys(cart).length) { e.preventDefault(); alert('Please add at least one item.'); } });
    load();
});
</script>
</body>
</html>
