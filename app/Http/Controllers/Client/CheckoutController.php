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
use App\Models\UserVoucher;
use App\Models\AdviceProduct;
use Carbon\Carbon;
class CheckoutController extends Controller
{
    // Hiển thị form thanh toán
    public function showCheckoutForm(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variant,id_variant',
            'quantity'   => 'required|integer|min:1',
        ], [
            'variant_id.exists' => 'Sản phẩm không tồn tại hoặc đã bị xóa.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
        ]);


        $variant = Variant::with(['product', 'size','adviceProduct'])->findOrFail($request->variant_id);

        $quantity = $request->quantity;

        if ($variant->quantity < $quantity) {
            return redirect()->back()->withErrors('Số lượng sản phẩm không đủ trong kho.');
        }
        if (!$variant || $variant->trashed() || !$variant->product || $variant->product->trashed()) {
            return redirect()->back()->withErrors('Sản phẩm đã bị xóa hoặc ngừng bán.');
        }


        $user = Auth::user();

        // Lấy voucher của user (có thể join lấy thêm info voucher)
        // Giả sử user_vouchers có discount_id, liên kết với bảng discount_codes
        $userVouchers = UserVoucher::where('user_id', $user->id_user)
            ->where('used', '0')
            ->with('discount') // Quan hệ discountCode trong model UserVoucher
            ->get();
        return view('client.pages.checkout', compact('variant', 'quantity','userVouchers'));    }

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
        'terms'           => 'accepted', // Kiểm tra đã đồng ý với chính sách mua hàng
    ], [
        'terms.accepted' => 'Bạn cần đồng ý với chính sách mua hàng.',
        'variant_id.exists' => 'Sản phẩm không tồn tại hoặc đã bị xóa.',
        'quantity.min' => 'Số lượng phải lớn hơn 0.',
        'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
        'province.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
        'ward.required' => 'Vui lòng chọn Xã/Phường.',
        'address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
        'email.required' => 'Vui lòng nhập email.',
        'phone.required' => 'Vui lòng nhập số điện thoại.',
        'user_name.required' => 'Vui lòng nhập họ tên.',
    ]);

    $user = Auth::user();
        try {
            DB::beginTransaction();
            // Khóa dòng variant để tránh race condition
            $variant = Variant::where('id_variant', $request->variant_id)->lockForUpdate()->first();

            if (!$variant || $variant->trashed() || !$variant->product || $variant->product->trashed()) {
                DB::rollBack();
                return redirect()->back()->withErrors('Sản phẩm đã bị xóa hoặc ngừng bán.');
            }

            if ($variant->quantity < $request->quantity) {
                DB::rollBack();
                return redirect()->back()->withErrors('Số lượng sản phẩm không đủ trong kho.');
            }
               $variant = Variant::find($request->variant_id);
        $adviceProduct = AdviceProduct::where('product_id', $variant->product_id)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->first();
        if ($adviceProduct && $adviceProduct->status == "on" ) {
            $pricevariantSale = $variant->price * ($adviceProduct->value/100);
            $subtotal = ($variant->price - $pricevariantSale ) * $request->quantity;

        }else {
            $subtotal = $variant->price * $request->quantity;

        }

            $totalAmount = $subtotal;
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
                     if($discount){
                        $discountId = $discount['discountId'] ?? null;

                        $userVoucher = UserVoucher::where('user_id', $user->id_user)
                            ->where('discount_id', $discountId)
                            ->first();

                        if ($userVoucher && $userVoucher->used == "0" ) {
                            UserVoucher::where('user_id', Auth::id())
                                ->where('discount_id', $discountId)
                                ->update([
                                    'used' => '1',
                                    'used_at' => now(),
                                ]);
                        DiscountCode::where('discount_id', $discountId)->decrement('quantity', 1);

                        }else{
                            UserVoucher::create([
                                'user_id'    => Auth::id(),
                                'discount_id'=> $discountId,
                                'used'       => 1,
                                'used_at'    => now(),
                            ]);
                            DiscountCode::where('discount_id', $discountId)->decrement('quantity', 1);
                        }
                    }

                    DB::commit();

                    // dd($userVoucher);
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
                if($discount){
                    $discountId = $discount['discountId'] ?? null;

                    $userVoucher = UserVoucher::where('user_id', $user->id_user)
                        ->where('discount_id', $discountId)
                        ->first();

                    if ($userVoucher && $userVoucher->used == "0" ) {
                        UserVoucher::where('user_id', Auth::id())
                            ->where('discount_id', $discountId)
                            ->update([
                                'used' => '1',
                                'used_at' => now(),
                            ]);
                        DiscountCode::where('discount_id', $discountId)->decrement('quantity', 1);
                    }else{
                        UserVoucher::create([
                            'user_id'    => Auth::id(),
                            'discount_id'=> $discountId,
                            'used'       => 1,
                            'used_at'    => now(),
                        ]);
                        DiscountCode::where('discount_id', $discountId)->decrement('quantity', 1);
                    }
                }

                DB::commit();
                Log::info('🔄 [Buy Now] Lưu session pending_order_buy_now:', session('pending_order_buy_now'));
                // Xóa session mã giảm giá sau khi đặt hàng thành công
                session()->forget('discount');
                return redirect()->route('payment.vnpay.buy_now');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Lỗi xử lý đơn hàng: ' . $e->getMessage());
        }

    //mua từ giỏ hàng
}

public function checkoutCart(Request $request)
{
    $user = Auth::user();

    // Debug 1: Log toàn bộ request input
    Log::info('CheckoutCart - Full Request Input:', ['request' => $request->all()]);

    // Lấy selected_variants từ request
    $selectedVariants = json_decode($request->input('selected_variants'), true);

    // Debug 2: Log $selectedVariants
    Log::info('CheckoutCart - Selected Variants:', ['selected_variants' => $selectedVariants]);

    if (empty($selectedVariants)) {
        Log::warning('CheckoutCart - No selected variants');
        return redirect()->route('cart')->with('error', 'Số lượng không đủ để thực hiện thanh toán.');
    }

    // Lấy cart của user
    $cart = Cart::where('user_id', $user->id_user)->first();

    if (!$cart) {
        Log::warning('CheckoutCart - Cart not found', ['user_id' => $user->id_user]);
        return redirect()->route('cart')->with('error', 'Giỏ hàng không tồn tại.');
    }

    // Debug 3: Log toàn bộ cart items trước khi lọc
    $allCartItems = CartItem::where('cart_id', $cart->id_cart)->get();
    Log::info('CheckoutCart - All Cart Items Before Filtering:', ['all_cart_items' => $allCartItems->toArray()]);

    // Bật log SQL
    DB::enableQueryLog();

    // Ép kiểu selected_variants thành số nguyên
    $selectedVariants = array_map('intval', $selectedVariants);

    // Lấy cart items kèm variant, product, size, chỉ lọc theo selected_variants
    $cartItemz = CartItem::with(['variant.product', 'variant.size', 'variant.color', 'variant.adviceProduct'])
        ->where('cart_id', $cart->id_cart)
        ->whereIn('variant_id', $selectedVariants)
        ->get();

    // Debug 4: Log SQL query và $cartItems
    Log::info('CheckoutCart - SQL Query:', DB::getQueryLog());
    Log::info('CheckoutCart - Filtered Cart Items:', ['cart_items' => $cartItemz->toArray()]);

    // Debug 5: Kiểm tra số lượng sản phẩm và variant_id
    $cartItemVariantIds = $cartItemz->pluck('variant_id')->toArray();
    Log::info('CheckoutCart - Cart Item Variant IDs:', ['variant_ids' => $cartItemVariantIds]);
    if ($cartItemVariantIds != $selectedVariants) {
        Log::warning('CheckoutCart - Mismatch between selected variants and cart items', [
            'selected_variants' => $selectedVariants,
            'cart_item_variant_ids' => $cartItemVariantIds
        ]);
    }

    if ($cartItemz->isEmpty()) {
        Log::warning('CheckoutCart - Empty cart items after filtering', ['selected_variants' => $selectedVariants]);
        return redirect()->route('cart')->with('error', 'Không tìm thấy sản phẩm được chọn trong giỏ hàng.');
    }

    // Kiểm tra từng sản phẩm được chọn
    foreach ($cartItemz as $item) {
        if (!$item->variant || $item->variant->trashed() || !$item->variant->product || $item->variant->product->trashed()) {
            Log::error('CheckoutCart - Invalid product', [
                'variant_id' => $item->variant_id,
                'product_name' => $item->variant->product->name_product ?? 'N/A'
            ]);
            return redirect()->route('cart')->with('error', "Sản phẩm '{$item->variant->product->name_product}' đã bị xóa hoặc ngừng bán.");
        }
        if ($item->quantity > $item->variant->quantity) {
            Log::error('CheckoutCart - Insufficient quantity', [
                'variant_id' => $item->variant_id,
                'requested' => $item->quantity,
                'available' => $item->variant->quantity
            ]);
            return redirect()->route('cart')->with('error', "Sản phẩm '{$item->variant->product->name_product}' không đủ số lượng. Chỉ còn {$item->variant->quantity} sản phẩm.");
        }
    }
//  dd($selectedVariants, $cartItemz);
            $userVouchers = UserVoucher::where('user_id', $user->id_user)
            ->where('used', '0')
            ->with('discount') // Quan hệ discountCode trong model UserVoucher
            ->get();
    // Truyền cartItems và selectedVariants sang view

return view('client.pages.checkout_cart', compact(
    'cartItemz',
    'selectedVariants',
    'userVouchers'
));
}


    public function placeOrderFromCart(Request $request)
    {
         $request->validate([
        'terms' => 'accepted',
    ], [
        'terms.accepted' => 'Bạn cần đồng ý với chính sách mua hàng.',
    ]);
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id_user)->first();

        if (!$cart) {
            return redirect()->route('cart')->withErrors('Giỏ hàng trống.');
        }

        // Lấy selected_variants từ request (từ hidden input trong form thanh toán)
        $selectedVariants = json_decode($request->input('selected_variants'), true);

        if (empty($selectedVariants)) {
            return redirect()->route('cart')->withErrors('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }

        // Lấy cart items kèm variant, product, size, chỉ lọc theo selected_variants
        $cartItems = CartItem::with([
            'variant' => function ($q) {
                $q->withTrashed()->with([
                    'product' => function ($q2) {
                        $q2->withTrashed();
                    },
                    'size',
                    'color'
                ]);
            }
        ])->where('cart_id', $cart->id_cart)
          ->whereIn('variant_id', $selectedVariants)
          ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->withErrors('Không tìm thấy sản phẩm được chọn trong giỏ hàng.');
        }

        // Kiểm tra từng sản phẩm được chọn
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
        // 'terms'           => 'accepted', // Kiem cha bam check box chua 
        ],[
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
            'province.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'ward.required' => 'Vui lòng chọn Xã/Phường.',
            'address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'email.required' => 'Vui lòng nhập email.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'user_name.required' => 'Vui lòng nhập họ tên.',
            // 'terms.accepted' => 'Bạn cần đồng ý với chính sách mua hàng.',
        ]);
   if ($request->payment_method === 'cod') {
        DB::beginTransaction();

        try {

            $totalAmount = 0;
            $grand_total =0;
            // $variant = Variant::find($request->variant_id);

             foreach ($cartItems as $item) {
                $variant = $item->variant;

                // Check sản phẩm bị xóa mềm
                if (!$variant || $variant->trashed() || !$variant->product || $variant->product->trashed()) {
                    throw new \Exception("Sản phẩm {$item->variant->product->name_product}, màu:{$item->variant->color->name_color}, size:{$item->variant->size->name} trong giỏ hàng đã bị xóa hoặc ngừng bán. Vui lòng xóa khỏi giỏ hàng để tiếp tục thanh toán.");
                }

                if ($variant->quantity < $item->quantity) {
                    throw new \Exception("Sản phẩm {$item->variant->product->name_product}, màu:{$item->variant->color->name_color}, size:{$item->variant->size->name} không đủ hàng. Chỉ còn {$variant->quantity} sản phẩm");
                }
 $adviceProduct = AdviceProduct::where('product_id', $variant->product_id)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->first();
        if ($adviceProduct && $adviceProduct->status == "on" ) {
            $valueSale = $adviceProduct->value;
            $pricevariantSale = $variant->price * (1- ($valueSale/100));
            $subtotal = $pricevariantSale * $item->quantity;

        }else {
            $subtotal = $variant->price * $item->quantity;

        }
                $totalAmount +=  $subtotal;
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

            // Chỉ xóa các sản phẩm được chọn khỏi giỏ hàng
            CartItem::where('cart_id', $cart->id_cart)->whereIn('variant_id', $selectedVariants)->delete();
            if($discount){
                $discountId = $discount['discountId'] ?? null;

                $userVoucher = UserVoucher::where('user_id', $user->id_user)
                    ->where('discount_id', $discountId)
                    ->first();

                if ($userVoucher && $userVoucher->used == '0' ) {
                    UserVoucher::where('user_id', Auth::id())
                        ->where('discount_id', $discountId)
                        ->update([
                            'used' => '1',
                            'used_at' => now(),
                        ]);
                    DiscountCode::where('discount_id', $discountId)->decrement('quantity', 1);


                }else{
                    UserVoucher::create([
                        'user_id'    => Auth::id(),
                        'discount_id'=> $discountId,
                        'used'       => 1,
                        'used_at'    => now(),
                    ]);
                    DiscountCode::where('discount_id', $discountId)->decrement('quantity', 1);

                }
            }

        DB::commit();
        // return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
                   if ($request->payment_method === 'cod') {
                        session()->forget('discount');

                    return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
                }

    } catch (\Exception $e) {
        DB::rollBack();
        // return redirect()->back()->withErrors( $e->getMessage());
        return redirect()->back()->withErrors('Lỗi xử lý đơn hàng: ' . $e->getMessage());
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
              $adviceProduct = AdviceProduct::where('product_id', $variant->product_id)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->first();
              if ($adviceProduct && $adviceProduct->status == "on" ) {
            $valueSale = $adviceProduct->value;
            $pricevariantSale = $variant->price * (1- ($valueSale/100));
            $subtotal = $pricevariantSale * $item->quantity;

        }else {
            $subtotal = $variant->price * $item->quantity;

        }

            $totalAmount += $subtotal;
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
        // Lưu thông tin đơn hàng tạm vào session session hoặc truyền qua request
         session([
        'pending_order_cart' => [
            'user_id'           => $user->id_user,
            'cart_id'           => $cart->id_cart,
            'selected_variants' => $selectedVariants,  // Lưu selected_variants để sau này lọc khi xử lý VNPay callback
            'payment_method'    => $request->payment_method,
            'province'          => $request->province,
            // 'district'      => $request->district,
            'phone'             => $request->phone,
            'user_name'         => $request->user_name,
            'email'             => $request->email,
            'ward'              => $request->ward,
            'address'           => $request->address,
            'total_amount'      => $finalTotal,     // Tổng tiền sau giảm, chưa cộng phí ship
            'grand_total'       => $grand_total,    // Tổng tiền đã giảm + phí ship
            'discount_code'     => $discountCode,

        ]
    ]);
    if($discount){
                $discountId = $discount['discountId'] ?? null;

                $userVoucher = UserVoucher::where('user_id', $user->id_user)
                    ->where('discount_id', $discountId)
                    ->first();

                if ($userVoucher && $userVoucher->used == '0' ) {
                    UserVoucher::where('user_id', Auth::id())
                        ->where('discount_id', $discountId)
                        ->update([
                            'used' => '1',
                            'used_at' => now(),
                        ]);
                    DiscountCode::where('discount_id', $discountId)->decrement('quantity', 1);
                }else{
                    UserVoucher::create([
                        'user_id'    => Auth::id(),
                        'discount_id'=> $discountId,
                        'used'       => 1,
                        'used_at'    => now(),
                    ]);
                    DiscountCode::where('discount_id', $discountId)->decrement('quantity', 1);
                }
            }

        DB::commit();
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
        $user = Auth::user();
        $coupon = DiscountCode::where('code', $request->coupon_code)->first();
        $userVoucher = UserVoucher::where('user_id', $user->id_user)
            ->where('discount_id', $coupon->discount_id)
            ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã không hợp lệ']);
        }
        if ($userVoucher && $userVoucher->used == '1') {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá bạn đã dùng rồi !']);
        }

        if (!now()->between($coupon->start_date, $coupon->end_date)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không còn hiệu lực']);
        }
        if ($coupon->is_active == '0') {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã bị vô hiệu hóa']);
        }



        // Tính tổng đơn
        $variant = Variant::find($request->variant_id);
        $adviceProduct = AdviceProduct::where('product_id', $variant->product_id)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->first();
        if ($adviceProduct && $adviceProduct->status == "on" ) {
            $pricevariantSale = $variant->price * ($adviceProduct->value/100);
            $subtotal = ($variant->price - $pricevariantSale ) * $request->quantity;

        }else {
            $subtotal = $variant->price * $request->quantity;

        }
        if ($subtotal < $coupon->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng phải từ ' . number_format($coupon->min_order_value, 0, ',', '.') . 'đ mới được áp dụng mã giảm giá'
            ]);
        }
        if ($subtotal > $coupon->max_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng phải dưới ' . number_format($coupon->max_order_value, 0, ',', '.') . 'đ mới được áp dụng mã giảm giá'
            ]);
        }
        if ($coupon->quantity == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng mã giảm giá có giới hạn.'
            ]);
        }
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
$shippingFee = ($adviceProduct && $adviceProduct->status == "on") ? 30000 : 30000;

$finalTotalShip = max(0, $subtotal - $discount) + $shippingFee;
            // tiền chuyền session - tiền ship
            $finalTotal = max(0, $subtotal - $discount );




        session([
            'discount' => [
                'code' => $coupon->code,
                'amount' => $discount,
                'final_total' => $finalTotal,
                'discountId' =>  $coupon->discount_id
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => "🎉 Đã áp dụng mã giảm giá!",
            'discount' => (int) round($discount),
            'final_total' => (int) round($finalTotalShip)
        ]);
    }
   public function applyCouponCart(Request $request)
{
    try {
        $request->validate([
            'coupon_code' => 'required|string',
            'selected_variants' => 'json',  // Expect selected_variants as JSON string
        ]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id_user)->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại']);
        }

        $selectedVariants = json_decode($request->selected_variants, true);
// Log::info('selected_variants', [$request->selected_variants]);

        if (empty($selectedVariants)) {
            return response()->json(['success' => false, 'message' => 'Vui lòng chọn sản phẩm để áp dụng mã giảm giá']);
        }

        // Filter cart items based on selected_variants
        $cartItems = CartItem::with('variant')
            ->where('cart_id', $cart->id_cart)
            ->whereIn('variant_id', $selectedVariants)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm được chọn']);
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
        foreach ($cartItems as $item) {
            if ($item->variant) {
                $variant = $item->variant; // đã load sẵn, không cần find()

                $adviceProduct = AdviceProduct::where('product_id', $variant->product_id)
                    ->whereDate('start_date', '<=', Carbon::today())
                    ->whereDate('end_date', '>=', Carbon::today())
                    ->first();

                if ($adviceProduct && $adviceProduct->status == "on") {
                    $discountAmount = $item->variant->price * ($adviceProduct->value / 100);
                    $price = $item->variant->price - $discountAmount;
                } else {
                    $price = $item->variant->price;
                }

                $subtotal += $price * $item->quantity;
                $shippingFee = ($adviceProduct && $adviceProduct->status == "on") ? 30000 : 30000;

            }
        }
        if ($subtotal < $coupon->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng phải từ ' . number_format($coupon->min_order_value, 0, ',', '.') . 'đ mới được áp dụng mã giảm giá'
            ]);
        }
        if ($coupon->quantity = 0) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng mã giảm giá có giới hạn.'
            ]);
        }
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
                'final_total' => $finalTotal,
                'discountId' =>  $coupon->discount_id
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã áp dụng mã giảm giá!',
            'discount' => (int) round($discount),
            'final_total' => (int) round($finalTotalShip)
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