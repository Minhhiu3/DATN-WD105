<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderPlacedMail;
use App\Models\AdviceProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function vnpay_payment(Request $request)
{
    $orderData = session('pending_order_cart');

    if (!$orderData) {
        return redirect()->route('cart')->with('error', 'Không tìm thấy dữ liệu đơn hàng.');
    }

    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = route('vnpay.return');
    $vnp_TmnCode = "EXCCT61K";
    $vnp_HashSecret = "CP0XHLTRZJQRZGUCBI3XPP3C5HQDNB7E";

    $orderCode = 'ORDER' . time(); // hoặc UUID
    session(['pending_order_cart.order_code' => $orderCode]);

    $total = $orderData['grand_total'];
    $vnp_TxnRef = $orderCode;
    $vnp_OrderInfo = "Thanh toán đơn hàng #" . $orderCode;
    $vnp_Amount = $total * 100;

    $inputData = [
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $request->ip(),
        "vnp_Locale" => "vn",
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => "billpayment",
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
    ];

    ksort($inputData);
    $query = '';
    $hashdata = '';
    foreach ($inputData as $key => $value) {
        $hashdata .= urlencode($key) . "=" . urlencode($value) . '&';
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_SecureHash = hash_hmac('sha512', rtrim($hashdata, '&'), $vnp_HashSecret);
    $vnp_Url .= '?' . $query . 'vnp_SecureHash=' . $vnp_SecureHash;

    return redirect($vnp_Url);
}

public function vnpayReturn(Request $request)
{
    $vnp_ResponseCode = $request->get('vnp_ResponseCode');
    $orderCode = $request->get('vnp_TxnRef');

    Log::info('VNPAY RETURN', [
        'vnp_ResponseCode' => $vnp_ResponseCode,
        'vnp_TxnRef' => $orderCode
    ]);

    $orderData = session('pending_order_cart');

    if (!$orderData || $orderData['order_code'] !== $orderCode) {
        return redirect()->route('cart')->with('error', 'Thông tin đơn hàng không hợp lệ hoặc hết hạn.');
    }

    if ($vnp_ResponseCode == '00') {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id'        => $orderData['user_id'],
                'order_code'     => $orderCode,
                'status'         => 'pending',
                'payment_method' => 'vnpay',
                'payment_status' => 'paid',
                'email'          => $orderData['email'],
                'phone'          => $orderData['phone'],
                'user_name'      => $orderData['user_name'],
                'total_amount'   => $orderData['total_amount'],
                'grand_total'    => $orderData['grand_total'],
                'province'       => $orderData['province'],
                // 'district'       => $orderData['district'],
                'ward'           => $orderData['ward'],
                'address'        => $orderData['address'],
                'created_at'     => now(),
            ]);

            $cartItems = CartItem::with('variant.product')
                ->where('cart_id', $orderData['cart_id'])
                ->get();

            foreach ($cartItems as $item) {
                  $variant = $item->variant;
                  $adviceProduct = AdviceProduct::where('product_id', $variant->product_id)
            ->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->first();
              if ($adviceProduct && $adviceProduct->status == "on" ) {
            $valueSale = $adviceProduct->value;
            $pricevariantSale = $variant->price * (1- ($valueSale/100));


        }else {
            $pricevariantSale = $variant->price;

        }
              OrderItem::create([
                    'order_id'   => $order->id_order,
                    'variant_id' => $item->variant_id,
                    'quantity'   => $item->quantity,
                    'product_name' => $item->variant->product->name_product,
                    'price'      =>  $pricevariantSale,
                    'color_name' => $item->variant->color->name_color ?? 'Không có màu',
                    'size_name'  => $item->variant->size->name ?? 'Không có size',
                    'image'      => $item->variant->color->image ?? 'khong-co-hinh-anh.jpg',
                    'created_at' => now(),
                ]);

                $item->variant->decrement('quantity', $item->quantity);
            }

           CartItem::where('cart_id', $orderData['cart_id'])->delete();
            session()->forget('pending_order_cart');

 DB::commit();
session()->forget('pending_order_cart');

// Gửi email xác nhận
$emailSend = $orderData['email'] ?? $pending['email'];
Mail::to($emailSend)->send(new OrderPlacedMail($order));


return redirect()->route('home')->with('success', 'Thanh toán thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi tạo đơn hàng sau thanh toán: ' . $e->getMessage());
            return redirect()->route('cart')->with('error', 'Đã thanh toán thành công nhưng xảy ra lỗi khi tạo đơn hàng.');
        }
    }

    return redirect()->route('cart')->with('error', 'Thanh toán thất bại hoặc bị hủy.');
}
public function paymentVnpayBuyNow()
{
    $pending = session('pending_order_buy_now');
    if (!$pending) {
        return redirect()->route('home')->withErrors('Không có đơn hàng mua ngay để thanh toán.');
    }

    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = route('vnpay.return.buy_now');
    $vnp_TmnCode = "EXCCT61K";
    $vnp_HashSecret = "CP0XHLTRZJQRZGUCBI3XPP3C5HQDNB7E";
$orderCode = 'ORDER' . time();
    $vnp_TxnRef = $orderCode;
    $vnp_OrderInfo = "Thanh toán mua ngay";
    $vnp_Amount = $pending['grand_total'] * 100;
    $vnp_Locale = "vn";
    $vnp_IpAddr = request()->ip();
$pending['order_code'] = $orderCode;
session(['pending_order_buy_now' => $pending]);
    $inputData = [
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => "billpayment",
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
    ];

     ksort($inputData);
    $query = '';
    $hashdata = '';
    foreach ($inputData as $key => $value) {
        $hashdata .= urlencode($key) . "=" . urlencode($value) . '&';
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_SecureHash = hash_hmac('sha512', rtrim($hashdata, '&'), $vnp_HashSecret);
    $vnp_Url .= '?' . $query . 'vnp_SecureHash=' . $vnp_SecureHash;

    return redirect()->away($vnp_Url);
}

public function vnpayReturnBuyNow(Request $request)
{
        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
    $orderCode = $request->get('vnp_TxnRef');

    Log::info('VNPAY RETURN', [
        'vnp_ResponseCode' => $vnp_ResponseCode,
        'vnp_TxnRef' => $orderCode
    ]);
    $pending = session('pending_order_buy_now');

    if (!$pending || !isset($pending['order_code']) || $pending['order_code'] !== $orderCode) {
        return redirect()->route('home')->with('error','Thông tin đơn hàng mua ngay không hợp lệ hoặc hết hạn.');
    }
    if (!$pending) {
        return redirect()->route('home')->with('error','Không tìm thấy thông tin đơn hàng mua ngay.');
    }

    if ($request->vnp_ResponseCode == '00') {
        DB::beginTransaction();
        try {
            // $orderCode = $this->generateOrderCode();

            $order = Order::create([
                'user_id'        => $pending['user_id'],
                'order_code'     => $orderCode,
                'status'         => 'pending',
                'payment_method' => 'vnpay',
                'payment_status' => 'paid',
                'email'          => $pending['email'],
                'user_name'      => $pending['user_name'],
                'phone'          => $pending['phone'],
                'province'       => $pending['province'],
                'ward'           => $pending['ward'],
                'address'        => $pending['address'],
                'total_amount'   => $pending['total_amount'],
                'grand_total'    => $pending['grand_total'],
                'created_at'     => now(),
            ]);

            OrderItem::create([
                'order_id'   => $order->id_order,
                'variant_id' => $pending['variant_id'],
                'quantity'   => $pending['quantity'],
                'product_name' => $pending['product_name'],
                'price'      => $pending['price'],
                'color_name' => $pending['color_name'] ?? 'Không có màu',
                'size_name'  => $pending['size_name'] ?? 'Không có size',
                'image'      => $pending['image'] ?? 'khong-co-hinh-anh.jpg',
                'created_at' => now(),
            ]);

            Variant::where('id_variant', $pending['variant_id'])
                   ->decrement('quantity', $pending['quantity']);

            DB::commit();
            // dd($order);
            session()->forget('pending_order_buy_now');
            // Gửi mail
$emailSend = $pending['email'];
Mail::to($emailSend)->send(new OrderPlacedMail($order));
Log::info('📧 [Checkout] Gửi email đặt hàng thành công đến: ' . $emailSend);
Log::info('VNPay Return All', $request->all());
// Log::info('Session Pending', session('pending_order_buy_now'));
            return redirect()->route('home')->with('success', 'Thanh toán thành công');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('home')->withErrors('Đã thanh toán nhưng lỗi tạo đơn hàng.');
        }
    }

    return redirect()->route('home')->withErrors('Thanh toán thất bại hoặc bị hủy.');
}




}
