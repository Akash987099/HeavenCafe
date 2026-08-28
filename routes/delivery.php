<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rider\RiderController;

Route::post('/login', [RiderController::class, 'login']);
