@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cập nhập trạng thái đơn hàng</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.orders.update', $order->id_order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="customer_name">Khách hàng:</label>
                <input type="text" id="customer_name" class="form-control" value="{{ old('name', $order->user->name) }}" disabled>
            </div>

            <div class="form-group mb-3">
                <label for="total_amount">Tổng tiền:</label>
                <input type="text" id="total_amount" class="form-control" value="{{ number_format($order->total_amount, 0, ',', '.') }} VND" disabled>
            </div>

            <div class="form-group mb-3">
                <label for="created_at">Ngày đặt:</label>
                <input type="text" id="created_at" class="form-control" value="{{ optional($order->created_at)->format('d/m/Y') }}" disabled>
            </div>
            {{-- <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{ old('user_id', $order->user_id) }}" > --}}
            <div class="form-group mb-3">
                <label for="status">Trạng thái đơn hàng:</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="note">Ghi chú:</label>
                <textarea name="note" id="note" class="form-control">nhập lý do</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
        </form>


    </div>
</div>
@endsection
