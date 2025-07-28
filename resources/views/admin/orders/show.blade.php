@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Chi tiết đơn hàng</h3>
    </div>

    <div class="card-body" >
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="border p-3 mb-4 rounded shadow-sm">
            <h5 class="fw-bold mb-3">📦 Trạng thái đơn hàng</h5>

            @php
                $steps = [
                    'pending' => ['label' => 'Chờ xử lý', 'icon' => 'fas fa-hourglass-start'],
                    'processing' => ['label' => 'Đang xử lý', 'icon' => 'fas fa-sync-alt fa-spin'],
                    'shipping' => ['label' => 'Đang giao', 'icon' => 'fas fa-truck'],
                    'completed' => ['label' => 'Hoàn thành', 'icon' => 'fas fa-check-circle'],
                    'canceled' => ['label' => 'Đã hủy', 'icon' => 'fas fa-times-circle'],
                ];
            @endphp

            {{-- Timeline trạng thái --}}
            <div class="d-flex justify-content-between align-items-center" id="orderTimeline">
                @foreach ($steps as $status => $step)
                    @php
                        $statusIndex = array_search($status, array_keys($steps));
                        $currentIndex = array_search($order->status, array_keys($steps));
                    @endphp
                    <div class="text-center flex-fill position-relative">
                        <div class="rounded-circle mb-2 mx-auto d-flex align-items-center justify-content-center step-circle"
                            data-status="{{ $status }}"
                            style="
                                width: 60px; height: 60px;
                                {{ $statusIndex < $currentIndex ? 'background: linear-gradient(135deg, #28a745, #218838); color: #fff;'
                                    : ($statusIndex == $currentIndex ? 'background: linear-gradient(135deg, #0d6efd, #0dcaf0); color: #fff; box-shadow: 0 0 15px rgba(13,110,253,0.8);'
                                    : 'background: #dee2e6; color: #6c757d;') }}">
                            <i class="{{ $step['icon'] }} fs-4"></i>
                        </div>
                        <small class="{{ $statusIndex < $currentIndex ? 'text-success fw-bold'
                                    : ($statusIndex == $currentIndex ? 'text-primary fw-bold' : 'text-muted') }}">
                            {{ $step['label'] }}
                        </small>
                    </div>
                    @if (!$loop->last)
                        <div class="flex-fill mx-1 d-flex align-items-center">
                            <div class="w-100"
                                style="height: 5px; {{ $statusIndex < $currentIndex ? 'background: #28a745;' : 'background: #dee2e6;' }}">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>


        <div class="border p-3 mb-4 rounded shadow-sm">
            {{-- Thông tin người dùng --}}
            <h5>🧍 Thông tin khách hàng</h5>
            <ul>
                <li><strong>Tên:</strong> {{ $user->name ?? 'N/A' }}</li>
                <li><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</li>
                <li><strong>Số điện thoại:</strong> {{ $user->phone_number ?? 'N/A' }}</li>
                <li><strong>Địa chỉ:</strong> {{ $user->address ?? 'N/A' }}</li>
            </ul>
        </div>

        <div class="border p-3 mb-4 rounded shadow-sm">
            {{-- Danh sách sản phẩm --}}
            <h5 class="mt-3">📦 Sản phẩm đã đặt</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-secondary">
                        <tr>
                            <th></th>
                            <th>Sản phẩm</th>
                            <th>Size</th>
                            <th>color</th>
                            <th>Số Lượng</th>
                            <th>Giá</th>
                            <th>Tổng tiền đơn hàng</th>
                            <th>Phí ship</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $tongTien = 0; @endphp
                        @foreach ($order_items as $item)
                            @php
                                $price = optional($item->variant)->price ?? 0;
                                $quantity = $item->quantity ?? 0;
                                $thanhTien = $price * $quantity;
                                $tongTien += $thanhTien + 30000;
                                $shippingFee = 30000
                            @endphp
                            <tr>
                                <td>{{ $item->variant->id_variant ?? 'Không rõ' }}</td>
                                <td>{{ $item->variant->product->name_product ?? 'Không rõ' }}</td>
                                <td>{{ $item->variant->size->name ?? 'Không rõ' }}</td>
                                <td>{{ $item->variant->color->name ?? 'không rõ' }}</td>
                                <td>{{ $item->quantity ?? 'Không rõ' }}</td>
                                <td>{{ number_format($price, 0, ',', '.') }} VND</td>
                                <td>{{ number_format($thanhTien, 0, ',', '.') }} VND</td>
                                <td>{{ number_format($shippingFee, 0, ', ', '.') }} VND</td>
                            </tr>
                        @endforeach
                        {{-- Dòng tổng tiền --}}
                        <tr class="table-success">
                            <td colspan="5" class="text-end"><strong>Tổng tiền:  </strong></td>
                            <td></td>
                            <td><strong>{{ number_format($tongTien, 0, ',', '.') }} VND</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const steps = Array.from(document.querySelectorAll('#orderTimeline .step-circle'));
        const lines = Array.from(document.querySelectorAll('#orderTimeline .step-line'));
        const labels = Array.from(document.querySelectorAll('#orderTimeline .step-label'));
        const dropdown = document.getElementById('statusDropdown');

        function updateTimeline(selectedStatus) {
            let currentIndex = steps.findIndex(step => step.dataset.status === selectedStatus);

            steps.forEach((step, index) => {
                if (index < currentIndex) {
                    step.style.background = 'linear-gradient(135deg, #28a745, #218838)';
                    step.style.color = '#fff';
                    lines[index]?.style.setProperty('background', '#28a745');
                    labels[index].classList.add('text-success', 'fw-bold');
                } else if (index === currentIndex) {
                    step.style.background = 'linear-gradient(135deg, #0d6efd, #0dcaf0)';
                    step.style.color = '#fff';
                    step.style.boxShadow = '0 0 15px rgba(13,110,253,0.8)';
                    labels[index].classList.add('text-primary', 'fw-bold');
                } else {
                    step.style.background = '#dee2e6';
                    step.style.color = '#6c757d';
                    lines[index]?.style.setProperty('background', '#dee2e6');
                    labels[index].classList.remove('text-success', 'text-primary', 'fw-bold');
                }
            });
        }

        // Gọi khi load lần đầu
        updateTimeline(dropdown.value);

        // Gọi khi chọn trạng thái mới
        dropdown.addEventListener('change', function () {
            updateTimeline(this.value);
        });
    });
</script>

@endsection
