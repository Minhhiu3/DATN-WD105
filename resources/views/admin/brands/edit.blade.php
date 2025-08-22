@extends('layouts.admin')

@section('title', 'Cập nhật Thương hiệu')

@section('content')
<style>
    body {
        background: #f0f2f5;
    }

    .card-clean {
        max-width: 650px;
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

    .form-control {
        border-radius: 12px;
        border: 1px solid #d1d5db;
        background-color: #f9fafb;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
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

    #image-preview {
        margin-top: 10px;
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
        .form-select {
        border-radius: 12px;
        border: 1px solid #d1d5db;
        background-color: #f9fafb;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .form-select:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 0.15rem rgba(56, 189, 248, 0.3);
        background: #fff;
    }

</style>

<div class="card-clean">
    <h2><i class="bi bi-pencil-square"></i> ✏️ Cập nhật Thương hiệu</h2>

    <form action="{{ route('admin.brands.update', $brand->id_brand) }}" method="POST" enctype="multipart/form-data" id="brand-form">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên thương hiệu</label>
            <input type="text" name="name" id="name" 
                class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name', $brand->name) }}" 
                placeholder="Nhập tên thương hiệu" >
            <div class="error-message text-danger">
                @error('name')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>

        <div class="mb-3">
            <label class="form-label">Logo thương hiệu</label>
            <label for="logo" class="file-upload">
                <i class="bi bi-cloud-upload"></i>
                <span>📂 Chọn logo mới (nếu muốn thay đổi)</span>
                <input type="file" name="logo" id="logo" class="d-none @error('logo') is-invalid @enderror" accept="image/*">
            </label>
            <div style="margin-top: 10px;">
                <img id="image-preview" src="{{ asset('storage/'.$brand->logo) }}" alt="Preview" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            </div>
            <div class="error-message text-danger">
                @error('logo')
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                @enderror
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.brands.index') }}" class="btn-secondary-custom">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>


<script>
const logoInput = document.getElementById('logo');
const logoPreview = document.getElementById('image-preview');

logoInput.addEventListener('change', function(){
    const file = this.files[0];
    if (file){
        const reader = new FileReader();
        reader.onload = function(e){
            logoPreview.src = e.target.result;
            logoPreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        logoPreview.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('brand-form');

    const rules = {
        name: { required: true, string: true, max: 255 },
        logo: { image: true, mimes: ['jpeg','jpg','png'], maxSize: 2048 },
    };

    const messages = {
        name: {
            required: 'Vui lòng nhập tên thương hiệu.',
            string: 'Tên thương hiệu không hợp lệ.',
            max: 'Tên sản thương hiệu được vượt quá 255 ký tự.'
        },
        logo: {
            mimes: 'File chỉ chấp nhận định dạng: jpeg, png, jpg.',
            maxSize: 'Kích thước ảnh không được vượt quá 2MB.'
        },

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
    const inputs = form.querySelectorAll('input, select');

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
