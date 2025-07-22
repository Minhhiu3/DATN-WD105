<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    //
    public function vnpay_payment(Order $order)
{
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = route('vnpay.return');
    $vnp_TmnCode = "EXCCT61K";
    $vnp_HashSecret = "CP0XHLTRZJQRZGUCBI3XPP3C5HQDNB7E";

   $vnp_TxnRef = $order->order_code;
    $vnp_OrderInfo = "Thanh toán đơn hàng " . $order->order_code . ", tổng tiền: " . $order->total_amount . " VND" . ", đã bao gồm phí vận chuyển: " . 30000 . " VND";
    $vnp_OrderType = "billpayment";
    $vnp_Amount = $order->total_amount * 100;
    $shipingFee = 30000; // Assuming no shipping fee for simplicity
    $vnp_Locale = "VN";
    $vnp_BankCode = "NCB";
    $vnp_IpAddr = request()->ip();


    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
        // "vnp_ShippingFee" => $shipingFee,
    );

    if (!empty($vnp_BankCode)) {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
    }

    ksort($inputData);
    $query = "";
    $hashdata = "";
    $i = 0;
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }

    return redirect($vnp_Url); // ✅ Laravel redirect
}
public function vnpayReturn(Request $request)
{
    $vnp_ResponseCode = $request->get('vnp_ResponseCode');
    $orderId = $request->get('vnp_TxnRef');

    // Debug tạm
    Log::info('VNPAY RETURN', [
        'vnp_ResponseCode' => $vnp_ResponseCode,
        'vnp_TxnRef' => $orderId
    ]);

    $order = Order::where('order_code', $orderId)->first();

    if (!$order) {
        Log::error("Order not found for order_code: " . $orderId);
        return redirect()->route('products')->with('error', 'Không tìm thấy đơn hàng.');
    }

    if ($vnp_ResponseCode == '00') {
        $order->update(['payment_status' => 'paid']);
        return redirect()->route('home')
            ->with('success', 'Thanh toán thành công!');
    } else {
        return redirect()->route('products')->with('error', 'Thanh toán thất bại!');
    }
}

}
