@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Danh sách đơn hàng</h3>

            <form action="{{ route('admin.orders.index') }}" 
                    method="GET" 
                    class="d-flex align-items-center ms-auto w-50 gap-2" style="margin-left: 50%">
                <input type="date"
                    name="date"
                    class="form-control "
                    value="{{ request('date', $date) }}" style=" width: 25%; height: 100%; margin-left: 1% " > 

                <input type="text"
                    name="code"
                    class="form-control  "
                    placeholder="Mã đơn"
                    value="{{ request('code', $code ?? '') }}" style=" width: 30%; height: 100%; margin-left: 1% ">

                <button type="submit" class="btn btn-primary  " style=" width: 50px;  margin-left: 1% ">Lọc</button>
            </form>
        </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>#{{ $order->id_order }}</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                           @php
                                $statusLevels = [
                                    'pending' => 1,
                                    'processing' => 2,
                                    'shipping' => 3,
                                    'completed' => 4,
                                    'canceled' => 5,
                                ];

                                $currentStatus = $order->status;
                            @endphp

                            <td>
                                <select class="form-control form-control-sm order-status" data-id="{{ $order->id_order }}">
                                    @foreach ($statusLevels as $status => $level)
                                        @php
                                            // Logic loại bỏ các trạng thái không hợp lệ
                                            $isInvalid = false;

                                            // Không cho chọn trạng thái thấp hơn hiện tại
                                            if ($level < $statusLevels[$currentStatus]) $isInvalid = true;

                                            // Nếu đã completed thì không được quay lại canceled
                                            if ($currentStatus === 'completed' && $status === 'canceled') $isInvalid = true;

                                            // Nếu đã canceled thì không cho đổi gì nữa
                                            if ($currentStatus === 'canceled' && $status !== 'canceled') $isInvalid = true;
                                        @endphp

                                        @if (!$isInvalid)
                                            <option value="{{ $status }}" {{ $currentStatus == $status ? 'selected' : '' }}>
                                                @switch($status)
                                                    @case('pending') Chờ xử lý @break
                                                    @case('processing') Đang xử lý @break
                                                    @case('shipping') Đang giao @break
                                                    @case('completed') Hoàn thành @break
                                                    @case('canceled') Đã hủy @break
                                                @endswitch
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>



                            <td>
                                <a href="{{ route('admin.orders.show', $order->id_order ) }}" class="btn btn-info btn-sm">Chi tiết</a>
                                {{-- <a href="{{ route('admin.orders.edit', $order->id_order ) }}" class="btn btn-warning btn-sm">Cập nhật</a> --}}
                             @if (in_array($order->status, ['pending', 'processing']))
                                <a href="javascript:void(0);" 
                                class="btn btn-danger btn-sm cancel-order-btn" 
                                data-id="{{ $order->id_order }}">
                                Hủy
                                </a>
                            @endif


                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Không có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
        @if ($orders->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Xử lý đổi trạng thái
    document.querySelectorAll('.order-status').forEach(select => {
        select.addEventListener('change', function () {
            const status = this.value;
            const orderId = this.dataset.id;

            fetch("{{ route('admin.orders.updateStatus') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: orderId,
                    status: status,
                }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            })
            .catch(error => {
                alert("Lỗi khi cập nhật!");
                console.error(error);
            });
        });
    });

    // Xử lý hủy đơn hàng
    document.querySelectorAll('.cancel-order-btn').forEach(button => {
        button.addEventListener('click', function () {
            if (!confirm("Bạn có chắc chắn muốn hủy đơn hàng này không?")) return;

            const orderId = this.dataset.id;

            fetch("{{ route('admin.orders.cancel') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ id: orderId })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            })
            .catch(error => {
                alert("Lỗi khi hủy đơn!");
                console.error(error);
            });
        });
    });
});
</script>



@endsection
