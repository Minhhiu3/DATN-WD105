
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng - ShoeMart</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f6f6f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #eee; padding: 32px; }
        .header { text-align: center; padding-bottom: 16px; border-bottom: 1px solid #eee; }
        .header h2 { color: #ff7e00; margin: 0; }
        .order-info { margin: 24px 0; }
        .order-code { font-size: 18px; color: #333; margin-bottom: 8px; }
        .product-list { margin-bottom: 24px; }
        .product-item { display: flex; align-items: center; border-bottom: 1px solid #eee; padding: 12px 0; }
        .product-img { width: 70px; height: 80px; object-fit: cover; border-radius: 6px; margin-right: 16px; border: 1px solid #eee; }
        .product-details { flex: 1; }
        .product-details p { margin: 2px 0; font-size: 15px; color: #444; }
        .total { font-size: 18px; color: #ff4d4f; font-weight: bold; margin-bottom: 8px; }
        .payment { font-size: 15px; color: #555; margin-bottom: 16px; }
        .footer { text-align: center; margin-top: 32px; font-size: 14px; color: #888; }
        .btn { display: inline-block; background: linear-gradient(135deg, #ff7e00, #ffb400); color: #fff; padding: 10px 24px; border-radius: 6px; text-decoration: none; font-weight: 500; margin-top: 12px; }
        a.btn:hover { background: #ff7e00; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Cảm ơn bạn đã đặt hàng tại ShoeMart!</h2>
            <p>Đơn hàng của bạn đã được xác nhận.</p>
        </div>
        <div class="order-info">
            <div class="order-code">Mã đơn hàng: <strong>{{ $order->order_code }}</strong></div>
            <div class="product-list">

                    {{-- <div class="product-item">
                        <img class="product-img" src="{{ asset('storage/' . ($item->image ?? 'default.jpg')) }}" alt="{{ $item->product_name }}" />
                        <div class="product-details">
                            <p><strong>{{ $item->product_name ?? 'Không có tên sản phẩm' }}</strong></p>
                            <p>Size: {{ $item->size_name }}, Màu: {{ $item->color_name }}</p>
                            <p>Số lượng: {{ $item->quantity }}</p>
                            <p>Giá: {{ number_format($item->price, 0, ',', '.') }}đ</p>
                        </div>
                    </div> --}}

            </div>
            <div class="total">Tổng tiền: {{ number_format($order->grand_total, 0, ',', '.') }}đ</div>
            <div class="payment">Phương thức thanh toán: <strong>{{ strtoupper($order->payment_method) }}</strong></div>
        </div>
        <div style="text-align:center;">
            <a href="{{ url('/account/orders') }}" class="btn">Kiểm tra trạng thái đơn hàng</a>
        </div>
        <div class="footer">
            Nếu có thắc mắc, vui lòng liên hệ bộ phận CSKH của ShoeMart.<br>
            Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi!
        </div>
    </div>
