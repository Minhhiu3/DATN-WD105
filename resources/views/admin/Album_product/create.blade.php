@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm mới')


@section('content')
<style>
    .card-clean {
        max-width: 750px;
        margin: 50px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    .card-clean h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #0ea5e9;
        text-align: center;
        margin-bottom: 20px;
    }
    .form-control, .file-upload {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        padding: 10px;
    }
    .file-upload {
        display: block;
        text-align: center;
        border: 2px dashed #38bdf8;
        color: #0ea5e9;
        cursor: pointer;
        margin-bottom: 10px;
    }
    .file-upload:hover {
        background: #f0f9ff;
    }
    .album-preview img {
        max-height: 100px;
        margin: 5px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .btn-primary-custom {
        background: #0ea5e9;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        color: #fff;
        font-weight: 500;
    }
    .btn-primary-custom:hover {
        background: #0284c7;
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
    <h2>➕ Thêm Sản phẩm</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.album-products.store') }}" method="POST" enctype="multipart/form-data" id="album-product-form">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id_product }}">

        <div class="mb-3">
            <label for="product_id" class="form-label">Tên sản phẩm</label>
            <input type="text" id="product_id" class="form-control" value="{{ $product->name_product }}" disabled>
        </div>

        <div class="mb-3">
            <label for="album" class="form-label">Chọn ảnh album</label>
            <label for="album" class="file-upload">
                📂 Chọn nhiều ảnh
                <input type="file" name="album[]" id="album" class="d-none @error('album.*') is-invalid @enderror" accept="image/*" multiple>
            </label>
            <div id="album-preview" class="album-preview"></div>
            <div class="error-message text-danger">
                @error('album.*')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-circle"></i> Thêm
            </button>
            <a href="{{ route('admin.album-products.show', $product->id_product) }}" class="btn-secondary-custom">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>

const albumInput = document.getElementById('album');
const albumPreview = document.getElementById('album-preview');


// Preview album ảnh
albumInput.addEventListener('change', function(){
    albumPreview.innerHTML = "";
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e){
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxHeight = '100px';
            albumPreview.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('album-product-form');

    const rules = {
        album: { required: true,image: true, mimes: ['jpeg','jpg','png'], maxSize: 2048 }, // album[]
    };

    const messages = {
        album: {
            required: 'Bạn cần tải lên hình ảnh album sản phẩm.',
            mimes: 'Ảnh trong album chỉ chấp nhận jpeg, jpg, png.',
            maxSize: 'Ảnh trong album không được vượt quá 2MB.'
        }
    };

    function getGroup(input){
        return input.closest('.mb-3, .mb-4') || input.parentNode;
    }

    function showError(input, message) {
        const group = getGroup(input);
        const errorDiv = group.querySelector('.error-message');
        if (errorDiv) errorDiv.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
        input.classList.add('is-invalid');
        const fileBox = group.querySelector('.file-upload');
        if (fileBox) fileBox.classList.add('border-danger');
    }

    function clearError(input) {
        const group = getGroup(input);
        const errorDiv = group.querySelector('.error-message');
        if (errorDiv) errorDiv.textContent = '';
        input.classList.remove('is-invalid');
        const fileBox = group.querySelector('.file-upload');
        if (fileBox) fileBox.classList.remove('border-danger');
    }

    function validateField(input) {
        const name = input.name.replace('[]', ''); // album[]
        const rule = rules[name];
        if (!rule) return true;

        const isFile = input.type === 'file';
        const value = isFile ? '' : (input.value || '').trim();

        // required
        if (rule.required) {
            if (isFile) {
                if (input.files.length === 0) {
                    showError(input, messages[name].required);
                    return false;
                }
            } else if (!value) {
                showError(input, messages[name].required);
                return false;
            }
        }
        // kiểm tra kích thức và kiểu file
        if (isFile && input.files.length > 0) {
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

    // Bắt sự kiện
    const inputs = form.querySelectorAll('input');

    inputs.forEach(input => {
        const h = () => validateField(input);
        input.addEventListener('input', h);
        input.addEventListener('blur', h);
        if (input.type === 'file') input.addEventListener('change', h);
    });

    form.addEventListener('submit', function(e) {
        let isValid = true;
        inputs.forEach(input => { if (!validateField(input)) isValid = false; });
        if (!isValid) e.preventDefault();
    });
});
</script>
@endsection
