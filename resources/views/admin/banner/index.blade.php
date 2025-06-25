@extends('layouts.admin')

@section('title', 'Quản lý banner')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Banner</h3>
    </div>

    <div class="card-header d-flex justify-content-between align-items-center">

        <a href="{{ route('banner.create') }}" class="btn btn-primary">Thêm mới</a>
    </div>


    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Banner</th>
                    <th>Ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($banners as $banner)
                <tr>
                    <td>{{ $banner->id_banner }}</td>
                    <td>{{ $banner->name }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image"
                            style="width: 100px; height: auto;">
                    <td>
                        <a href="{{ route('banner.edit', $banner->id_banner) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('banner.destroy', $banner->id_banner) }}" method="POST"
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