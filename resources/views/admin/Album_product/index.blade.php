@extends('layouts.admin')

@section('title', 'Quản lý Album Sản phẩm')

@section('content')
<div class="container py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0 text-primary">
            <i class="fas fa-images me-2"></i> Album Sản phẩm
        </h3>
        @php
            $id = basename(request()->url());
        @endphp
        <a href="{{ route('admin.album-products.create', ['id' => $id]) }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i> Thêm ảnh
        </a>
    </div>

    {{-- Album Grid --}}
    @if ($album_products->count())
        <div class="row g-3">
            @foreach ($album_products as $album_product)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card shadow-sm border-0 position-relative">
                        {{-- Ảnh --}}
                        <img src="{{ asset('storage/'.$album_product->image) }}"
                            alt="Ảnh sản phẩm"
                            class="card-img-top rounded bg-light"
                            style="object-fit:cover;width:100%;height:250px;display:block;z-index:1"
                            onerror="this.onerror=null;this.src='https://via.placeholder.com/250x250?text=No+Image';">

                        {{-- Overlay --}}
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 rounded 
                                    d-flex justify-content-center align-items-center opacity-0 hover-overlay"
                             style="transition: 0.3s;z-index:2">
                            <form action="{{ route('admin.album-products.destroy', $album_product->id_album_product) }}" 
                                  method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger shadow">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Footer --}}
                        <div class="card-footer text-center bg-light">
                            <small class="text-muted">Mã SP: {{ $album_product->product_id }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle me-1"></i> Chưa có ảnh nào trong album.
        </div>
    @endif
</div>

<style>
    .hover-overlay {
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    .card:hover .hover-overlay {
        opacity: 1 !important;
        visibility: visible;
    }
</style>
@endsection
