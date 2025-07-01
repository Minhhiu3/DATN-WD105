@extends('layouts.admin')

@section('title', 'Quản lý Danh mục sản phẩm')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách size</h3>
        </div>

       <div class="card-header d-flex justify-content-between align-items-center">

        <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary">Thêm mới</a>
    </div>


        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên size</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sizes as $size)
                        @if($size && $size->name)
                        <tr>
                            <td>{{ $size->id_size ?? 'N/A' }}</td>
                            <td>{{ $size->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.sizes.edit', $size->id_size) }}"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('admin.sizes.destroy', $size->id_size) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Bạn có chắc muốn xóa?')"
                                        class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
