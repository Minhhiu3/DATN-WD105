<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Variant;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderSuccessMail;
use App\Mail\OrderPlacedMail;

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
        if (!$variant || $variant->trashed() || !$variant->product || $variant->product->trashed()) {
            return redirect()->back()->withErrors('Sản phẩm đã bị xóa hoặc ngừng bán.');
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
        'email'           => 'required|email',
        'phone'           => 'required|string|max:11',
        'user_name'       => 'required|string',
        'ward'            => 'required|string',
        'address'         => 'required|string',
    ]);

    $user = Auth::user();
   $variant = Variant::with(['product', 'color', 'size'])->findOrFail($request->variant_id);
    if (!$variant || $variant->trashed() || !$variant->product || $variant->product->trashed()) {
        return redirect()->back()->withErrors('Sản phẩm đã bị xóa hoặc ngừng bán.');
    }

    if ($variant->quantity < $request->quantity) {
        return redirect()->back()->withErrors('Số lượng sản phẩm không đủ trong kho.');
    }

    $totalAmount = $variant->price * $request->quantity;
    $shippingFee = 30000;

    //  Lấy giảm giá từ session nếu có
    $discount = session('discount');
    $discountCode = $discount['code'] ?? null;
    if(isset($discount)){
        //  Tổng tiền sau giảm
        $finalTotal = max(0, $discount['final_total']);
        $grand_total = $discount['final_total'] + $shippingFee;

    }else{
        $finalTotal = $totalAmount;
        $grand_total = $totalAmount + $shippingFee;
    }

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
                'email'          => $request->email,
                'phone'          => $request->phone,
                'user_name'      => $request->user_name,
                'payment_status' => 'unpaid',
                'province'       => $request->province,
                'ward'           => $request->ward,
                'address'        => $request->address,
                'total_amount'   => $finalTotal,
                'grand_total'    => $grand_total,
                'created_at'     => now(),
            ]);

            // dd($order);
            // Gửi email thông báo đặt hàng thành công
            // Mail::to('vmink2004@gmail.com')->send(new OrderPlacedMail($order));
$emailSend = $request->email;
Mail::to($emailSend)->send(new OrderPlacedMail($order));
Log::info('📧 [Checkout] Gửi email đặt hàng thành công đến: ' . $emailSend);
            // Log::info('📧 [Checkout] Gửi email đặt hàng thành công đến: ' . $order->email);
            // Mail::to((string) $order->email)->send(new OrderPlacedMail($order));


            OrderItem::create([
                'order_id'   => $order->id_order,
                'variant_id' => $variant->id_variant,
                'quantity'   => $request->quantity,
                 'product_name' => $variant->product->name_product,
    'price'        => $variant->price,
    'color_name'   => $variant->color->name_color ?? null,
    'size_name'    => $variant->size->name ?? null,
    'image'        => $variant->color->image ?? null,
                'created_at' => now(),
            ]);

            $variant->decrement('quantity', $request->quantity);

            DB::commit();
            // Xóa session mã giảm giá sau khi đặt hàng thành công
            session()->forget('discount');
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
                'email'         => $request->email,
                'phone'         => $request->phone,
                'user_name'     => $request->user_name,
                'total_amount'  => $finalTotal,     // Tổng tiền sau giảm, chưa cộng phí ship
                'grand_total'   => $grand_total,    // Tổng tiền đã giảm + phí ship
                'discount_code' => $discountCode,
                 'product_name'  => $variant->product->name ?? '',
            'price'         => $variant->price ?? 0,
            'color_name'    => $variant->color->name_color ?? 'Không có màu',
            'size_name'     => $variant->size->name ?? 'Không có size',
            'image'         => $variant->color->image ?? 'khong-co-hinh-anh.jpg',
            ]
        ]);

        Log::info('🔄 [Buy Now] Lưu session pending_order_buy_now:', session('pending_order_buy_now'));
        // Xóa session mã giảm giá sau khi đặt hàng thành công
        session()->forget('discount');
        return redirect()->route('payment.vnpay.buy_now');
    }
}


    //mua từ giỏ hàng
    public function checkoutCart()
    {
        $user = Auth::user();

        // Lấy cart của user
        $cart = Cart::where('user_id', $user->id_user)->first();


        // Kiểm tra từng sản phẩm trong giỏ hàng
        if (!$cart) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng của bạn đang trống!');
        }



        // Lấy cart items kèm variant, product, size
        $cartItems = CartItem::with(['variant.product', 'variant.size'])
            ->where('cart_id', $cart->id_cart)
            ->get();
  foreach ($cartItems as $item) {
        if (!$item->variant) {
            return redirect()->route('cart')
                ->with('error', "Sản phẩm '{$item->variant->product->name_product}'\nMàu: {$item->variant->color->name_color} | Size: {$item->variant->size->name}\ntrong giỏ hàng đã bị xóa hoặc ngừng bán.\nVui lòng xóa khỏi giỏ hàng để tiếp tục thanh toán.");
        }
 if (
            !$item->variant || $item->variant->trashed() ||
            !$item->variant->product || $item->variant->product->trashed()
        ) {
            return redirect()->back()->with('error', "Sản phẩm '{$item->variant->product->name_product}'\nMàu: {$item->variant->color->name_color} | Size: {$item->variant->size->name}\ntrong giỏ hàng đã bị xóa hoặc ngừng bán.\nVui lòng xóa khỏi giỏ hàng để tiếp tục thanh toán.");
        }
        if ($item->quantity > $item->variant->quantity) {
            return redirect()->route('cart')
                ->with('error',
    "Sản phẩm '{$item->variant->product->name_product}'\nMàu: {$item->variant->color->name_color} | Size: {$item->variant->size->name}\nKhông đủ số lượng\nChỉ còn {$item->variant->quantity} sản phẩm.");
        }
    }
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
        foreach ($cartItems as $item) {
        if (
            !$item->variant || $item->variant->trashed() ||
            !$item->variant->product || $item->variant->product->trashed()
        ) {
            return redirect()->back()->with('error', "Sản phẩm '{$item->variant->product->name_product}'\nMàu: {$item->variant->color->name_color} | Size: {$item->variant->size->name}\ntrong giỏ hàng đã bị xóa hoặc ngừng bán.\nVui lòng xóa khỏi giỏ hàng để tiếp tục thanh toán.");
        }
        if ($item->variant->quantity < $item->quantity) {
            return redirect()->back()->with('error',
    "Sản phẩm '{$item->variant->product->name_product}'\nMàu: {$item->variant->color->name_color} | Size: {$item->variant->size->name}\nKhông đủ số lượng\nChỉ còn {$item->variant->quantity} sản phẩm.");
        }
    }

        // Validate địa chỉ và phương thức thanh toán
        $request->validate([
            'payment_method' => 'required|in:cod,vnpay',
            'province'        => 'required|string',
            'email'           => 'required|email',
            'phone'           => 'required|string|max:11',
            'user_name'       => 'required|string',
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
                    throw new \Exception("Sản phẩm {$item->variant->product->name_product}, màu:{$item->variant->color->name_color}, size:{$item->variant->size->name} trong giỏ hàng đã bị xóa hoặc ngừng bán. Vui lòng xóa khỏi giỏ hàng để tiếp tục thanh toán.");
                }

                if ($variant->quantity < $item->quantity) {
                    throw new \Exception("Sản phẩm {$item->variant->product->name_product}, màu:{$item->variant->color->name_color}, size:{$item->variant->size->name} không đủ hàng. Chỉ còn {$variant->quantity} sản phẩm");
                }

                $totalAmount += $variant->price * $item->quantity;
            }

            $shippingFee = 30000;
            $orderCode = $this->generateOrderCode();
            $discount = session('discount');
            $discountCode = $discount['code'] ?? null;
            if(isset($discount)){
                //  Tổng tiền sau giảm
                $finalTotal = max(0, $discount['final_total']);
                $grand_total = $discount['final_total'] + $shippingFee;

            }else{
                $finalTotal = $totalAmount;
                $grand_total = $totalAmount + $shippingFee;
            }

         $order = Order::create([
            'user_id'        => $user->id_user,
            'order_code'     => $orderCode,
            'status'         => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'total_amount'   => $finalTotal,
            'province'       => $request->province,
            'phone'          => $request->phone,
            'user_name'      => $request->user_name,
            // 'district'       => $request->district,
            'email'          => $request->email,
            'ward'           => $request->ward,
            'address'        => $request->address,
            'grand_total'    => $grand_total,
            'created_at'     => now(),
        ]);
$emailSend = $request->email;
Mail::to($emailSend)->send(new OrderPlacedMail($order));
Log::info('📧 [Checkout] Gửi email đặt hàng thành công đến: ' . $emailSend);
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id_order,
                    'variant_id' => $item->variant_id,
                    'quantity'   => $item->quantity,
                    'product_name' => $item->variant->product->name_product,
                    'price'        => $item->variant->price,
                    'color_name'   => $item->variant->color->name_color ?? null,
                    'size_name'    => $item->variant->size->name ?? null,
                    'image'        => $item->variant->color->image ?? null,


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
                return redirect()->route('cart')->withErrors("Sản phẩm {$variant->product->name_product},màu:{$variant->color->name_color},size:{$variant->size->name} trong giỏ hàng đã bị xóa hoặc ngừng bán. Vui lòng xóa khỏi giỏ hàng để tiếp tục thanh toán.");
            }

            if ($variant->quantity < $item->quantity) {
                return redirect()->route('cart')->withErrors("Sản phẩm {$variant->product->name_product},màu:{$variant->color->name_color},size:{$variant->size->name} không đủ hàng.");
            }

            $totalAmount += $variant->price * $item->quantity;
        }

            $shippingFee = 30000;
            $discount = session('discount');
            $discountCode = $discount['code'] ?? null;
            if(isset($discount)){
                //  Tổng tiền sau giảm
                $finalTotal = max(0, $discount['final_total']);
                $grand_total = $discount['final_total'] + $shippingFee;

            }else{
                $finalTotal = $totalAmount;
                $grand_total = $totalAmount + $shippingFee;
            }
        // Lưu thông tin đơn hàng tạm vào session hoặc truyền qua request
         session([
        'pending_order_cart' => [
            'user_id'       => $user->id_user,
            'cart_id'       => $cart->id_cart,
            'payment_method'=> $request->payment_method,
            'province'      => $request->province,
            // 'district'      => $request->district,
            'phone'         => $request->phone,
            'user_name'     => $request->user_name,
            'email'         => $request->email,
            'ward'          => $request->ward,
            'address'       => $request->address,
            'total_amount'  => $finalTotal,     // Tổng tiền sau giảm, chưa cộng phí ship
            'grand_total'   => $grand_total,    // Tổng tiền đã giảm + phí ship
            'discount_code' => $discountCode,

        ]
    ]);
    session()->forget('discount');
    // Chuyển hướng tới VNPay để thanh toán
    return redirect()->route('payment.vnpay');
    }
}
// áp mã giảm giá cho đơn hàng

    public function apply(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = DiscountCode::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã không hợp lệ']);
        }

        if (!now()->between($coupon->start_date, $coupon->end_date)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không còn hiệu lực']);
        }
        if ($coupon->is_active == '0') {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã bị vô hiệu hóa']);
        }



        // Tính tổng đơn
        $variant = Variant::find($request->variant_id);
        $subtotal = $variant->price * $request->quantity;
        if ($subtotal < $coupon->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng phải từ ' . number_format($coupon->min_order_value, 0, ',', '.') . 'đ mới được áp dụng mã giảm giá'
            ]);
        }

        $shippingFee = 30000;
        $type = (int) $coupon->type; // ép kiểu chắc chắn

        switch ($type) {
            case 0:
                $discount = $subtotal * ($coupon->value / 100);
                break;
            case 1:
                $discount = $coupon->value;
                break;
            default:
                $discount = 0;
                break;
        }
        if ($subtotal < $discount) {
            return response()->json([
                'success' => false,
                'message' => 'số tiền giảm quá lớn so với đơn hàng'
            ]);
        }
            $finalTotalShip = max(0, $subtotal - $discount + $shippingFee);
            // tiền chuyền session - tiền ship
            $finalTotal = max(0, $subtotal - $discount );




        session([
            'discount' => [
                'code' => $coupon->code,
                'amount' => $discount,
                'final_total' => $finalTotal
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => "Đã áp dụng mã giảm giá!",
            'discount' => $discount,
            'final_total' => $finalTotalShip
        ]);
    }
   public function applyCouponCart(Request $request)
{
    try {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $user = Auth::user();
        $cart = Cart::with('cartItems.variant')->where('user_id', $user->id_user)->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng trống']);
        }

        $coupon = DiscountCode::where('code', $request->coupon_code)->first();
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã không hợp lệ']);
        }

        if (!now()->between($coupon->start_date, $coupon->end_date)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không còn hiệu lực']);
        }
        if ($coupon->is_active == '0') {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã bị vô hiệu hóa']);
        }

        $subtotal = 0;
        foreach ($cart->cartItems as $item) {
            if ($item->variant) {
                $subtotal += $item->variant->price * $item->quantity;
            }
        }
        if ($subtotal < $coupon->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng phải từ ' . number_format($coupon->min_order_value, 0, ',', '.') . 'đ mới được áp dụng mã giảm giá'
            ]);
        }
        $shippingFee = 30000;
        $discount = 0;
        $type = (int) $coupon->type;

        switch ($type) {
            case 0: // phần trăm
                $discount = $subtotal * ($coupon->value / 100);
                break;
            case 1: // số tiền cố định
                $discount = $coupon->value;
                break;
            default:
                $discount = 0;
        }
        if ($subtotal < $discount) {
            return response()->json([
                'success' => false,
                'message' => 'số tiền giảm quá lớn so với đơn hàng'
            ]);
        }

            $finalTotalShip = max(0, $subtotal - $discount + $shippingFee);
            // tiền chuyền session - tiền ship
            $finalTotal = max(0, $subtotal - $discount );
        session([
            'discount' => [
                'code' => $coupon->code,
                'amount' => $discount,
                'final_total' => $finalTotal
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã áp dụng mã giảm giá!',
            'discount' => $discount,
            'final_total' => $finalTotalShip
        ]);
    } catch (\Exception $e) {
        Log::error('Lỗi áp mã giảm giá', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi áp mã giảm giá!',
            'error' => $e->getMessage()
        ], 500);
    }


}
}