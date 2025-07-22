@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Chi tiết đơn hàng</h3>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

            <div class="border p-3 mb-4 rounded shadow-sm">
                {{-- Thông tin người dùng --}}
                <h5>🧍 Thông tin khách hàng</h5>
                <ul>
                   <li><strong>Tên:</strong> {{ $order->user->name }}</li>
                <li><strong>Email:</strong> {{ $order->user->email }}</li>
                <li><strong>Số điện thoại:</strong> {{ $order->user->phone_number }}</li>
                <li><strong>Địa chỉ:</strong> {{ $order->fullAddress() }}</li>
  <li><strong>Trạng thái đơn hàng:</strong>  @php $status = $order->status; @endphp
                                                        @if ($status == 'pending')
                                                            <span class="btn btn-sm btn-warning text-black">Chờ xác nhận</span>
                                                        @elseif ($status == 'processing')
                                                            <span class="btn btn-sm btn-primary text-white">Đã xác nhận</span>
                                                        @elseif ($status == 'shipping')
                                                            <span class="btn btn-sm btn-info text-white">Đang giao</span>
                                                        @elseif ($status == 'completed')
                                                            <span class="btn btn-sm btn-success text-white">Đã giao</span>
                                                        @elseif ($status == 'canceled')
                                                            <span class="btn btn-sm btn-danger text-white">Đã hủy</span>
                                                        @else
                                                            <span class="btn btn-sm btn-light text-black">{{ $status }}</span>
                                                        @endif</li>
    <li><strong>Trạng thái thanh toán:</strong>  @php $payment_status = $order->payment_status; @endphp
                                                        @if ($payment_status == 'unpaid')
                                                            <span class="btn btn-sm btn-warning text-black">Chưa thanh toán</span>
                                                        @elseif($payment_status == 'paid')
                                                            <span class="btn btn-sm btn-success text-white">Đã thanh toán</span>
                                                                  @elseif($payment_status == 'canceled')
                                                            <span class="btn btn-sm btn-danger text-white">Đã hoàn tiền</span>
                                                             @else
                                                            <span class="btn btn-sm btn-light text-black">{{ $payment_status }}</span>
                                                        @endif</li>
       <li><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</li>
                </ul>

            </div>

            <div class="border p-3 mb-4 rounded shadow-sm">
    {{-- Danh sách sản phẩm --}}
    <h5 class="mt-3">📦 Sản phẩm đã đặt</h5>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th></th>
                    <th>Sản phẩm</th>
                    <th>Size</th>
                    <th>Số Lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tongTien = 0;
                @endphp
                @foreach ($order_items as $item)
                    @php
                        $price = optional($item->variant)->price ?? 0;
                        $quantity = $item->quantity ?? 0;
                        $thanhTien = $price * $quantity;
                        $tongTien += $thanhTien;
                    @endphp
                    <tr>
                        <td>{{ $item->variant->id_variant ?? 'Không rõ' }}</td>
                        <td>{{ $item->variant->product->name_product ?? 'Không rõ' }}</td>
                        <td>{{ $item->variant->size->name ?? 'Không rõ' }}</td>
                        <td>{{ $item->quantity ?? 'Không rõ' }}</td>
                        <td>{{ number_format($price, 0, ',', '.') }} VND</td>
                        <td>{{ number_format($thanhTien, 0, ',', '.') }} VND</td>
                    </tr>
                @endforeach

                {{-- Dòng tạm tính --}}
                <tr>
                    <td colspan="5" class="text-end"><strong>Tạm tính:</strong></td>
                    <td><strong>{{ number_format($tongTien, 0, ',', '.') }} VND</strong></td>
                </tr>

                {{-- Phí vận chuyển --}}
                <tr>
                    <td colspan="5" class="text-end"><strong>Phí vận chuyển:</strong></td>
                    <td><strong>{{ number_format($order->shipping_fee ?? 30000, 0, ',', '.') }} VND</strong></td>
                </tr>

                {{-- Tổng cộng --}}
                <tr class="table-success">
                    <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                    <td>
                        <strong>{{ number_format(($tongTien + ($order->shipping_fee ?? 30000)), 0, ',', '.') }} VND</strong>
                    </td>
                </tr>

    </div>
</div>
@endsection
