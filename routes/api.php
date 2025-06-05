<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\ItemSizeController;
use App\Http\Controllers\API\OrderController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('items', ItemController::class);
Route::apiResource('item-sizes', ItemSizeController::class);

Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::get('/admin/notifications', function () {
    return auth()->user()->notifications;
});


