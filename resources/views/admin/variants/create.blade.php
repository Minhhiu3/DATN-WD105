@extends('layouts.admin')

@section('title', 'Thêm Biến Thể')

@section('content')
@php
    $id_product = request('product_id');
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
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease-in-out;
        position: relative;
    }
    .variant-item:hover {
        transform: scale(1.01);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .btn-add-variant {
        background: linear-gradient(135deg, #42a5f5, #478ed1);
        color: #fff;
        font-weight: 600;
        border-radius: 30px;
        padding: 10px 25px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(66, 165, 245, 0.4);
    }
    .btn-add-variant:hover {
        background: linear-gradient(135deg, #2196f3, #1e88e5);
        box-shadow: 0 6px 14px rgba(33, 150, 243, 0.5);
        transform: translateY(-2px);
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

    .btn-save {
        background: linear-gradient(135deg, #7e57c2, #5e35b1);
        color: #fff;
        font-weight: 600;
        border-radius: 12px;
        padding: 10px 25px;
        box-shadow: 0 4px 10px rgba(126, 87, 194, 0.4);
        transition: all 0.3s ease;
    }
    .btn-save:hover {
        background: linear-gradient(135deg, #673ab7, #512da8);
        box-shadow: 0 6px 14px rgba(103, 58, 183, 0.5);
        transform: translateY(-2px);
    }

    .btn-back {
        border: 2px solid #9e9e9e;
        color: #616161;
        font-weight: 500;
        border-radius: 12px;
        padding: 10px 25px;
        transition: all 0.3s ease;
    }
    .btn-back:hover {
        background: #f5f5f5;
        color: #424242;
        border-color: #757575;
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

        <form action="{{ route('admin.variants.store') }}" method="POST">
            @csrf

            <div class="form-group mb-4">
                <label for="product_id" class="fw-bold text-primary">Sản Phẩm:</label>
                <select name="product_id" id="product_id" class="form-control form-select" required>
                    <option value="">-- Chọn Sản Phẩm --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id_product }}"
                            {{ (old('product_id') ?? $id_product) == $product->id_product ? 'selected' : '' }}>
                            {{ $product->name_product }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Biến thể --}}
            <div id="variants-wrapper">
                <div class="variant-item mb-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="fw-semibold text-secondary">Kích Cỡ (Size):</label>
                            <select name="variants[0][size_id]" class="form-control form-select size-select" required>
                                <option value="">-- Chọn Size --</option>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->id_size }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="fw-semibold text-secondary">Giá:</label>
                            <input type="number" name="variants[0][price]" class="form-control" min="0" required placeholder="VNĐ">
                        </div>

                        <div class="col-md-3">
                            <label class="fw-semibold text-secondary">Số Lượng:</label>
                            <input type="number" name="variants[0][quantity]" class="form-control" min="0" required placeholder="SL">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-remove-variant w-100">
                                <i class="bi bi-trash3"></i> Xóa
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3 text-center">
                <button type="button" class="btn btn-add-variant">
                    <i class="bi bi-plus-circle-fill"></i> Thêm Biến Thể
                </button>
            </div>

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
    let index = 1;
    const wrapper = document.getElementById('variants-wrapper');
    const allSizes = @json($sizes);

    function getSelectedSizes() {
        return Array.from(document.querySelectorAll('.size-select'))
            .map(select => select.value)
            .filter(val => val !== "");
    }

    function updateSizeOptions() {
        const selectedSizes = getSelectedSizes();

        document.querySelectorAll('.size-select').forEach(select => {
            const currentValue = select.value;

            // Reset dropdown
            select.innerHTML = `<option value="">-- Chọn Size --</option>`;

            allSizes.forEach(size => {
                const idStr = String(size.id_size);
                if (!selectedSizes.includes(idStr) || idStr === currentValue) {
                    const option = document.createElement('option');
                    option.value = idStr;
                    option.textContent = size.name;
                    if (idStr === currentValue) option.selected = true;
                    select.appendChild(option);
                }
            });
        });
    }

    document.querySelector('.btn-add-variant').addEventListener('click', function () {
        const selectedSizes = getSelectedSizes();
        const availableSizes = allSizes.filter(size => !selectedSizes.includes(String(size.id_size)));

        if (availableSizes.length === 0) {
            alert("Tất cả các size đã được chọn!");
            return;
        }

        const variantItem = document.createElement('div');
        variantItem.classList.add('variant-item', 'mb-3');
        variantItem.innerHTML = `
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="fw-semibold text-secondary">Kích Cỡ (Size):</label>
                    <select name="variants[${index}][size_id]" class="form-control form-select size-select" required>
                        <option value="">-- Chọn Size --</option>
                        ${availableSizes.map(size => `<option value="${size.id_size}">${size.name}</option>`).join('')}
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="fw-semibold text-secondary">Giá:</label>
                    <input type="number" name="variants[${index}][price]" class="form-control" min="0" required placeholder="VNĐ">
                </div>

                <div class="col-md-3">
                    <label class="fw-semibold text-secondary">Số lượng:</label>
                    <input type="number" name="variants[${index}][quantity]" class="form-control" min="0" required placeholder="SL">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-remove-variant w-100">
                        <i class="bi bi-trash3"></i> Xóa
                    </button>
                </div>
            </div>
        `;
        wrapper.appendChild(variantItem);
        index++;
        updateSizeOptions();
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-variant')) {
            e.target.closest('.variant-item').remove();
            updateSizeOptions();
        }
    });

    wrapper.addEventListener('change', function (e) {
        if (e.target.classList.contains('size-select')) {
            updateSizeOptions();
        }
    });

    updateSizeOptions();
});
</script>
@endsection
