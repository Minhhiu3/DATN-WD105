@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm mới')
@php
    $product_id = $_GET['id']; // Lấy giá trị từ query string
@endphp

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

    <form action="{{ route('admin.album-products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product_id }}">

        <div class="mb-3">
            <label for="product_id" class="form-label">ID sản phẩm</label>
            <input type="text" id="product_id" class="form-control" value="{{ $product_id }}" disabled>
        </div>

        <div class="mb-3">
            <label for="album" class="form-label">Chọn ảnh album</label>
            <label for="album" class="file-upload">
                📂 Chọn nhiều ảnh
                <input type="file" name="album[]" id="album" class="d-none" accept="image/*" multiple>
            </label>
            <div id="album-preview" class="album-preview"></div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-circle"></i> Thêm
            </button>
            <a href="{{ route('admin.album-products.show', $product_id) }}" class="btn-secondary-custom">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('album').addEventListener('change', function(){
        const preview = document.getElementById('album-preview');
        preview.innerHTML = "";
        [...this.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection
