@extends('layouts.admin')

@section('title', 'Quản lý Danh mục sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa size</h3>
    </div>
    <div class="card-body">

        <form action="{{ route('categories.update', $category->id_category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name_category" class="form-label">Tên size</label>
                <input type="text" name="name_category" id="name_category" class="form-control" value="{{ old('name_category', $category->name_category) }}" placeholder="Nhập tên danh mục mới" required >
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection
