@extends('layouts.admin')

@section('title', '✏️ Sửa danh mục sản phẩm')

@section('content')
<style>
    body {
        background: #f0f2f5;
    }

    .card-clean {
        max-width: 600px;
        margin: 50px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 40px;
        animation: fadeIn 0.4s ease-in-out;
    }

    .card-clean h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #38bdf8;
        text-align: center;
        margin-bottom: 25px;
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
    <h3><i class="bi bi-pencil-square"></i> Sửa Danh mục</h3>

    <form action="{{ route('admin.categories.update', $category->id_category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name_category" class="form-label">Tên danh mục</label>
            <input type="text" name="name_category" id="name_category" 
                   class="form-control" 
                   value="{{ old('name_category', $category->name_category) }}" 
                   placeholder="Nhập tên danh mục" required>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-circle"></i> Cập nhật
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary-custom">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
