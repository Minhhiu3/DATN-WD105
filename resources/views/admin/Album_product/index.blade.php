@extends('layouts.admin')

@section('title', 'Quản lý Danh mục sản phẩm')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách album_product</h3>
        </div>

       <div class="card-header d-flex justify-content-between align-items-center">
        @php
            $id = basename(request()->url());
        @endphp
        <a href="{{ route('Ablum_products.create', ['id' => $id]) }}" class="btn btn-primary">
            Thêm mới
        </a>
    </div>


        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ma san pham</th>
                        <th>Anh san pham</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($album_products as $album_product)
                        <tr>
                            <td>{{ $album_product->id_album_product }}</td>
                            <td>{{ $album_product->product_id }}</td>
                            <td><img src="{{ asset('/storage/'.$album_product->image) }}" alt="{{$album_product->image}}" width="50px" height="50px"></td>
                            <td>
                                <form action="{{ route('Ablum_products.destroy', $album_product->id_album_product) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn có chắc muốn xóa?')"
                                        class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
