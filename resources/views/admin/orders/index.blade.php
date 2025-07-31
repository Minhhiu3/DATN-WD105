@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

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
            gap: 10px;
            align-items: center;
            justify-content: flex-start;
            margin: 15px 0;
            flex-wrap: nowrap;
            /* giữ tất cả trên 1 dòng */
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

        .order-status {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            font-size: 0.85rem;
        }

        .btn-view-sm {
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 6px 12px;
            font-size: 0.85rem;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-view-sm:hover {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            transform: scale(1.05);
        }

        .btn-cancel-sm {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 6px 12px;
            font-size: 0.85rem;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-cancel-sm:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: scale(1.05);
        }

        /* Màu nền dropdown theo trạng thái */
        .order-status.pending {
            background-color: #fef3c7;
            /* vàng nhạt */
            color: #92400e;
        }

        .order-status.processing {
            background-color: #bfdbfe;
            /* xanh dương nhạt */
            color: #1e40af;
        }

        .order-status.shipping {
            background-color: #d1fae5;
            /* xanh lá nhạt */
            color: #065f46;
        }

        .order-status.completed {
            background-color: #bbf7d0;
            /* xanh lá sáng */
            color: #166534;
        }

        .order-status.canceled {
            background-color: #fecaca;
            /* đỏ nhạt */
            color: #7f1d1d;
        }
    </style>

    <div class="card card-modern">
        <div class="card-modern-header">
            <h3><i class="bi bi-cart"></i> Quản lý Đơn hàng</h3>
        </div>

        <div class="card-body">
            {{-- Filter Form --}}
            <form action="{{ route('admin.orders.index') }}" method="GET" class="search-form">
                <input type="date" name="date" class="form-control" value="{{ request('date', $date) }}">
                <input type="text" name="code" class="form-control" placeholder="Mã đơn"
                    value="{{ request('code', $code ?? '') }}">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                     <option value="shipping" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>

                      <option value="shipping" {{ request('status') == 'received' ? 'selected' : '' }}>Đã nhận hàng</option>
                      
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
                <button type="submit" class="btn-primary-custom"><i class="bi bi-search"></i> Tìm</button>
            </form>

            {{-- Orders Table --}}
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái đơn hàng</th>
                            <th>Trạng thái thanh toán</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subTotal = $cartItems->sum(fn($item) => $item->variant->price * $item->quantity);
                            $shippingFee = 30000;
                            $grandTotal = $subTotal + $shippingFee;
                        @endphp
                        @forelse ($orders as $order)
                            <tr>
                                <td>#{{ $order->id_order }}</td>
                                <td>{{ $order->order_code }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>{{ number_format($order->grand_total, 0, ',', '.') }}₫</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                                @php
                                    $statusLevels = [
                                        'pending' => 1,
                                        'processing' => 2,
                                        'shipping' => 3,
                                        'delivered' => 4,
                                        'received' => 5,
                                        'completed' => 6,
                                        'canceled' => 7,
                                    ];

                                    $currentStatus = $order->status;
                                @endphp

                                <td>
                                    {{-- Nếu đã hoàn thành hoặc hủy thì chỉ hiển thị badge --}}
                                    @if (in_array($currentStatus, ['completed', 'canceled']))
                                        <span
                                            class="badge
                                        {{ $currentStatus == 'completed' ? 'bg-success' : 'bg-danger' }}">
                                            @switch($currentStatus)
                                                @case('completed')
                                                    Hoàn thành
                                                @break

                                                @case('canceled')
                                                    Đã hủy
                                                @break
                                            @endswitch
                                        </span>
                                    @else
                                        {{-- Dropdown thay đổi trạng thái --}}
                                        <select class="form-select form-select-sm order-status " style="min-width: 140px;"
                                            data-id="{{ $order->id_order }}">
                                            @foreach ($statusLevels as $status => $level)
                                                @php
                                                    $isAllowed =
                                                        $level == $statusLevels[$currentStatus] ||
                                                        $level == $statusLevels[$currentStatus] + 1;
                                                @endphp

                                                @if ($isAllowed)
                                                    <option value="{{ $status }}"
                                                        class="status-option-{{ $status }}"
                                                        {{ $currentStatus == $status ? 'selected' : '' }}>
                                                        @switch($status)
                                                            @case('pending')
                                                                🟡 Chờ xử lý
                                                            @break

                                                            @case('processing')
                                                                🔵 Đang xử lý
                                                            @break

                                                            @case('shipping')
                                                                🚚 Đang giao
                                                            @break
                                                            @case('delivered')
                                                                📦 Đã giao
                                                            @break
                                                            @case('received')
                                                                📦 Đã nhận hàng
                                                            @break

                                                            @case('completed')
                                                                ✅ Hoàn thành
                                                            @break

                                                            @case('canceled')
                                                                ❌ Đã hủy
                                                            @break
                                                        @endswitch
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                                <td>
                                    @php $payment_status = $order->payment_status; @endphp
                                    @if ($payment_status == 'unpaid')
                                        <span class="btn btn-sm btn-warning text-black">Chưa thanh
                                            toán</span>
                                    @elseif($payment_status == 'paid')
                                        <span class="btn btn-sm btn-success text-white">Đã thanh
                                            toán</span>
                                    @elseif($payment_status == 'canceled')
                                        <span class="btn btn-sm btn-danger text-white">Đã hoàn
                                            tiền</span>
                                    @else
                                        <span class="btn btn-sm btn-light text-black">{{ $payment_status }}</span>
                                    @endif



                                </td>

                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id_order) }}" class="btn-view-sm"><i
                                            class="bi bi-eye"></i> Xem</a>
                                    @if (in_array($order->status, ['pending', 'processing']))
                                        <a href="javascript:void(0);" class="btn-cancel-sm cancel-order-btn"
                                            data-id="{{ $order->id_order }}">
                                            <i class="bi bi-x-circle"></i> Hủy
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Không có đơn hàng nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                @if ($orders->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
        {{-- Script giữ nguyên --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Xử lý đổi trạng thái
                document.querySelectorAll('.order-status').forEach(select => {
                    select.addEventListener('change', function() {
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
                    button.addEventListener('click', function() {
                        if (!confirm("Bạn có chắc chắn muốn hủy đơn hàng này không?")) return;

                        const orderId = this.dataset.id;

                        fetch("{{ route('admin.orders.cancel') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    id: orderId
                                })
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
