<h2>Cảm ơn bạn đã đặt hàng!</h2>
<p>Đơn hàng của bạn đã được xác nhận.</p>
<p>Mã đơn hàng: <strong>{{ $order->order_code }}</strong></p>
<p>Tổng tiền: <strong>{{ number_format($order->grand_total, 0, ',', '.') }}đ</strong></p>
<p>Phương thức thanh toán: {{ strtoupper($order->payment_method) }}</p>
