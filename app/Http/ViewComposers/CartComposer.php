<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $cartItems = collect();
        
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cartItems = $cart->items()->with(['variant.product', 'variant.size'])->get();
            }
        }
        
        $view->with('cartItems', $cartItems);
    }
}
