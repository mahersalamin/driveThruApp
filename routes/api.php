<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\ItemSizeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('items', ItemController::class);
Route::apiResource('item-sizes', ItemSizeController::class);


Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'API is working']);
});
