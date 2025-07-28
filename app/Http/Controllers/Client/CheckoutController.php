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
use Illuminate\Support\Facades\Log;
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
        'province'        => 'required|string',
        'ward'            => 'required|string',
        'address'         => 'required|string',
    ]);

    $user = Auth::user();
    $variant = Variant::findOrFail($request->variant_id);

    if ($variant->quantity < $request->quantity) {
        return redirect()->back()->withErrors('Số lượng sản phẩm không đủ trong kho.');
    }

    $totalAmount = $variant->price * $request->quantity;
    $shippingFee = 30000;
    $grand_total = $totalAmount + $shippingFee;

    // COD
    if ($request->payment_method === 'cod') {
        DB::beginTransaction();
        try {
            $orderCode = $this->generateOrderCode();

            $order = Order::create([
                'user_id'        => $user->id_user,
                'order_code'     => $orderCode,
                'status'         => 'pending',
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'province'       => $request->province,
                'ward'           => $request->ward,
                'address'        => $request->address,
                'total_amount'   => $totalAmount,
                'grand_total'    => $grand_total,
                'created_at'     => now(),
            ]);

            OrderItem::create([
                'order_id'   => $order->id_order,
                'variant_id' => $variant->id_variant,
                'quantity'   => $request->quantity,
                'created_at' => now(),
            ]);

            $variant->decrement('quantity', $request->quantity);

            DB::commit();
            return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Lỗi xử lý đơn hàng: ' . $e->getMessage());
        }
    }

    //VNPay
    if ($request->payment_method === 'vnpay') {
        session([
            'pending_order_buy_now' => [
                'user_id'       => $user->id_user,
                'variant_id'    => $variant->id_variant,
                'quantity'      => $request->quantity,
                'province'      => $request->province,
                'ward'          => $request->ward,
                'address'       => $request->address,
                'total_amount'  => $totalAmount,
                'grand_total'   => $grand_total,
            ]
        ]);

        Log::info('🔄 [Buy Now] Lưu session pending_order_buy_now:', session('pending_order_buy_now'));

        return redirect()->route('payment.vnpay.buy_now');
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

        // $cartItems = CartItem::with('variant.product')
        //     ->where('cart_id', $cart->id_cart)
        //     ->get();
        $cartItems = CartItem::with([
        'variant' => function ($q) {
            $q->withTrashed()->with([
                'product' => function ($q2) {
                    $q2->withTrashed();
                },
                'size'
            ]);
        }
    ])->where('cart_id', $cart->id_cart)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->withErrors('Giỏ hàng trống.');
        }

        // Validate địa chỉ và phương thức thanh toán
        $request->validate([
            'payment_method' => 'required|in:cod,vnpay',
            'province'        => 'required|string',
            // 'district'        => 'required|string',
            'ward'            => 'required|string',
            'address'         => 'required|string',
        ]);
   if ($request->payment_method === 'cod') {
        DB::beginTransaction();

        try {
            $totalAmount = 0;
$grand_total =0;
             foreach ($cartItems as $item) {
                $variant = $item->variant;

                // Check sản phẩm bị xóa mềm
                if (!$variant || $variant->trashed() || !$variant->product || $variant->product->trashed()) {
                    throw new \Exception("Một sản phẩm trong giỏ hàng đã bị ngừng bán. Vui lòng xóa khỏi giỏ hàng để tiếp tục thanh toán.");
                }

                if ($variant->quantity < $item->quantity) {
                    throw new \Exception("Sản phẩm {$variant->product->name_product} không đủ hàng.");
                }

                $totalAmount += $variant->price * $item->quantity;
            }

$shippingFee = 30000;
           $grand_total =  $totalAmount +$shippingFee;
            $orderCode = $this->generateOrderCode();



         $order = Order::create([
            'user_id'        => $user->id_user,
            'order_code'     => $orderCode,
            'status'         => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'total_amount'   => $totalAmount,
            'province'       => $request->province,
            // 'district'       => $request->district,
            'ward'           => $request->ward,
            'address'        => $request->address,
            'grand_total'=> $grand_total,
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

            CartItem::where('cart_id', $cart->id_cart)->delete();

        DB::commit();
        // return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
                   if ($request->payment_method === 'cod') {
                    return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
                }

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors( $e->getMessage());
    }
}
// Nếu chọn VNPAY thì chuyển sang trang xác nhận OTP
    if ($request->payment_method === 'vnpay') {
        $totalAmount = 0;
         foreach ($cartItems as $item) {
            $variant = $item->variant;

            if (!$variant || $variant->trashed() || !$variant->product || $variant->product->trashed()) {
                return redirect()->route('cart')->withErrors("Một sản phẩm trong giỏ hàng đã bị xóa hoặc ngừng bán.");
            }

            if ($variant->quantity < $item->quantity) {
                return redirect()->route('cart')->withErrors("Sản phẩm {$variant->product->name_product} không đủ hàng.");
            }

            $totalAmount += $variant->price * $item->quantity;
        }

    $shippingFee = 30000;
    $grand_total = $totalAmount + $shippingFee;
        // Lưu thông tin đơn hàng tạm vào session hoặc truyền qua request
         session([
        'pending_order_cart' => [
            'user_id'       => $user->id_user,
            'cart_id'       => $cart->id_cart,
            'payment_method'=> $request->payment_method,
            'province'      => $request->province,
            // 'district'      => $request->district,
            'ward'          => $request->ward,
            'address'       => $request->address,
            'total_amount'  => $totalAmount,
            'grand_total'   => $grand_total,
        ]
    ]);

    // Chuyển hướng tới VNPay để thanh toán
    return redirect()->route('payment.vnpay');
    }
}

}
