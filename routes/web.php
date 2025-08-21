<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
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
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AdviceProductController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\ProductReviewController as ClientProductReviewController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Client\ClientDiscountController;


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ClientProductController::class, 'index'])->name('products');
Route::get('/product-detail/{id}', [ClientProductController::class, 'show'])->name('client.product.show');
Route::get('/products/filter', [ClientProductController::class, 'filterByPrice'])->name('products.filterByPrice');
Route::get('/discounts', [ClientDiscountController::class, 'index'])->name('discounts');
    // lưu voucher vao tài khoản 
    Route::post('/save-voucher-user', [ClientDiscountController::class, 'saveVoucherUser'])->name('save.voucherUser')->middleware('auth');
Route::get('/contact', function () {
    return view('client.pages.contact');
})->name('contact');


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
    Route::put('/orders/{id}/cancel', [AccountController::class, 'cancelOrder'])->name('account.cancelOrder');
    Route::get('/orders/{id}', [AccountController::class, 'orderDetail'])->name('account.orderDetail');
    Route::get('/checkout-cart', [CheckoutController::class, 'checkoutCart'])->name('account.checkout.cart');
    Route::post('/checkout-cart', [CheckoutController::class, 'checkoutCart'])->name('account.checkout.cart');
    Route::post('/place-order-cart', [CheckoutController::class, 'placeOrderFromCart'])->name('account.placeOrder.cart');
    Route::post('/vnpay_payment', [PaymentController::class, 'vnpay_payment'])->name('account.vnpay.payment'); // VNPAY payment route
    Route::get('/payment/vnpay', [PaymentController::class, 'vnpay_payment'])->name('payment.vnpay');
    Route::get('/vnpay-return', [PaymentController::class, 'vnpayReturn'])->name('vnpay.return');
    // routes/web.php
    Route::get('/get-provinces', [LocationController::class, 'getProvinces'])->name('get.provinces');
    // Route::get('/get-districts/{province_id}', [LocationController::class, 'getDistricts'])->name('get.districts');
    Route::get('/get-wards/{district_id}', [LocationController::class, 'getWards'])->name('get.wards');
    Route::get('/payment/vnpay-buy-now', [PaymentController::class, 'paymentVnpayBuyNow'])->name('payment.vnpay.buy_now');
    Route::get('/vnpay-return-buy-now', [PaymentController::class, 'vnpayReturnBuyNow'])->name('vnpay.return.buy_now');
    //xac nhan nhan hang
    Route::put('/orders/{id}/confirm-receive', [ClientOrderController::class, 'confirmReceive'])
    ->name('account.confirmReceive');
    Route::post('/apply-coupon', [CheckoutController::class, 'apply'])->name('apply.coupon');
    Route::post('/apply-coupon-cart', [CheckoutController::class, 'applyCouponCart'])->name('apply.couponCart');
    Route::post('/reviews', [ClientProductReviewController::class, 'store'])->name('product.reviews.store');
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
    // Thùng rác danh mục
    Route::get('categories/trash', [CategoryController::class, 'trash'])->name('admin.categories.trash');
    Route::post('categories/restore/{id}', [CategoryController::class, 'restore'])->name('admin.categories.restore');
    Route::delete('categories/force-delete/{id}', [CategoryController::class, 'forceDelete'])->name('admin.categories.forceDelete');
    Route::resource('/categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    // thùng rác bảng thương hiệu
    Route::get('brands/trash', [BrandController::class, 'trash'])->name('admin.brands.trash');
    Route::post('brands/restore/{id}', [BrandController::class, 'restore'])->name('admin.brands.restore');
    Route::delete('brands/force-delete/{id}', [BrandController::class, 'forceDelete'])->name('admin.brands.forceDelete');
    // Brand Management Routes
    Route::resource('/brands', BrandController::class)->names([
        'index' => 'admin.brands.index',
        'create' => 'admin.brands.create',
        'store' => 'admin.brands.store',
        'show' => 'admin.brands.show',
        'edit' => 'admin.brands.edit',
        'update' => 'admin.brands.update',
        'destroy' => 'admin.brands.destroy',
    ]);

    Route::patch('/products/{id}/toggle-visibility', [ProductController::class, 'toggleVisibility'])
        ->name('admin.products.toggle-visibility');

        // Thùng rác sản phẩm
    Route::get('products/trash', [ProductController::class, 'trash'])->name('admin.products.trash');
    Route::post('products/restore/{id}', [ProductController::class, 'restore'])->name('admin.products.restore');
    Route::delete('products/force-delete/{id}', [ProductController::class, 'forceDelete'])->name('admin.products.forceDelete');
    // Product Management Routes
    Route::resource('products', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

    // Thùng rác size
    Route::get('sizes/trash', [SizeController::class, 'trash'])->name('admin.sizes.trash');
    Route::post('sizes/restore/{id}', [SizeController::class, 'restore'])->name('admin.sizes.restore');
    Route::delete('sizes/force-delete/{id}', [SizeController::class, 'forceDelete'])->name('admin.sizes.forceDelete');
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

    // thùng rác bảng banner
    Route::get('banner/trash', [BannerController::class, 'trash'])->name('admin.banner.trash');
    Route::post('banner/restore/{id}', [BannerController::class, 'restore'])->name('admin.banner.restore');
    Route::delete('banner/force-delete/{id}', [BannerController::class, 'forceDelete'])->name('admin.banner.forceDelete');
    // Banner Management Routes
    Route::resource('banner', BannerController::class)->names([
        'index' => 'admin.banner.index',
        'create' => 'admin.banner.create',
        'store' => 'admin.banner.store',
        'show' => 'admin.banner.show',
        'edit' => 'admin.banner.edit',
        'update' => 'admin.banner.update',
        'destroy' => 'admin.banner.destroy',
    ]);

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

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


    // Route cho thùng rác biến thể con
    Route::get('/variants/trash/{product_id}', [VariantController::class, 'trash'])->name('admin.variants.trash');
    Route::post('/variants/restore/{id}', [VariantController::class, 'restore'])->name('admin.variants.restore');
    Route::delete('/variants/force-delete/{id}', [VariantController::class, 'forceDelete'])->name('admin.variants.forceDelete');
    // Route cho thùng rác biến thể chinh
    Route::post('/variants/restore-color/{color_id}', [VariantController::class, 'restoreColor'])->name('admin.variants.restore-color');
    Route::delete('/variants/force-delete-color/{color_id}', [VariantController::class, 'forceDeleteColor'])->name('admin.variants.forceDelete-color');
    // Variant Management Routes
    Route::get('/variants/create-item', [VariantController::class, 'create_item'])->name('admin.variants.create_item');
    Route::post('/variants/store-item', [VariantController::class, 'storeItem'])->name('admin.variants.store_item');
    Route::resource('/variants', VariantController::class)->names([
        'index' => 'admin.variants.index',
        'create' => 'admin.variants.create',
        'store' => 'admin.variants.store',
        'show' => 'admin.variants.show',
        'edit' => 'admin.variants.edit',
        'update' => 'admin.variants.update',
        'destroy' => 'admin.variants.destroy',
    ]);
   

    // Size Management Routes
    Route::resource('/colors', ColorController::class)->names([
        'index' => 'admin.colors.index',
        'create' => 'admin.colors.create',
        'store' => 'admin.colors.store',
        'show' => 'admin.colors.show',
        'edit' => 'admin.colors.edit',
        'update' => 'admin.colors.update',
        'destroy' => 'admin.colors.destroy',
    ]);
    // Discount Management Routes

    Route::get('/check-code', [DiscountController::class, 'checkCode']);

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
           Route::post('orders/update-payment-status', [OrderController::class, 'updatePaymentStatus'])
         ->name('admin.orders.updatePaymentStatus');

    // AJAX: hủy đơn hàng
    Route::post('orders/cancel', [OrderController::class, 'cancel'])
         ->name('admin.orders.cancel');



    Route::get('reviews', [ProductReviewController::class, 'index'])
         ->name('admin.reviews.index');
    Route::post('/reviews/update-status', [ProductReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
    Route::post('/reviews/update-status', [ProductReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
    Route::delete('/reviews/{id_review}', [ProductReviewController::class, 'destroy'])->name('admin.reviews.destroy');

     //xem chi tiét sale của sản phẩm
    Route::get('/sale/{id_product}', [AdviceProductController::class, 'index'])->name('admin.sale.index');
    Route::post('/sale/update/{id_product}', [AdviceProductController::class, 'update'])->name('admin.sale.update');
    Route::post('sales/{id}/toggle-status', [AdviceProductController::class, 'toggleStatus'])->name('admin.sale.toggleStatus');
    // sua so luong bieen the
    Route::post('/variant/update-quantity/{id}', [VariantController::class, 'updateQuantity'])->name('admin.updateQuantity');


});

//call api ben thu 3
// Route lấy danh sách tỉnh
Route::get('/api/vl/provinces', function () {
    $response = Http::get('https://vietnamlabs.com/api/vietnamprovince');

    if ($response->successful()) {
        $data = $response->json();

        return response()->json([
            'success' => true,
            'data' => $data['data']['data'] ?? []
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Không thể lấy tỉnh/thành'], 500);
});

// Route lấy quận/huyện theo mã tỉnh
Route::get('/api/vl/districts/{provinceCode}', function ($provinceCode) {
    $response = Http::get("https://vietnamlabs.com/api/vietnamprovince/district/$provinceCode");

    if ($response->successful()) {
        $data = $response->json();
        return response()->json([
            'success' => true,
            'data' => $data['data'] ?? []
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Không thể lấy quận/huyện'], 500);
});

// Route lấy phường/xã theo mã quận/huyện
Route::get('/api/vl/wards/{districtCode}', function ($districtCode) {
    $response = Http::get("https://vietnamlabs.com/api/vietnamprovince/commune/$districtCode");

    if ($response->successful()) {
        $data = $response->json();
        return response()->json([
            'success' => true,
            'data' => $data['data'] ?? []
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Không thể lấy phường/xã'], 500);
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
