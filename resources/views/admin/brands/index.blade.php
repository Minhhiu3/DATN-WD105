@extends('layouts.admin')

@section('title', 'Danh Sách Thương Hiệu')

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
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 15px;
        margin-bottom: 15px;
        animation: fadeIn 0.5s ease-out;
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
                        <td colspan="3" class="text-center text-muted"></td>
                        <td colspan="1" class="text-center text-muted">        
                            <a href="{{ route('admin.brands.trash') }}" class="btn ">
                                    <i class="bi bi-trash3-fill"></i> Thùng Rác
                            </a>
                        </td>
                    </tr>
            </tbody>
        </table>
        @if ($brands->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {!! $brands->links('pagination::bootstrap-5') !!}
            </div>
        @endif
    </div>
</div>
@endsection
