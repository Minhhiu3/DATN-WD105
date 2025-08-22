@extends('layouts.admin')

@section('title', '⚡ Chi tiết Mã Giảm Giá')

@section('content')
<style>
    .discount-detail-card {
        max-width: 800px;
        margin: 30px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 30px 40px;
        animation: fadeIn 0.4s ease-in-out;
    }

    .discount-header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
    }

    .discount-header h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #4e73df;
        margin: 0 auto;
    }

    .discount-detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
    }
    .discount-detail-label {
        font-weight: 600;
        color: #6b7280;
    }
    .discount-detail-value {
        font-size: 1rem;
        color: #111827;
        font-weight: 500;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .status-active {
        background: #d1fae5;
        color: #065f46;
    }
    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-back {
        background: #e5e7eb;
        border-radius: 8px;
        padding: 8px 20px;
        color: #374151;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.3s ease;
    }
    .btn-back:hover {
        background: #d1d5db;
    }
    .btn-save {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn-save:hover {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        transform: translateY(-1px);
    }
</style>

<div class="discount-detail-card">
    <div class="discount-header">
        <h3><i class="bi bi-ticket-perforated-fill"></i> Chi tiết Mã Giảm Giá</h3>
    </div>

    <div class="discount-detail-row">
        <div class="discount-detail-label">ID:</div>
        <div class="discount-detail-value">{{ $discount->discount_id }}</div>
    </div>

    <div class="discount-detail-row">
        <div class="discount-detail-label">Mã Code:</div>
        <div class="discount-detail-value">{{ $discount->code }}</div>
    </div>

    <div class="discount-detail-row">
        <div class="discount-detail-label">Giá trị:</div>
        <div class="discount-detail-value">
            {{ $discount->type === 'percent' ? $discount->value . '%' : number_format($discount->value) . ' VNĐ' }}
        </div>
    </div>

    <div class="discount-detail-row">
        <div class="discount-detail-label">Loại:</div>
        <div class="discount-detail-value">
            {{ $discount->type === 'percent' ? 'Phần trăm' : 'Cố định (VNĐ)' }}
        </div>
    </div>

    <div class="discount-detail-row">
        <div class="discount-detail-label">Đơn tối thiểu:</div>
        <div class="discount-detail-value">
            {{number_format($discount->min_order_value) . ' VNĐ' }}
        </div>
    </div>

    <div class="discount-detail-row">
        <div class="discount-detail-label">Đơn tối đa:</div>
        <div class="discount-detail-value">
            {{ number_format($discount->max_order_value) . ' VNĐ' }}
        </div>
    </div>

        
    <div class="discount-detail-row">
        <div class="discount-detail-label">Số lượng:</div>
        <div class="discount-detail-value">
            {{ $discount->quantity }}
        </div>
    </div>

    <div class="discount-detail-row">
    <div class="discount-detail-label">Ngày bắt đầu:</div>
        <div class="discount-detail-value">
            {{ \Carbon\Carbon::parse($discount->start_date)->format('d/m/Y') }}
        </div>
    </div>

    <div class="discount-detail-row">
        <div class="discount-detail-label">Ngày kết thúc:</div>
        <div class="discount-detail-value">
            {{ \Carbon\Carbon::parse($discount->end_date)->format('d/m/Y') }}
        </div>
    </div>

    <div class="discount-detail-row">
        <div class="discount-detail-label">Trạng thái:</div>
        <div class="discount-detail-value">
            @if ($discount->is_active)
                <span class="status-badge status-active">Đang hoạt động</span>
            @else
                <span class="status-badge status-inactive">Ngừng</span>
            @endif
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('admin.discounts.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>

        <a href="{{ route('admin.discounts.edit', $discount->discount_id) }}" class="btn-save">
            <i class="bi bi-pencil-square"></i> Sửa
        </a>
    </div>

</div>
@endsection
