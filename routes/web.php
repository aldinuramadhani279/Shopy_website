<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\ProductController as FrontendProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\WishlistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categories', [ProductController::class, 'categories'])->name('categories.index');

// Fallback route for 404


// Product Routes
Route::get('/products', [FrontendProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [FrontendProductController::class, 'show'])->name('products.show');

// Review Routes
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/products/{product}/reviews', [ReviewController::class, 'productReviews'])->name('products.reviews');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buy_now');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Account Routes
Route::prefix('account')->middleware(['auth', 'ensure.customer'])->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderDetail'])->name('account.orders.detail');
});

// Review Management Routes
Route::prefix('admin')->middleware(['auth', 'ensure.admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Product Management
    Route::get('products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('admin.products.show');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::post('products/toggle-active', [ProductController::class, 'toggleActive'])->name('admin.products.toggle-active');
    
    // Category Management
    Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('admin.categories.show');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::post('categories/toggle-active', [CategoryController::class, 'toggleActive'])->name('admin.categories.toggle-active');
    
    // Order Management
    Route::resource('orders', OrderController::class);
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::get('orders/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('admin.orders.invoice');
    
    // Customer Management
    Route::resource('customers', CustomerController::class);
    Route::get('customers/export', [CustomerController::class, 'export'])->name('admin.customers.export');
    
    // Coupon Management
    Route::resource('coupons', CouponController::class);
    
    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/approved', [ReviewController::class, 'approved'])->name('reviews.approved');
    Route::get('/reviews/rejected', [ReviewController::class, 'rejected'])->name('reviews.rejected');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});
