<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Halaman Buyer / Pelanggan
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/lab', 'lab')->name('lab');
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/product/{product_id}/review', [ProductController::class, 'storeReview'])->name('product.review.store');

// Manajemen Keranjang Belanja (Local Storage Sync)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Alur Checkout & Pelacakan Pesanan
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/order/process', [OrderController::class, 'process'])->name('order.process');
Route::get('/order/payment/{transaction_id}', [OrderController::class, 'payment'])->name('order.payment');
Route::get('/order/tracking/{transaction_id}', [OrderController::class, 'tracking'])->name('order.tracking');
Route::post('/order/confirm-delivery/{transaction_id}', [OrderController::class, 'confirmDelivery'])->name('order.confirm_delivery');

// API RajaOngkir / Biteship
Route::get('/api/cities', [OrderController::class, 'getCities']);
Route::post('/api/shipping-cost', [OrderController::class, 'calculateShipping']);
Route::get('/api/biteship/search-areas', [OrderController::class, 'searchAreas']);

// Autentikasi Kustom
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Pelanggan (Protected)
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::post('/profile/update', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
});

// Panel Admin (Protected)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/order/update-status', [AdminDashboardController::class, 'updateStatus'])->name('order.update_status');
    Route::get('/order/{transaction_id}/print', [AdminDashboardController::class, 'printReceipt'])->name('order.print');
});
