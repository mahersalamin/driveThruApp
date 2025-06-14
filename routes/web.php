<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::redirect('/', '/admin/login');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/notifications/count', function () {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    })->name('admin.notifications.count');
    Route::get('/admin/notifications/latest', [AdminController::class, 'latest'])->name('admin.notifications.latest');

    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/pending', [OrderController::class, 'pendingOrders'])->name('admin.orders.pending');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{order}/process', [OrderController::class, 'process'])->name('admin.orders.process');
});

Route::middleware(['web', 'auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all-read', [AdminController::class, 'markAllNotificationsRead'])->name('notifications.markAllRead');
});
