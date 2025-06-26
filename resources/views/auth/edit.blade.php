@extends('layouts.client_home')
@section('title', 'Chỉnh sửa thông tin')
@section('content')
<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Chỉnh sửa thông tin</h1>
                <nav class="d-flex align-items-center">
                    <a href="{{ route('home') }}">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="{{ route('account.profile') }}">Tài khoản<span class="lnr lnr-arrow-right"></span></a>
                    <a href="{{ route('account.edit') }}">Chỉnh sửa</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!-- Start Edit Profile Area -->
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
                            <a href="{{ route('account.edit') }}" class="list-group-item list-group-item-action active">
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
                        <h5><i class="fa fa-edit me-2"></i>Chỉnh sửa thông tin</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('account.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="account_name" class="form-label">Tên tài khoản <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                               id="account_name" name="account_name" value="{{ old('account_name', $user->account_name) }}" required>
                                        @error('account_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="phone_number" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                               id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="role_id" class="form-label">Vai trò</label>
                                <input type="text" class="form-control" value="{{ $user->role ? $user->role->name : 'N/A' }}" readonly>
                                <small class="form-text text-muted">Vai trò không thể thay đổi từ đây. Liên hệ admin nếu cần thay đổi.</small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-2"></i>Cập nhật thông tin
                                </button>
                                <a href="{{ route('account.profile') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left me-2"></i>Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Edit Profile Area -->
@endsection 