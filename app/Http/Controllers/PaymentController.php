<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                'total_amount'   => $orderData['total_amount'],
                'grand_total'    => $orderData['grand_total'],
                'province'       => $orderData['province'],
                'district'       => $orderData['district'],
                'ward'           => $orderData['ward'],
                'address'        => $orderData['address'],
                'created_at'     => now(),
            ]);

            $cartItems = CartItem::with('variant.product')
                ->where('cart_id', $orderData['cart_id'])
                ->get();

            foreach ($cartItems as $item) {
              OrderItem::create([
                    'order_id'   => $order->id_order,
                    'variant_id' => $item->variant_id,
                    'quantity'   => $item->quantity,
                    'created_at' => now(),
                ]);

                $item->variant->decrement('quantity', $item->quantity);
            }

           CartItem::where('cart_id', $orderData['cart_id'])->delete();
            session()->forget('pending_order_cart');

            DB::commit();
            return redirect()->route('home')->with('success', 'Thanh toán thành công, đơn hàng đã được tạo!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi tạo đơn hàng sau thanh toán: ' . $e->getMessage());
            return redirect()->route('cart')->with('error', 'Đã thanh toán thành công nhưng xảy ra lỗi khi tạo đơn hàng.');
        }
    }

    return redirect()->route('cart')->with('error', 'Thanh toán thất bại hoặc bị hủy.');
}


}
