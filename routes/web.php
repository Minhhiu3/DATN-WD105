<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;

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