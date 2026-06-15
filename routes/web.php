<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;

// Halaman Buyer / Pelanggan
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/lab', 'lab')->name('lab');
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Manajemen Keranjang Belanja (Local Storage Sync)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Alur Checkout & Pelacakan Pesanan
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/order/process', [OrderController::class, 'process'])->name('order.process');
Route::get('/order/tracking/{transaction_id}', [OrderController::class, 'tracking'])->name('order.tracking');

// Panel Admin (Sederhana & Real-time)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/order/update-status', [AdminDashboardController::class, 'updateStatus'])->name('order.update_status');
});
