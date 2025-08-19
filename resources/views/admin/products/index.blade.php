@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<style>
    /* ===== CARD ===== */
    .card-modern {
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        background: #ffffff;
        overflow: hidden;
        animation: fadeIn 0.3s ease-in-out;
    }
    .card-modern-header {
        background: linear-gradient(135deg, #4e73df, #1cc88a);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 25px;
        color: #fff;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .card-modern-header h3 {
        font-weight: 700;
        font-size: 1.4rem;
        margin: 0;
    }
    .btn-icon {
        background: #ffffff;
        color: #1cc88a;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.4rem;
        border: 2px solid #1cc88a;
        transition: all 0.3s ease;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    .btn-icon:hover {
        background: #1cc88a;
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    /* ===== TABLE ===== */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 12px;
        width: 100%;
    }
    .table-modern thead th {
        background: #f1f5f9;
        font-weight: 700;
        color: #4b5563;
        padding: 14px;
        border: none;
        text-align: center;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    .table-modern tbody td {
        background: #ffffff;
        border: none;
        padding: 14px;
        vertical-align: middle;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
    }
    .table-modern tbody tr:hover td {
        background: #f8fafc;
        transition: background 0.3s ease;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #fff;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-view {
        background: linear-gradient(135deg, #4e73df, #2e59d9);
    }
    .btn-edit {
        background: linear-gradient(135deg, #f6c23e, #dda20a);
    }
    .btn-delete {
        background: linear-gradient(135deg, #e74a3b, #c0392b);
    }
    .btn-view:hover,
    .btn-edit:hover,
    .btn-delete:hover {
        opacity: 0.95;
        transform: scale(1.05);
    }

    /* Image in table */
    .product-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    .search-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .search-form input,
    .search-form select {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        padding: 10px 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        
    }

    .search-form input:focus,
    .search-form select:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.15rem rgba(56, 189, 248, 0.3);
        background: #fff;
    }
        .btn-primary-custom {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(56, 189, 248, 0.4);
    }
     .alert-success-modern {
        background: #d1f2eb;
        color: #117864;
        border: 1px solid #a3e4d7;
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 15px;
        margin-bottom: 15px;
        animation: fadeIn 0.5s ease-out;
    }
    .alert-danger-modern {
        background: #f9d6d5;
        color: #c0392b;
        border: 1px solid #f5b7b1;
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 15px;
        margin-bottom: 15px;
        animation: fadeIn 0.5s ease-out;
    }
    .switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}
    .switch input { display: none; }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px; width: 18px;
        left: 3px; bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #38bdf8;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    .slider.round { border-radius: 24px; }
    .slider.round:before { border-radius: 50%; }
.stock-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.stock-badge.in-stock {
    background: linear-gradient(135deg, #4ade80, #22c55e); /* xanh lá */
    color: white;
}

.stock-badge.out-of-stock {
    background: linear-gradient(135deg, #f87171, #ef4444); /* đỏ */
    color: white;
}

</style>

<div class="card card-modern">
    <div class="card-modern-header">
        <h3><i class="bi bi-bag"></i> Danh sách Sản phẩm</h3>
        <a href="{{ route('admin.products.create') }}" class="btn-icon" title="Thêm sản phẩm mới">
            <i class="bi bi-plus-lg"></i>
        </a>
         <a href="{{ route('admin.products.trash') }}" class="btn btn-add-modern">
                <i class="bi bi-trash3-fill"></i> Thùng Rác
            </a>
        
    </div>

    <div class="card-body">
        
        <form action="{{ route('admin.products.index') }}" method="GET" class="search-form mb-3">
            {{-- Tìm theo tên --}}
            <input type="text" name="keyword" class="form-control" 
                placeholder="Tìm theo tên sản phẩm" 
                value="{{ request('keyword') }}" style="flex: 2;">

            {{-- Lọc danh mục --}}
            <select name="category" class="form-select" style="flex: 1;">
                <option value="">-- Tất cả danh mục --</option>
                @foreach ($categoris as $category)
                    <option value="{{ $category->id_category }}" 
                        {{ request('category') == $category->id_category ? 'selected' : '' }}>
                        {{ $category->name_category }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary-custom"><i class="bi bi-search"></i> Tìm</button>
        </form>
        @if (session('error'))
            <div class="alert alert-danger-modern">
                <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success-modern">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên</th>
                        <th>Giá</th>                     
                        <th>Danh Mục</th>
                        <th>Thương Hiệu</th>
                        <th>Giá Sale</th>
                        <th>Tổng Kho</th>
                        <th>Trạng Thái Kho</th>
                        <th>Sale</th>
                        <th>Biến Thể</th>                               
                        <th>Album</th>
                        <th>Trạng Thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id_product }}</td>
                            <td>
                                <img src="{{ asset('/storage/'.$product->image) }}" 
                                     alt="{{$product->name_product}}" 
                                     class="product-thumb">
                            </td>
                            <td>{{ $product->name_product }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                            <td>{{ $product->category->name_category ?? 'Chưa có' }}</td>
                            <td>{{ $product->brand->name ?? 'Chưa có' }}</td>

                            <td>
                                @if ($product->advice_product)
                                    {{ $product->advice_product->value }}%
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td>{{ $product->variants_sum_quantity ?? 0 }}</td>
                            <td>
                                @if (($product->variants_sum_quantity ?? 0) > 0)
                                    <span class="stock-badge in-stock">Còn hàng</span>
                                @else
                                    <span class="stock-badge out-of-stock">Hết hàng</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.sale.index', $product->advice_product->id_advice ?? 0) }}" 
                                   class="btn-action btn-view">
                                    <i class="bi bi-tag "></i> 
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.variants.show', $product->id_product) }}" 
                                   class="btn-action btn-view">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.album-products.show', $product->id_product) }}" 
                                   class="btn-action btn-view">
                                    <i class="bi bi-images"></i>
                                </a>
                            </td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" 
                                        class="toggle-visibility" 
                                        data-id="{{ $product->id_product }}" 
                                        {{ $product->visibility === 'visible' ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </td>

                            <td>
                                <a href="{{ route('admin.products.show', $product->id_product) }}" 
                                   class="btn-action btn-view">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id_product) }}" 
                                   class="btn-action btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id_product) }}" 
                                      method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')" 
                                            type="submit" 
                                            class="btn-action btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Không có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {!! $products->links('pagination::bootstrap-5') !!}
            </div>
        @endif
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-visibility').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            let productId = this.dataset.id;
            let status = this.checked ? 'visible' : 'hidden';

            fetch(`/admin/products/${productId}/toggle-visibility`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ visibility: status })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // 🟢 hiển thị thông báo từ server
                } else {
                    alert('❌ Cập nhật thất bại!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('❌ Có lỗi xảy ra!');
            });
        });
    });
});
</script>

@endsection
