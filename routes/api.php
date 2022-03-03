<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StripeController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//secure routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/auth', [SessionController::class, 'auth']);
    Route::post('/logout', [SessionController::class, 'logout']);
    Route::post('/createPaymentIntent', [StripeController::class, 'createPaymentIntent']);
    Route::post('/handleOrder', [StripeController::class, 'handleOrder']);
    Route::delete('/cancelOrder', [StripeController::class, 'cancelOrder']);
    Route::post('/checkAddress', [StripeController::class, 'checkAddress']);
});
// public routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/featuredproducts', [ProductController::class, 'featuredProducts']);
Route::post('/register', [SessionController::class, 'register']);
Route::post('/login', [SessionController::class, 'login']);

Route::get('/order', [Admin::class, 'order']);