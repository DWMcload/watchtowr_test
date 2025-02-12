<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'addProduct']);
    Route::post('/cart/remove', [CartController::class, 'removeProduct']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/order/checkout', [OrderController::class, 'checkout']);

    Route::apiResource('products', ProductController::class);
});
