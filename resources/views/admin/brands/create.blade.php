
@extends('layouts.admin')

@section('title', 'Thêm Mới Thương Hiệu')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm thương hiệu mới</h3>
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

        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên thương hiệu</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" placeholder="Tự động tạo nếu để trống">
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" name="logo" id="logo" class="form-control">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select name="status" id="status" class="form-select">
                    <option value="visible" {{ old('status') == 'visible' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection
