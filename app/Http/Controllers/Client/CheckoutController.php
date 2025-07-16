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
            'district'        => 'required|string',
            'ward'            => 'required|string',
            'address'         => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $variant = Variant::findOrFail($request->variant_id);

            if ($variant->quantity < $request->quantity) {
                return redirect()->back()->withErrors('Số lượng sản phẩm không đủ trong kho.');
            }

            $orderCode = $this->generateOrderCode();
            $order = Order::create([
                'user_id'        => $user->id_user,
                'order_code'     => $orderCode,
                'status'         => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'province'       => $request->province,
                'district'       => $request->district,
                'ward'           => $request->ward,
                'address'        => $request->address,
                'total_amount'   => $variant->price * $request->quantity,
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
            DB::rollback();
            return redirect()->back()->withErrors('Lỗi xử lý đơn hàng: ' . $e->getMessage());
        }
    }

    public function checkoutCart()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id_user)->first();

        if (!$cart) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $cartItems = CartItem::with(['variant.product', 'variant.size'])
            ->where('cart_id', $cart->id_cart)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        return view('client.pages.checkout_cart', compact('cartItems'));
    }
//tt onl
    public function handleVNPayCheckout(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id_user)->first();

        if (!$cart) return response()->json(['error' => 'Giỏ hàng trống.'], 400);

        $cartItems = CartItem::with('variant.product')->where('cart_id', $cart->id_cart)->get();
        if ($cartItems->isEmpty()) return response()->json(['error' => 'Giỏ hàng trống.'], 400);

        $total = 0;
        foreach ($cartItems as $item) {
            $variant = $item->variant;
            if (!$variant || $variant->quantity < $item->quantity) {
                return response()->json(['error' => 'Sản phẩm không tồn tại hoặc không đủ hàng.'], 400);
            }
            $total += $variant->price * $item->quantity;
        }

        session([
            'vnp_address' => [
                'province' => $request->province,
                'district' => $request->district,
                'ward'     => $request->ward,
                'address'  => $request->address,
            ]
        ]);

        $vnp_TmnCode = config('vnpay.tmncode');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_Returnurl = config('vnpay.return_url');

        $vnp_TxnRef = $this->generateOrderCode();
        $vnp_Amount = $total * 100;
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toán đơn hàng giỏ hàng",
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $hashData = '';
        foreach ($inputData as $key => $value) {
            $hashData .= $key . '=' . $value . '&';
        }
        $hashData = rtrim($hashData, '&');

        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $redirectUrl = $vnp_Url . '?' . http_build_query($inputData) . '&vnp_SecureHash=' . $vnp_SecureHash;
// dd($redirectUrl)
        return response()->json(['redirect_url' => $redirectUrl]);
//         dd([
//     'hashData' => $hashData,
//     'secureHash' => $vnp_SecureHash,
//     'redirectUrl' => $redirectUrl
// ]);

    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');
        $inputData = $request->except('vnp_SecureHash', 'vnp_SecureHashType');

        ksort($inputData);
        $hashData = '';
        foreach ($inputData as $key => $value) {
            $hashData .= $key . '=' . $value . '&';
        }
        $hashData = rtrim($hashData, '&');

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $request->vnp_SecureHash && $request->vnp_ResponseCode == '00') {
            return $this->placeOrderFromCartAfterPayment('vnpay');
        }

        return redirect()->route('cart')->with('error', 'Thanh toán thất bại hoặc bị hủy.');
    }

    private function placeOrderFromCartAfterPayment($method = 'vnpay')
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id_user)->first();
        if (!$cart) return redirect()->route('cart')->withErrors('Giỏ hàng trống.');

        $cartItems = CartItem::with('variant.product')->where('cart_id', $cart->id_cart)->get();
        if ($cartItems->isEmpty()) return redirect()->route('cart')->withErrors('Giỏ hàng trống.');

        $address = session('vnp_address');

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($cartItems as $item) {
                $variant = $item->variant;
                if (!$variant || $variant->quantity < $item->quantity) {
                    throw new \Exception("Sản phẩm {$variant->product->name_product} không đủ hàng.");
                }
                $total += $variant->price * $item->quantity;
            }

            $order = Order::create([
                'user_id'        => $user->id_user,
                'order_code'     => $this->generateOrderCode(),
                'status'         => 'pending',
                'payment_method' => $method,
                'payment_status' => 'paid',
                'province'       => $address['province'] ?? '',
                'district'       => $address['district'] ?? '',
                'ward'           => $address['ward'] ?? '',
                'address'        => $address['address'] ?? '',
                'total_amount'   => $total,
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
            session()->forget('vnp_address');

            return redirect()->route('home')->with('success', 'Thanh toán VNPay thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart')->withErrors('Lỗi: ' . $e->getMessage());
        }
    }
}
