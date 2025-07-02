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
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Admin\AlbumProductController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AccountController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Admin\ProductReviewController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ClientProductController::class, 'index'])->name('products');
Route::get('/product-detail/{id}', [ClientProductController::class, 'show'])->name('client.product.show');
Route::get('/products/filter', [ClientProductController::class, 'filterByPrice'])->name('products.filterByPrice');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::put('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
Route::get('/cart/details', [CartController::class, 'getCartDetails'])->name('cart.details');

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
 Route::get('/checkout-form', [CheckoutController::class, 'showCheckoutForm'])->name('account.checkout.form');
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('account.placeOrder');
Route::put('/account/orders/{id}/cancel', [AccountController::class, 'cancelOrder'])->name('account.cancelOrder');
Route::get('/account/orders/{id}', [AccountController::class, 'orderDetail'])->name('account.orderDetail');
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
    Route::get('orders', [OrderController::class, 'index'])
         ->name('admin.orders.index');

    // Hiển thị chi tiết 1 đơn hàng
    Route::get('orders/{order}', [OrderController::class, 'show'])
         ->name('admin.orders.show');

    // // Trang edit (nếu dùng)
    // Route::get('orders/{order}/edit', [OrderController::class, 'edit'])
    //      ->name('admin.orders.edit');

    // // Cập nhật (PUT)
    // Route::put('orders/{order}', [OrderController::class, 'update'])
    //      ->name('admin.orders.update');

    // AJAX: cập nhật trạng thái
    Route::post('orders/update-status', [OrderController::class, 'updateStatus'])
         ->name('admin.orders.updateStatus');

    // AJAX: hủy đơn hàng
    Route::post('orders/cancel', [OrderController::class, 'cancel'])
         ->name('admin.orders.cancel');



    Route::get('reviews', [ProductReviewController::class, 'index'])
         ->name('admin.reviews.index');
    Route::post('/reviews/update-status', [ProductReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
    Route::post('/reviews/update-status', [ProductReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
    Route::delete('/reviews/{id_review}', [ProductReviewController::class, 'destroy'])->name('admin.reviews.destroy');

});

// Client Routes
Route::get('/blogs', function () {
    return view('client.pages.blogs');
})->name('blogs');

Route::get('/blog-detail', function () {
    return view('client.pages.blog-detail');
})->name('blog-detail');

// Route::get('/checkout', function () {
//     return view('client.pages.checkout');
// })->name('checkout');

