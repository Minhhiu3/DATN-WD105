@extends('layouts.client_home')
@section('title', 'Chi tiết đơn hàng')
@section('content')
<section class="section_gap">
    <div class="container">
        <h2 class="mb-4">Chi tiết đơn hàng #{{ $order->id_order }}</h2>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Trạng thái:</strong>
            @if ($order->status == 'pending')
                <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                  @elseif ($order->status == 'confirmed')
                <span class="badge bg-success text-white">Đã xác nhận</span>
            @elseif ($order->status == 'shipping')
                <span class="badge bg-primary text-white">Đang giao</span>
            @elseif ($order->status == 'delivered')
                <span class="badge bg-success text-white">Đã giao</span>
            @elseif ($order->status == 'canceled')
                <span class="badge bg-danger text-white">Đã hủy</span>
            @else
                <span class="badge">{{ $order->status }}</span>
            @endif
        </p>
<p>@foreach ($order->orderItems as $item)
    <strong>Phương thức thanh toán:</strong> {{ $item->payment_method}} @endforeach</p>
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
<tr>
    <td>{{ $item->variant->product->name_product ?? 'Không có sản phẩm' }}</td>
    <td>{{ $item->quantity }}</td>
    <td>{{ number_format($item->variant->product->price) }} VNĐ</td>
    <td>{{ number_format($item->total_amount) }} VNĐ</td>
</tr>
@endforeach
                </tbody>
            </table>
        </div>

        <p class="mt-3 text-end">
            <strong>Tổng tiền:</strong> {{ number_format($order->orderItems->sum('total_amount')) }} VNĐ
        </p>

        <a href="{{ route('account.orders') }}" class="btn btn-secondary mt-3">
            <i class="fa fa-arrow-left"></i> Quay lại danh sách đơn hàng
        </a>
    </div>
</section>
@endsection
