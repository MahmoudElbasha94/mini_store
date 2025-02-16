<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

//home
Route::get('/', [HomeController::class, 'index'])->name('home');

//user register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

//user login & logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//admin login
Route::get('/admin/login', [AdminLoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'adminLogin']);
Route::post('/admin/logout', [AdminLoginController::class, 'adminLogout'])->name('admin.logout');


//admin dashboard
Route::middleware(['auth:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        //admin dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        //admin categories
        Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('categories/{category}', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        //admin products
        Route::get('products', [ProductController::class, 'index'])->name('admin.products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::post('products', [ProductController::class, 'store'])->name('admin.products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    });
});

//user product routes (not need Auth)
Route::get('products/all', [ShopController::class, 'index'])->name('user.products.index');
Route::get('/products/{product}/{category}', [ShopController::class, 'show'])->name('user.products.show');


// User Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])
    ->middleware('auth')
    ->name('cart.add');
Route::post('/cart/update/{productId}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/remove/{productId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');


//user wishlist routes
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'store'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'destroy'])->name('wishlist.remove');
});
