<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('login/', [AuthController::class, 'login']);

Route::prefix('payment')->group(function () {
    Route::get('/pay/{paymentMethodSlug}/{orderID}', [PaymentController::class, 'pay']);
    Route::get('/confirm/{orderID}', [PaymentController::class, 'confirmPayment']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'show']);
        Route::post('/add', [CartController::class, 'add']);
        Route::post('/remove', [CartController::class, 'remove']);
    });

    Route::prefix('orders')->group(function () {
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
    });
});
