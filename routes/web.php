<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CakeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StoreSettingController;

// Customer Routes
Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/cart', [CustomerController::class, 'cart'])->name('customer.cart');
Route::post('/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
Route::get('/order-success/{order}', [CustomerController::class, 'orderSuccess'])->name('customer.order-success');

// Admin Routes
Auth::routes();

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('cakes', CakeController::class);
    Route::resource('orders', OrderController::class);
    Route::get('/settings', [StoreSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [StoreSettingController::class, 'update'])->name('settings.update');
});
