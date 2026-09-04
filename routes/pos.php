<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pos\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PosLeaveController;
use App\Http\Controllers\PosProductController;

Route::middleware(['auth:pos'])->group(function () {
    Route::controller(PosController::class)->name('pos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/order', 'order')->name('order');
        Route::get('/order/search', 'search')->name('search');
        Route::get('/order/create', 'save')->name('save');
        Route::get('/order/view/{id}', 'orderView')->name('order.view');
        Route::get('/order/bill/{id}', 'orderbill')->name('order.bill');
        Route::post('/order/payment/{id}', 'payment')->name('order.payment');

        Route::post('/order/razorpay/{id}', 'createRazorpayOrder')->name('order.razorpay');
        Route::post('/payment/verify', 'verifyRazorpayPayment')->name('order.razorpay.verify');

        Route::get('/bills', 'bills')->name('bills');

        // Staffs
        Route::get('/staffs', 'staff')->name('staff');
        Route::get('/staff/add', 'staffAdd')->name('staff.add');
        Route::post('/staff/save', 'staffSave')->name('staff.save');
        Route::get('/staff/{id}', 'staffView')->name('staff.view');

        // Poliy
         Route::get('/policy', 'policy')->name('policy');
    });

    Route::prefix('leave')->controller(PosLeaveController::class)->name('leave.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('save', 'save')->name('save');
        Route::get('view/{id}', 'view')->name('view');
        Route::post('/{id}/status', 'updateStatus')->name('status');
    });

    Route::prefix('product')->controller(PosProductController::class)->name('pos_product.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/stock', 'stock')->name('stock');
        Route::get('/list', 'products')->name('list');
        Route::post('/order', 'storeOrder')->name('order');
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/orders/{order}', 'orderView')->name('orders.view');
        Route::get('/orders/{order}/bill', 'downloadBill')->name('orders.bill');
    });

});
