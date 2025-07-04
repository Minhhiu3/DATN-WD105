<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    // Hiển thị form thanh toán
    public function showCheckoutForm(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variant,id_variant',
            'quantity'   => 'required|integer|min:1',
        ]);

        $variant = Variant::with(['product', 'size'])->findOrFail($request->variant_id);
        $quantity = $request->quantity;

        if ($variant->quantity < $quantity) {
            return redirect()->back()->withErrors('Số lượng sản phẩm không đủ trong kho.');
        }

        return view('client.pages.checkout', compact('variant', 'quantity'));
    }

    // Xử lý đặt hàng
    private function generateOrderCode()
{
    do {
        $code = 'SM' . strtoupper(Str::random(6));
    } while (Order::where('order_code', $code)->exists());

    return $code;
}
    public function placeOrder(Request $request)
    {
        $request->validate([
            'variant_id'      => 'required|exists:variant,id_variant',
            'quantity'        => 'required|integer|min:1',
            'payment_method'  => 'required|in:cod,vnpay',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $variant = Variant::findOrFail($request->variant_id);

            if ($variant->quantity < $request->quantity) {
                return redirect()->back()->withErrors('Số lượng sản phẩm không đủ trong kho.');
            }
            // Tạo đơn hàng
            $orderCode = $this->generateOrderCode();
            $order = Order::create([
                'user_id'        => $user->id_user,
                 'order_code'     => $orderCode,
                'status'         => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'total_amount'   => $variant->price * $request->quantity,
                'created_at'     => now(),
            ]);

            // Thêm chi tiết đơn hàng
            OrderItem::create([
                'order_id'   => $order->id_order,
                'variant_id' => $variant->id_variant,
                'quantity'   => $request->quantity,
                'created_at' => now(),
            ]);

            // Trừ kho
            $variant->decrement('quantity', $request->quantity);

            DB::commit();
            return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Lỗi xử lý đơn hàng: ' . $e->getMessage());
        }
    }
    //mua từ giỏ hàng
public function checkoutCart()
{
    $user = Auth::user();

    // Lấy cart của user
    $cart = Cart::where('user_id', $user->id_user)->first();

    if (!$cart) {
        return redirect()->route('cart')->with('error', 'Giỏ hàng của bạn đang trống!');
    }

    // Lấy cart items kèm variant, product, size
    $cartItems = CartItem::with(['variant.product', 'variant.size'])
        ->where('cart_id', $cart->id_cart)
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart')->with('error', 'Giỏ hàng của bạn đang trống!');
    }

    return view('client.pages.checkout_cart', compact('cartItems'));
}
public function placeOrderFromCart(Request $request)
{
    $user = Auth::user();

    $cart = Cart::where('user_id', $user->id_user)->first();

    if (!$cart) {
        return redirect()->route('cart')->withErrors('Giỏ hàng trống.');
    }

    $cartItems = CartItem::with('variant.product')
        ->where('cart_id', $cart->id_cart)
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart')->withErrors('Giỏ hàng trống.');
    }

    $request->validate([
        'payment_method' => 'required|in:cod,vnpay',
    ]);

    DB::beginTransaction();

    try {
        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $variant = $item->variant;

            if (!$variant) {
                throw new \Exception("Sản phẩm không tồn tại.");
            }

            if ($variant->quantity < $item->quantity) {
                throw new \Exception("Sản phẩm {$variant->product->name_product} không đủ hàng.");
            }

            $totalAmount += $variant->price * $item->quantity;
        }

        $orderCode = $this->generateOrderCode();

        $order = Order::create([
            'user_id'        => $user->id_user,
            'order_code'     => $orderCode,
            'status'         => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'total_amount'   => $totalAmount,
            'created_at'     => now(),
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id_order,
                'variant_id' => $item->variant_id,
                'quantity'   => $item->quantity,
                'created_at' => now(),
            ]);

            $item->variant->decrement('quantity', $item->quantity);
        }

        // Xoá toàn bộ gio hang
        CartItem::where('cart_id', $cart->id_cart)->delete();

        DB::commit();
        return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors('Lỗi đặt hàng: ' . $e->getMessage());
    }
}
}
