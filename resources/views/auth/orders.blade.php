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
                                                <th>Trạng thái Đơn Hàng</th>
                                                <th>Trạng thái thanh toán</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $order->order_code }}</td>
                                                    <td>{{ $order->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                                    @php
                                                        $shippingFee = $order->shipping_fee; // Mặc định 30,000 nếu null
                                                        $grandTotal = $order->total_amount + $shippingFee;
                                                    @endphp

                                                    <td>{{ number_format($order->grand_total ?? $order->total_amount + ($order->shipping_fee ?? 30000), 0, ',', '.') }}
                                                        VNĐ</td>

                                                    </td>
                                                    <td>
                                                        @php $status = $order->status;
                                                        $reason = $order->cancel_reason ?? 'Chưa có lý do hủy';
                                                         @endphp
                                                        @if ($status == 'pending')
                                                            <span class="btn btn-sm btn-warning text-black">Chờ xác
                                                                nhận</span>
                                                        @elseif ($status == 'processing')
                                                            <span class="btn btn-sm btn-primary text-white">Đã xác
                                                                nhận</span>
                                                        @elseif ($status == 'shipping')
                                                            <span class="btn btn-sm btn-info text-white">Đang giao</span>
                                                        @elseif ($status == 'delivered')
                                                            <span class="btn btn-sm btn-success text-white">Đã giao
                                                                hàng</span>
                                                        @elseif ($status == 'received')
                                                            <span class="btn btn-sm btn-success text-white">Đã nhận
                                                                hàng</span>
                                                        @elseif ($status == 'completed')
                                                            <span class="btn btn-sm btn-success text-white">Hoàn
                                                                thành</span>
                                                        @elseif ($status == 'canceled')
                                                            <span class="btn btn-sm btn-danger text-white">Đã Hủy</span>
                                                           <p class="btn btn-sm btn-danger text-white  mt-2"> Lý do hủy: <span >{{$reason}}</span></p>
                                                        @else
                                                            <span
                                                                class="btn btn-sm btn-light text-black">{{ $status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php $payment_status = $order->payment_status; @endphp
                                                        @if ($payment_status == 'unpaid')
                                                            <span class="btn btn-sm btn-warning text-black">Chưa thanh
                                                                toán</span>
                                                        @elseif($payment_status == 'paid')
                                                            <span class="btn btn-sm btn-success text-white">Đã thanh
                                                                toán</span>
                                                        @elseif($payment_status == 'canceled')
                                                            <span class="btn btn-sm btn-danger text-white">Đã hoàn
                                                                tiền</span>
                                                        @else
                                                            <span
                                                                class="btn btn-sm btn-light text-black">{{ $payment_status }}</span>
                                                        @endif



                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <a href="{{ route('account.orderDetail', $order->id_order) }}"
                                                                class="btn btn-sm btn-info">
                                                                <i class="fa fa-eye"></i> Xem chi tiết
                                                            </a>

                                                            @if ($order->status == 'pending' || $order->status == 'processing')
                                                                <button type="button" class="btn btn-sm btn-danger"
                                                                    onclick="showCancelModal({{ $order->id_order }})">
                                                                    <i class="fa fa-recycle"></i> Hủy
                                                                </button>
                                                            @endif
                                                            @if ($order->status == 'delivered')
                                                                <form
                                                                    action="{{ route('account.confirmReceive', $order->id_order) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Xác nhận bạn đã nhận được hàng này?')">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-sm btn-success">
                                                                        <i class="fa fa-check"></i> Xác nhận đã nhận hàng
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="pagination-wrapper">
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
    <!-- Modal nhập lý do hủy -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Nhập lý do hủy đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="cancelOrderForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cancel_reason" class="form-label">Lý do hủy</label>
                            <textarea name="cancel_reason" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showCancelModal(orderId) {
            let form = document.getElementById('cancelOrderForm');
            form.action = "/account/orders/" + orderId + "/cancel";
            var modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            modal.show();
        }
    </script>
@endsection
