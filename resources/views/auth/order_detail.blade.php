@extends('layouts.client_home')
@section('title', 'Chi tiết đơn hàng')
@section('content')
<section class="section_gap">
    <div class="container ">
         <div class="m-5">
        <h2 class="mb-4">Chi tiết đơn hàng #{{ $order->order_code }}</h2>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Trạng thái đơn hàng:</strong>
            @if ($order->status == 'pending')
                <span class="btn btn-sm btn-warning text-black">Chờ xác nhận</span>
                  @elseif ($order->status == 'processing')
                <span class="btn btn-sm btn-primary text-white">Đã xác nhận</span>
            @elseif ($order->status == 'shipping')
                <span class="btn btn-sm btn-info text-white">Đang giao</span>
            @elseif ($order->status == 'completed')
                <span class="btn btn-sm btn-success text-white">Đã giao</span>
            @elseif ($order->status == 'canceled')
                <span class="btn btn-sm btn-danger text-white">Đã hủy</span>
            @else
                <span class="btn btn-sm btn-light text-black">{{ $order->status }}</span>
            @endif
        </p>
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
            <p><strong>Tên:</strong>{{$order->user->name}}</p>
             <p><strong>Số điện thoại:</strong>{{$order->user->phone_number}}</p>
              <p><strong>Email:</strong>{{$order->user->email}}</p>
               <p><strong>Địa chỉ:</strong></p>
<p>@foreach ($order->orderItems as $item)
    @endforeach</p>
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Size</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $item->variant->product->name_product ?? 'Không có sản phẩm' }}</td>
                    <td>{{$item->variant->size->name}}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->variant->price) }} VNĐ</td>
                    <td>{{ number_format($item->quantity*$item->variant->price) }} VNĐ</td>
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
