@extends('admin.index')

@section('title', 'Chi tiết người dùng')
@section('page_title', 'Chi tiết người dùng')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thông tin người dùng: {{ $user->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.edit', $user->id_user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
    <div class="card-body">
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
                        <td>{{ $user->phone_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Vai trò:</th>
                        <td>
                            @if($user->role)
                                <span class="badge badge-{{ $user->role->name === 'Admin' ? 'danger' : 'info' }}">
                                    {{ $user->role->name }}
                                </span>
                            @else
                                <span class="badge badge-secondary">N/A</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối:</th>
                        <td>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 