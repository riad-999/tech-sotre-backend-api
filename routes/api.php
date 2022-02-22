<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SessionController;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/auth', function () {
        return response(['message' => 'good'], 200);
    });
    Route::post('/logout', [SessionController::class, 'logout']);
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/featuredproducts', [ProductController::class, 'featuredProducts']);
Route::post('/register', [SessionController::class, 'register']);
Route::post('/login', [SessionController::class, 'login']);