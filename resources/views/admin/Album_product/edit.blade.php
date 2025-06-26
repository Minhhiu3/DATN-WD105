@extends('layouts.admin')

@section('title', 'Quản lý Danh mục sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa size</h3>
    </div>
    <div class="card-body">

        <form action="{{ route('admin.sizes.update', $size->id_size) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Anh</label>
                <input type="file" name="name" id="name" class="form-control" value="{{ old('name', $size->name) }}" placeholder="Nhập tên danh mục mới" required >
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection
