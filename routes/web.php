<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Client\ClientProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\AlbumProductController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AccountController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ClientProductController::class, 'index'])->name('products');
Route::get('/product-detail/{id}', [ClientProductController::class, 'show'])->name('client.product.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Account Management Routes (Protected by Auth)
Route::prefix('account')->middleware('auth')->group(function () {
    Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::get('/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/update', [AccountController::class, 'update'])->name('account.update');
    Route::get('/change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
    Route::put('/update-password', [AccountController::class, 'updatePassword'])->name('account.update-password');
    Route::get('/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/settings', [AccountController::class, 'settings'])->name('account.settings');
});

// Admin Routes (Protected by AdminMiddleware)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // User Management Routes
    Route::resource('/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    
    // Category Management Routes
    Route::resource('/categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    
    // Product Management Routes
    Route::resource('/products', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
    
    // Size Management Routes
    Route::resource('/sizes', SizeController::class)->names([
        'index' => 'admin.sizes.index',
        'create' => 'admin.sizes.create',
        'store' => 'admin.sizes.store',
        'show' => 'admin.sizes.show',
        'edit' => 'admin.sizes.edit',
        'update' => 'admin.sizes.update',
        'destroy' => 'admin.sizes.destroy',
    ]);
    
    // Banner Management Routes
    Route::resource('/banner', BannerController::class)->names([
        'index' => 'admin.banner.index',
        'create' => 'admin.banner.create',
        'store' => 'admin.banner.store',
        'show' => 'admin.banner.show',
        'edit' => 'admin.banner.edit',
        'update' => 'admin.banner.update',
        'destroy' => 'admin.banner.destroy',
    ]);
    
    // Album Product Management Routes
    Route::resource('/album-products', AlbumProductController::class)->names([
        'index' => 'admin.album-products.index',
        'create' => 'admin.album-products.create',
        'store' => 'admin.album-products.store',
        'show' => 'admin.album-products.show',
        'edit' => 'admin.album-products.edit',
        'update' => 'admin.album-products.update',
        'destroy' => 'admin.album-products.destroy',
    ]);
    Route::get('/album-products/{product_id}/show-album', [AlbumProductController::class, 'showAblum'])
        ->name('admin.album-products.show-album');
    
    // Variant Management Routes
    Route::resource('/variants', VariantController::class)->names([
        'index' => 'admin.variants.index',
        'create' => 'admin.variants.create',
        'store' => 'admin.variants.store',
        'show' => 'admin.variants.show',
        'edit' => 'admin.variants.edit',
        'update' => 'admin.variants.update',
        'destroy' => 'admin.variants.destroy',
    ]);
    
    // Discount Management Routes
    Route::resource('/discounts', DiscountController::class)->names([
        'index' => 'admin.discounts.index',
        'create' => 'admin.discounts.create',
        'store' => 'admin.discounts.store',
        'show' => 'admin.discounts.show',
        'edit' => 'admin.discounts.edit',
        'update' => 'admin.discounts.update',
        'destroy' => 'admin.discounts.destroy',
    ]);
});

// Client Routes
Route::get('/blogs', function () {
    return view('client.pages.blogs');
})->name('blogs');

Route::get('/blog-detail', function () {
    return view('client.pages.blog-detail');
})->name('blog-detail');

Route::get('/cart', function () {
    return view('client.pages.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('client.pages.checkout');
})->name('checkout');
