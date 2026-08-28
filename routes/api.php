<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromotionalController;
use App\Http\Controllers\Api\CmsController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\PaylaterController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\FaqController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forget/password', [AuthController::class, 'forgotPassword']);
Route::post('/verify/email', [AuthController::class, 'verifyEmail']);

// Category
Route::get('/category', [CategoryController::class, 'category']);
Route::get('/category-subcategory', [CategoryController::class, 'categorySubcategory']);
Route::get('/favourite-category', [CategoryController::class, 'FavouriteSubcategory']);
Route::get('/sub-category/{id?}', [CategoryController::class, 'subCategory']);
Route::get('/brands', [CategoryController::class, 'brands']);

// Sliders
Route::get('/slider', [SliderController::class, 'slider']);

// Faq
Route::get('/faq', [FaqController::class, 'faq']);
    
// Products
Route::get('/products', [ProductController::class, 'products']);
Route::get('/summer-products/{id}', [ProductController::class, 'summerProducts']);
Route::get('/category-products/{id}', [ProductController::class, 'categoryProducts']);
Route::get('/all-products', [ProductController::class, 'allProducts']);
Route::get('/products/{url}', [ProductController::class, 'productsDetails']);
Route::get('/review/{id}', [ProductController::class, 'productsReview']);
Route::get('/search', [ProductController::class, 'search']);
Route::get('product/combo', [ProductController::class, 'comboProducts']);

// Promotional
Route::get('/promotionals', [PromotionalController::class, 'promotional']);

// CMS
Route::get('/cms', [CmsController::class, 'index']);
Route::get('/cms/{url}', [CmsController::class, 'show']);

// Settings
Route::get('/setting/{name}', [SettingController::class, 'setting']);
Route::get('/settings', [SettingController::class, 'settings']);

// Support
Route::get('/support/country', [SupportController::class, 'country']);
Route::get('/support/state/{countryID}', [SupportController::class, 'state']);
Route::get('/support/district/{stateID}', [SupportController::class, 'district']);
Route::get('/support/tehsil/{districtID}', [SupportController::class, 'tehsil']);
Route::get('/support/block/{tehsilID}', [SupportController::class, 'block']);
Route::get('/support/village/{blockID}', [SupportController::class, 'village']);

Route::controller(OrderController::class)->group(function () {
        Route::get('track/{id}', 'track');
});

// Authenticate Page
Route::middleware(['auth:api', \App\Http\Middleware\TrackApiUserActivity::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
    Route::get('/table-no/{tableno}', [TableController::class, 'tableNo']);

    Route::get('/wallet', [UserController::class, 'walletPoints']);
    Route::get('/notifications', [UserController::class, 'notifications']);
    Route::get('/notification/{id}', [UserController::class, 'notificationDetails']);
    Route::get('/loyalty/points', [UserController::class, 'loyaltyPoints']);
    Route::get('/user-profile', [UserController::class, 'profile']);
    Route::post('/edit-profile', [UserController::class, 'editProfile']);

    // address
    Route::post('/add-new-address', [AddressController::class, 'addAddress']);
    Route::post('/update-address', [AddressController::class, 'updateAddress']);
    Route::delete('/delete-address', [AddressController::class, 'deleteAddress']);
    Route::get('/user-address', [AddressController::class, 'userAddress']);
    Route::post('/change-address', [AddressController::class, 'changeAddress']);

    Route::prefix('cart')->controller(CartController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/add', 'add');
        Route::delete('/remove', 'remove');
        Route::delete('/clear', 'clear');
    });

    Route::prefix('wishlist')->controller(WishlistController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/add', 'add');
        Route::get('/remove', 'remove');
    });

    Route::prefix('orders')->controller(OrderController::class)->group(function () {
        Route::post('/', 'placeOrder');
        Route::post('/success', 'success');
        Route::get('/{status?}', 'index');
        Route::get('/details/{id}', 'show');
        Route::get('/{id}/invoice', 'invoice');
        Route::post('/{id}/cancel', 'cancel');
    });

    Route::prefix('rating')->controller(ReviewController::class)->group(function () {
        Route::post('/order-rating', 'orderRating');
        Route::post('/product-rating', 'productRating');
    });

    Route::prefix('card')->controller(LeadController::class)->group(function () {
        Route::get('/', 'myCards');
        Route::get('transaction-history', 'transactionHistory');
        Route::post('/set-primary/{id}', 'setPrimaryCard');
        Route::post('/apply', 'apply');
    });

    Route::prefix('leads')->controller(LeadController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
    });

    Route::prefix('offers')->controller(OfferController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
    });

    Route::prefix('paylater')->controller(PaylaterController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/apply', 'apply');
        Route::get('/status', 'status');
    });

});
