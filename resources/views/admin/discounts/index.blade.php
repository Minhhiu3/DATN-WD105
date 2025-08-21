@extends('layouts.admin')

@section('title', 'Quản lý Mã Giảm Giá')

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
    .badge-active {
        background: linear-gradient(135deg, #42a5f5, #1e88e5);
        color: #fff;
        border-radius: 20px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .badge-inactive {
        background: #d1d5db;
        color: #374151;
        border-radius: 20px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        font-weight: 600;
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
            .btn-primary-custom {
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            border: none;
            border-radius: 10px;
            padding: 8px 20px;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.3s ease, transform 0.2s ease;
            white-space: nowrap;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(56, 189, 248, 0.4);
        }
                .search-form input,
        .search-form select {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding: 8px 12px;
            font-size: 0.95rem;
            flex: none;
            /* không để input kéo dài */
            min-width: 200px;
            transition: all 0.3s ease;
            width: 30%;
        }

        .search-form input:focus,
        .search-form select:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 0.15rem rgba(56, 189, 248, 0.3);
            background: #fff;
        }
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
</style>

<div class="card card-modern">
    <div class="card-modern-header">
        <span><i class="fas fa-tags"></i> Danh sách mã giảm giá</span>
        <a href="{{ route('admin.discounts.create') }}" class="btn btn-add-modern">
            <i class="fas fa-plus-circle"></i> Thêm mới
        </a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-modern-success" id="success-alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
 <!-- Form tìm kiếm và lọc -->
       <form method="GET" action="{{ route('admin.discounts.index') }}"  class="search-form">
            <div class="row g-3">
                <div class="col-12 col-md-5">
                    <input type="text" name="keyword" class="form-control w-100" 
                        placeholder="Tìm kiếm sản phẩm..." value="{{ request('keyword') }}">
                </div>
                <div class="col-12 col-md-3">
                    <select name="type" class="form-select form-control w-100">
                        <option value="">Tất cả loại mã giảm giá</option>
                        <option value="0" >Phần trăm</option>
                        <option value="1" >Cố định</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="is_active" class="form-select form-control w-100">
                        <option value="">Tất cả trạng thái</option>
                        <option value="0" >Không hoạt động</option>
                        <option value="1" >Hoạt động</option>
                    </select>
                </div>
                <div class="col-12 col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-search"></i> Tìm</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã</th>
                        <th>Loại</th>
                        <th>Giá trị</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($discounts as $discount)
                        <tr>
                            <td>{{ $discount->discount_id }}</td>
                            <td>{{ $discount->code }}</td>
                            <td>
                                @if ($discount->type == 0)
                                    %
                                @elseif ($discount->type == 1)
                                    Giá trị
                                @else
                                    Không xác định
                                @endif
                            </td>
                            <td>
                                @if ($discount->type == 0)
                                    {{(int) $discount->value }} %
                                @elseif ($discount->type == 1)
                                    {{ number_format($discount->value, 0, ',', '.') }} VNĐ
                                @endif
                            </td>
                            <td>{{ $discount->quantity }}</td>
                            <td>
                                {{-- @php
                                    $today = now();
                                    $discounts = App\Models\DiscountCode::all();
                                    $activeDiscounts = []; // Mảng để lưu trữ trạng thái

                                    foreach ($discounts as $discount1) {
                                        if ($discount1->is_active && $discount1->end_date < $today) {
                                            $discount1->is_active = 0;
                                            $discount1->save();
                                        }
                                        $activeDiscounts[] = $discount1->is_active; // Lưu trạng thái
                                    }
                                @endphp --}}
                                @if ($discount->is_active === 1)
                                    <span class="badge-active">Hoạt động</span>
                                @else
                                    <span class="badge-inactive">Không </span>
                                @endif

                                
                            </td>
                            <td>
                                <a href="{{ route('admin.discounts.show', $discount->discount_id) }}" 
                                   class="btn-action btn-view">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('admin.discounts.edit', $discount->discount_id) }}" 
                                   class="btn-action btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.discounts.destroy', $discount->discount_id) }}" 
                                      method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn có chắc muốn xóa?')" 
                                            type="submit" 
                                            class="btn-action btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Không có mã giảm giá nào.</td>
                        </tr>
                    @endforelse
                        <tr>
                            <td colspan="6" class="text-center text-muted"></td>
                            <td colspan="1" class="text-center text-muted">        
                                <a href="{{ route('admin.discounts.trash') }}" class="btn ">
                                        <i class="bi bi-trash3-fill"></i> Thùng Rác
                                </a>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
@if ($discounts->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $discounts->links('pagination::bootstrap-5') }}
    </div>
@endif

    </div>
</div>
<script>
  window.addEventListener('load', function() {
  let isLoading = false;

  function reloadPageInBackground() {
    if (isLoading) return;
    isLoading = true;

    // Tạo một iframe ẩn
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);

    // Load trang mới trong iframe
    iframe.contentWindow.location.replace(window.location.href);

    // Lắng nghe sự kiện 'load' của iframe
    iframe.onload = function() {
      // Lấy nội dung mới từ iframe
      var newContent = new DOMParser().parseFromString(iframe.contentDocument.documentElement.innerHTML, 'text/html').querySelector('table');

      // Cập nhật nội dung của trang chính
      var mainElement = document.querySelector('table');
      mainElement.parentNode.replaceChild(newContent, mainElement);

      // Xóa iframe
      document.body.removeChild(iframe);
      isLoading = false;

      // Gọi lại hàm reloadPageInBackground() sau 1 giây
      setTimeout(reloadPageInBackground, 1000);
    };
  }

    // Khởi chạy animation
    reloadPageInBackground();
    });
    // Chờ 3 giây rồi ẩn alert
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            // Tùy chọn: fade out mượt
            alert.style.transition = "opacity 0.7s";
            alert.style.opacity = 0.5;

            // Sau 0.5s remove khỏi DOM
            setTimeout(() => alert.remove(), 500);
        }
    }, 2000);
</script>
@endsection
