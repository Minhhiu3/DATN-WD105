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

        <form action="{{ route('products.update',$product->id_product) }}" method="POST" enctype="multipart/form-data">
            @csrf
              @method('PUT')
            <div class="form-group mb-3">
                <label for="name_product">Tên Sản Phẩm:</label>
                <input type="text" name="name_product" id="name_product" class="form-control" value="{{ old('name_product',$product->name_product) }}" disabled>
            </div>

            <div class="form-group mb-3">
                <label for="price">Giá:</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price',$product->price) }}" required min="0" disabled>
            </div>

            <div class="form-group mb-3">
                <label for="category_id">Danh Mục:</label>
                <select name="category_id" id="category_id" class="form-control form-select" required disabled>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id_category }}" {{ old('category_id', $product->category_id) == $category->id_category ? 'selected' : '' }}>
                            {{ $category->name_category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="description">Mô Tả:</label>
                <textarea name="description" id="description" class="form-control" rows="5" disabled>{{ old('description',$product->description) }}</textarea>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @foreach($product->albumProducts as $album)
                                        <img src="{{ $album->image }}"
                                            alt="Ảnh của {{ $product->name_product }}"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    @endforeach
                                </div>

            {{-- <button type="submit" class="btn btn-primary">Lưu Sản Phẩm</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a> --}}
        </form>
    </div>
</div>
@endsection
