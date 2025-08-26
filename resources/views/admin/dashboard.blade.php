@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page_title', 'Dashboard')

@section('content')
<div class="d-flex align-items-end justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Bảng điều khiển</h4>
        <small class="text-muted">
            @if(isset($startDate) && isset($endDate))
                Khoảng thời gian: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
            @endif
        </small>
    </div>
    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex gap-2 align-items-end">
        <div>
            <label for="start_date" class="form-label mb-1">Từ ngày</label>
            <input type="date" id="start_date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date', isset($startDate) ? $startDate->format('Y-m-d') : now()->startOfMonth()->format('Y-m-d')) }}">
        </div>
        <div>
            <label for="end_date" class="form-label mb-1">Đến ngày</label>
            <input type="date" id="end_date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date', isset($endDate) ? $endDate->format('Y-m-d') : now()->format('Y-m-d')) }}">
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Mặc định</a>
        </div>
    </form>
</div>
<div class="row g-4">
    {{-- Cards Section --}}
    <div class="col-md-3">
        <div class="card shadow border-0 rounded-lg p-3 d-flex flex-row align-items-center" style="background-color: #f0f4f8;">
            <div class="flex-grow-1">
                <h6 class="text-muted mb-1">Doanh thu @if(!empty($isFiltered) && $isFiltered) (lọc) @else hôm nay @endif</h6>
                <h3 class="fw-bold text-dark">{{ number_format($dailyRevenue) }} ₫</h3>
            </div>
            <div class="ms-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #36A2EB;">
                    <i class="fas fa-dollar-sign text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow border-0 rounded-lg p-3 d-flex flex-row align-items-center" style="background-color: #eaf7f1;">
            <div class="flex-grow-1">
                <h6 class="text-muted mb-1">Tổng khách hàng</h6>
                <h3 class="fw-bold text-dark">{{ $totalUsers }}</h3>
            </div>
            <div class="ms-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #28a745;">
                    <i class="fas fa-users text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow border-0 rounded-lg p-3 d-flex flex-row align-items-center" style="background-color: #fff0f0;">
            <div class="flex-grow-1">
                <h6 class="text-muted mb-1">Sản phẩm</h6>
                <h3 class="fw-bold text-dark">{{ $totalProducts }}</h3>
            </div>
            <div class="ms-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #dc3545;">
                    <i class="fas fa-box text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow border-0 rounded-lg p-3 d-flex flex-row align-items-center" style="background-color: #fff9e6;">
            <div class="flex-grow-1">
                <h6 class="text-muted mb-1">Đơn hàng @if(!empty($isFiltered) && $isFiltered) (lọc) @else hôm nay @endif</h6>
                <h3 class="fw-bold text-dark">{{ $totalOrdersToday }}</h3>
            </div>
            <div class="ms-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #ffc107;">
                    <i class="fas fa-shopping-cart text-white"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Tổng doanh thu tháng --}}
    <div class="col-md-3">
        <div class="card shadow border-0 rounded-lg p-3 d-flex flex-row align-items-center" style="background-color: #e8f0fe;">
            <div class="flex-grow-1">
                <h6 class="text-muted mb-1">Doanh thu tháng</h6>
                <h3 class="fw-bold text-dark">{{ number_format($monthlyRevenue) }} ₫</h3>
            </div>
            <div class="ms-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #6f42c1;">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Top Lists Section --}}
<div class="row g-4 mt-4">
    {{-- Top 5 Khách hàng --}}
<div class="col-md-4">
    <div class="card border-0 shadow rounded-lg overflow-hidden">
        <div class="card-header bg-primary text-white rounded-top-lg d-flex justify-content-between align-items-center">
            <span><i class="fas fa-crown me-2"></i> Top 5 Khách hàng</span>
            <button class="btn btn-sm btn-icon-only text-white toggle-btn"
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseCustomers" 
                    aria-expanded="true" 
                    aria-controls="collapseCustomers">
                <i class="fas fa-minus"></i>
            </button>
        </div>
        <div id="collapseCustomers" class="collapse show">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse ($topCustomers as $customer)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-primary">#{{ $loop->iteration }}</strong> 
                                {{ $customer->name }}<br>
                                <small class="text-muted">{{ $customer->email }}</small>
                            </div>
                            <span class="badge bg-success">
                                {{ number_format($customer->total_spent, 0, ',', '.') }} ₫
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">Không có dữ liệu.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Top 5 Sản phẩm --}}
<div class="col-md-4">
    <div class="card border-0 shadow rounded-lg overflow-hidden">
        <div class="card-header bg-success text-white rounded-top-lg d-flex justify-content-between align-items-center">
            <span><i class="fas fa-fire me-2"></i> Top 5 Sản phẩm</span>
            <button class="btn btn-sm btn-icon-only text-white toggle-btn"
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseProducts" 
                    aria-expanded="true" 
                    aria-controls="collapseProducts">
                <i class="fas fa-minus"></i>
            </button>
        </div>
        <div id="collapseProducts" class="collapse show">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse ($topProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-success">#{{ $loop->iteration }}</strong> 
                                {{ $product->name_product }}
                            </div>
                            <span class="badge bg-primary">{{ $product->total_sold }} đã bán</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">Không có dữ liệu.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>


{{-- Đơn hàng mới --}}
<div class="col-md-4">
    <div class="card border-0 shadow rounded-lg overflow-hidden">
        <div class="card-header bg-warning text-dark rounded-top-lg d-flex justify-content-between align-items-center">
            <span><i class="fas fa-clock me-2"></i> Đơn hàng mới</span>
            <button class="btn btn-sm btn-icon-only text-dark toggle-btn"
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseOrders" 
                    aria-expanded="true" 
                    aria-controls="collapseOrders">
                <i class="fas fa-minus"></i>
            </button>
        </div>
        <div id="collapseOrders" class="collapse collapse-transition show">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse ($latestOrders as $order)
                        <a href="{{ route('admin.orders.show', $order->id_order) }}" class="text-decoration-none">
                            <li class="list-group-item hover-card d-flex justify-content-between align-items-center rounded shadow-sm my-2 px-3 py-2"
                                style="transition: all 0.3s ease; border: 1px solid #f1f1f1;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle">
                                        <i class="fas fa-user text-white" style="font-size: 1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark d-flex align-items-center">
                                            {{ $order->user->name ?? 'Ẩn danh' }}
                                            <span class="ms-2 status-dot bg-success"></span>
                                        </div>
                                        <div class="text-muted d-flex align-items-center mt-1" style="font-size: 0.85rem;">
                                            <i class="fas fa-clock me-1" style="font-size: 0.85rem;"></i>
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-gradient-success shadow px-3 py-2 fs-6" style="font-size: 0.85rem;">
                                    {{ number_format($order->total_amount) }} ₫
                                </span>
                            </li>
                        </a>
                    @empty
                        <li class="list-group-item text-center text-muted">Không có đơn hàng mới.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>



</div>


<style>
    .collapse-transition {
        transition: height 0.4s ease;
    }

    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
     .hover-card {
        background-color: #fff;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .hover-card:hover {
        background-color: #fdfdfd;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1rem;
        flex-shrink: 0; /* Không co lại khi text dài */
    }
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .badge.bg-gradient-success {
        background: linear-gradient(135deg, #28a745, #218838);
        border-radius: 20px;
        color: #fff;
        font-weight: 500;
    }
    .btn-icon-only {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0;
        margin-left: 50%;
    }
    .btn-icon-only:hover {
        opacity: 0.8;
    }

</style>

<script>
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-minus')) {
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            } else {
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Chart Section --}}
<div class="card mt-4 border-0 shadow rounded-lg">
    <div class="card-body">
        <h5 class="fw-bold text-center mb-3">
            📊 Biểu đồ Doanh thu @if(!empty($isFiltered) && $isFiltered) theo khoảng {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }} @else tháng {{ now()->format('m/Y') }} @endif
        </h5>
        <div style=" margin: 0 auto;">
            <canvas id="revenueChart" height="300"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chartData = @json($chartData);
    const chartLabels = @json(isset($chartLabels) ? $chartLabels : (isset($chartData) ? array_map(fn($i)=>'Ngày '+($i+1), range(0, count($chartData)-1)) : []));

    // Gradient đẹp
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(0, 194, 255, 0.7)');
    gradient.addColorStop(1, 'rgba(0, 194, 255, 0.1)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Doanh thu (₫)',
                data: chartData,
                borderColor: '#36A2EB',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#36A2EB',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: context => `Doanh thu: ${context.raw.toLocaleString('vi-VN')} ₫`
                    },
                    backgroundColor: '#111827',
                    titleColor: '#fff',
                    bodyColor: '#d1d5db'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value.toLocaleString('vi-VN') + ' ₫'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Ngày',
                        font: { size: 14, weight: 'bold' },
                        color: '#6b7280'
                    }
                }
            }
        }
    });
});
</script>
@endsection
