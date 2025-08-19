@extends('layouts.admin')

@section('title', 'Thùng Rác - Biến Thể Sản Phẩm')

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

/* ==== BUTTONS ==== */
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

/* ==== TABLE ==== */
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

/* ==== ACTION BUTTONS ==== */
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

/* ==== ALERT ==== */
.alert-success-modern {
    background: #e6fffa;
    color: #117864;
    border: 1px solid #a3e4d7;
    border-radius: 10px;
    padding: 12px 18px;
    font-weight: 500;
    margin-bottom: 16px;
}

/* ==== COLOR HEADER ==== */
.color-header {
    background-color: #e3f2fd;
    font-weight: bold;
    padding: 10px 16px;
    border-radius: 10px;
}
.color-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.color-info img {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    object-fit: cover;
}
.color-info div {
    display: flex;
    flex-direction: column;
}
.color-info div span:first-child {
    font-size: 1.1rem;
    color: #2c3e50;
}
.color-info div span:last-child {
    font-size: 0.85rem;
    color: #6c757d;
}
</style>

<div class="card card-modern">
    <div class="card-modern-header">
        <span><i class="bi bi-trash3-fill"></i> Thùng Rác - Biến Thể</span>
        <a href="{{ route('admin.variants.show', $product_id) }}" class="btn btn-add-modern">
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
            @forelse ($groupedVariants as $colorId => $variantsGroup)
                @php
                    $color = $variantsGroup->first()->color ?? null;
                @endphp

                <!-- Tiêu đề màu -->
                <div class="color-header d-flex justify-content-between align-items-center mb-2">
                    @if ($color)
                        <div class="color-info">
                            <img src="{{ asset('storage/' . $color->image) }}" alt="{{ $color->name_color }}">
                            <div>
                                <span><i class="bi bi-palette-fill"></i> {{ $color->name_color }}</span>
                                <span>ID: {{ $color->id_color }}</span>
                            </div>
                        </div>
                        <div class="color-info">
                            <form action="{{ route('admin.variants.restore-color', $color->id_color) }}" method="POST" class="d-inline">
                                @csrf
                                <button onclick="return confirm('Bạn có chắc muốn khôi phục?')" class="btn btn-action btn-restore">
                                    <i class="bi bi-arrow-counterclockwise"></i> 
                                </button>
                            </form>
                            <form action="{{ route('admin.variants.forceDelete-color', $color->id_color) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn?')" class="btn btn-action btn-force-delete">
                                    <i class="bi bi-trash3-fill"></i> 
                                </button>
                            </form>
                        </div>
                                                            
                    @else
                        <span class="text-danger">Màu sắc không tồn tại hoặc bị lỗi.</span>
                    @endif
                </div>

                <!-- Bảng biến thể theo từng màu -->
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã Sản Phẩm</th>
                            <th>Tên Màu</th>
                            <th>Kích Cỡ</th>
                            <th>Giá</th>
                            <th>Số Lượng</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($variantsGroup as $variant)
                            <tr>
                                <td>{{ $variant->id_variant }}</td>
                                <td>{{ $variant->product->name_product }}</td>
                                <td>{{ $variant->color->name_color }}</td>
                                <td>{{ $variant->size->name }}</td>
                                <td>{{ number_format($variant->price, 0, ',', '.') }} VNĐ</td>
                                <td>{{ $variant->quantity }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.variants.restore', $variant->id_variant) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button onclick="return confirm('Bạn có chắc muốn khôi phục?')" class="btn btn-action btn-restore">
                                            <i class="bi bi-arrow-counterclockwise"></i> Khôi phục
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.variants.forceDelete', $variant->id_variant) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn?')" class="btn btn-action btn-force-delete">
                                            <i class="bi bi-trash3-fill"></i> Xóa vĩnh viễn
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @empty
                <p class="text-center">Không có biến thể nào trong thùng rác.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection