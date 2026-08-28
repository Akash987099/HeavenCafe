<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pos\ProductController;

Route::prefix('/products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
});