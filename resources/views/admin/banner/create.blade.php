@extends('layouts.admin')

@section('title', 'Thêm Banner')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm banner mới</h3>
    </div>
    <div class="card-body">

        <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên banner</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                    placeholder="Nhập tên banner" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh banner</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Thêm mới</button>
                <a href="{{ route('banners.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection