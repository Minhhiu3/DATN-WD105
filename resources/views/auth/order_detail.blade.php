@extends('layouts.client_home')
@section('title', 'Chi tiết đơn hàng')
@section('content')
<section class="section_gap">
    <div class="container ">
         <div class="m-5">
        <h2 class="mb-4">Chi tiết đơn hàng #{{ $order->order_code }}</h2>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
         @php
                                                        $reason = $order->cancel_reason ?? 'Chưa có lý do hủy';
                                                         @endphp
        <p><strong>Trạng thái đơn hàng:</strong>
            @if ($order->status == 'pending')
                <span class="btn btn-sm btn-warning text-black">Chờ xác nhận</span>
                  @elseif ($order->status == 'processing')
                <span class="btn btn-sm btn-primary text-white">Đã xác nhận</span>
            @elseif ($order->status == 'shipping')
                <span class="btn btn-sm btn-info text-white">Đang giao hàng</span>
                 @elseif ($order->status == 'delivered')
                <span class="btn btn-sm btn-info text-white">Đã giao hàng</span>
                @elseif ($order->status == 'received')
                <span class="btn btn-sm btn-info text-white">Đã nhận hàng</span>
            @elseif ($order->status == 'completed')
                <span class="btn btn-sm btn-success text-white">Hoàn thành</span>
            @elseif ($order->status == 'canceled')
                <span class="btn btn-sm btn-danger text-white">Đã hủy</span>

            @else
                <span class="btn btn-sm btn-light text-black">{{ $order->status }}</span>
            @endif
        </p>
        @if($order->status == 'canceled')
         <p><strong> Lý do hủy:</strong><span class=""> {{$reason}}</span></p>
        @endif
        <p><strong>Trạng thái thanh toán:</strong>
            @if ($order->payment_status == 'unpaid')
                <span class="btn btn-sm btn-warning text-dark">Chưa thanh toán</span>
            @elseif($order->payment_status == 'paid')
                <span class="btn btn-sm btn-success text-white">Đã thanh toán</span>
            @else
                <span class="badge">{{ $order->payment_status }}</span>
            @endif
            </p>
            <p ><strong >Phương thức thanh toán:</strong> <span  class=" btn btn-sm btn-light text-black"> {{ $order->payment_method}}</span> </p>
            <h3>Thông tin người đặt</h3>

            <p><strong>Tên:</strong>{{$order->user_name}}</p>
             <p><strong>Số điện thoại:</strong>{{$order->phone}}</p>
              <p><strong>Email:</strong>{{$order->email}}</p>
              <p><strong>Địa chỉ:</strong>
    {{ $order->address }},
    {{ $order->ward }},
    {{ $order->province }}
</p>

<p>@foreach ($order->orderItems as $item)
    @endforeach</p>
        <div class="table-responsive mt-4">
<table class="table table-bordered order-table">
    <thead>
        <tr>
            <th>Ảnh</th>
            <th>Sản phẩm</th>
            <th>Size</th>
            <th>Màu</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->orderItems as $item)
            <tr>
                <td>
                    <img src="{{ asset('storage/' . ($item->image ?? 'default.jpg')) }}"
                         alt="{{ $item->product_name }}"
                         style="width: 70px; height: 80px; object-fit: cover;">
                </td>
                <td>{{ $item->product_name ?? 'Không có tên sản phẩm' }}</td>
                <td>{{ $item->size_name }}</td>
                <td>{{ $item->color_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price) }} VNĐ</td>
                <td>{{ number_format($item->quantity * $item->price) }} VNĐ</td>
            </tr>
        @endforeach
    </tbody>
</table>
        </div>

       <div class="mt-3 text-end">
   <p><strong>Tạm tính:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</p>
<p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee ?? 30000, 0, ',', '.') }} VNĐ</p>
<p><strong>Tổng thanh toán:</strong> {{ number_format($order->grand_total ?? ($order->total_amount + ($order->shipping_fee ?? 30000)), 0, ',', '.') }} VNĐ</p>

</div>


        <a href="{{ route('account.orders') }}" class="btn btn-secondary mt-3">
            <i class="fa fa-arrow-left"></i> Quay lại danh sách đơn hàng
        </a>
    </div>
    </div>
</section>
@endsection
@push('styles')
<style>
    .order-table {
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }

    .order-table th, .order-table td {
        vertical-align: middle !important;
        text-align: center;
    }

    .order-table img {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 3px;
        background-color: #f9f9f9;
    }

    .order-table thead {
        background-color: #f8f9fa;
    }

    .order-table td {
        font-size: 15px;
    }

    .order-table td:nth-child(2) {
        text-align: left;
    }
</style>
@endpush
