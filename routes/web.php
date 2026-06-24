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
Route::post('/order/process', [OrderController::class, 'process'])->name('order.process')->middleware('throttle:5,1');
Route::get('/order/tracking', [OrderController::class, 'showTrackingForm'])->name('order.tracking.form');
Route::post('/order/tracking', [OrderController::class, 'findOrder'])->name('order.tracking.search')->middleware('throttle:10,1');
Route::get('/order/payment/{uuid}', [OrderController::class, 'payment'])->name('order.payment');
Route::get('/order/tracking/{uuid}', [OrderController::class, 'tracking'])->name('order.tracking');
Route::post('/order/confirm-delivery/{uuid}', [OrderController::class, 'confirmDelivery'])->name('order.confirm_delivery');

// Halaman Kemitraan Wholesale B2B
Route::get('/wholesale', [HomeController::class, 'wholesale'])->name('wholesale');
Route::post('/wholesale', [HomeController::class, 'submitWholesale']);

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

// Lupa Kata Sandi
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

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
    Route::post('/products/{id}/toggle-best-seller', [AdminDashboardController::class, 'toggleBestSeller'])->name('products.toggle_best_seller');
    Route::post('/products/store', [AdminDashboardController::class, 'storeProduct'])->name('products.store');
    Route::post('/products/{id}/update', [AdminDashboardController::class, 'updateProduct'])->name('products.update');
    Route::post('/products/{id}/delete', [AdminDashboardController::class, 'destroyProduct'])->name('products.delete');
    Route::post('/settings/update-couriers', [AdminDashboardController::class, 'updateCouriers'])->name('settings.update_couriers');
});

// Halaman Legal
Route::view('/privacy-policy', 'legal.privacy_policy')->name('legal.privacy');
Route::view('/refund-policy', 'legal.refund_policy')->name('legal.refund');

// Route Pengujian Halaman Error (Temporer)
Route::get('/test-error/{code}', function ($code) {
    if (in_array($code, [403, 404, 419, 500])) {
        abort($code);
    }
    return 'Gunakan kode error: 403, 404, 419, atau 500. Contoh: /test-error/404';
});
