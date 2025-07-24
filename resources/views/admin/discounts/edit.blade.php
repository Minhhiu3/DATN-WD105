@extends('layouts.admin')

@section('title', 'Sửa Mã Giảm Giá')

@section('content')
<style>
    .card-clean {
        max-width: 700px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 35px;
        animation: fadeIn 0.4s ease-in-out;
    }

    .card-clean h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #38bdf8;
        text-align: center;
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border: 1px solid #d1d5db;
        background-color: #f9fafb;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.15rem rgba(56, 189, 248, 0.3);
        background: #fff;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        color: #ffffff;
        font-weight: 600;
        font-size: 1rem;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(56, 189, 248, 0.4);
    }

    .btn-secondary-custom {
        background: #e5e7eb;
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        color: #374151;
        font-weight: 600;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .btn-secondary-custom:hover {
        background: #d1d5db;
    }
</style>

<div class="card-clean">
    <h2><i class="bi bi-pencil-square"></i> ✏️ Sửa Mã Giảm Giá</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.discounts.update', $discount->discount_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="code" class="form-label">Mã giảm giá</label>
            <input type="text" name="code" id="code" class="form-control"
                value="{{ old('code', $discount->code) }}" placeholder="Nhập mã giảm giá" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Loại</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">-- Chọn loại giảm giá --</option>
                <option value="0" {{ old('type', $discount->type) == 0 ? 'selected' : '' }}>Phần trăm (%)</option>
                <option value="1" {{ old('type', $discount->type) == 1 ? 'selected' : '' }}>Giảm cố định (VND)</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="value" class="form-label">Giá trị</label>
            <input type="number" step="0.01" name="value" id="value" class="form-control"
                value="{{ old('value', $discount->value) }}" placeholder="Nhập giá trị" required>
        </div>

        <div class="mb-3">
            <label for="max_discount" class="form-label">Giảm tối đa</label>
            <input type="number" step="0.01" name="max_discount" id="max_discount" class="form-control"
                value="{{ old('max_discount', $discount->max_discount) }}" placeholder="Nhập giảm tối đa" required>
        </div>

        <div class="mb-3">
            <label for="min_order_value" class="form-label">Giá trị đơn tối thiểu</label>
            <input type="number" step="0.01" name="min_order_value" id="min_order_value" class="form-control"
                value="{{ old('min_order_value', $discount->min_order_value) }}" placeholder="Nhập giá trị đơn tối thiểu" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                    value="{{ old('start_date', $discount->start_date) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Ngày kết thúc</label>
                <input type="date" name="end_date" id="end_date" class="form-control"
                    value="{{ old('end_date', $discount->end_date) }}" required>
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Hoạt động</label>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-circle"></i> Lưu
            </button>
            <a href="{{ route('admin.discounts.index') }}" class="btn-secondary-custom">
                <i class="bi bi-arrow-left-circle"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
