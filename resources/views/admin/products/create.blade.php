@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm mới')

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

    .file-upload {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #38bdf8;
        border-radius: 12px;
        background: #f0f9ff;
        padding: 20px;
        cursor: pointer;
        transition: border-color 0.3s ease, background 0.3s ease;
        text-align: center;
        color: #0ea5e9;
    }

    .file-upload:hover {
        border-color: #0ea5e9;
        background: #e0f2fe;
    }

    .file-upload i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #0ea5e9;
    }

    .file-upload span {
        display: block;
        font-size: 1rem;
    }

    #image-preview,
    .album-preview img {
        margin-top: 10px;
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .album-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-top: 15px;
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
    <h2><i class="bi bi-bag-plus"></i> ➕ Thêm Sản phẩm</h2>

    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
        @csrf

        {{-- Thông tin sản phẩm --}}
        <div class="mb-3">
            <label for="name_product" class="form-label">Tên Sản Phẩm</label>
            <input type="text" name="name_product" id="name_product" class="form-control @error('name_product') is-invalid @enderror" value="{{ old('name_product') }}" placeholder="Nhập tên sản phẩm" >
            <div class="error-message text-danger">
                @error('name_product')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Nhập giá" min="0" step="1000" >
            <div class="error-message text-danger">
                @error('price')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Danh Mục</label>
            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" >
                <option value="">-- Chọn Danh Mục --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id_category }}" {{ old('category_id') == $category->id_category ? 'selected' : '' }}>
                        {{ $category->name_category }}
                    </option>
                @endforeach
            </select>
            <div class="error-message text-danger">
                @error('category_id')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="brand_id" class="form-label ">Thương Hiệu</label>
            <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror" >
                <option value="">-- Chọn Thương Hiệu --</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id_brand }}" {{ old('brand_id') == $brand->id_brand ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            <div class="error-message text-danger">
                @error('brand_id')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô Tả</label>
            <textarea name="description" id="description" cols="30" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Mô tả chi tiết sản phẩm..."></textarea>
            <div class="error-message text-danger">
                @error('description')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        {{-- Ảnh sản phẩm --}}
        <div class="mb-4">
            <label class="form-label">Ảnh Sản Phẩm</label>
            <label for="image" class="file-upload">
                <i class="bi bi-cloud-upload"></i>
                <span>  Chọn ảnh sản phẩm</span>
                <input type="file" name="image" id="image" class="d-none @error('image') is-invalid @enderror"  >
            </label>
            <div style="margin-top: 10px;">
                <img id="image-preview" alt="Preview" style="max-width: 200px; display: none; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            </div>
            <div class="error-message text-danger">
                @error('image')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        {{-- Album ảnh --}}
        <div class="mb-4">
            <label class="form-label">Album Ảnh Sản Phẩm</label>
            <label for="album" class="file-upload">
                <i class="bi bi-images"></i>
                <span>  Chọn nhiều ảnh cho album</span>
                <input type="file" name="album[]" id="album" class="d-none @error('album.*') is-invalid @enderror" accept="image/*" multiple>
            </label>
            <div class="album-preview" id="album-preview"></div>
            <div class="error-message text-danger">
                @error('album.*')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        {{-- Thông tin bảng advice_product --}}
        <hr>
        <h5 class="text-primary">📢 Thêm Advice Product</h5>

        <div class="mb-3">
            <label for="value" class="form-label ">Value</label>
            <input type="number" name="value" id="value" class="form-control @error('value') is-invalid @enderror"
                   value="{{ old('value') }}"
                   placeholder="Nhập phần trăm giảm giá..."
                   min="1" max="99" >
            <div class="error-message text-danger">
                @error('value')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                <div class="error-message text-danger">
                    @error('start_date')
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Ngày kết thúc</label>
                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                <div class="error-message text-danger">
                    @error('end_date')
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label ">Trạng thái</label>
            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" >
                <option value="on" {{ old('status') == 'on' ? 'selected' : '' }}>On</option>
                <option value="off" {{ old('status') == 'off' ? 'selected' : '' }}>Off</option>
            </select>
            <div class="error-message text-danger">
                @error('status')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>

        {{-- Nút Submit --}}
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-circle"></i> Thêm
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary-custom">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>
const imageInput = document.getElementById('image');
const imagePreview = document.getElementById('image-preview');
const albumInput = document.getElementById('album');
const albumPreview = document.getElementById('album-preview');

// Preview ảnh chính
imageInput.addEventListener('change', function(){
    const file = this.files[0];
    if (file){
        const reader = new FileReader();
        reader.onload = function(e){
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        imagePreview.style.display = 'none';
    }
});

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
    const form = document.getElementById('product-form');

    const rules = {
        name_product: { required: true, string: true, max: 255 },
        price: { required: true, numeric: true, min: 1000 },
        category_id: { required: true },
        brand_id: { required: true },
        description: { required: true, string: true },
        image: { required: true, image: true, mimes: ['jpeg','jpg','png'], maxSize: 2048 },
        album: { required: true,image: true, mimes: ['jpeg','jpg','png'], maxSize: 2048 }, // album[]
        value: { required: true, integer: true, min: 1, max: 99 },
        start_date: { required: true, date: true },
        end_date: { required: true, date: true, afterOrEqual: 'start_date' },
        status: { required: true, in: ['on','off'] }
    };

    const messages = {
        name_product: {
            required: 'Vui lòng nhập tên sản phẩm.',
            string: 'Tên sản phẩm không hợp lệ.',
            max: 'Tên sản phẩm không được vượt quá 255 ký tự.'
        },
        price: {
            required: 'Vui lòng nhập giá sản phẩm.',
            numeric: 'Giá phải là số.',
            min: 'Giá sản phẩm không được nhỏ hơn 1000.'
        },
        category_id: { required: 'Vui lòng chọn danh mục.' },
        brand_id: { required: 'Vui lòng chọn thương hiệu.' },
        description: {
            required: 'Vui lòng mô tả sản phẩm.',
            string: 'Mô tả sản phẩm không hợp lệ.'
        },
        image: {
            required: 'Bạn cần tải lên hình ảnh chính sản phẩm.',
            mimes: 'File chỉ chấp nhận định dạng: jpeg, png, jpg.',
            maxSize: 'Kích thước ảnh không được vượt quá 2MB.'
        },
        album: {
            required: 'Bạn cần tải lên hình ảnh album sản phẩm.',
            mimes: 'Ảnh trong album chỉ chấp nhận jpeg, jpg, png.',
            maxSize: 'Ảnh trong album không được vượt quá 2MB.'
        },
        value: {
            required: 'Bạn phải nhập phần trăm giảm giá',
            integer: 'Bạn phải nhập phần trăm giảm giá',
            min: 'Giá trị khuyến mãi ít nhất là 1%',
            max: 'Giá trị khuyến mãi tối đa là 99%'
        },
        start_date: {
            required: 'Vui lòng chọn ngày bắt đầu.',
            date: 'Giá trị phải chọn theo kiểu thời gian.'
        },
        end_date: {
            required: 'Vui lòng chọn ngày kết thúc.',
            date: 'Giá trị phải chọn theo kiểu thời gian.',
            afterOrEqual: 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'
        },
        status: { in: 'Trạng thái chỉ được chọn On hoặc Off' }
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

        // string
        if (rule.string && !isFile && value && typeof value !== 'string') {
            showError(input, messages[name].string);
            return false;
        }

        // numeric/min/max
        if (rule.numeric && value && isNaN(value)) {
            showError(input, messages[name].numeric); return false;
        }
        if (rule.min && value && Number(value) < rule.min) {
            showError(input, messages[name].min); return false;
        }
        if (rule.max && value && Number(value) > rule.max) {
            showError(input, messages[name].max); return false;
        }

        // in
        if (rule.in && value && !rule.in.includes(value)) {
            showError(input, messages[name].in); return false;
        }

        // date
        if (rule.date && value && isNaN(Date.parse(value))) {
            showError(input, messages[name].date); return false;
        }

        // after or equal
        if (rule.afterOrEqual && value) {
            const start = document.querySelector(`[name="${rule.afterOrEqual}"]`).value;
            if (start && new Date(value) < new Date(start)) {
                showError(input, messages[name].afterOrEqual); return false;
            }
        }

        // file check (image types & size)
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
    const inputs = form.querySelectorAll('input, select, textarea');

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
