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
    .discount-detail-value input,
    .discount-detail-value select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.95rem;
        width: 100%;
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

    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 28px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #e74a3b;
        transition: 0.4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #1cc88a;
    }
    input:checked + .slider:before {
        transform: translateX(22px);
    }
</style>

<div class="discount-detail-card">
    <div class="discount-header">
        <h3><i class="bi bi-ticket-perforated-fill"></i> Chi tiết Mã Giảm Giá</h3>
    </div>

    <form method="POST" action="{{ route('admin.discount_codes.update', $discount->id_discount) }}">
        @csrf
        @method('PUT')

        <div class="discount-detail-row">
            <div class="discount-detail-label">ID:</div>
            <div class="discount-detail-value">{{ $discount->id_discount }}</div>
        </div>

        <div class="discount-detail-row">
            <div class="discount-detail-label">Mã Code:</div>
            <div class="discount-detail-value">
                <input type="text" name="code" value="{{ old('code', $discount->code) }}">
            </div>
        </div>

        <div class="discount-detail-row">
            <div class="discount-detail-label">Giá trị:</div>
            <div class="discount-detail-value" style="width: 100px">
                <input type="number" name="value" value="{{ old('value', $discount->value) }}">
            </div>
        </div>

        <div class="discount-detail-row">
            <div class="discount-detail-label">Loại:</div>
            <div class="discount-detail-value">
                <select name="type">
                    <option value="percent" {{ $discount->type == 'percent' ? 'selected' : '' }}>%</option>
                    <option value="fixed" {{ $discount->type == 'fixed' ? 'selected' : '' }}>VNĐ</option>
                </select>
            </div>
        </div>

        <div class="discount-detail-row">
            <div class="discount-detail-label">Ngày bắt đầu:</div>
            <div class="discount-detail-value">
                <input type="date" name="start_date" value="{{ \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') }}">
            </div>
        </div>

        <div class="discount-detail-row">
            <div class="discount-detail-label">Ngày kết thúc:</div>
            <div class="discount-detail-value">
                <input type="date" name="end_date" value="{{ \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') }}">
            </div>
        </div>

        <div class="discount-detail-row">
            <div class="discount-detail-label">Trạng thái:</div>
            <div class="discount-detail-value">
                <label class="switch">
                    <input type="checkbox" name="status" value="on" {{ $discount->status == 'on' ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('admin.discount_codes.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn-save">
                <i class="bi bi-save"></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>
@endsection
