@extends('pos.layout.app')

@section('content')
<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-5">
        @if (session('error'))
            <div class="lg:col-span-12 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif
        <section class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 space-y-3">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input id="productSearch" type="search" placeholder="Search product or SKU..." class="w-full h-11 pl-11 pr-4 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]">
                    </div>
                    <select id="categoryFilter" class="h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 focus:outline-none focus:border-[#128C7E]">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <div><h1 class="text-lg font-bold text-slate-800">Store Products</h1><p class="text-xs text-slate-400 mt-1">Only store products are shown. Every item is added at ₹0.</p></div>
                    <span id="productCount" class="text-xs font-medium px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700">Loading...</span>
                </div>
            </div>

            <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 p-4"></div>
            <div id="loader" class="py-6 text-center text-sm text-slate-400"><i class="fas fa-spinner fa-spin mr-2"></i>Loading products...</div>
            <div id="emptyState" class="hidden py-14 text-center text-slate-400"><i class="fas fa-box-open text-3xl mb-3"></i><p>No store products found.</p></div>
            <div id="scrollTarget" class="h-1"></div>
        </section>

        <aside class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col lg:min-h-[620px]">
            <div class="p-5 border-b border-slate-200"><h2 class="text-xl font-bold text-slate-800">Current Order</h2><p class="text-xs text-slate-400 mt-1">Store product price: ₹0.00</p></div>
            <div id="cartItems" class="flex-1 p-4 space-y-3 overflow-y-auto"><div id="emptyCart" class="min-h-[260px] flex flex-col items-center justify-center text-center text-slate-400"><i class="fas fa-shopping-cart text-3xl mb-3"></i><p class="text-sm">No products added yet</p></div></div>
            <div class="p-5 border-t border-slate-200"><div class="flex justify-between text-sm"><span class="text-slate-500">Total</span><span class="font-bold text-[#128C7E]">₹0.00</span></div><form id="orderForm" method="POST" action="{{ route('pos_product.order') }}">@csrf<div id="cartInputs"></div><button class="w-full h-12 mt-5 rounded-xl bg-[#128C7E] text-white font-semibold hover:bg-[#0f766e] transition"><i class="fas fa-receipt mr-2"></i>Create Order</button></form></div>
        </aside>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('productGrid'), loader = document.getElementById('loader'), empty = document.getElementById('emptyState'), count = document.getElementById('productCount');
    const search = document.getElementById('productSearch'), category = document.getElementById('categoryFilter'), cartItems = document.getElementById('cartItems'), cartInputs = document.getElementById('cartInputs');
    const fallbackImage = @json(asset('images/no-product.png')), assetBase = @json(asset(''));
    let page = 1, nextPage = 1, loading = false, cart = {}, searchTimer;

    const escapeHtml = value => String(value || '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));
    const imageUrl = image => image ? assetBase + image : fallbackImage;

    function addToCart(product) {
        const id = String(product.id);
        const availableQty = Number(product.store_qty) || 0;

        if (availableQty === 0) {
            alert('This product is out of stock.');
            return;
        }

        if (cart[id] && cart[id].qty >= cart[id].storeQty) {
            alert(`Only ${cart[id].storeQty} item(s) are available.`);
            return;
        }

        cart[id] = cart[id]
            ? {...cart[id], qty: cart[id].qty + 1}
            : {id: product.id, name: product.name, image: product.image, storeQty: availableQty, qty: 1};

        renderCart();
    }
    function renderCart() {
        const items = Object.values(cart); cartInputs.innerHTML = items.map((item, index) => `<input type="hidden" name="cart[${index}][id]" value="${item.id}"><input type="hidden" name="cart[${index}][qty]" value="${item.qty}">`).join('');
        if (!items.length) { cartItems.innerHTML = '<div id="emptyCart" class="min-h-[260px] flex flex-col items-center justify-center text-center text-slate-400"><i class="fas fa-shopping-cart text-3xl mb-3"></i><p class="text-sm">No products added yet</p></div>'; return; }
        cartItems.innerHTML = items.map(item => `<div class="border border-slate-200 rounded-xl p-3 bg-slate-50"><div class="flex gap-3 items-center"><img src="${imageUrl(item.image)}" onerror="this.src='${fallbackImage}'" class="w-11 h-11 rounded-lg object-cover bg-white border border-slate-200"><div class="flex-1 min-w-0"><p class="text-sm font-semibold text-slate-800 truncate">${escapeHtml(item.name)}</p><p class="text-xs text-[#128C7E] mt-1">₹0.00</p></div><button type="button" data-action="remove" data-id="${item.id}" class="w-8 h-8 rounded-lg text-rose-500 bg-rose-50"><i class="fas fa-trash text-xs"></i></button></div><div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-200"><div class="flex items-center gap-2"><button type="button" data-action="minus" data-id="${item.id}" class="w-7 h-7 rounded-lg border bg-white">−</button><span class="w-5 text-center text-sm font-semibold">${item.qty}</span><button type="button" data-action="plus" data-id="${item.id}" class="w-7 h-7 rounded-lg border bg-white">+</button></div><span class="text-xs text-slate-400">Qty</span></div></div>`).join('');
    }
    cartItems.addEventListener('click', event => { const button = event.target.closest('button[data-action]'); if (!button) return; const item = cart[button.dataset.id]; if (!item) return; if (button.dataset.action === 'plus') { if (item.qty >= item.storeQty) return alert(`Only ${item.storeQty} item(s) are available.`); item.qty++; } if (button.dataset.action === 'minus') item.qty > 1 ? item.qty-- : delete cart[item.id]; if (button.dataset.action === 'remove') delete cart[item.id]; renderCart(); });

    function productCard(product) { const card = document.createElement('article'); card.className = 'border border-slate-200 rounded-xl overflow-hidden p-3 bg-white'; card.innerHTML = `<img src="${imageUrl(product.image)}" onerror="this.src='${fallbackImage}'" class="w-full h-36 object-cover rounded-lg bg-slate-100"><h3 class="font-semibold text-sm text-slate-800 truncate mt-3">${escapeHtml(product.name)}</h3><p class="text-xs text-slate-400 truncate mt-1">SKU: ${escapeHtml(product.sku_product_id || '-')}</p><div class="flex items-center justify-between mt-4"><span class="font-bold text-[#128C7E]">₹0.00</span><button type="button" class="add-product px-3 py-2 rounded-lg text-xs font-semibold bg-[#128C7E] text-white hover:bg-[#0f766e]"><i class="fas fa-plus mr-1"></i>Add</button></div>`; card.querySelector('.add-product').addEventListener('click', () => addToCart(product)); return card; }
    async function loadProducts(reset = false) { if (loading || !nextPage) return; if (reset) { page = 1; nextPage = 1; grid.innerHTML = ''; } loading = true; loader.classList.remove('hidden'); empty.classList.add('hidden'); const params = new URLSearchParams({page: page}); if (search.value.trim()) params.set('search', search.value.trim()); if (category.value) params.set('category', category.value); try { const response = await fetch(`{{ route('pos_product.list') }}?${params}`, {headers: {'Accept': 'application/json'}}); if (!response.ok) throw new Error('Unable to load products'); const data = await response.json(); data.products.forEach(product => grid.appendChild(productCard(product))); nextPage = data.next_page; page = nextPage || page; count.textContent = `${grid.children.length} product${grid.children.length === 1 ? '' : 's'}`; if (!grid.children.length) empty.classList.remove('hidden'); } catch (error) { loader.textContent = 'Products could not be loaded. Please try again.'; console.error(error); } finally { loading = false; if (nextPage) loader.classList.remove('hidden'); else loader.classList.add('hidden'); } }
    const reset = () => loadProducts(true); search.addEventListener('input', () => { clearTimeout(searchTimer); searchTimer = setTimeout(reset, 350); }); category.addEventListener('change', reset);
    new IntersectionObserver(entries => { if (entries[0].isIntersecting) loadProducts(); }, {rootMargin: '300px'}).observe(document.getElementById('scrollTarget'));
    const formatMoney = amount => Number(amount || 0).toFixed(2);
    const cartTotalElement = document.getElementById('orderForm').closest('div').querySelector('span.font-bold');
    document.querySelector('#cartItems').previousElementSibling.querySelector('p').textContent = 'Product prices are from the products table.';

    function addToCart(product) {
        const id = String(product.id);
        const availableQty = Number(product.store_qty) || 0;
        if (availableQty === 0) return alert('This product is out of stock.');
        if (cart[id] && cart[id].qty >= cart[id].storeQty) return alert(`Only ${cart[id].storeQty} item(s) are available.`);
        cart[id] = cart[id]
            ? {...cart[id], qty: cart[id].qty + 1}
            : {id: product.id, name: product.name, image: product.image, price: Number(product.price) || 0, storeQty: availableQty, qty: 1};
        renderCart();
    }

    function renderCart() {
        const items = Object.values(cart);
        const total = items.reduce((sum, item) => sum + (item.price * item.qty), 0);
        cartInputs.innerHTML = items.map((item, index) => `<input type="hidden" name="cart[${index}][id]" value="${item.id}"><input type="hidden" name="cart[${index}][qty]" value="${item.qty}">`).join('');
        cartTotalElement.innerHTML = `&#8377;${formatMoney(total)}`;
        if (!items.length) { cartItems.innerHTML = '<div id="emptyCart" class="min-h-[260px] flex flex-col items-center justify-center text-center text-slate-400"><i class="fas fa-shopping-cart text-3xl mb-3"></i><p class="text-sm">No products added yet</p></div>'; return; }
        cartItems.innerHTML = items.map(item => `<div class="border border-slate-200 rounded-xl p-3 bg-slate-50"><div class="flex gap-3 items-center"><img src="${imageUrl(item.image)}" onerror="this.src='${fallbackImage}'" class="w-11 h-11 rounded-lg object-cover bg-white border border-slate-200"><div class="flex-1 min-w-0"><p class="text-sm font-semibold text-slate-800 truncate">${escapeHtml(item.name)}</p><p class="text-xs text-[#128C7E] mt-1">&#8377;${formatMoney(item.price)} &times; ${item.qty}</p></div><button type="button" data-action="remove" data-id="${item.id}" class="w-8 h-8 rounded-lg text-rose-500 bg-rose-50"><i class="fas fa-trash text-xs"></i></button></div><div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-200"><div class="flex items-center gap-2"><button type="button" data-action="minus" data-id="${item.id}" class="w-7 h-7 rounded-lg border bg-white">&minus;</button><span class="w-5 text-center text-sm font-semibold">${item.qty}</span><button type="button" data-action="plus" data-id="${item.id}" class="w-7 h-7 rounded-lg border bg-white">+</button></div><span class="text-sm font-bold text-[#128C7E]">&#8377;${formatMoney(item.price * item.qty)}</span></div></div>`).join('');
    }

    function productCard(product) {
        const card = document.createElement('article');
        const availableQty = Number(product.store_qty) || 0;
        card.className = 'border border-slate-200 rounded-xl overflow-hidden p-3 bg-white';
        card.innerHTML = `<img src="${imageUrl(product.image)}" onerror="this.src='${fallbackImage}'" class="w-full h-36 object-cover rounded-lg bg-slate-100"><h3 class="font-semibold text-sm text-slate-800 truncate mt-3">${escapeHtml(product.name)}</h3><p class="text-xs text-slate-400 truncate mt-1">SKU: ${escapeHtml(product.sku_product_id || '-')}</p><div class="flex items-center justify-between mt-4"><span class="font-bold text-[#128C7E]">&#8377;${formatMoney(product.price)}</span><button type="button" class="add-product px-3 py-2 rounded-lg text-xs font-semibold bg-[#128C7E] text-white hover:bg-[#0f766e] ${availableQty ? '' : 'opacity-40 cursor-not-allowed'}" ${availableQty ? '' : 'disabled'}><i class="fas fa-plus mr-1"></i>Add</button></div>`;
        card.querySelector('.add-product').addEventListener('click', () => addToCart(product));
        return card;
    }

    document.getElementById('orderForm').addEventListener('submit', event => { if (!Object.keys(cart).length) { event.preventDefault(); alert('Please add at least one product.'); } });
    loadProducts();
});
</script>
@endsection
