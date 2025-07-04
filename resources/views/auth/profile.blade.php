@extends('layouts.client_home')
@section('title', 'Thông tin tài khoản')
@section('content')
<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Thông tin tài khoản</h1>
                <nav class="d-flex align-items-center">
                    <a href="{{ route('home') }}">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="{{ route('account.profile') }}">Tài khoản</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!-- Start Profile Area -->
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
                            <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action active">
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
                        <h5><i class="fa fa-user-circle me-2"></i>Thông tin cá nhân</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="150">ID:</th>
                                        <td>{{ $user->id_user }}</td>
                                    </tr>
                                    <tr>
                                        <th>Họ tên:</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tên tài khoản:</th>
                                        <td>{{ $user->account_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="150">Số điện thoại:</th>
                                        <td>{{ $user->phone_number ?? 'Chưa cập nhật' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Vai trò:</th>
                                        <td>
                                            @if($user->role)
                                                <span class="badge badge-{{ $user->role->name === 'Admin' ? 'danger' : ($user->role->name === 'Staff' ? 'warning' : 'info') }}">
                                                    {{ $user->role->name }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ngày tham gia:</th>
                                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cập nhật lần cuối:</th>
                                        <td>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('account.edit') }}" class="btn btn-primary">
                                <i class="fa fa-edit me-2"></i>Chỉnh sửa thông tin
                            </a>
                            <a href="{{ route('account.change-password') }}" class="btn btn-warning">
                                <i class="fa fa-lock me-2"></i>Đổi mật khẩu
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger" class="nav-item" class="nav-link" >
                                    <i class="fa fa-sign-out me-2"></i>Đăng xuất
                                </button>
                            </form>

                            @if($user->role && $user->role->name === 'Admin')
                                <a href="{{ route('admin.dashboard') }}" method="POST" class="btn btn-danger">
                                    <i class="fa fa-cogs me-2"></i>Admin Panel
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Profile Area -->
@endsection
