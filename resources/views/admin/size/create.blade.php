@extends('layouts.admin')

@section('title', 'Thêm size sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm size sản phẩm </h3>
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
        <form action="{{ route('admin.sizes.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên size</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Nhập tên size mới" required >
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection
