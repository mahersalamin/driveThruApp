<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\ItemSizeController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Public Routes (No authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/orders', [OrderController::class, 'store']);

// API Resources (Accessible publicly for read operations, but write operations are generally protected)
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('items', ItemController::class)->only(['index', 'show']);
Route::apiResource('item-sizes', ItemSizeController::class)->only(['index', 'show']);

// Authenticated Routes (Requires 'auth:sanctum' middleware)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);

    Route::get('/notifications', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()->notifications
        ]);
    });
    Route::post('/notifications/{id}/read', function ($id, Request $request) {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    });

    // Admin Routes (Requires 'auth:sanctum' and 'admin' middleware)
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);

        // Admin notifications

        // Orders management
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
        Route::get('/orders/status/{status}', [OrderController::class, 'filterByStatus']);

        // Customer management
        Route::apiResource('customers', CustomerController::class);

        // Admin-only API resource actions (create, update, delete)
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
        Route::apiResource('items', ItemController::class)->except(['index', 'show']);
        Route::apiResource('item-sizes', ItemSizeController::class)->except(['index', 'show']);
    });
});
