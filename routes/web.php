<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\AlbumProductController;

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
    Route::resource('/Ablum_products', AlbumProductController::class);    // /admin/size
    Route::resource('/Variants', SizeController::class);    // /admin/size

});

//usser
Route::get('/', function () {
    return view('client.pages.home');
})->name('home');
Route::get('/products', function () {
    return view('client.pages.products');
})->name('products');
Route::get('/product-detail', function () {
    return view('client.pages.product-detail');
})->name('product-detail');

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
