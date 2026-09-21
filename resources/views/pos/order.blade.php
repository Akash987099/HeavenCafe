@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="mx-auto">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            {{-- =========================================================
                LEFT SIDE : PRODUCT SEARCH
            ========================================================== --}}
            <div class="lg:col-span-8 lg:h-[calc(100vh-3rem)]">

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden lg:h-full lg:flex lg:flex-col">

                    {{-- Search --}}
                    <div class="sticky top-0 z-20 p-4 border-b border-slate-200 bg-white">

                        <div class="flex flex-wrap items-center gap-3">

                            <div class="relative flex-1">

                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-search"></i>
                                </span>

                                <input
                                    type="text"
                                    id="productSearch"
                                    autocomplete="off"
                                    placeholder="Search by product name, SKU or barcode..."
                                    class="w-full h-12 pl-11 pr-4 rounded-xl border border-slate-200
                                           bg-slate-50 text-sm text-slate-700
                                           focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20
                                           focus:border-[#128C7E]"
                                >

                            </div>

                            <select
                                id="categoryFilter"
                                class="h-12 min-w-44 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#128C7E]/20 focus:border-[#128C7E]"
                            >
                                <option value="">All categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                            {{-- Camera --}}
                            <button
                                type="button"
                                id="scanProduct"
                                class="w-12 h-12 shrink-0 rounded-xl bg-[#128C7E] text-white
                                       flex items-center justify-center
                                       hover:bg-[#0f766e] transition"
                                title="Scan Barcode"
                            >
                                <i class="fas fa-camera text-lg"></i>
                            </button>

                        </div>

                    </div>


                    {{-- Product Section --}}
                    <div class="p-4 lg:flex-1 lg:overflow-y-auto">

                        <div class="flex items-center justify-between mb-4">

                            <div>

                                <h2 class="text-lg font-bold text-slate-800">
                                    Products
                                </h2>

                                <p class="text-xs text-slate-400 mt-1">
                                    Search by product name, SKU or barcode
                                </p>

                            </div>

                            <span
                                id="resultCount"
                                class="hidden text-xs font-medium px-3 py-1.5 rounded-full
                                       bg-emerald-50 text-emerald-600"
                            ></span>

                        </div>


                        {{-- Loader --}}
                        <div
                            id="productLoader"
                            class="hidden py-10 text-center"
                        >

                            <div class="inline-flex items-center gap-2 text-slate-400">

                                <i class="fas fa-spinner fa-spin"></i>

                                <span class="text-sm">
                                    Searching product...
                                </span>

                            </div>

                        </div>


                        {{-- Empty State --}}
                        <div
                            id="productEmpty"
                            class="py-12 text-center"
                        >

                            <div
                                class="w-16 h-16 mx-auto rounded-2xl bg-slate-100
                                       flex items-center justify-center mb-4"
                            >
                                <i class="fas fa-box-open text-slate-400 text-2xl"></i>
                            </div>

                            <h3 class="text-sm font-semibold text-slate-700">
                                Search Product
                            </h3>

                            <p class="text-xs text-slate-400 mt-1">
                                Enter a product name, SKU or barcode to view products
                            </p>

                        </div>


                        {{-- No Result --}}
                        <div
                            id="noResult"
                            class="hidden py-12 text-center"
                        >

                            <div
                                class="w-16 h-16 mx-auto rounded-2xl bg-slate-100
                                       flex items-center justify-center mb-4"
                            >
                                <i class="fas fa-search text-slate-400 text-xl"></i>
                            </div>

                            <h3 class="text-sm font-semibold text-slate-700">
                                No products found
                            </h3>

                            <p class="text-xs text-slate-400 mt-1">
                                No product found with this product name, SKU or barcode
                            </p>

                        </div>


                        {{-- Product Table --}}
                        <div
                            id="productTable"
                            class="hidden border border-slate-200 rounded-xl overflow-hidden"
                        >

                            {{-- Dynamic Products --}}
                            <div id="productList" class="grid grid-cols-2 md:grid-cols-3 gap-3 p-3"></div>

                            <div id="productLoadMore" class="hidden px-3 py-4 text-center text-xs text-slate-400">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Loading more products...
                            </div>

                            <div id="productScrollSentinel" class="h-px"></div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                RIGHT SIDE : BILL
            ========================================================== --}}
            <div class="lg:col-span-4 lg:sticky lg:top-5 lg:self-start lg:h-[calc(100vh-3rem)] lg:max-h-[720px]">

                <div
                    class="bg-white rounded-2xl border border-slate-200 shadow-sm
                           flex flex-col h-full"
                >

                    {{-- Bill Header --}}
                    <div class="p-5 border-b border-slate-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <h2 class="text-xl font-bold text-slate-800">
                                    Current Bill
                                </h2>

                                <p class="text-xs text-slate-400 mt-1">
                                    Selected products
                                </p>

                            </div>

                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50
                                       text-[#128C7E] flex items-center justify-center"
                            >
                                <i class="fas fa-receipt"></i>
                            </div>

                        </div>

                    </div>


                    {{-- Cart --}}
                    <div
                        id="cartItems"
                        class="flex-1 p-4 space-y-3 overflow-y-auto"
                    >

                        {{-- Empty Cart --}}
                        <div
                            id="emptyCart"
                            class="h-full min-h-[300px] flex flex-col
                                   items-center justify-center text-center"
                        >

                            <div
                                class="w-16 h-16 rounded-2xl bg-slate-100
                                       flex items-center justify-center mb-4"
                            >
                                <i class="fas fa-shopping-cart text-slate-400 text-2xl"></i>
                            </div>

                            <h3 class="font-semibold text-slate-700">
                                No products selected
                            </h3>

                            <p class="text-xs text-slate-400 mt-1">
                                Select a product from the left side
                            </p>

                        </div>

                    </div>


                    {{-- Bill Summary --}}
                    <div class="border-t border-slate-200 p-5">

                        <div class="space-y-3 text-sm">

                            <div class="flex justify-between">

                                <span class="text-slate-500">
                                    Subtotal
                                </span>

                                <span
                                    id="subtotal"
                                    class="font-medium text-slate-800"
                                >
                                    ₹0.00
                                </span>

                            </div>


                            <div class="flex justify-between">

                                <span class="text-slate-500">
                                    Discount
                                </span>

                                <span
                                    id="discount"
                                    class="font-medium text-slate-800"
                                >
                                    ₹0.00
                                </span>

                            </div>

                        </div>


                        <div class="border-t border-dashed border-slate-200 my-4"></div>


                        <div class="flex items-center justify-between">

                            <span class="text-base font-semibold text-slate-700">
                                Grand Total
                            </span>

                            <span
                                id="grandTotal"
                                class="text-2xl font-bold text-[#128C7E]"
                            >
                                ₹0.00
                            </span>

                        </div>


                        {{-- Generate Bill --}}
                        <button
                            type="button"
                            id="generateBill"
                            class="w-full h-12 mt-5 rounded-xl
                                   bg-[#128C7E] text-white font-semibold
                                   flex items-center justify-center gap-2
                                   hover:bg-[#0f766e] transition"
                        >

                            <i class="fas fa-receipt"></i>

                            Generate Bill

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div
    id="scannerModal"
    class="hidden fixed inset-0 z-[9999] bg-black/80
           flex items-center justify-center p-4"
>

    <div
        class="w-full max-w-md bg-white rounded-2xl overflow-hidden shadow-2xl"
    >

        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b border-slate-200">

            <div>
                <h3 class="text-lg font-bold text-slate-800">
                    Scan Barcode
                </h3>

                <p class="text-xs text-slate-400 mt-1">
                    Barcode ko camera ke box ke andar rakhein
                </p>
            </div>

            <button
                type="button"
                id="closeScanner"
                class="w-9 h-9 rounded-lg bg-slate-100
                       text-slate-500 hover:bg-slate-200
                       flex items-center justify-center"
            >
                <i class="fas fa-times"></i>
            </button>

        </div>


        {{-- Camera --}}
        <div class="p-4">

            <div
                id="barcodeScanner"
                class="relative w-full aspect-[4/3]
                       bg-black rounded-xl overflow-hidden"
            >

                {{-- Camera video will come here --}}

                <video
                    id="scannerVideo"
                    class="w-full h-full object-cover"
                    autoplay
                    muted
                    playsinline
                ></video>


                {{-- Scanner Overlay --}}
                <div class="absolute inset-0 pointer-events-none">

                    <div class="absolute inset-0 flex items-center justify-center">

                        <div
                            class="w-[78%] h-[30%]
                                   border-2 border-white rounded-lg
                                   relative"
                        >

                            {{-- Scanner Line --}}
                            <div
                                id="scannerLine"
                                class="absolute left-0 right-0 top-1/2
                                       h-0.5 bg-red-500"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Status --}}
            <div
                id="scannerStatus"
                class="mt-3 text-center text-sm text-slate-500"
            >
                Camera starting...
            </div>


            {{-- Manual close --}}
            <button
                type="button"
                id="cancelScanner"
                class="w-full h-11 mt-4 rounded-xl
                       border border-slate-200
                       text-slate-600 font-semibold
                       hover:bg-slate-50 transition"
            >
                Cancel
            </button>

        </div>

    </div>

</div>

<script src="https://unpkg.com/@zxing/browser@0.1.5"></script>
<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const searchInput = document.getElementById('productSearch');
    const categoryFilter = document.getElementById('categoryFilter');

    const productLoader = document.getElementById('productLoader');
    const productEmpty = document.getElementById('productEmpty');
    const noResult = document.getElementById('noResult');

    const productTable = document.getElementById('productTable');
    const productList = document.getElementById('productList');
    const productLoadMore = document.getElementById('productLoadMore');
    const productScrollSentinel = document.getElementById('productScrollSentinel');

    const resultCount = document.getElementById('resultCount');

    const cartItems = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');

    const subtotalElement = document.getElementById('subtotal');
    const discountElement = document.getElementById('discount');
    const grandTotalElement = document.getElementById('grandTotal');


    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    */

    let cart = {};

    let searchTimer = null;
    let searchRequestId = 0;
    let nextProductPage = 1;
    let hasMoreProducts = false;
    let isLoadingProducts = false;


    /*
    |--------------------------------------------------------------------------
    | Search Product
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener('input', function () {

        const skuProductId = this.value.trim();

        clearTimeout(searchTimer);


        /*
        |--------------------------------------------------------------------------
        | Empty Search
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | Search Delay
        |--------------------------------------------------------------------------
        */

        searchTimer = setTimeout(function () {

            searchProduct(skuProductId);

        }, 400);

    });

    categoryFilter.addEventListener('change', function () {

        clearTimeout(searchTimer);

        searchProduct(searchInput.value.trim());

    });


    /*
    |--------------------------------------------------------------------------
    | AJAX Product Search
    |--------------------------------------------------------------------------
    */

    function searchProduct(skuProductId, autoAdd = false, page = 1, append = false) {

        const categoryId = categoryFilter.value;

        if (append && (isLoadingProducts || !hasMoreProducts)) {
            return;
        }

        const requestId = append ? searchRequestId : ++searchRequestId;
        isLoadingProducts = true;

        if (append) {
            productLoadMore.classList.remove('hidden');
        } else {
            nextProductPage = 1;
            hasMoreProducts = false;
            productLoader.classList.remove('hidden');

            productEmpty.classList.add('hidden');

            noResult.classList.add('hidden');

            productTable.classList.add('hidden');

            resultCount.classList.add('hidden');

            productList.innerHTML = '';
        }


        const url =
            "{{ route('pos.search') }}" +
            "?sku_product_id=" +
            encodeURIComponent(skuProductId) +
            "&category_id=" +
            encodeURIComponent(categoryId) +
            "&page=" +
            encodeURIComponent(page);


        fetch(url, {

            method: 'GET',

            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }

        })
        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'HTTP Error: ' + response.status
                );

            }

            return response.json();

        })
        .then(function (data) {

            if (requestId !== searchRequestId) {
                return;
            }

            isLoadingProducts = false;
            productLoader.classList.add('hidden');
            productLoadMore.classList.add('hidden');

            if (
                !data.success ||
                !Array.isArray(data.products) ||
                data.products.length === 0
            ) {
                if (!append) {
                    productTable.classList.add('hidden');
                    noResult.classList.remove('hidden');
                }
                return;
            }

            hasMoreProducts = Boolean(data.pagination?.has_more_pages);
            nextProductPage = page + 1;

            resultCount.textContent =
                (data.pagination?.total ?? productList.children.length + data.products.length) + ' Products';

            resultCount.classList.remove('hidden');

            /*
            |--------------------------------------------------------------------------
            | SCANNER PRODUCT -> DIRECT BILL
            |--------------------------------------------------------------------------
            */

            if (autoAdd && data.products.length === 1) {

                const product = data.products[0];

                // Directly add to right-side bill
                addProductToBill(product);

                // Hide search result
                productTable.classList.add('hidden');
                resultCount.classList.add('hidden');

                // Clear search
                searchInput.value = '';

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Normal Manual Search
            |--------------------------------------------------------------------------
            */

            productTable.classList.remove('hidden');

            data.products.forEach(function (product) {

                const row = document.createElement('button');

                row.type = 'button';

                row.className =
                    'w-full rounded-2xl border border-slate-200 p-3 text-left ' +
                    'hover:border-[#128C7E] hover:bg-emerald-50 transition bg-white';

                row.dataset.id = product.id;
                row.dataset.name = product.name;
                row.dataset.price = product.price;

                const image = getProductImageUrl(product.image);

                row.innerHTML = `

                    <div class="h-36 rounded-xl bg-slate-100 p-2 overflow-hidden">
                        <img
                            src="${image}"
                            alt="${escapeHtml(product.name)}"
                            class="w-full h-full object-contain"
                            onerror="this.onerror=null;this.src='{{ asset('images/no-product.png') }}';"
                        >
                    </div>

                    <div class="pt-3">
                        <p class="min-h-12 text-base font-semibold leading-6 text-slate-800">
                            ${escapeHtml(product.name)}
                        </p>
                        <p class="mt-1 text-xs text-slate-400 truncate">
                            SKU: ${escapeHtml(product.sku_product_id || product.barcode_base || 'N/A')}
                        </p>
                    </div>

                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-xs font-medium text-slate-400">Price</span>
                        <p class="text-base font-bold text-[#128C7E]">
                            ₹${formatPrice(product.price)}
                        </p>
                    </div>
                `;

                row.addEventListener('click', function (event) {

                    event.preventDefault();

                    addProductToBill(product);

                });

                productList.appendChild(row);

            });

        })
        .catch(function (error) {

            if (requestId !== searchRequestId) {
                return;
            }

            isLoadingProducts = false;
            console.error('Product Search Error:', error);

            productLoader.classList.add('hidden');
            productLoadMore.classList.add('hidden');

            productTable.classList.add('hidden');

            resultCount.classList.add('hidden');

            noResult.classList.remove('hidden');

        });

    }

    const productScrollObserver = new IntersectionObserver(function (entries) {

        if (!entries[0].isIntersecting || !hasMoreProducts || isLoadingProducts) {
            return;
        }

        searchProduct(searchInput.value.trim(), false, nextProductPage, true);

    }, {
        rootMargin: '160px 0px',
    });

    productScrollObserver.observe(productScrollSentinel);

    // Display the first 20 active products as soon as the POS page opens.
    searchProduct('');


    /*
    |--------------------------------------------------------------------------
    | Add Product To Bill
    |--------------------------------------------------------------------------
    */

    function addProductToBill(product) {

        const id = String(product.id);

        const price = parseFloat(product.price) || 0;


        /*
        |--------------------------------------------------------------------------
        | Product Already Exists
        |--------------------------------------------------------------------------
        */

        if (cart[id]) {

            cart[id].qty += 1;

        } else {

            cart[id] = {

                id: id,

                name: product.name,

                price: price,

                image: product.image,

                qty: 1

            };

        }


        /*
        |--------------------------------------------------------------------------
        | Render Bill
        |--------------------------------------------------------------------------
        */

        renderCart();

    }


    /*
    |--------------------------------------------------------------------------
    | Render Cart
    |--------------------------------------------------------------------------
    */

    function renderCart() {

        const items = Object.values(cart);


        /*
        |--------------------------------------------------------------------------
        | Empty Cart
        |--------------------------------------------------------------------------
        */

        if (items.length === 0) {

            cartItems.innerHTML = '';

            cartItems.appendChild(emptyCart);

            emptyCart.classList.remove('hidden');

            updateTotals();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Cart Has Products
        |--------------------------------------------------------------------------
        */

        emptyCart.classList.add('hidden');

        cartItems.innerHTML = '';


        items.forEach(function (item) {

            const itemTotal =
                item.price * item.qty;


            const itemHTML =
                document.createElement('div');


            itemHTML.className =
                'border border-slate-200 rounded-xl p-3 bg-slate-50';


            itemHTML.innerHTML = `

                <div class="flex items-center gap-3">

                    <!-- Product Image -->

                    <div
                        class="w-12 h-12 rounded-lg bg-white
                               overflow-hidden shrink-0 border border-slate-200"
                    >

                        <img
                            src="${
                                item.image
                                    ? "{{ asset('') }}" + item.image
                                    : "{{ asset('images/no-product.png') }}"
                            }"
                            alt="${escapeHtml(item.name)}"
                            class="w-full h-full object-cover"
                            onerror="this.onerror=null;this.src='{{ asset('images/no-product.png') }}';"
                        >

                    </div>


                    <!-- Product Details -->

                    <div class="flex-1 min-w-0">

                        <h3
                            class="text-sm font-semibold text-slate-800 truncate"
                        >
                            ${escapeHtml(item.name)}
                        </h3>

                        <p class="text-xs text-slate-400 mt-1">
                            ₹${formatPrice(item.price)} × ${item.qty}
                        </p>

                    </div>


                    <!-- Remove -->

                    <button
                        type="button"
                        class="remove-item w-8 h-8 rounded-lg
                               bg-red-50 text-red-500
                               hover:bg-red-100 transition"
                        data-id="${item.id}"
                        title="Remove"
                    >
                        <i class="fas fa-trash text-xs"></i>
                    </button>

                </div>


                <!-- Quantity + Total -->

                <div
                    class="flex items-center justify-between mt-3
                           pt-3 border-t border-slate-200"
                >

                    <!-- Quantity -->

                    <div class="flex items-center gap-2">

                        <button
                            type="button"
                            class="qty-minus w-7 h-7 rounded-lg
                                   bg-white border border-slate-200
                                   text-slate-600 hover:bg-slate-100"
                            data-id="${item.id}"
                        >
                            -
                        </button>

                        <span
                            class="text-sm font-semibold w-6 text-center"
                        >
                            ${item.qty}
                        </span>

                        <button
                            type="button"
                            class="qty-plus w-7 h-7 rounded-lg
                                   bg-white border border-slate-200
                                   text-slate-600 hover:bg-slate-100"
                            data-id="${item.id}"
                        >
                            +
                        </button>

                    </div>


                    <!-- Item Total -->

                    <span
                        class="text-sm font-bold text-[#128C7E]"
                    >
                        ₹${formatPrice(itemTotal)}
                    </span>

                </div>

            `;


            cartItems.appendChild(itemHTML);

        });


        /*
        |--------------------------------------------------------------------------
        | Bind Cart Buttons
        |--------------------------------------------------------------------------
        */

        bindCartEvents();


        /*
        |--------------------------------------------------------------------------
        | Update Totals
        |--------------------------------------------------------------------------
        */

        updateTotals();

    }


    /*
    |--------------------------------------------------------------------------
    | Cart Events
    |--------------------------------------------------------------------------
    */

    function bindCartEvents() {


        /*
        | Plus
        */

        document
            .querySelectorAll('.qty-plus')
            .forEach(function (button) {

                button.addEventListener('click', function () {

                    const id = this.dataset.id;

                    if (!cart[id]) {
                        return;
                    }

                    cart[id].qty += 1;

                    renderCart();

                });

            });


        /*
        | Minus
        */

        document
            .querySelectorAll('.qty-minus')
            .forEach(function (button) {

                button.addEventListener('click', function () {

                    const id = this.dataset.id;

                    if (!cart[id]) {
                        return;
                    }


                    if (cart[id].qty > 1) {

                        cart[id].qty -= 1;

                    } else {

                        delete cart[id];

                    }

                    renderCart();

                });

            });


        /*
        | Remove
        */

        document
            .querySelectorAll('.remove-item')
            .forEach(function (button) {

                button.addEventListener('click', function () {

                    const id = this.dataset.id;

                    delete cart[id];

                    renderCart();

                });

            });

    }


    /*
    |--------------------------------------------------------------------------
    | Update Totals
    |--------------------------------------------------------------------------
    */

    function updateTotals() {

        let subtotal = 0;


        Object.values(cart).forEach(function (item) {

            subtotal +=
                item.price * item.qty;

        });


        const discount = 0;

        const grandTotal =
            subtotal - discount;


        subtotalElement.textContent =
            '₹' + formatPrice(subtotal);


        discountElement.textContent =
            '₹' + formatPrice(discount);


        grandTotalElement.textContent =
            '₹' + formatPrice(grandTotal);

    }


    /*
    |--------------------------------------------------------------------------
    | Generate Bill
    |--------------------------------------------------------------------------
    */

    document
    .getElementById('generateBill')
    .addEventListener('click', function () {

        const items = Object.values(cart);


        if (items.length === 0) {

            alert('Please select at least one product.');

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Encode Cart
        |--------------------------------------------------------------------------
        */

        const cartData = btoa(
            unescape(
                encodeURIComponent(
                    JSON.stringify(items)
                )
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Go To Save Route
        |--------------------------------------------------------------------------
        */

        const url =
            "{{ route('pos.save') }}" +
            "?cart=" +
            encodeURIComponent(cartData);


        window.location.href = url;

    });

    /*
    |--------------------------------------------------------------------------
    | Camera
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| Barcode Scanner
|--------------------------------------------------------------------------
*/

const scanProduct = document.getElementById('scanProduct');

const scannerModal = document.getElementById('scannerModal');

const closeScanner = document.getElementById('closeScanner');

const cancelScanner = document.getElementById('cancelScanner');

const scannerVideo = document.getElementById('scannerVideo');

const scannerStatus = document.getElementById('scannerStatus');

let barcodeReader = null;

let scannerControls = null;

let scannerRunning = false;


/*
|--------------------------------------------------------------------------
| Open Scanner
|--------------------------------------------------------------------------
*/

scanProduct.addEventListener('click', function () {

    openBarcodeScanner();

});


/*
|--------------------------------------------------------------------------
| Close Scanner
|--------------------------------------------------------------------------
*/

closeScanner.addEventListener('click', function () {

    stopBarcodeScanner();

});


cancelScanner.addEventListener('click', function () {

    stopBarcodeScanner();

});


/*
|--------------------------------------------------------------------------
| Open Barcode Scanner
|--------------------------------------------------------------------------
*/

async function openBarcodeScanner()
{
    scannerModal.classList.remove('hidden');

    scannerStatus.textContent = 'Starting camera...';

    try {

        /*
        |--------------------------------------------------------------------------
        | Check Camera Support
        |--------------------------------------------------------------------------
        */

        if (!navigator.mediaDevices ||
            !navigator.mediaDevices.getUserMedia) {

            throw new Error(
                'Camera is not supported by this browser.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ZXing Reader
        |--------------------------------------------------------------------------
        */

        barcodeReader =
            new ZXingBrowser.BrowserMultiFormatReader(
                new Map([
                    [
                        ZXingBrowser.BarcodeFormat.CODE_128,
                        {}
                    ]
                ])
            );


        scannerRunning = true;


        scannerStatus.textContent =
            'Point the camera at the barcode...';


        /*
        |--------------------------------------------------------------------------
        | Start Camera
        |--------------------------------------------------------------------------
        |
        | facingMode: environment = BACK CAMERA
        |
        */

        scannerControls =
            await barcodeReader.decodeFromConstraints(
                {
                    audio: false,

                    video: {
                        facingMode: {
                            ideal: 'environment'
                        },

                        width: {
                            ideal: 1280
                        },

                        height: {
                            ideal: 720
                        }
                    }
                },

                scannerVideo,

                function (result, error) {

                    if (!scannerRunning) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Barcode Found
                    |--------------------------------------------------------------------------
                    */

                    if (result) {

                        let barcode = result.getText();

                        if (!barcode) {
                            return;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Clean Scanner Value
                        |--------------------------------------------------------------------------
                        */

                        barcode = String(barcode)
                            .trim()
                            .replace(/\s+/g, '');

                        /*
                        |--------------------------------------------------------------------------
                        | Only Numeric SKU Product ID
                        |--------------------------------------------------------------------------
                        */

                        if (!/^\d+$/.test(barcode)) {

                            scannerStatus.textContent =
                                'Invalid barcode. Please scan again...';

                            return;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Minimum / Maximum validation
                        |--------------------------------------------------------------------------
                        */

                        if (barcode.length < 4 || barcode.length > 30) {

                            scannerStatus.textContent =
                                'Invalid SKU Product ID. Please scan again...';

                            return;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Barcode Successfully Read
                        |--------------------------------------------------------------------------
                        */

                        scannerStatus.textContent =
                            'Barcode found: ' + barcode;

                        /*
                        |--------------------------------------------------------------------------
                        | Stop Camera
                        |--------------------------------------------------------------------------
                        */

                        stopBarcodeScanner();

                        /*
                        |--------------------------------------------------------------------------
                        | Put Exact Barcode Into Search
                        |--------------------------------------------------------------------------
                        */

                        searchInput.value = barcode;

                        /*
                        |--------------------------------------------------------------------------
                        | Search Product
                        |--------------------------------------------------------------------------
                        */

                        searchProduct(barcode, true);
                    }

                }
            );

    } catch (error) {

        console.error(
            'Barcode Scanner Error:',
            error
        );


        scannerStatus.textContent =
            'Unable to access camera. Please allow camera permission.';


        alert(
            'Camera access nahi mil raha hai. Browser camera permission allow karein.'
        );

    }

}


/*
|--------------------------------------------------------------------------
| Stop Scanner
|--------------------------------------------------------------------------
*/

function stopBarcodeScanner()
{
    scannerRunning = false;


    /*
    |--------------------------------------------------------------------------
    | Stop ZXing
    |--------------------------------------------------------------------------
    */

    try {

        if (scannerControls) {

            scannerControls.stop();

            scannerControls = null;

        }

    } catch (error) {

        console.error(
            'Scanner Stop Error:',
            error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Stop Video Tracks
    |--------------------------------------------------------------------------
    */

    if (scannerVideo.srcObject) {

        scannerVideo.srcObject
            .getTracks()
            .forEach(function (track) {

                track.stop();

            });

        scannerVideo.srcObject = null;

    }


    /*
    |--------------------------------------------------------------------------
    | Reset Reader
    |--------------------------------------------------------------------------
    */

    barcodeReader = null;


    /*
    |--------------------------------------------------------------------------
    | Close Modal
    |--------------------------------------------------------------------------
    */

    scannerModal.classList.add('hidden');

    scannerStatus.textContent =
        'Camera starting...';

}


    /*
    |--------------------------------------------------------------------------
    | Price Format
    |--------------------------------------------------------------------------
    */

    function formatPrice(price) {

        const value =
            parseFloat(price) || 0;

        return value.toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;

    }

    function getProductImageUrl(image) {

        const placeholder = "{{ asset('images/no-product.png') }}";

        if (!image) {
            return placeholder;
        }

        const imagePath = String(image).trim();

        if (/^(https?:)?\/\//i.test(imagePath) || imagePath.startsWith('data:')) {
            return imagePath;
        }

        return "{{ asset('') }}" + imagePath.replace(/^\/+/, '');

    }

});

</script>

@endsection
