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


