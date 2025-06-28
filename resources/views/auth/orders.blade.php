@extends('layouts.client_home')
@section('title', 'Lịch sử đơn hàng')
@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Lịch sử đơn hàng</h1>
                    <nav class="d-flex align-items-center">
                        <a href="{{ route('home') }}">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                        <a href="{{ route('account.profile') }}">Tài khoản<span class="lnr lnr-arrow-right"></span></a>
                        <a href="{{ route('account.orders') }}">Lịch sử đơn hàng</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!-- Start Orders Area -->
    <section class="section_gap">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fa fa-user-circle"></i> Tài khoản</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                                    <i class="fa fa-user me-2"></i>Thông tin cá nhân
                                </a>
                                <a href="{{ route('account.edit') }}" class="list-group-item list-group-item-action">
                                    <i class="fa fa-edit me-2"></i>Chỉnh sửa thông tin
                                </a>
                                <a href="{{ route('account.change-password') }}"
                                    class="list-group-item list-group-item-action">
                                    <i class="fa fa-lock me-2"></i>Đổi mật khẩu
                                </a>
                                <a href="{{ route('account.orders') }}"
                                    class="list-group-item list-group-item-action active">
                                    <i class="fa fa-shopping-bag me-2"></i>Lịch sử đơn hàng
                                </a>
                                <a href="{{ route('account.settings') }}" class="list-group-item list-group-item-action">
                                    <i class="fa fa-cog me-2"></i>Cài đặt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fa fa-shopping-bag me-2"></i>Lịch sử đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            @if ($orders->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Mã đơn hàng</th>
                                                <th>Ngày đặt</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->id_order }}</td>
                                                    <td>{{ $order->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                                    <td>{{ number_format($order->total_amount) }} VNĐ
                                                    </td>
                                                    <td>
                                                        @php $status = $order->status; @endphp
                                                        @if ($status == 'pending')
                                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                                        @elseif ($status == 'processing')
                                                            <span class="badge bg-success text-white">Đã xác nhận</span>
                                                        @elseif ($status == 'shipping')
                                                            <span class="badge bg-primary text-white">Đang giao</span>
                                                        @elseif ($status == 'completed')
                                                            <span class="badge bg-success text-white">Đã giao</span>
                                                        @elseif ($status == 'canceled')
                                                            <span class="badge bg-danger text-white">Đã hủy</span>
                                                        @else
                                                            <span class="badge ">{{ $status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('account.orderDetail', $order->id_order) }}"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fa fa-eye"></i> Xem chi tiết
                                                        </a>
                                                        @if ($order->status == 'pending')
                                                            <form
                                                                action="{{ route('account.cancelOrder', $order->id_order) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-recycle"></i> Hủy đơn hàng
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="mt-3">
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fa fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5>Chưa có đơn hàng nào</h5>
                                    <p class="text-muted">Bạn chưa có đơn hàng nào. Hãy mua sắm ngay!</p>
                                    <a href="{{ route('products') }}" class="btn btn-primary">
                                        <i class="fa fa-shopping-cart me-2"></i>Mua sắm ngay
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Orders Area -->
@endsection
