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




// Admin
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Route::resource('/users', UserController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/sizes', SizeController::class);
    Route::resource('/banner', BannerController::class);
    Route::resource('/Ablum_products', AlbumProductController::class);    // /admin/size
    Route::get('/AblumProducts/{product_id}', [AlbumProductController::class, 'showAblum'])
    ->name('AblumProducts.show_ablum');
    Route::resource('/variants', VariantController::class);
Route::resource('/discounts', DiscountController::class); // /admin/discounts

});

// User
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ClientProductController::class, 'index'])->name('products');
Route::get('/product-detail/{id}', [ClientProductController::class, 'show'])->name('client.product.show');


// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

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
    Route::resource('/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    Route::resource('/categories', CategoryController::class); // /admin/categories
    Route::resource('/products', ProductController::class);    // /admin/products
});






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
