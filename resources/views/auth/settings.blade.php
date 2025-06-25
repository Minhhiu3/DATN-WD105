@extends('layouts.client_home')
@section('title', 'Cài đặt tài khoản')
@section('content')
<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Cài đặt tài khoản</h1>
                <nav class="d-flex align-items-center">
                    <a href="{{ route('home') }}">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="{{ route('account.profile') }}">Tài khoản<span class="lnr lnr-arrow-right"></span></a>
                    <a href="{{ route('account.settings') }}">Cài đặt</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!-- Start Settings Area -->
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
                            <a href="{{ route('account.change-password') }}" class="list-group-item list-group-item-action">
                                <i class="fa fa-lock me-2"></i>Đổi mật khẩu
                            </a>
                            <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">
                                <i class="fa fa-shopping-bag me-2"></i>Lịch sử đơn hàng
                            </a>
                            <a href="{{ route('account.settings') }}" class="list-group-item list-group-item-action active">
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
                        <h5><i class="fa fa-cog me-2"></i>Cài đặt tài khoản</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body text-center">
                                        <i class="fa fa-user-circle fa-3x text-primary mb-3"></i>
                                        <h6>Thông tin tài khoản</h6>
                                        <p class="text-muted small">Quản lý thông tin cá nhân</p>
                                        <a href="{{ route('account.profile') }}" class="btn btn-outline-primary btn-sm">
                                            Xem thông tin
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body text-center">
                                        <i class="fa fa-lock fa-3x text-warning mb-3"></i>
                                        <h6>Bảo mật</h6>
                                        <p class="text-muted small">Đổi mật khẩu và bảo mật</p>
                                        <a href="{{ route('account.change-password') }}" class="btn btn-outline-warning btn-sm">
                                            Đổi mật khẩu
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body text-center">
                                        <i class="fa fa-shopping-bag fa-3x text-success mb-3"></i>
                                        <h6>Đơn hàng</h6>
                                        <p class="text-muted small">Xem lịch sử đơn hàng</p>
                                        <a href="{{ route('account.orders') }}" class="btn btn-outline-success btn-sm">
                                            Xem đơn hàng
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body text-center">
                                        <i class="fa fa-cogs fa-3x text-info mb-3"></i>
                                        <h6>Quản trị</h6>
                                        <p class="text-muted small">Truy cập admin panel</p>
                                        @if($user->role && $user->role->name === 'Admin')
                                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-info btn-sm">
                                                Admin Panel
                                            </a>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                                Không có quyền
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fa fa-info-circle me-2"></i>Thông tin tài khoản</h6>
                            <ul class="mb-0">
                                <li><strong>Vai trò:</strong> {{ $user->role ? $user->role->name : 'N/A' }}</li>
                                <li><strong>Ngày tham gia:</strong> {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</li>
                                <li><strong>Trạng thái:</strong> <span class="badge badge-success">Hoạt động</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Settings Area -->
@endsection 