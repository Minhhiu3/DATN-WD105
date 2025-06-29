<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Variant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Lấy giỏ hàng từ database cho user đã đăng nhập
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cartItems = CartItem::with(['variant.product', 'variant.size'])
                    ->where('cart_id', $cart->id_cart)
                    ->get();
            } else {
                $cartItems = collect();
            }
        } else {
            // Lấy giỏ hàng từ session cho user chưa đăng nhập
            $cartItems = session('cart', collect());
        }

        return view('client.pages.cart', compact('cartItems'));
    }

    // public function addToCart(Request $request)
    // {
    //     $request->validate([
    //         'variant_id' => 'required|exists:variant,id_variant',
    //         'quantity' => 'required|integer|min:1',
    //     ]);

    //     $variant = Variant::with(['product', 'size'])->find($request->variant_id);
        
    //     if (!$variant) {
    //         return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
    //     }

    //     if ($variant->quantity < $request->quantity) {
    //         return response()->json(['success' => false, 'message' => 'Số lượng không đủ']);
    //     }
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'variant_id' => 'required|exists:variant,id_variant',
                'quantity' => 'required|integer|min:1',
            ]);

            $variant = Variant::with(['product', 'size'])->find($request->variant_id);

            if (!$variant) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            }

            if ($variant->quantity < $request->quantity) {
                return response()->json(['success' => false, 'message' => 'Số lượng không đủ']);
            }

            // ... phần còn lại giữ nguyên ...
            
        //     return response()->json([
        //         'success' => true, 
        //         'message' => 'Đã thêm vào giỏ hàng',
        //         'cart_count' => $this->getCartCountPrivate()
        //     ]);
        // } catch (\Exception $e) {
        //     \Log::error('Add to cart error: ' . $e->getMessage());
        //     return response()->json(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()], 500);
        // }

        if (Auth::check()) {
            // User đã đăng nhập - lưu vào database
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            
            $cartItem = CartItem::where('cart_id', $cart->id_cart)
                ->where('variant_id', $request->variant_id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id_cart,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity,
                ]);
            }
        } else {
            // User chưa đăng nhập - lưu vào session
            $cart = session('cart', collect());
            
            $existingItem = $cart->firstWhere('variant_id', $request->variant_id);
            
            if ($existingItem) {
                $cart = $cart->map(function ($item) use ($request) {
                    if ($item['variant_id'] == $request->variant_id) {
                        $item['quantity'] += $request->quantity;
                    }
                    return $item;
                });
            } else {
                $cart->push([
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity,
                    'variant' => $variant,
                ]);
            }
            
            session(['cart' => $cart]);
        }

        // return response()->json([
        //     'success' => true, 
        //     'message' => 'Đã thêm vào giỏ hàng',
        //     'cart_count' => $this->getCartCountPrivate()
        // ]);
        return response()->json([
                'success' => true, 
                'message' => 'Đã thêm vào giỏ hàng',
                'cart_count' => $this->getCartCountPrivate()
            ]);
        } catch (\Exception $e) {
            \Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()], 500);
        }
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variant,id_variant',
            'quantity' => 'required|integer|min:1',
        ]);

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $cartItem = CartItem::where('cart_id', $cart->id_cart)
                    ->where('variant_id', $request->variant_id)
                    ->first();
                
                if ($cartItem) {
                    $cartItem->quantity = $request->quantity;
                    $cartItem->save();
                }
            }
        } else {
            $cart = session('cart', collect());
            $cart = $cart->map(function ($item) use ($request) {
                if ($item['variant_id'] == $request->variant_id) {
                    $item['quantity'] = $request->quantity;
                }
                return $item;
            });
            session(['cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variant,id_variant',
        ]);

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id_cart)
                    ->where('variant_id', $request->variant_id)
                    ->delete();
            }
        } else {
            $cart = session('cart', collect());
            $cart = $cart->filter(function ($item) use ($request) {
                return $item['variant_id'] != $request->variant_id;
            });
            session(['cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }

    public function clearCart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id_cart)->delete();
            }
        } else {
            session()->forget('cart');
        }

        return response()->json(['success' => true]);
    }

    private function getCartCountPrivate()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                return CartItem::where('cart_id', $cart->id_cart)->sum('quantity');
            }
        } else {
            $cart = session('cart', collect());
            return $cart->sum('quantity');
        }
        
        return 0;
    }

    public function getCartCount()
    {
        return response()->json(['count' => $this->getCartCountPrivate()]);
    }
} 