<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client.pages.home');
})->name('home');
Route::get('/products', function () {
    return view('client.pages.products');
})->name('products');
Route::get('/product-detail', function () {
    return view('client.pages.product-detail');
})->name('product-detail');
