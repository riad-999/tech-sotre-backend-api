<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// admin routes
Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    Route::post('/sessionStore', [SessionController::class, 'save']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/allProducts', [ProductController::class, 'allProducts']);
    Route::post('/products/store', [ProductController::class, 'store']);
    Route::post('/products/update/{product}', [ProductController::class, 'update']);
    Route::patch('/products/archive/{product}', [ProductController::class, 'archive']);
    Route::patch('/orders/deliver/{order}', [OrderController::class, 'deliver']);
});
//secure routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/productReview/{product}', [OrderController::class, 'productReview']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/userOrders', [OrderController::class, 'userOrders']);
    Route::get('/auth', [SessionController::class, 'auth']);
    Route::post('/logout', [SessionController::class, 'logout']);
    Route::post('/orders/save', [OrderController::class, 'save']);
    Route::post('/createPaymentIntent', [StripeController::class, 'createPaymentIntent']);
    Route::post('/orders/store', [OrderController::class, 'store']);
    Route::delete('/session', [SessionController::class, 'destroy']);
    Route::post('/checkAddress', [StripeController::class, 'checkAddress']);
});
// public routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/featuredproducts', [ProductController::class, 'featuredProducts']);
Route::post('/register', [SessionController::class, 'register']);
Route::post('/login', [SessionController::class, 'login']);