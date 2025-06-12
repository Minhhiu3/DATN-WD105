<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Client\ClientProductController;
use App\Http\Controllers\Client\HomeController;

// Admin
// Route::prefix('admin')->middleware('auth')->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
//     Route::resource('/users', UserController::class);      // /admin/users
//     Route::resource('/categories', CategoryController::class); // /admin/categories
//     Route::resource('/products', ProductController::class);    // /admin/products
// });
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/users', UserController::class);      // /admin/users
    Route::resource('/categories', CategoryController::class); // /admin/categories
    Route::resource('/products', ProductController::class);    // /admin/products
    Route::resource('/sizes', SizeController::class);    // /admin/size

});

//user
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ClientProductController::class, 'index'])->name('products');
Route::get('/product-detail/{id}', [ClientProductController::class, 'show'])->name('client.product.show');

Route::get('/blogs', function () {
    return view('client.pages.blogs');
})->name('blogs');

Route::get('/blog-detail', function () {
    return view('client.pages.blog-detail');
})->name('blog-detail');

Route::get('/login', function () {
    return view('client.pages.login');
})->name('login');

Route::get('/cart', function () {
    return view('client.pages.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('client.pages.checkout');
})->name('checkout');