@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa sản phẩm</h3>
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

        <form action="{{ route('products.update',$product->id_product) }}" method="POST" enctype="multipart/form-data">
            @csrf
              @method('PUT')
            <div class="form-group mb-3">
                <label for="name_product">Tên Sản Phẩm:</label>
                <input type="text" name="name_product" id="name_product" class="form-control" value="{{ old('name_product',$product->name_product) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="price">Giá:</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price',$product->price) }}" required min="0">
            </div>

            <div class="form-group mb-3">
                <label for="category_id">Danh Mục:</label>
                <select name="category_id" id="category_id" class="form-control form-select" required>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id_category }}" {{ old('category_id', $product->category_id) == $category->id_category ? 'selected' : '' }}>
                            {{ $category->name_category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="description">Mô Tả:</label>
                <textarea name="description" id="description" class="form-control" rows="5">{{ old('description',$product->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Ảnh sản phẩm</label> <br>
                <img src="{{ asset('/storage/'.$product->image) }}" alt="{{$product->image}}" width="50px" height="50px">
                <input type="file" name="image" id="image" class="form-control" value="{{ old('description',$product->image) }}">
            </div>
            <button type="submit" class="btn btn-primary">Lưu Sản Phẩm</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection
