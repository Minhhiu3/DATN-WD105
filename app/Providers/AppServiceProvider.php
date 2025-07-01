<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
        $cartItems = collect();

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cartItems = CartItem::with(['variant.product', 'variant.size'])
                    ->where('cart_id', $cart->id_cart)
                    ->get();
            }
        } else {
            // Nếu dùng session lưu cart cho guest:
            $cartItems = session('cart', collect());
        }

        $view->with('cartItems', $cartItems);
    });
    }
}
