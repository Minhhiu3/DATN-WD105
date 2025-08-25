@extends('layouts.admin')

@section('title', 'Thùng Rác - Banner')

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
}
.btn-add-modern:hover {
    opacity: 0.95;
    transform: translateY(-2px);
}
.table-modern {
    width: 100%;
    border-spacing: 0 12px;
    border-collapse: separate;
}
.table-modern thead th {
    background: #f6f9fc;
    font-weight: 600;
    padding: 14px;
    color: #495057;
    border: none;
}
.table-modern tbody td {
    background: #fff;
    padding: 14px;
    border-radius: 10px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.05);
    vertical-align: middle;
    border: none;
}
.table-modern tbody tr:hover td {
    background: #f4f6f9;
    transition: all 0.2s ease;
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
.btn-restore {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
}
.btn-restore:hover {
    transform: scale(1.05);
    background: linear-gradient(135deg, #218838, #1c6d2f);
}
.btn-force-delete {
    background: linear-gradient(135deg, #e53935, #c62828);
    box-shadow: 0 4px 10px rgba(229, 57, 53, 0.4);
}
.btn-force-delete:hover {
    transform: scale(1.05);
    background: linear-gradient(135deg, #d32f2f, #b71c1c);
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
</style>

<div class="card card-modern">
    <div class="card-modern-header">
        <span><i class="bi bi-trash3-fill"></i> Thùng Rác - Banner</span>
        <a href="{{ route('admin.banner.index') }}" class="btn btn-add-modern">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success-modern">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên banner</th>
                        <th>Ảnh</th>
                        <th>Sản phẩm liên kết</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banners as $banner)
                        <tr>
                            <td>{{ $banner->id }}</td>
                            <td>{{ $banner->name }}</td>
                            <td>
                                @if($banner->image)
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner" width="120">
                                @endif
                            </td>
                            <td>{{ $banner->product->name_product ?? "N/A"}}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.banner.restore', $banner->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button onclick="return confirm('Bạn có chắc muốn khôi phục banner này?')" class="btn btn-action btn-restore">
                                        <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                                    </button>
                                </form>
                                <form action="{{ route('admin.banner.forceDelete', $banner->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn banner này?')" class="btn btn-action btn-force-delete">
                                        <i class="bi bi-trash3-fill"></i> Xóa vĩnh viễn
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Không có banner nào trong thùng rác.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $banners->links() }}
        </div>
    </div>
</div>
@endsection
