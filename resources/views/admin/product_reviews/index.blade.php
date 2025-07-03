@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Danh sách đánh giá sản phẩm</h3>

        <form action="{{ route('admin.reviews.index') }}" method="GET" class="d-flex gap-2 w-100">
            <input type="date" name="date" class="form-control"
                value="{{ request('date', $date) }}" style="width: 25%;">

            <input type="text" name="keyword" class="form-control"
                placeholder="Tìm theo tên người dùng hoặc mã đơn"
                value="{{ request('keyword') }}">

            <select name="status" class="form-select" style="width: 20%;">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>Hiển thị</option>
                <option value="hidden"  {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
            </select>

            <button type="submit" class="btn btn-primary">Tìm</button>
        </form>

    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
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
                                    <img src="{{ asset('storage/' . $review->image_url) }}" width="60" height="60" class="img-thumbnail">
                                @else
                                    Không có
                                @endif
                            </td>
                            <td>
                                <select class="form-select form-select-sm review-status" data-id="{{ $review->id_review }}">
                                    <option value="pending" {{ $review->status === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="visible" {{ $review->status === 'visible' ? 'selected' : '' }}>Hiển thị</option>
                                    <option value="hidden"  {{ $review->status === 'hidden' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </td>

                            
                            <td>
                                <form action="{{ route('admin.reviews.destroy', $review->id_review) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </form>
 
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center">Không có đánh giá nào.</td></tr>
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
