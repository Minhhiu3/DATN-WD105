@extends('layouts.admin')

@section('title', 'Thêm danh mục sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm size</h3>
    </div>
    <div class="card-body">

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name_category" class="form-label">Tên size</label>
                <input type="text" name="name_category" id="name_category" class="form-control" value="{{ old('name_category') }}" placeholder="Nhập tên danh mục mới" required >
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection
