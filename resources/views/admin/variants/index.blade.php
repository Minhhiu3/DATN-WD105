@extends('layouts.admin')

@section('title', 'Quản lý Danh mục sản phẩm')

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

/* ==== INPUT ==== */
.quantity-input {
    width: 80px;
    text-align: center;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 5px 10px;
    transition: 0.3s;
}
.quantity-input:focus {
    border-color: #42a5f5;
    box-shadow: 0 0 0 0.1rem rgba(66, 165, 245, 0.3);
    outline: none;
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
.btn-purple-add {
    background: linear-gradient(135deg, #9b59b6, #6c3483); /* tím đậm */
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(155, 89, 182, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-purple-add:hover {
    background: linear-gradient(135deg, #8e44ad, #5b2c6f);
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(108, 52, 131, 0.4);
    color: #fff;
    text-decoration: none;
}


</style>

<div class="card card-modern">
    <div class="card-modern-header">
        <span><i class="bi bi-box-seam"></i> Danh sách Biến Thể</span>
        @php
            $id = basename(request()->url());
        @endphp
               <div class="d-flex gap-2">
            <a href="{{ route('admin.variants.create', ['product_id' => $id]) }}" class="btn btn-add-modern">
                <i class="fas fa-plus-circle"></i> Thêm mới
            </a>
            <a href="{{ route('admin.variants.trash') }}" class="btn btn-add-modern">
                <i class="bi bi-trash3-fill"></i> Thùng Rác
            </a>
        </div>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success-modern">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            @foreach ($groupedVariants as $colorId => $variantsGroup)
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
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ url('/admin/colors/' . $color->id_color . '/edit') }}?product_id={{ $id_product }}" class="btn btn-action btn-edit">
                            <i class="bi bi-pencil-square"></i> Sửa
                        </a>
                        <form action="{{ url('/admin/colors/' . $color->id_color) }}?product_id={{ $id_product }}" method="POST" class="d-inline" style="margin-left: 5px">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-action btn-delete">
                                <i class="bi bi-trash3-fill"></i> Xóa
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
                                <td>
                                    <input type="number" min="0" class="quantity-input" value="{{ $variant->quantity }}"
                                           data-id="{{ $variant->id_variant }}">
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.variants.edit', $variant->id_variant) }}" class="btn btn-action btn-edit">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.variants.destroy', $variant->id_variant) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-action btn-delete">
                                            <i class="bi bi-trash3-fill"></i> Xóa
                                        </button>
                                    </form>
                                </td>

                            </tr>

                        @endforeach
                            <tr>
                                <td colspan="6" class="text-end"></td>
                                <td colspan="6" class="text-center">
                                    <a href="{{ route('admin.variants.create_item', ['product_id' => $id_product,'color_id' => $color->id_color ]) }}"
                                    class="btn btn-purple-add">
                                    <i class="fas fa-plus-circle"></i> Thêm mới
                                    </a>
                                </td>
                                
                            </tr>
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            let id = this.dataset.id;
            let newQuantity = this.value;

            fetch(`/admin/variant/update-quantity/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ quantity: newQuantity })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Số lượng đã được cập nhật!');
                } else {
                    alert('Lỗi khi cập nhật số lượng.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Không thể kết nối đến server.');
            });
        });
    });
</script>
@endsection