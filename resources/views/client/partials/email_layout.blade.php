<!DOCTYPE html>
<html>
<head>
    <title>Thanh toán thành công</title>
</head>
<body>
    <h1>Xin chào {{ $paymentDetails['user_name'] }}</h1>
    <p>Cảm ơn bạn đã thực hiện thanh toán thành công!</p>
    <p>Chi tiết thanh toán:</p>
    <ul>
        <li>Mã giao dịch: {{ $paymentDetails['transaction_id'] }}</li>
        <li>Số tiền: {{ number_format($paymentDetails['amount']) }} VND</li>
        <li>Ngày thanh toán: {{ $paymentDetails['date'] }}</li>
    </ul>
    <p>Trân trọng,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>