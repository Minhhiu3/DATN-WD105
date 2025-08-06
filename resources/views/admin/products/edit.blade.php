@extends('layouts.admin')

@section('title', 'Cập nhật Sản phẩm')

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
</style>

<div class="card-clean">
    <h2><i class="bi bi-pencil-square"></i> ✏️ Cập nhật Sản phẩm</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id_product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name_product" class="form-label">Tên Sản Phẩm</label>
            <input type="text" name="name_product" id="name_product" class="form-control" value="{{ old('name_product', $product->name_product) }}" placeholder="Nhập tên sản phẩm" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="text" name="price" id="price" 
                class="form-control" 
                value="{{ old('price', $product->price) }}" 
                placeholder="Nhập giá" 
                required>
        </div>


        <div class="mb-3">
            <label for="category_id" class="form-label">Danh Mục</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">-- Chọn Danh Mục --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id_category }}" {{ old('category_id', $product->category_id) == $category->id_category ? 'selected' : '' }}>
                        {{ $category->name_category }}
                    </option>
                @endforeach
            </select>
        </div>
                <div class="mb-3">
            <label for="brand_id" class="form-label">Thương Hiệu</label>
            <select name="brand_id" id="brand_id" class="form-select" required>
                <option value="">-- Chọn Thương Hiệu --</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id_brand }}" {{ old('brand_id', $product->brand_id) == $brand->id_brand ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô Tả</label>
            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Mô tả chi tiết sản phẩm...">{{$product->description?? 'N/A'}}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Ảnh Sản Phẩm</label>
            <label for="image" class="file-upload">
                <i class="bi bi-cloud-upload"></i>
                <span>📂 Chọn ảnh mới (nếu muốn thay đổi)</span>
                <input type="file" name="image" id="image" class="d-none" accept="image/*">
            </label>
            <div style="margin-top: 10px;">
                <img id="image-preview" src="{{ asset('storage/'.$product->image) }}" alt="Preview" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-save"></i> Cập nhật
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

    // Preview ảnh mới khi chọn
    imageInput.addEventListener('change', function(){
        const file = this.files[0];
        if (file){
            const reader = new FileReader();
            reader.onload = function(e){
                imagePreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
