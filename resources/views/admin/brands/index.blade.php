
@extends('layouts.admin')

@section('title', 'Danh Sách Thương Hiệu')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách thương hiệu</h3>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary float-end">Thêm mới</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên thương hiệu</th>
                    <th>Slug</th>
                    <th>Logo</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>{{ $brand->id_brand }}</td>
                        <td>{{ $brand->name }}</td>
                        <td>{{ $brand->slug }}</td>
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" width="50">
                            @endif
                        </td>
                        <td>{{ $brand->status == 'visible' ? 'Hiển thị' : 'Ẩn' }}</td>
                        <td>
                            <a href="{{ route('admin.brands.edit', $brand->id_brand) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('admin.brands.destroy', $brand->id_brand) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có thương hiệu nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $brands->links() }}
    </div>
</div>
@endsection
