@extends('layouts.admin')

@section('title', 'Thêm Biến Thể')

@section('content')
@php
    $productId = request('product_id');
    $colorId = request('color_id');
@endphp
<style>
    .card-custom {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .variant-item {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
        position: relative;
    }
    .variant-item:hover {
        transform: scale(1.01);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-add-variant {
        background: linear-gradient(135deg, #42a5f5, #478ed1);
        color: #fff;
        font-weight: 600;
        border-radius: 30px;
        padding: 8px 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(66, 165, 245, 0.4);
    }

    .btn-add-variant:hover {
        background: linear-gradient(135deg, #2196f3, #1e88e5);
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(33, 150, 243, 0.5);
    }

    .btn-remove-variant {
        background: #f44336;
        color: #fff;
        font-weight: 500;
        border-radius: 50px;
        padding: 6px 15px;
        transition: all 0.3s ease;
    }

    .btn-remove-variant:hover {
        background: #d32f2f;
        box-shadow: 0 3px 10px rgba(244, 67, 54, 0.4);
        transform: scale(1.05);
    }

    .btn-save,
    .btn-back {
        font-weight: 600;
        border-radius: 30px;
        padding: 10px 25px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #4caf50, #43a047);
        color: #fff;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #388e3c, #2e7d32);
        transform: translateY(-2px);
    }

    .btn-back {
        background: linear-gradient(135deg, #9e9e9e, #757575);
        color: #fff;
        margin-left: 10px;
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #616161, #424242);
        transform: translateY(-2px);
    }

    /* Optional: đẹp hơn với select và input */
    select.form-control,
    input.form-control {
        border-radius: 10px;
        box-shadow: none;
        border: 1px solid #ced4da;
        padding: 10px;
    }

    select.form-control:focus,
    input.form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(66, 165, 245, 0.25);
        border-color: #42a5f5;
    }
    .variant-item {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: all 0.3s ease-in-out;
    position: relative;
}

.variant-item:hover {
    transform: scale(1.01);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

/* Label đẹp */
.variant-item label {
    font-weight: 600;
    color: #343a40;
    margin-bottom: 4px;
}

/* Select & input style */
.variant-item select,
.variant-item input {
    border-radius: 10px;
    border: 1px solid #ced4da;
    padding: 10px;
    box-shadow: none;
    height: 45px;
}

.variant-item select:focus,
.variant-item input:focus {
    box-shadow: 0 0 0 0.2rem rgba(66, 165, 245, 0.25);
    border-color: #42a5f5;
}

/* Nút xóa đỏ, bo tròn hoàn toàn */
 .btn-remove-variant {
        background: #f44336;
        color: #fff;
        font-weight: 500;
        border-radius: 50px;
        padding: 6px 15px;
        transition: all 0.3s ease;
    }
    .btn-remove-variant:hover {
        background: #d32f2f;
        box-shadow: 0 3px 10px rgba(244, 67, 54, 0.4);
        transform: scale(1.05);
    }

.btn-remove-variant i {
    font-size: 18px;
}



</style>


<div class="card card-custom">
    <div class="card-header bg-gradient-primary text-white rounded-top">
        <h3 class="card-title mb-0">
            <i class="bi bi-plus-circle me-1"></i> Thêm Biến Thể Màu
        </h3>
    </div>

    <div class="card-body">
        @if (!$productId || !$colorId)
            <div class="alert alert-danger">
                Thiếu <strong>product_id</strong> hoặc <strong>color_id</strong> trên URL.
            </div>
        @else
            <div class="product-color-info p-3 mb-4" style="background: #f1f3f5; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.05);">
                <div class="mb-2">
                    <strong class="text-dark" style="font-size: 18px;">
                        <i class="bi bi-box-seam-fill me-1 text-primary"></i> Sản phẩm:
                    </strong>
                    <span class="text-secondary" style="font-size: 17px;">{{ $product->name_product }}</span>
                </div>

                <div class="color-info d-flex align-items-center gap-3 mt-2">
                    <div style="border: 2px solid #dee2e6; border-radius: 10px; overflow: hidden; width: 60px; height: 60px;">
                        <img src="{{ asset('storage/' . $color->image) }}"
                            alt="{{ $color->name_color }}"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 16px;">
                            <i class="bi bi-palette-fill text-warning me-1"></i>
                            <strong>{{ $color->name_color }}</strong>
                        </div>
                        <small class="text-secondary">Mã màu: #{{ $color->id_color }}</small>
                    </div>
                </div>
            </div>


           <form action="{{ route('admin.variants.store_item') }}" method="POST" id="variantForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $productId }}">
                <input type="hidden" name="variants[0][color_id]" value="{{ $colorId }}">

                <div id="variant-container">
                    <div class="variant-item row g-3 align-items-end mb-3">
                        <div class="col-md-4">
                            <label class="fw-semibold">Kích Cỡ (Size):</label>
                            <select name="variants[0][children][0][size_id]" class="form-control form-select" >
                                <option value="">-- Chọn Size --</option>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->id_size }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                            <div class="error-message text-danger mt-1"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold">Giá:</label>
                            <input type="number" name="variants[0][children][0][price]" class="form-control" placeholder="VNĐ" min="0">
                            <div class="error-message text-danger mt-1"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold">Số lượng:</label>
                            <input type="number" name="variants[0][children][0][quantity]" class="form-control" placeholder="0" min="0">
                            <div class="error-message text-danger mt-1"></div>
                        </div>
                        <div class="col-md-2 ">
                            <button type="button" class="btn btn-remove-variant w-100">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="button" id="add-variant" class="btn btn-add-variant mb-3">+ Thêm size khác</button>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-save">Lưu biến thể</button>
                    <a href="{{ route('admin.variants.show', $productId) }}" class="btn btn-back">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </form>
        @endif
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let variantIndex = 1;
    const sizesOptions = @json($sizes); // danh sách size từ Controller
    const form = document.getElementById('variantForm');

    // quản lý size
    function getUsedSizeIds() {
        const selects = document.querySelectorAll('select[name^="variants[0][children]"][name$="[size_id]"]');
        return Array.from(selects)
            .map(select => select.value)
            .filter(value => value !== '');
    }

    function createSizeOptionsHTML(excludeIds = [], currentValue = '') {
        let options = '<option value="">-- Chọn Size --</option>';
        sizesOptions.forEach(size => {
            const id = String(size.id_size);
            const isExcluded = excludeIds.includes(id);
            const isCurrent = currentValue === id;
            if (!isExcluded || isCurrent) {
                options += `<option value="${id}" ${isCurrent ? 'selected' : ''}>${size.name}</option>`;
            }
        });
        return options;
    }

    function refreshAllSizeSelects() {
        const selects = document.querySelectorAll('select[name^="variants[0][children]"][name$="[size_id]"]');
        const used = getUsedSizeIds();
        selects.forEach(select => {
            const currentValue = select.value;
            select.innerHTML = createSizeOptionsHTML(used.filter(id => id !== currentValue), currentValue);
        });
    }

    document.getElementById('add-variant').addEventListener('click', function () {
        const container = document.getElementById('variant-container');

        const row = document.createElement('div');
        row.classList.add('variant-item', 'row', 'g-3', 'align-items-end', 'mb-3');

        row.innerHTML = `
            <div class="col-md-4">
                <label class="fw-semibold">Kích Cỡ (Size):</label>
                <select name="variants[0][children][${variantIndex}][size_id]" class="form-control form-select">
                    ${createSizeOptionsHTML(getUsedSizeIds())}
                </select>
                <div class="error-message text-danger"></div>
            </div>
            <div class="col-md-3">
                <label class="fw-semibold">Giá:</label>
                <input type="number" name="variants[0][children][${variantIndex}][price]" class="form-control" placeholder="VNĐ" min="0">
                <div class="error-message text-danger"></div>
            </div>
            <div class="col-md-3">
                <label class="fw-semibold">Số lượng:</label>
                <input type="number" name="variants[0][children][${variantIndex}][quantity]" class="form-control" placeholder="0" min="0">
                <div class="error-message text-danger"></div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-remove-variant w-100">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        `;

        container.appendChild(row);
        variantIndex++;
        refreshAllSizeSelects();
    });

    document.addEventListener('change', function (e) {
        if (e.target.matches('select[name^="variants[0][children]"][name$="[size_id]"]')) {
            refreshAllSizeSelects();
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-variant')) {
            e.target.closest('.variant-item').remove();
            refreshAllSizeSelects();
        }
    });

    window.addEventListener('DOMContentLoaded', refreshAllSizeSelects);

    // validate
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
        const errorDiv = input.closest('.col-md-4, .col-md-3')?.querySelector('.error-message');
        if (errorDiv) errorDiv.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
        input.classList.add('is-invalid');
    }

    function clearError(input) {
        const errorDiv = input.closest('.col-md-4, .col-md-3')?.querySelector('.error-message');
        if (errorDiv) errorDiv.textContent = '';
        input.classList.remove('is-invalid');
    }

    function validateField(input) {
        const name = input.name.match(/\[(\w+)\]$/)?.[1] || input.name;
        const rule = rules[name];
        if (!rule) return true;

        const value = (input.value || '').trim();

        if (rule.required && !value) {
            showError(input, messages[name].required); return false;
        }
        if (rule.numeric && value && isNaN(value)) {
            showError(input, messages[name].numeric); return false;
        }
        if (rule.min && value && Number(value) < rule.min) {
            showError(input, messages[name].min); return false;
        }

        clearError(input);
        return true;
    }

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
