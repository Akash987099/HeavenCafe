<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\TehsilController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SummerController;
use App\Http\Controllers\PromotionalController;
use App\Http\Controllers\CMSController;
use App\Http\Controllers\RecommendedController;
use App\Http\Controllers\AplusController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\VarientController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\CardTypeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\Api\FaqController as ApiFaqController;

// Cafe
use App\Http\Controllers\cafe\TypeController;
use App\Http\Controllers\cafe\CategoryController as CafeCategoryController;

Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('logins', 'logins')->name('logins');
});

Route::prefix('pos')->controller(LoginController::class)->name('pos.')->group(function () {
    Route::get('login', 'loginPos')->name('login');
    Route::post('logins', 'loginsPos')->name('logins');
    Route::get('logout', 'logout')->name('logout');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
});

// Fallback API route for environments where api route cache/subfolder routing is stale.
Route::get('api/faq', [ApiFaqController::class, 'faq']);

Route::middleware(['auth:admin'])->group(function () {

    Route::controller(AdminController::class)->group(function () {
        Route::get('home', 'index')->name('index');
        Route::get('wallets', 'wallets')->name('wallets');
        Route::get('stocks', 'stocks')->name('stocks');
        Route::get('run-wallet-cron', 'runCron')->name('run.cron');
    });

    Route::prefix('users')->controller(UserController::class)->name('users.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('cart/{id}', 'cart')->name('cart');
        Route::get('order/{id}', 'order')->name('order');
        Route::get('order/details/{id}', 'orderDetails')->name('order_details');
        Route::post('status', 'status')->name('status');
    });

    Route::prefix('categories')->controller(CategoryController::class)->name('category.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('update-position', 'updatePosition')->name('updatePosition');
        Route::post('status', 'status')->name('status');
    });

    Route::prefix('sub/category')->controller(SubCategoryController::class)->name('sub_category.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('brands')->controller(BrandController::class)->name('brand.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
    });

    Route::prefix('discount')->controller(DiscountController::class)->name('discount.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
    });

    Route::prefix('products')->controller(ProductController::class)->name('product.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('search', 'search')->name('search');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('status', 'status')->name('status');
        Route::post('update', 'update')->name('update');
        // Gallery
        Route::get('gallery/{id}', 'gallery')->name('gallery');
        Route::post('gallery_save', 'gallery_save')->name('gallery_save');
        Route::delete('gallery_delete/{id}', 'gallery_delete')->name('gallery_delete');
        // Stock
        Route::get('stock/{id}', 'stock')->name('stock');
        Route::post('stock_save', 'stockSave')->name('stock_save');
        Route::post('select_stock', 'selectStock')->name('select_stock');
        Route::post('summer_status', 'summerStatus')->name('summer_status');
        // Simalar
        Route::get('similar/{id}', 'similar')->name('similar');
        Route::post('similar/save', 'saveSimilar')->name('similar.save');
        // Product Type
        Route::post('product/type', 'productType')->name('product_type');
        // Import Product
        Route::post('import', 'import')->name('import');
        Route::get('sample/download', 'sampleDownload')->name('sample.download');
        Route::post('import-api-products', 'importApiProducts')->name('import_api_products');
    });

    Route::prefix('stores')->controller(StoreController::class)->name('store.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
    });

    Route::prefix('country')->controller(CountryController::class)->name('country.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('state')->controller(StateController::class)->name('state.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('district')->controller(DistrictController::class)->name('district.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('tehsil')->controller(TehsilController::class)->name('tehsil.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('block')->controller(BlockController::class)->name('block.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('village')->controller(VillageController::class)->name('village.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('tax')->controller(TaxController::class)->name('tax.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'status')->name('status');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('settings')->controller(SettingController::class)->name('setting.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete', 'delete')->name('delete');
    });

    Route::prefix('email/template')->controller(EmailTemplateController::class)->name('email_template.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete', 'delete')->name('delete');
    });

    Route::prefix('sliders')->controller(SliderController::class)->name('slider.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'status')->name('status');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('summers')->controller(SummerController::class)->name('summer.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'status')->name('status');
        Route::post('update-position', 'updatePosition')->name('updatePosition');
    });

    Route::prefix('promotionals')->controller(PromotionalController::class)->name('promotional.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'status')->name('status');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('cms')->controller(CMSController::class)->name('cms.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'status')->name('status');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('recommendeds')->controller(RecommendedController::class)->name('recommended.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'status')->name('status');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('aplus')->controller(AplusController::class)->name('aplus.')->group(function () {
        Route::get('/{id}', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'status')->name('status');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('status')->controller(StatusController::class)->name('status.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::controller(ReportController::class)->group(function () {
        Route::get('transaction', 'transaction')->name('transaction');
    });

    Route::prefix('supplier')->controller(SupplierController::class)->name('supplier.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::post('assign_role', 'assignRole')->name('assign_role');
    });

    Route::prefix('buyer')->controller(BuyerController::class)->name('buyer.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::post('assign_role', 'assignRole')->name('assign_role');
    });

    Route::prefix('order')->controller(OrderController::class)->name('order.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::post('status', 'status')->name('status');
        Route::post('delivery_boy', 'deliveryBoy')->name('delivery_boy');
        Route::get('barcodes', 'barcodes')->name('barcodes');
        Route::get('barcode/{id}', 'barcode')->name('barcode');
        Route::get('invoice/{id}', 'invoice')->name('invoice');
        Route::post('barcode-print', 'barcode_print')->name('barcode_print');
    });

    Route::prefix('attribute')->controller(AttributeController::class)->name('attribute.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('update-position', 'updatePosition')->name('updatePosition');
    });

    Route::prefix('attribute/value')->controller(AttributeValueController::class)->name('attribute_value.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('update-position', 'updatePosition')->name('updatePosition');
    });

    Route::prefix('varient')->controller(VarientController::class)->name('varient.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add/{id}', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('update-position', 'updatePosition')->name('updatePosition');
    });

    Route::prefix('type')->controller(TypeController::class)->name('type.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('export', 'export')->name('export');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
    });

    Route::prefix('combos')->controller(ComboController::class)->name('combo.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add/{id}', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::get('delete-item/{id}', 'deleteItem')->name('deleteItem');
    });

    Route::prefix('table')->controller(TableController::class)->name('table.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::get('delete-item/{id}', 'deleteItem')->name('deleteItem');
    });

    Route::prefix('points')->controller(PointController::class)->name('points.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::get('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('payment-methods')->controller(PaymentMethodController::class)->name('payment_method.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'updateStatus')->name('status');
        Route::get('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('card-types')->controller(CardTypeController::class)->name('card_type.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'updateStatus')->name('status');
        Route::get('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('leads')->controller(LeadController::class)->name('leads.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('view/{id}', 'view')->name('view');
        Route::post('status/{id}', 'updateStatus')->name('status');
    });

    Route::prefix('cards')->controller(CardController::class)->name('cards.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('status', 'updateStatus')->name('status');
    });

    Route::prefix('offers')->controller(OfferController::class)->name('offer.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'updateStatus')->name('status');
        Route::get('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('client')->controller(ClientController::class)->name('client.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'updateStatus')->name('status');
        Route::get('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('faq')->controller(FaqController::class)->name('faq.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('status', 'updateStatus')->name('status');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

});
