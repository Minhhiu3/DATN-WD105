<?php

use Illuminate\Support\Facades\Route;


//admin
Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);

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