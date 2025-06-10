<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\ItemSizeController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/my-orders', [OrderController::class, 'myOrders']);

Route::middleware('auth:sanctum')->get('/debug-user', function (Request $request) {
    return [
        'user' => $request->user(),
        'auth_user' => auth()->user(),
        'guards' => config('auth.guards'),
    ];
});


Route::post('/orders', [OrderController::class, 'store']);
Route::get('/admin/notifications', function () {
    return auth()->user()->notifications;
});


Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('item-sizes', ItemSizeController::class);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::get('/orders/status/{status}', [OrderController::class, 'filterByStatus']);

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/notifications', function () {
        return auth()->user()->notifications;
    });
    Route::post('/admin/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['message' => 'Notification marked as read']);
    });

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::put('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
});
