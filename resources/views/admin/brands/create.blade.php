@extends('layouts.admin')

@section('title', 'Thêm Thương Hiệu')

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

    #logo-preview {
        margin-top: 10px;
        max-width: 200px;
        height: auto;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        display: none;
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
    <h2><i class="bi bi-building"></i> ➕ Thêm Thương Hiệu</h2>

    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên thương hiệu</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="Nhập tên thương hiệu...">
            @error('name')
                <div class="text-danger">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Logo</label>
            <label for="logo" class="file-upload">
                <i class="bi bi-cloud-upload"></i>
                <span>Chọn logo thương hiệu</span>
                <input type="file" name="logo" id="logo" class="d-none" >
            </label>
            <img id="logo-preview" alt="Preview logo">
            @error('logo')
                <div class="text-danger">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select">
                <option value="visible" {{ old('status') == 'visible' ? 'selected' : '' }}>Hiển thị</option>
                <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-circle"></i> Lưu
            </button>
            <a href="{{ route('admin.brands.index') }}" class="btn-secondary-custom">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logo-preview');

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
</script>
@endsection
