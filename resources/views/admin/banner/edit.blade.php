@extends('layouts.admin')

@section('title', 'Quản lý banner')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa banner</h3>
    </div>
    <div class="card-body">

        <form action="{{ route('banners.update', ['banner' => $banner->id_banner]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $banner->name) }}"
                    placeholder="Nhập tên danh mục mới" required>
            </div>
            <div>
                <label for="image" class="form-label">Ảnh banner</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                @if($banner->image)
                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image"
                    style="width: 100px; height: auto; margin-top: 10px;">
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('banners.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection