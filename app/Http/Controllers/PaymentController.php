<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function vnpay_payment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = config('vnpay.return_url');
        $vnp_TmnCode = config('vnpay.tmncode');
        $vnp_HashSecret = config('vnpay.hash_secret');

        // Lấy dữ liệu từ request
        $vnp_TxnRef = uniqid(); // Hoặc lấy từ order_id nếu có
        $vnp_OrderInfo = "Thanh toán đơn hàng: " . ($request->order_info ?? 'Không có mô tả');
        $vnp_OrderType = "other";
        $vnp_Amount = intval($request->amount) * 100; // nhân 100 theo yêu cầu VNPay
        $vnp_Locale = "vn";
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        ];

        // Bắt buộc phải sắp xếp theo thứ tự a-z
        ksort($inputData);

        // Tạo chuỗi hashData chính xác (chuẩn theo VNPay)
        $hashData = urldecode(http_build_query($inputData));

        // Tạo mã ký tự bảo mật
        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Tạo URL thanh toán đầy đủ
        $redirectUrl = $vnp_Url . '?' . http_build_query($inputData) . '&vnp_SecureHash=' . $vnp_SecureHash;

        // Ghi log để debug nếu cần
        Log::info('VNPay Hash Data: ' . $hashData);
        Log::info('VNPay Secure Hash: ' . $vnp_SecureHash);
        Log::info('VNPay Input Data: ', $inputData);
        Log::info('VNPay Redirect URL: ' . $redirectUrl);

        // Nếu là gọi bằng fetch/AJAX
        if ($request->ajax()) {
            return response()->json([
                'redirect_url' => $redirectUrl,
                'hashData' => $hashData,
                'secureHash' => $vnp_SecureHash,
                'inputData' => $inputData,
            ]);
        }

        // Nếu là submit form truyền thống
        return redirect($redirectUrl);
    }
}
