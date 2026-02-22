<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SpecialEventController;
use App\Http\Controllers\Admin\SettingController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/menu/{slug}', [MenuController::class, 'category'])->name('menu.category');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Cart Routes
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add-event', [CartController::class, 'addEvent'])->name('cart.add-event');
Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/cart', [CartController::class, 'index'])->name('cart');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Order Routes
Route::get('/order/status/{orderNumber}', [OrderController::class, 'status'])->name('order.status');
Route::get('/order/track', [OrderController::class, 'track'])->name('order.track');
Route::post('/order/track', [OrderController::class, 'trackSearch'])->name('order.track.search');

// Admin Auth
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Panel
Route::prefix('admin')->middleware(['auth', 'admin'])->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('banners', BannerController::class)->except(['show']);

    Route::get('/special-event', [SpecialEventController::class, 'index'])->name('special-event.index');
    Route::post('/special-event/settings', [SpecialEventController::class, 'updateSettings'])->name('special-event.settings');
    Route::post('/special-event/upload', [SpecialEventController::class, 'upload'])->name('special-event.upload');
    Route::put('/special-event/{gallery}', [SpecialEventController::class, 'updateGallery'])->name('special-event.update');
    Route::delete('/special-event/{gallery}', [SpecialEventController::class, 'destroyGallery'])->name('special-event.destroy');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
