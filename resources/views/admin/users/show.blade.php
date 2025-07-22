@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
<style>
    /* Card tổng thể */
    .profile-card {
        transition: box-shadow 0.3s ease;
    }
    .profile-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Header với xanh biển nhẹ */
    .profile-header {
        background: linear-gradient(135deg, #2c7be5, #00bcd4); /* ✅ Xanh biển dịu */
        color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-bottom: 2px solid #00bcd4;
        text-align: left;
        padding-left: 2rem;
    }

    /* Avatar */
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Badge */
    .profile-badge {
        background: #00bcd4; /* Xanh pastel */
        color: #fff;
        font-weight: 500;
        border-radius: 50px;
        padding: 5px 15px;
        font-size: 14px;
    }

    /* Buttons xanh biển dịu */
    .btn-blue-primary {
        background: linear-gradient(135deg, #2c7be5, #00bcd4);
        color: #fff;
        border: none;
        transition: all 0.3s ease;
        border-radius: 6px;
    }
    .btn-blue-primary:hover {
        background: linear-gradient(135deg, #00bcd4, #2c7be5);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transform: translateY(-1px);
    }

    .btn-blue-secondary {
        background: #6c757d;
        color: #fff;
        border: none;
        transition: all 0.3s ease;
        border-radius: 6px;
    }
    .btn-blue-secondary:hover {
        background: #5a6268;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    /* Ô thông tin người dùng (tối giản, bỏ hover zoom) */
    .info-box {
        background-color: #f8f9fa;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 15px;
        transition: none;
    }
</style>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden profile-card">
    {{-- Header --}}
    <div class="profile-header py-4">
        <h4 class="mb-1 fw-bold">
            <i class="fas fa-user-circle me-2"></i> Hồ sơ người dùng
        </h4>
        <p class="mb-0 small text-white-50">Thông tin chi tiết của <strong>{{ $user->name }}</strong></p>
    </div>

    {{-- Body --}}
    <div class="row g-0">
        {{-- Avatar và Role --}}
        <div class="col-md-4 bg-light d-flex flex-column align-items-center justify-content-center p-4">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2c7be5&color=fff&size=160" 
                 alt="Avatar" 
                 class="profile-avatar mb-3">
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <p class="text-muted mb-2"><i class="fas fa-envelope me-1"></i>{{ $user->email }}</p>
            @if($user->role)
                <span class="profile-badge shadow-sm">
                    {{ $user->role->name }}
                </span>
            @else
                <span class="badge bg-secondary fs-6 px-3 py-2 shadow-sm">Chưa phân quyền</span>
            @endif
        </div>

        {{-- Info Details --}}
        <div class="col-md-8 p-4 bg-white">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="info-box shadow-sm h-100">
                        <h6 class="text-muted">ID người dùng</h6>
                        <p class="fw-semibold fs-5 mb-0 text-primary">{{ $user->id_user }}</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="info-box shadow-sm h-100">
                        <h6 class="text-muted">Tên tài khoản</h6>
                        <p class="fw-semibold fs-5 mb-0 text-primary">{{ $user->account_name }}</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="info-box shadow-sm h-100">
                        <h6 class="text-muted">Số điện thoại</h6>
                        <p class="fw-semibold fs-5 mb-0 text-primary">{{ $user->phone_number ?? 'Chưa cập nhật' }}</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="info-box shadow-sm h-100">
                        <h6 class="text-muted">Ngày tạo</h6>
                        <p class="fw-semibold fs-5 mb-0 text-primary">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                        </p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-box shadow-sm">
                        <h6 class="text-muted">Cập nhật lần cuối</h6>
                        <p class="fw-semibold fs-5 mb-0 text-primary">
                            {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Buttons ở dưới (căn phải) --}}
    <div class="d-flex justify-content-end p-3 bg-light border-top gap-2">
        <a href="{{ route('admin.users.edit', $user->id_user) }}" 
           class="btn btn-blue-primary btn-sm fw-semibold">
            <i class="fas fa-pen-to-square me-1"></i> Chỉnh sửa
        </a>
        <a href="{{ route('admin.users.index') }}" 
           class="btn btn-blue-secondary btn-sm fw-semibold">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>
</div>
@endsection
