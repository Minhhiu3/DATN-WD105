@extends('layouts.admin')

@section('title', 'Thêm size sản phẩm')

@section('content')
@php
    $product_id= $_GET['id']; // Lấy giá trị từ query string

@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm anh sản phẩm </h3>
    </div>
    <div class="card-body">

        <form action="{{ route('Ablum_products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product_id }}">
            <div class="mb-3">
                <label for="product_id" class="form-label">Ma san pham</label>
                <input type="input" name="product_id" id="product_id" class="form-control" value="{{ $product_id }}" placeholder="Nhập tên size mới" disabled >
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Tên size</label>
                <input type="file" name="image" id="image" class="form-control" value="{{ old('image') }}" placeholder="Nhập tên size mới" required >
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="{{ route('Ablum_products.show', $product_id) }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection
