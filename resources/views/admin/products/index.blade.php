@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách sản phẩm</h3>
        </div>

        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Thêm Sản Phẩm Mới</a>

        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Ảnh Sản Phẩm</th> {{-- Đổi tên cột cho phù hợp --}}
                        <th>Tên Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Danh Mục</th>
<th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id_product }}</td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @foreach($product->albumProducts as $album)
                                        <img src="{{ $album->image }}"
                                            alt="Ảnh của {{ $product->name_product }}"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $product->name_product }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                            <td>{{ $product->category->name_category ?? 'N/A' }}</td>
                            <td>{{ $product->description }}</td>
                            <td>
                                <a href="{{ route('products.show', $product->id_product) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('products.edit', $product->id_product) }}"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('products.destroy', $product->id_product) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn có chắc muốn chuyển sản phẩm này vào thùng rác?')"
                                        type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($products->hasPages())
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
