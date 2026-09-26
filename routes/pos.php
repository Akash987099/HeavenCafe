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
        Route::get('/customer-orders/{order}/receipt', 'customerOrderReceipt')->name('customer-orders.receipt');
        Route::get('/customer-orders/{order}/receipt/download', 'downloadCustomerOrderReceipt')->name('customer-orders.receipt.download');
        Route::post('/customer-orders/{order}/delivered', 'markCustomerOrderDelivered')->name('customer-orders.delivered');
        Route::get('/customer-orders/{order}', 'customerOrderView')->name('customer-orders.view');

        Route::get('/kitchen/orders', 'kitchenOrders')->name('kitchen.orders');
        Route::get('/kitchen/alerts', 'kitchenAlerts')->name('kitchen.alerts');
        Route::get('/kitchen/orders/{id}', 'kitchenOrderView')->name('kitchen.orders.view');
        Route::post('/kitchen/orders/{id}/delivered', 'kitchenMarkDelivered')->name('kitchen.orders.delivered');

        // Staffs
        Route::get('/staffs', 'staff')->name('staff');
        Route::get('/staff-tasks/print', 'allStaffTasksPrint')->name('staff.tasks.print');
        Route::get('/staff-tasks', 'allStaffTasks')->name('staff.tasks.index');
        Route::get('/staff/add', 'staffAdd')->name('staff.add');
        Route::post('/staff/save', 'staffSave')->name('staff.save');
        Route::get('/staff/{id}/edit', 'staffEdit')->name('staff.edit');
        Route::post('/staff/{id}/update', 'staffUpdate')->name('staff.update');
        Route::get('/staff/{id}/advances', 'staffAdvances')->name('staff.advances');
        Route::post('/staff/{id}/advances', 'staffAdvanceStore')->name('staff.advances.store');
        Route::get('/staff/{id}/salary', 'staffSalary')->name('staff.salary');
        Route::get('/staff/{id}/tasks', 'staffTasks')->name('staff.tasks');
        Route::post('/staff/{id}/tasks', 'staffTaskStore')->name('staff.tasks.store');
        Route::get('/staff/{id}/offer-letter', 'staffOfferLetter')->name('staff.offer-letter');
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
