@extends('layouts.admin')

@section('title', 'Thêm Biến Thể')

@section('content')
@php
    $id_product = request('product_id');
@endphp

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm Biến Thể Sản Phẩm</h3>
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

        <form action="{{ route('variants.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="product_id">Sản Phẩm:</label>
                <select name="product_id" id="product_id" class="form-control form-select" required>
                    <option value="">-- Chọn Sản Phẩm --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id_product }}"
                            {{ (old('product_id') ?? $id_product) == $product->id_product ? 'selected' : '' }}>
                            {{ $product->name_product }}
                        </option>

                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="size_id">Kích Cỡ (Size):</label>
                <select name="size_id" id="size_id" class="form-control form-select" required>
                    <option value="">-- Chọn Size --</option>
                    @foreach ($sizes as $size)
                        <option value="{{ $size->id_size }}">
                            {{ $size->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="price">Giá:</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" min="0" required placeholder="Nhập giá biến thể">
            </div>

            <div class="form-group mb-3">
                <label for="quantity">Số Lượng:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" min="0" required placeholder="Nhập số lượng">
            </div>

            <button type="submit" class="btn btn-primary">Thêm Biến Thể</button>
            {{-- <a href="{{ route('variants.index') }}" class="btn btn-secondary">Hủy</a> --}}
            <a href="{{ route('variants.show', $id_product) }}" class="btn btn-secondary">Quay lại</a>

        </form>
    </div>
</div>
@endsection
