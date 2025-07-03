@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm mới')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm Sản phẩm mới</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="name_product">Tên Sản Phẩm:</label>
                <input type="text" name="name_product" id="name_product" class="form-control" value="{{ old('name_product') }}" placeholder="Nhập tên sản phẩm mới" required>
            </div>

            <div class="form-group mb-3">
                <label for="price">Giá:</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" placeholder="Nhập giá sản phẩm" required min="0">
            </div>

            <div class="form-group mb-3">
                <label for="category_id">Danh Mục:</label>
                <select name="category_id" id="category_id" class="form-control form-select" required>
                    <option value="">-- Chọn Danh Mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id_category }}" {{ old('category_id') == $category->id_category ? 'selected' : '' }}>
                            {{ $category->name_category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="description">Mô Tả:</label>
                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Anh san pham</label>
                <input type="file" name="image" id="image" class="form-control" value="{{ old('image') }}" placeholder="Nhập tên size mới" required >
            </div>


            <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection
