@extends('layouts.admin')

@section('title', 'Quản lý Danh mục sản phẩm')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách danh mục</h3>
        </div>

       <div class="card-header d-flex justify-content-between align-items-center">

        <a href="{{ route('categories.create') }}" class="btn btn-primary">Thêm mới</a>
    </div>


        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
               @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id_category }}</td>
                            <td>{{ $category->name_category }}</td>
                            <td>
                                <a href="{{ route('categories.edit', $category->id_category) }}"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('categories.destroy', $category->id_category) }}" method="POST"
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
