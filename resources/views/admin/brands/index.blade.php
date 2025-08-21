@extends('layouts.admin')

@section('title', 'Danh Sách Thương Hiệu')

@section('content')
<style>
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
    background: linear-gradient(135deg, #42a5f5, #1e88e5);
    color: #fff;
    border-radius: 40px;
    padding: 0.45rem 1.3rem;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 6px 16px rgba(66, 165, 245, 0.3);
    text-decoration: none;
}
.btn-add-modern:hover {
    opacity: 0.95;
    transform: translateY(-2px);
}
.alert-success-modern {
    background: #e6fffa;
    color: #117864;
    border: 1px solid #a3e4d7;
    border-radius: 10px;
    padding: 12px 18px;
    font-weight: 500;
    margin-bottom: 16px;
}
.table-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 1rem;
}
.table-modern thead {
    background: #f1f5f9;
}
.table-modern th, 
.table-modern td {
    padding: 12px 16px;
    border-bottom: 1px solid #e9ecef;
    text-align: left;
    vertical-align: middle;
}
.table-modern th {
    font-weight: 600;
    color: #374151;
}
.table-modern tr:hover {
    background: #f9fafb;
}
.btn-sm-modern {
    border-radius: 8px;
    padding: 5px 12px;
    font-size: 0.85rem;
    transition: 0.2s;
}
.btn-warning-modern {
    background: #fbbf24;
    color: #fff;
    border: none;
}
.btn-warning-modern:hover {
    background: #f59e0b;
}
.btn-danger-modern {
    background: #ef4444;
    color: #fff;
    border: none;
}
.btn-danger-modern:hover {
    background: #dc2626;
}
 .btn-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.45rem 1rem;
    font-size: 0.95rem;
    font-weight: 600;
    border-radius: 10px;
    color: #fff;
    text-decoration: none;
    transition: 0.25s;
    border: none;
}
.btn-edit {
    background: linear-gradient(135deg, #fbc02d, #f57f17);
    box-shadow: 0 4px 10px rgba(251, 192, 45, 0.4);
}
.btn-edit:hover {
    transform: scale(1.05);
    background: linear-gradient(135deg, #f9a825, #f57c00);
}
.btn-delete {
    background: linear-gradient(135deg, #e53935, #c62828);
    box-shadow: 0 4px 10px rgba(229, 57, 53, 0.4);
}
.btn-delete:hover {
    transform: scale(1.05);
    background: linear-gradient(135deg, #d32f2f, #b71c1c);
}
</style>

<div class="card card-modern">
    <div class="card-modern-header">
        <span><i class="bi bi-tags"></i> Danh Sách Thương Hiệu</span>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-add-modern">
            <i class="bi bi-plus-circle"></i> Thêm mới
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert-success-modern">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên thương hiệu</th>
                    <th>Logo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>{{ $brand->id_brand }}</td>
                        <td>{{ $brand->name }}</td>
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" width="50" class="rounded">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.brands.edit', $brand->id_brand) }}" class="btn btn-action btn-edit">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>
                            <form action="{{ route('admin.brands.destroy', $brand->id_brand) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-action btn-delete">
                                    <i class="bi bi-trash3-fill"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có thương hiệu nào</td>
                    </tr>
                @endforelse
                    <tr>
                        <td colspan="2" class="text-center text-muted"></td>
                        <td colspan="1" class="text-center text-muted">        
                            <a href="{{ route('admin.brands.trash') }}" class="btn ">
                                    <i class="bi bi-trash3-fill"></i> Thùng Rác
                            </a>
                        </td>
                    </tr>
            </tbody>
        </table>

        {{ $brands->links() }}
    </div>
</div>
@endsection
