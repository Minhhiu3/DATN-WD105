@extends('layouts.admin')

@section('title', 'Thêm Biến Thể')

@section('content')
@php
    $id_product = request('product_id');
@endphp

<style>
    .card-custom { border-radius: 15px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); }
    .variant-item { background: #f8f9fa; border-radius: 12px; padding: 15px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); transition: all 0.3s ease-in-out; position: relative; }
    .variant-item:hover { transform: scale(1.01); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }

    .btn-add-variant, .btn-add-color {
        background: linear-gradient(135deg, #42a5f5, #478ed1);
        color: #fff; font-weight: 600; border-radius: 30px;
        padding: 8px 20px; transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(66, 165, 245, 0.4);
    }
    .btn-add-variant:hover, .btn-add-color:hover {
        background: linear-gradient(135deg, #2196f3, #1e88e5);
        box-shadow: 0 6px 14px rgba(33, 150, 243, 0.5);
        transform: translateY(-2px);
    }

    .btn-remove-variant {
        background: #f44336; color: #fff; font-weight: 500;
        border-radius: 50px; padding: 6px 15px;
        transition: all 0.3s ease;
    }
    .btn-remove-variant:hover {
        background: #d32f2f;
        box-shadow: 0 3px 10px rgba(244, 67, 54, 0.4);
        transform: scale(1.05);
    }

    .file-input {
        border: 2px dashed #cfd8dc; border-radius: 10px;
        padding: 15px; text-align: center; cursor: pointer;
        transition: border-color 0.3s ease;
    }
    .file-input:hover { border-color: #42a5f5; }
    .file-input input[type="file"] { display: none; }
    .file-input-label { font-weight: 500; color: #607d8b; display: block; }

    .btn-save, .btn-back {
        font-weight: 600; border-radius: 30px;
        padding: 10px 25px; transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
    }
    .btn-back:hover {
        background: linear-gradient(135deg, #616161, #424242);
        transform: translateY(-2px);
    }
    .image-preview {
        margin-top: 10px;
        max-width: 100px;
        max-height: 100px;
        border-radius: 8px;
        object-fit: cover;
        display: none;
    }
</style>

<div class="card card-custom">
    <div class="card-header bg-gradient-primary text-white rounded-top">
        <h3 class="card-title mb-0">
            <i class="bi bi-plus-circle me-1"></i> Thêm Biến Thể Sản Phẩm
        </h3>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger rounded-2 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.variants.store') }}" method="POST" enctype="multipart/form-data" id="form">
            @csrf

            {{-- Chọn sản phẩm --}}
            <div class="form-group mb-4">
                <label for="product_id" class="fw-bold text-primary">Sản Phẩm:</label>
                <select name="product_id" id="product_id" class="form-control form-select" required>
                    <option value="">-- Chọn Sản Phẩm --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id_product }}" {{ (old('product_id') ?? $id_product) == $product->id_product ? 'selected' : '' }}>
                            {{ $product->name_product }}
                        </option>
                    @endforeach
                </select>
                <div class="error-message text-danger"></div>
            </div>

            {{-- Biến thể mẹ (Color + Image) --}}
            <div id="colors-wrapper">
                <div class="variant-item mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="fw-semibold text-secondary">Màu sắc:</label>
                            <input type="text" name="variants[0][color_name]" class="form-control" placeholder="Nhập tên màu">
                            <div class="error-message text-danger"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-semibold text-secondary">Hình ảnh đại diện:</label>
                            <label class="file-input">
                                <span class="file-input-label"><i class="bi bi-upload me-1"></i> Chọn ảnh</span>
                                <input type="file" name="variants[0][image]" accept="image/*" class="image-input">
                                <img class="image-preview" alt="Preview">
                            </label>
                            <div class="error-message text-danger"></div>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-remove-variant w-100">
                                <i class="bi bi-trash3"></i> Xóa Màu
                            </button>
                        </div>
                    </div>

                    {{-- Biến thể con (Size + Price + Quantity) --}}
                    <div class="mt-3">
                        <h6 class="fw-bold text-success">Danh sách Size:</h6>
                        <div class="child-variants-wrapper"></div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-add-variant">
                                <i class="bi bi-plus-circle-fill"></i> Thêm Size
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thêm màu mới --}}
            <div class="mb-3 text-center">
                <button type="button" class="btn btn-add-color">
                    <i class="bi bi-plus-circle-fill"></i> Thêm Màu
                </button>
            </div>

            {{-- Submit --}}
            <div class="mt-4 d-flex justify-content-between">
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-save"></i> Lưu Biến Thể
                </button>
                <a href="{{ route('admin.variants.show', $id_product) }}" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let colorIndex = 1;
    const form = document.querySelector('form');
    const colorsWrapper = document.getElementById('colors-wrapper');
    const allSizes = @json($sizes); // Danh sách size từ server

    // QUY TẮC + THÔNG BÁO LỖI
    const rules = {
        product_id: { required: true },
        color_name: { required: true, string: true, max: 100 },
        image: { required: true, mimes: ['jpeg','jpg','png'], maxSize: 2048 },
        size_id: { required: true },
        price: { required: true, numeric: true, min: 1000 },
        quantity: { required: true, numeric: true, min: 1 }
    };

    const messages = {
        product_id: { required: 'Vui lòng chọn sản phẩm.' },
        color_name: { required: 'Vui lòng nhập tên màu.', max: 'Tên màu không quá 100 ký tự.' },
        image: {
            required: 'Vui lòng chọn ảnh đại diện cho màu.',
            mimes: 'Ảnh chỉ chấp nhận jpeg, jpg, png.',
            maxSize: 'Ảnh không vượt quá 2MB.'
        },
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

    // HÀM HIỂN THỊ LỖI
    function showError(input, message) {
        const errorDiv = input.closest('.col-md-4, .col-md-3, .col-md-2, .form-group')?.querySelector('.error-message');
        if (errorDiv) errorDiv.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
        input.classList.add('is-invalid');
    }

    function clearError(input) {
        const errorDiv = input.closest('.col-md-4, .col-md-3, .col-md-2, .form-group')?.querySelector('.error-message');
        if (errorDiv) errorDiv.textContent = '';
        input.classList.remove('is-invalid');
    }

    // VALIDATE FIELD
    function validateField(input) {
        const name = input.name.match(/\[(\w+)\]$/)?.[1] || input.name;
        const rule = rules[name];
        if (!rule) return true;

        const value = input.type === 'file' ? '' : (input.value || '').trim();

        if (rule.required) {
            if (input.type === 'file') {
                if (input.files.length === 0) { showError(input, messages[name].required); return false; }
            } else if (!value) { showError(input, messages[name].required); return false; }
        }

        if (rule.string && value && typeof value !== 'string') {
            showError(input, messages[name].string); return false;
        }
        if (rule.max && value && value.length > rule.max) {
            showError(input, messages[name].max); return false;
        }

        if (rule.numeric && value && isNaN(value)) {
            showError(input, messages[name].numeric); return false;
        }
        if (rule.min && value && Number(value) < rule.min) {
            showError(input, messages[name].min); return false;
        }

        if (input.type === 'file' && input.files.length > 0) {
            for (const file of input.files) {
                const ext = file.name.split('.').pop().toLowerCase();
                if (rule.mimes && !rule.mimes.includes(ext)) {
                    showError(input, messages[name].mimes); return false;
                }
                if (rule.maxSize && file.size > rule.maxSize * 1024) {
                    showError(input, messages[name].maxSize); return false;
                }
            }
        }

        clearError(input);
        return true;
    }

    // VALIDATE FORM
    form.addEventListener('input', e => validateField(e.target));
    form.addEventListener('change', e => validateField(e.target));
    form.addEventListener('submit', e => {
        let isValid = true;
        form.querySelectorAll('input, select').forEach(input => {
            if (!validateField(input)) isValid = false;
        });
        if (!isValid) e.preventDefault();
    });

    // CHỨC NĂNG THÊM/XOÁ MÀU
    document.querySelector('.btn-add-color').addEventListener('click', function () {
        const colorItem = document.createElement('div');
        colorItem.classList.add('variant-item', 'mb-4');
        colorItem.innerHTML = `
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="fw-semibold text-secondary">Màu sắc:</label>
                    <input type="text" name="variants[${colorIndex}][color_name]" class="form-control" placeholder="Nhập tên màu">
                    <div class="error-message text-danger"></div>
                </div>
                <div class="col-md-4">
                    <label class="fw-semibold text-secondary">Hình ảnh đại diện:</label>
                    <label class="file-input">
                        <span class="file-input-label"><i class="bi bi-upload me-1"></i> Chọn ảnh</span>
                        <input type="file" name="variants[${colorIndex}][image]" accept="image/*" class="image-input">
                        <img class="image-preview" alt="Preview">
                    </label>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" class="btn btn-remove-variant w-100">
                        <i class="bi bi-trash3"></i> Xóa Màu
                    </button>
                </div>
            </div>
            <div class="mt-3">
                <h6 class="fw-bold text-success">Danh sách Size:</h6>
                <div class="child-variants-wrapper"></div>
                <div class="mt-2">
                    <button type="button" class="btn btn-add-variant">
                        <i class="bi bi-plus-circle-fill"></i> Thêm Size
                    </button>
                </div>
            </div>
        `;
        colorsWrapper.appendChild(colorItem);
        colorIndex++;
    });

    colorsWrapper.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-variant') && !e.target.closest('.child-variants-wrapper')) {
            e.target.closest('.variant-item').remove();
        }
    });

    // CHỨC NĂNG THÊM/XOÁ SIZE
    colorsWrapper.addEventListener('click', function (e) {
        if (e.target.closest('.btn-add-variant')) {
            const parentVariant = e.target.closest('.variant-item');
            const childWrapper = parentVariant.querySelector('.child-variants-wrapper');
            const parentIndex = Array.from(colorsWrapper.children).indexOf(parentVariant);
            const childIndex = childWrapper.children.length;

            const childItem = document.createElement('div');
            childItem.classList.add('row', 'g-3', 'align-items-end', 'mb-2');
            childItem.innerHTML = `
                <div class="col-md-4">
                    <label>Kích Cỡ (Size):</label>
                    <select name="variants[${parentIndex}][children][${childIndex}][size_id]" class="form-control form-select size-select">
                        <option value="">-- Chọn Size --</option>
                        ${allSizes.map(size => `<option value="${size.id_size}">${size.name}</option>`).join('')}
                    </select>
                    <div class="error-message text-danger"></div>
                </div>
                <div class="col-md-3">
                    <label>Giá:</label>
                    <input type="number" name="variants[${parentIndex}][children][${childIndex}][price]" class="form-control" min="0" placeholder="VNĐ">
                    <div class="error-message text-danger"></div>
                </div>
                <div class="col-md-3">
                    <label>Số lượng:</label>
                    <input type="number" name="variants[${parentIndex}][children][${childIndex}][quantity]" class="form-control" min="0" placeholder="0">
                    <div class="error-message text-danger"></div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-remove-variant w-100"><i class="bi bi-trash3"></i></button>
                </div>
            `;
            childWrapper.appendChild(childItem);
            updateSizeOptions(childWrapper);
        }
    });

    colorsWrapper.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-variant') && e.target.closest('.child-variants-wrapper')) {
            const childRow = e.target.closest('.row');
            const wrapper = e.target.closest('.child-variants-wrapper');
            childRow.remove();
            updateSizeOptions(wrapper);
        }
    });

    // PREVIEW ẢNH
    colorsWrapper.addEventListener('change', function (e) {
        if (e.target.classList.contains('image-input')) {
            const file = e.target.files[0];
            const preview = e.target.closest('.file-input').querySelector('.image-preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    });

    // ẨN SIZE TRÙNG
    colorsWrapper.addEventListener('change', function (e) {
        if (e.target.classList.contains('size-select')) {
            const wrapper = e.target.closest('.child-variants-wrapper');
            updateSizeOptions(wrapper);
        }
    });

    function updateSizeOptions(wrapper) {
        const selectedValues = Array.from(wrapper.querySelectorAll('.size-select'))
            .map(select => select.value)
            .filter(val => val);

        wrapper.querySelectorAll('.size-select').forEach(select => {
            const currentValue = select.value;
            select.innerHTML = `<option value="">-- Chọn Size --</option>`;
            allSizes.forEach(size => {
                const id = String(size.id_size);
                if (!selectedValues.includes(id) || id === currentValue) {
                    const option = document.createElement('option');
                    option.value = id;
                    option.textContent = size.name;
                    if (id === currentValue) option.selected = true;
                    select.appendChild(option);
                }
            });
        });
    }
});
</script>

@endsection
