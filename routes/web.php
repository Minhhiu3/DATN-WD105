<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Client\ClientProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\AlbumProductController;


// Admin
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/users', UserController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/sizes', SizeController::class);
    Route::resource('/banner', BannerController::class);
    Route::resource('/Ablum_products', AlbumProductController::class);    // /admin/size
    Route::get('/AblumProducts/{product_id}', [AlbumProductController::class, 'showAblum'])
    ->name('AblumProducts.show_ablum');
    Route::resource('/Variants', SizeController::class);    // /admin/size

});

// User
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
