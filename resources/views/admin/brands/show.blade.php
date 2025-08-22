
@extends('layouts.admin')

@section('title', 'Chi Tiết Thương Hiệu')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chi tiết thương hiệu</h3>
    </div>
    <div class="card-body">
        <p><strong>ID:</strong> {{ $brand->id_brand }}</p>
        <p><strong>Tên:</strong> {{ $brand->name }}</p>
        <p><strong>Logo:</strong>
            @if($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" width="80">
            @endif
        </p>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
@endsection
