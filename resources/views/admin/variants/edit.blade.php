@extends('layouts.admin')

@section('title', 'Cập Nhật Biến Thể')

@section('content')
<style>
    .card-clean {
        max-width: 700px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        padding: 30px;
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
        margin-bottom: 6px;
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
    <h2><i class="bi bi-pencil-square"></i> ✏️ Cập Nhật Biến Thể</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.variants.update', $variant->id_variant) }}" method="POST" id="variantForm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="product_id" class="form-label">Sản Phẩm</label>
            <select name="product_id" id="product_id" class="form-select" required>
                <option value="">-- Chọn Sản Phẩm --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id_product }}" 
                        {{ $variant->product_id == $product->id_product ? 'selected' : '' }}>
                        {{ $product->name_product }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="size_id" class="form-label">Kích Cỡ (Size)</label>
            <select name="size_id" id="size_id" class="form-select" >
                <option value="">-- Chọn Size --</option>
                @foreach ($sizes as $size)
                    <option value="{{ $size->id_size }}" 
                        {{ $variant->size_id == $size->id_size ? 'selected' : '' }}>
                        {{ $size->name }}
                    </option>
                @endforeach
            </select>
            <div class="error-message text-danger"></div>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" name="price" id="price" class="form-control" 
                value="{{ old('price', (int) $variant->price) }}"
                placeholder="Nhập giá biến thể" min="0" step="1000">

            <div class="error-message text-danger"></div>
        </div>

        <div class="mb-4">
            <label for="quantity" class="form-label">Số Lượng</label>
            <input type="number" name="quantity" id="quantity" class="form-control" 
                value="{{ $variant->quantity }}" min="0"  placeholder="Nhập số lượng">
            <div class="error-message text-danger"></div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary-custom">
                <i class="bi bi-check-circle"></i> Cập Nhật
            </button>
            <a href="{{ route('admin.variants.show', $product->id_product) }}" class="btn btn-secondary-custom">
                <i class="bi bi-arrow-left-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('variantForm');

    // QUY TẮC + THÔNG BÁO LỖI
    const rules = {
        size_id: { required: true },
        price: { required: true, numeric: true, min: 1000 },
        quantity: { required: true, numeric: true, min: 1 }
    };

    const messages = {
        size_id: { required: 'Vui lòng chọn size.' },
        price: {
            required: 'Vui lòng nhập giá.',
            numeric: 'Giá phải là số.',
            min: 'Giá phải >= 1000.'
        },
        quantity: {
            required: 'Vui lòng nhập số lượng.',
            numeric: 'Số lượng phải là số.',
            min: 'Số lượng tối thiểu là 1.'
        }
    };

function showError(input, message) {
    const errorDiv = input.parentElement.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
    }
    input.classList.add('is-invalid');
}

function clearError(input) {
    const errorDiv = input.parentElement.querySelector('.error-message');
    if (errorDiv) errorDiv.textContent = '';
    input.classList.remove('is-invalid');
}

    // VALIDATE FIELD
    function validateField(input) {
        const name = input.name.includes('size_id')
            ? 'size_id'
            : input.name.includes('price')
                ? 'price'
                : input.name.includes('quantity')
                    ? 'quantity'
                    : null;

        if (!name) return true; // không phải field cần validate

        const rule = rules[name];
        const value = (input.value || '').trim();

        if (rule.required && !value) {
            showError(input, messages[name].required);
            return false;
        }
        if (rule.numeric && value && isNaN(value)) {
            showError(input, messages[name].numeric);
            return false;
        }
        if (rule.min && value && Number(value) < rule.min) {
            showError(input, messages[name].min);
            return false;
        }

        clearError(input);
        return true;
    }

    // GẮN SỰ KIỆN VALIDATE
    form.addEventListener('input', e => validateField(e.target));
    form.addEventListener('change', e => validateField(e.target));
    form.addEventListener('submit', e => {
        let isValid = true;
        form.querySelectorAll('input, select').forEach(input => {
            if (!validateField(input)) isValid = false;
        });
        if (!isValid) e.preventDefault();
    });
});
</script>

@endsection
