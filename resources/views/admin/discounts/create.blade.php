@extends('layouts.admin')

@section('title', 'Thêm Mới Mã Giảm Giá')

@section('content')
<style>
    body {
        background: #f0f2f5;
    }

    .card-clean {
        max-width: 750px;
        margin: 50px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 40px;
        animation: fadeIn 0.4s ease-in-out;
    }

    .card-clean h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #38bdf8;
        text-align: center;
        margin-bottom: 30px;
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

    .form-check-input {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        margin-top: 4px;
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
    <h2>➕ Thêm Mã Giảm Giá</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.discounts.store') }}" method="POST" class="form-modern" novalidate>
        @csrf

        {{-- Mã giảm giá --}}
        <div class="mb-3">
            <label for="code" class="form-label">Mã Giảm Giá</label>
            <input type="text" name="code" id="code" class="form-control" 
                   value="{{ old('code') }}" placeholder="Nhập mã giảm giá" required>
        </div>

        {{-- Loại --}}
        <div class="mb-3">
            <label for="type" class="form-label">Loại</label>
            <select name="type" id="type" class="form-select" required>
                <option value="" disabled selected>-- Chọn loại giảm giá --</option>
                <option value="0" {{ old('type') == '0' ? 'selected' : '' }}>Phần trăm (%)</option>
                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Cố định (VNĐ)</option>
            </select>
        </div>

        {{-- Giá trị --}}
        <div class="mb-3">
            <label for="value" class="form-label">Giá Trị</label>
            <input type="number" step="" name="value" id="value" class="form-control" 
                   value="{{ old('value') }}" placeholder="Nhập giá trị" required>
        </div>
        {{-- Giá trị đơn tối thiểu --}}
        <div class="mb-3">
            <label for="min_order_value" class="form-label">Giá Trị Đơn Tối Thiểu</label>
            <input type="number" step=""  name="min_order_value" id="min_order_value" class="form-control" 
                   value="{{ old('min_order_value') }}" placeholder="Nhập giá trị đơn tối thiểu" required>
        </div>

        {{-- Ngày bắt đầu --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Ngày Bắt Đầu</label>
                <input type="date" name="start_date" id="start_date" class="form-control" 
                       value="{{ old('start_date') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Ngày Kết Thúc</label>
                <input type="date" name="end_date" id="end_date" class="form-control" 
                       value="{{ old('end_date') }}" required>
            </div>
        </div>

        {{-- Hoạt động --}}
        <div class="form-check mb-4">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                   value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Hoạt động</label>
        </div>

        {{-- Nút --}}
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn-primary-custom">
                Thêm
            </button>
            <a href="{{ route('admin.discounts.index') }}" class="btn-secondary-custom">
                Hủy
            </a>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type');
    const valueInput = document.getElementById('value');
    const form = document.querySelector('form.form-modern');
    const valueError = document.getElementById('value-error');

    function showValueError(msg) {
        valueError.textContent = msg;
        valueError.style.display = 'block';
        valueInput.classList.add('is-invalid'); // nếu bạn dùng bootstrap
    }

    function hideValueError() {
        valueError.textContent = '';
        valueError.style.display = 'none';
        valueInput.classList.remove('is-invalid');
    }

    function checkValueValid() {
        const val = Number(valueInput.value);
        if (typeSelect.value === '0') {
            // loại phần trăm: không được > 100
            if (isNaN(val) || valueInput.value === '') {
                showValueError('Vui lòng nhập giá trị giảm (số).');
                return false;
            }
            if (val < 0) {
                showValueError('Giá trị không được âm.');
                return false;
            }
            if (val > 100) {
                showValueError('Khi chọn phần trăm, không được vượt quá 100%.');
                return false;
            }
            // hợp lệ
            hideValueError();
            return true;
        } else {
            // loại cố định: chỉ cần >= 0
            if (isNaN(val) || valueInput.value === '') {
                showValueError('Vui lòng nhập giá trị giảm (số).');
                return false;
            }
            if (val < 0) {
                showValueError('Giá trị không được âm.');
                return false;
            }
            hideValueError();
            return true;
        }
    }

    function updateMaxAttr() {
        if (typeSelect.value === '0') {
            valueInput.setAttribute('max', '100');
        } else {
            valueInput.removeAttribute('max');
        }
        // kiểm tra lại khi đổi type (ví dụ từ VNĐ -> %)
        checkValueValid();
    }

    // sự kiện khi đổi loại
    typeSelect.addEventListener('change', function () {
        updateMaxAttr();
    });

    // sự kiện khi nhập value
    valueInput.addEventListener('input', function () {
        // không dùng reportValidity() ở đây, dùng message inline
        checkValueValid();
    });

    // khi submit form: kiểm tra một lần nữa, nếu có lỗi thì ngăn submit
    form.addEventListener('submit', function (e) {
        const ok = checkValueValid();
        if (!ok) {
            // cuộn đến thông báo lỗi nếu cần
            valueError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            e.preventDefault();
            return false;
        }
    });

    // khởi tạo trạng thái ban đầu (nếu load lại form với old())
    updateMaxAttr();
});
</script>



@endsection
