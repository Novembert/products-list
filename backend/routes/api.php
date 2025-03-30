<?php

use App\Modules\Product\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'getAllProducts');
    Route::get('/products/{productId}', 'getProduct');
    Route::post('/products', 'createProduct');
    Route::put('/products/{productId}', 'updateProduct');
    Route::delete('/products/{productId}', 'deleteProduct');
});