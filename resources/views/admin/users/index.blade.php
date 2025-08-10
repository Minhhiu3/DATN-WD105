@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<style>
    /* ==== CARD ==== */
    .card-modern {
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    .card-modern-header {
        background: #f8f9fc;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1.2rem;
        color: #495057;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e9ecef;
    }
    .btn-add-modern {
        background: linear-gradient(135deg, #42a5f5, #478ed1);
        color: #fff;
        border-radius: 50px;
        padding: 0.5rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(66, 165, 245, 0.3);
    }
    .btn-add-modern:hover {
        opacity: 0.95;
        transform: translateY(-2px);
    }

    /* ==== TABLE ==== */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 10px;
        width: 100%;
    }
    .table-modern thead {
        background-color: #f1f3f5;
    }
    .table-modern th {
        font-weight: 600;
        color: #495057;
        padding: 12px;
        border: none;
        text-align: center;
    }
    .table-modern td {
        background: #fff;
        border: none;
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        border-radius: 8px;
    }
    .table-modern tbody tr:hover td {
        background: #f8f9fa;
        transition: background 0.3s ease;
    }

    /* ==== BADGE ==== */
    .badge-modern {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .badge-admin {
        background: linear-gradient(135deg, #e53935, #b71c1c);
        color: #fff;
    }
    .badge-user {
        background: linear-gradient(135deg, #42a5f5, #1e88e5);
        color: #fff;
    }
    .badge-secondary {
        background: #d1d5db;
        color: #374151;
    }

    /* ==== ACTION BUTTONS ==== */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 8px;
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #fff;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-view {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
    }
    .btn-view:hover {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        transform: translateY(-1px);
    }
    .btn-edit {
        background: linear-gradient(135deg, #fbc02d, #f57f17);
    }
    .btn-edit:hover {
        background: linear-gradient(135deg, #f9a825, #f57c00);
        transform: translateY(-1px);
    }
    .btn-delete {
        background: linear-gradient(135deg, #e53935, #b71c1c);
    }
    .btn-delete:hover {
        background: linear-gradient(135deg, #d32f2f, #b71c1c);
        transform: translateY(-1px);
    }

    /* ==== ALERT ==== */
    .alert-modern-success {
        background: #d1f2eb;
        color: #117864;
        border: 1px solid #a3e4d7;
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 15px;
        margin-bottom: 15px;
        animation: fadeIn 0.5s ease-out;
    }
    .alert-modern-danger {
        background: #f9d6d5;
        color: #c0392b;
        border: 1px solid #f5b7b1;
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 15px;
        margin-bottom: 15px;
        animation: fadeIn 0.5s ease-out;
    }
</style>

<div class="card card-modern">
    <div class="card-modern-header">
        <span><i class="fas fa-users"></i> Danh sách người dùng</span>
        <a href="{{ route('admin.users.create') }}" class="btn btn-add-modern">
            <i class="fas fa-plus-circle"></i> Thêm người dùng
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-modern-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-modern-danger">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Tài khoản</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id_user }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->account_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number ?? 'N/A' }}</td>
                            <td>
                                @if($user->role)
                                    <span class="badge badge-modern 
                                        {{ $user->role->name === 'Admin' ? 'badge-admin' : 'badge-user' }}">
                                        {{ $user->role->name }}
                                    </span>
                                @else
                                    <span class="badge badge-modern badge-secondary">N/A</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id_user) }}" class="btn btn-action btn-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id_user) }}" class="btn btn-action btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id_user !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có người dùng nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
