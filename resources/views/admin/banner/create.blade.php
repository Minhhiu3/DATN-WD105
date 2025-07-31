@extends('layouts.admin')

@section('title', 'Tạo Banner')

@section('content')
<style>
.card-modern {
    border-radius: 14px;
    background: #ffffff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}
.card-modern-header {
    background: #f8f9fc;
    padding: 1rem 1.5rem;
    font-weight: 600;
    font-size: 1.2rem;
    color: #495057;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e9ecef;
}
.btn-add-modern {
    background: linear-gradient(135deg, #42a5f5, #1e88e5);
    color: #fff;
    border-radius: 40px;
    padding: 0.45rem 1.3rem;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 6px 16px rgba(66, 165, 245, 0.3);
}
.btn-add-modern:hover {
    opacity: 0.95;
    transform: translateY(-2px);
}
.alert-success-modern {
    background: #e6fffa;
    color: #117864;
    border: 1px solid #a3e4d7;
    border-radius: 10px;
    padding: 12px 18px;
    font-weight: 500;
    margin-bottom: 16px;
}
.form-select-modern {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem 1.25rem;
    font-size: 0.95rem;
    color: #495057;
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}
.form-select-modern:focus {
    outline: none;
    border-color: #42a5f5;
    box-shadow: 0 0 0 3px rgba(66, 165, 245, 0.2);
}
.form-select-modern:hover {
    border-color: #cbd5e1;
    background-color: #f8f9fc;
}
.form-select-modern option {
    padding: 0.5rem;
    color: #495057;
}
</style>

<div class="card card-modern">
    <div class="card-modern-header">
        <span><i class="bi bi-image"></i> Tạo Banner</span>
        <a href="{{ route('admin.banner.index') }}" class="btn btn-add-modern">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success-modern">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên Banner</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="product_id" class="form-label">Sản phẩm liên kết</label>
                <select name="product_id" id="product_id" class="form-select form-select-modern">
                    <option value="">Không liên kết sản phẩm</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id_product }}" {{ old('product_id') == $product->id_product ? 'selected' : '' }}>
                            {{ $product->name_product }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Hình ảnh</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-add-modern">Tạo Banner</button>
        </form>
    </div>
</div>
@endsection
