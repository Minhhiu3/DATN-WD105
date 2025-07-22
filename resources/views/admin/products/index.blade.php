@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách sản phẩm</h3>
        </div>

        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Thêm Sản Phẩm Mới</a>

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
                        <th>Biến thể</th>
                        <th>Ablum Ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id_product }}</td>
                            <td><img src="{{ asset('/storage/'.$product->image) }}" alt="{{$product->image}}" width="50px" height="50px"></td>

                            <td>{{ $product->name_product }}</td>
                               @php
    $minPrice = $product->variants->min('price');
    $maxPrice = $product->variants->max('price');
@endphp

{{-- <div class="price"> --}}
    @if ($minPrice === null)
        <td>Đang cập nhật</td>
    @elseif ($minPrice == $maxPrice)
        <td>{{ number_format($minPrice, 0, ',', '.') }} VNĐ</td>
    @else
        <td>{{ number_format($minPrice, 0, ',', '.') }} – {{ number_format($maxPrice, 0, ',', '.') }} VNĐ</td>
    @endif
{{-- </div> --}}
                            <td>{{ $product->category->name_category ?? 'Không có danh mục' }}</td>
                            <th><a href="{{ route('admin.variants.show', $product->id_product) }}" class="btn btn-info btn-sm">Xem</a></th>
                            <th><a href="{{ route('admin.album-products.show', $product->id_product) }}" class="btn btn-info btn-sm">Xem</a></th>
                            <td>
                                <a href="{{ route('admin.products.show', $product->id_product) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('admin.products.edit', $product->id_product) }}"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('admin.products.destroy', $product->id_product) }}" method="POST"
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
    <div class="d-flex justify-content-center mt-3 ">
         {!! $products->links('pagination::bootstrap-5') !!}
    </div>
@endif

        </div>
    </div>
@endsection
