@extends('layouts.admin')

@section('title', 'Quản lý Danh mục sản phẩm')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách variant</h3>
        </div>

       <div class="card-header d-flex justify-content-between align-items-center">
        @php
            $id = basename(request()->url());
        @endphp
        <a href="{{ route('variants.create', ['product_id' => $id]) }}" class="btn btn-primary">
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
                        <th>size</th>
                        <th>gia</th>
                        <th>so luong</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($variants as $variant)
                        <tr>
                            <td>{{ $variant->id_variant }}</td>
                            <td>{{ $variant->product->name_product }}</td>
                            <td>{{ $variant->size->name }}</td>
                            <td>{{ $variant->price }}</td>
                            <td>{{ $variant->quantity }}</td>
                            <td>
                                <a href="{{ route('variants.edit', $variant->id_variant) }}"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('variants.destroy', $variant->id_variant) }}" method="POST"
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
