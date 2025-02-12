<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);

Route::get('/cart', [CartController::class, 'index'])->middleware('auth:sanctum');;
Route::post('/add-to-cart', [CartController::class, 'addProduct'])->middleware('auth:sanctum');
Route::post('/remove_from_cart', [CartController::class, 'removeProduct'])->middleware('auth:sanctum');

Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
