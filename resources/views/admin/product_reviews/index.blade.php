@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<style>
    .card-modern {
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        background: #ffffff;
        overflow: hidden;
        animation: fadeIn 0.4s ease-in-out;
        margin: 20px 0;
    }

    .card-modern-header {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 25px;
        color: #fff;
    }

    .card-modern-header h3 {
        font-weight: 700;
        font-size: 1.4rem;
        margin: 0;
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

    .table-modern {
        border-collapse: separate;
        border-spacing: 0 10px;
        width: 100%;
    }

    .table-modern thead th {
        background: #f1f5f9;
        font-weight: 700;
        color: #4b5563;
        padding: 12px;
        text-align: center;
        text-transform: uppercase;
        font-size: 0.85rem;
        border: none;
    }

    .table-modern tbody td {
        background: #ffffff;
        border: none;
        padding: 12px;
        vertical-align: middle;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .table-modern tbody tr:hover td {
        background: #f8fafc;
        transition: background 0.3s ease;
    }

    .form-select-sm {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 6px 8px;
        font-size: 0.85rem;
    }

    .btn-danger-sm {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-danger-sm:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: scale(1.05);
    }
</style>

<div class="card card-modern">
    <div class="card-modern-header">
        <h3><i class="bi bi-chat-left-text"></i> Danh sách đánh giá sản phẩm</h3>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="search-form mb-3">
            <input type="date" name="date" class="form-control" value="{{ request('date', $date) }}" style="flex: 1;">
            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên người dùng hoặc mã đơn" value="{{ request('keyword') }}" style="flex: 2;">
            <select name="status" class="form-select" style="flex: 1;">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>Hiển thị</option>
                <option value="hidden"  {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
            </select>
            <button type="submit" class="btn-primary-custom"><i class="bi bi-search"></i> Tìm</button>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Người đánh giá</th>
                        <th>Sản phẩm</th>
                        <th>Đơn hàng</th>
                        <th>Rating</th>
                        <th>Bình luận</th>
                        <th>Ảnh</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td>#{{ $review->id_review }}</td>
                            <td>{{ $review->user->name ?? 'Ẩn danh' }}</td>
                            <td>{{ $review->product->name_product ?? 'Không rõ' }}</td>
                            <td>#{{ $review->order_id ?? 'Không có' }}</td>
                            <td>{{ $review->rating }}/5</td>
                            <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $review->comment }}
                            </td>
                            <td>
                                @if ($review->image_url)
                                    <img src="{{ asset('storage/' . $review->image_url) }}" width="50" height="50" class="img-thumbnail rounded">
                                @else
                                    Không có
                                @endif
                            </td>
                            <td>
                            <select class="form-select form-select-sm review-status" data-id="{{ $review->id_review }}">
                                @if($review->status !== 'visible')
                                    {{-- Nếu chưa hiển thị thì vẫn có thể chọn “Chờ duyệt” --}}
                                    <option value="pending" {{ $review->status === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                @endif
                                <option value="visible" {{ $review->status === 'visible' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="hidden"  {{ $review->status === 'hidden' ? 'selected' : '' }}>Ẩn</option>
                            </select>

                            </td>
                            <td>
                                <form action="{{ route('admin.reviews.destroy', $review->id_review) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger-sm"><i class="bi bi-trash"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">Không có đánh giá nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reviews->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $reviews->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.review-status').forEach(select => {
            select.addEventListener('change', function () {
                const reviewId = this.dataset.id;
                const newStatus = this.value;

                fetch("{{ route('admin.reviews.updateStatus') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: reviewId,
                        status: newStatus,
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(err => {
                    alert("Lỗi khi cập nhật trạng thái!");
                    console.error(err);
                });
            });
        });
    });
</script>
@endsection
