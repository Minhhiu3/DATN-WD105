<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Variant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thêm vào giỏ hàng',
                'require_login' => true
            ], 401);
        }
        try {
            $request->validate([
                'variant_id' => 'required|exists:variant,id_variant',
                'quantity' => 'required|integer|min:1',
            ]);

            $variant = Variant::with(['product', 'size'])->find($request->variant_id);

            if (!$variant) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            }

            // if ($variant->quantity < $request->quantity) {
            //     return response()->json(['success' => false, 'message' => 'Số lượng không đủ']);
            // }

            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartItem = CartItem::where('cart_id', $cart->id_cart)
                ->where('variant_id', $request->variant_id)
                ->first();


       if ($cartItem) {
    $totalQty = $cartItem->quantity + $request->quantity;


    if ($totalQty > $variant->quantity) {
        return response()->json([
            'success' => false,
            'message' => 'Số lượng hàng không đủ. Chỉ còn ' . $variant->quantity . ' sản phẩm'
        ]);
    }


    $cartItem->quantity = $totalQty;
    $cartItem->save();
} else {

                CartItem::create([
                    'cart_id' => $cart->id_cart,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity,
                ]);
            }

           return response()->json([
    'success' => true,
    'message' => 'Đã thêm vào giỏ hàng!'
]);
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()], 500);
        }
    }

    public function updateQuantity(Request $request)
    {
        try {
            $request->validate([
                'variant_id' => 'required|exists:variant,id_variant',
                'quantity' => 'required|integer|min:1',
            ]);

            $variant = Variant::find($request->variant_id);

            if (!$variant) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            }

            // Kiểm tra số lượng tồn kho
            if ($request->quantity > $variant->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá tồn kho. Chỉ còn ' . $variant->quantity . ' sản phẩm'
                ]);
            }

            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $cartItem = CartItem::where('cart_id', $cart->id_cart)
                        ->where('variant_id', $request->variant_id)
                        ->first();

                    if ($cartItem) {
                        $cartItem->quantity = $request->quantity;
                        $cartItem->save();
                    } else {
                        return response()->json(['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại']);
                }
            } else {
                $cart = session('cart', collect());
                $existingItem = $cart->firstWhere('variant_id', $request->variant_id);

                if ($existingItem) {
                    $cart = $cart->map(function ($item) use ($request) {
                        if ($item['variant_id'] == $request->variant_id) {
                            $item['quantity'] = $request->quantity;
                        }
                        return $item;
                    });
                    session(['cart' => $cart]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng']);
                }
            }

            return response()->json(['success' => true, 'message' => 'Cập nhật số lượng thành công']);
        } catch (\Exception $e) {
            Log::error('Update cart quantity error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()], 500);
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            $request->validate([
                'variant_id' => 'required|exists:variant,id_variant',
            ]);

            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $deleted = CartItem::where('cart_id', $cart->id_cart)
                        ->where('variant_id', $request->variant_id)
                        ->delete();

                    if ($deleted > 0) {
                        return response()->json(['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại']);
                }
            } else {
                $cart = session('cart', collect());
                $initialCount = $cart->count();
                $cart = $cart->filter(function ($item) use ($request) {
                    return $item['variant_id'] != $request->variant_id;
                });
                session(['cart' => $cart]);

                if ($cart->count() < $initialCount) {
                    return response()->json(['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Remove from cart error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()], 500);
        }
    }

    public function clearCart()
    {
        try {
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    CartItem::where('cart_id', $cart->id_cart)->delete();
                    return response()->json(['success' => true, 'message' => 'Đã xóa tất cả sản phẩm khỏi giỏ hàng']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại']);
                }
            } else {
                session()->forget('cart');
                return response()->json(['success' => true, 'message' => 'Đã xóa tất cả sản phẩm khỏi giỏ hàng']);
            }
        } catch (\Exception $e) {
            Log::error('Clear cart error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()], 500);
        }
    }

    public function getCartDetails()
    {
        try {
             $shippingFee = 30000;
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $cartItems = CartItem::with(['variant.product', 'variant.size'])
                        ->where('cart_id', $cart->id_cart)
                        ->get();
                } else {
                    $cartItems = collect();
                }
            } else {
                $cartItems = session('cart', collect());
            }

            $total = 0;
            $itemCount = 0;

            foreach ($cartItems as $item) {
                $variant = $item->variant ?? $item['variant'];
                $quantity = $item->quantity ?? $item['quantity'];
                $total += $variant->price * $quantity;
                $itemCount += $quantity;
            }
            $grandTotal = $total + $shippingFee;
            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $cartItems,
                    'total' => $total,
                    'shipping_fee' => $shippingFee,
                    'grand_total'=> $grandTotal,
                    'item_count' => $itemCount,
                    'formatted_total' => number_format($grandTotal, 0, ',', '.') . ' VNĐ'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get cart details error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()], 500);
        }
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
