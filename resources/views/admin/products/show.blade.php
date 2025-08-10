@extends('layouts.admin')

@section('title', '📦 Chi tiết sản phẩm')

@section('content')
<style>
    .product-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        animation: fadeIn 0.5s ease-in-out;
    }

    .product-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
    }

    .product-images {
        flex: 1;
        min-width: 400px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
    }

    .product-main-image {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        object-fit: cover;
        cursor: zoom-in;
        transition: transform 0.4s ease;
        max-height: 450px;
    }

    .product-main-image:hover {
        transform: scale(1.05);
    }

    .thumbnail-slider-wrapper {
        display: flex;
        align-items: center;
        position: relative;
        width: 100%;
        margin-top: 15px;
    }

    .thumbnail-slider {
        scroll-behavior: smooth;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        display: flex;
        gap: 10px;
        padding: 5px 0;
    }

    .thumbnail-slider::-webkit-scrollbar {
        display: none; /* Ẩn scrollbar */
    }

    .thumb-image {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.3s ease;
    }

    .thumb-image:hover {
        border-color: #0ea5e9;
    }

    .slider-btn {
        background: rgba(0, 0, 0, 0.4);
        border: none;
        color: #fff;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .slider-btn:hover {
        background: rgba(0, 0, 0, 0.7);
    }

    .slider-prev {
        left: -10px;
    }

    .slider-next {
        right: -10px;
    }

    .product-info {
        flex: 1;
        min-width: 400px;
        padding: 30px;
    }

    .product-info h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1d4ed8;
        margin-bottom: 15px;
    }

    .product-info p {
        margin-bottom: 10px;
        color: #4b5563;
    }

    .product-price {
        color: #16a34a;
        font-weight: 700;
        font-size: 1.6rem;
        margin-bottom: 15px;
    }

    .btn-custom {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: linear-gradient(135deg, #3b82f6, #0ea5e9);
        color: #fff;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
    }

    .btn-back {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-back:hover {
        background: #d1d5db;
    }

    .modal-fullscreen .modal-content {
        background: #000;
        border: none;
    }

    .modal-fullscreen img {
        max-height: 90vh;
        object-fit: contain;
    }
</style>

<div class="product-detail-container">
    <div class="product-card">
        {{-- Left: Images --}}
        <div class="product-images">
            <img id="mainImage" src="{{ asset('/storage/' . $product->image) }}"
                 class="product-main-image"
                 alt="{{ $product->name_product }}">
            <div class="thumbnail-slider-wrapper">
                <!-- Prev Button -->
                <button class="slider-btn slider-prev">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <!-- Thumbnail Slider -->
                <div class="thumbnail-slider">
                    @foreach($album_products as $album_product)
                        <img src="{{ asset('/storage/' . $album_product->image) }}"
                            class="thumb-image"
                            alt="Thumbnail">
                    @endforeach
                </div>

                <!-- Next Button -->
                <button class="slider-btn slider-next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        {{-- Right: Info --}}
        <div class="product-info">
            <h3><i class="fas fa-box-open me-2"></i> {{ $product->name_product }}</h3>
            <p><strong>Danh mục:</strong> 
                <span class="badge bg-secondary">{{ $product->category->name_category }}</span>
            </p>

            <p class="product-price">
                {{ number_format($product->price, 0, ',', '.') }} ₫
            </p>

            <div class="mb-3">
                <strong>Mô tả:</strong>
                <p class="text-muted" style="white-space: pre-wrap;">
                    {{ $product->description ?? 'Không có mô tả.' }}
                </p>
            </div>
            <div class="mt-3">
    <p><strong>Tổng sản phẩm còn trong kho:</strong> {{ $total_in_stock }}</p>
    <p><strong>Tổng sản phẩm đã bán:</strong> {{ $total_sold }}</p>
</div>


            <div class="mt-4">
                <a href="{{ route('admin.products.edit', $product->id_product) }}" class="btn btn-custom btn-edit">
                    <i class="fas fa-edit me-1"></i> Sửa sản phẩm
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-custom btn-back ms-2">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal for zoomed image --}}
<div class="modal fade modal-fullscreen" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img src="" class="img-fluid" id="modalImage" alt="Ảnh lớn">
            </div>
        </div>
    </div>
</div>

<script>
    // Change main image when clicking thumbnail
    const mainImage = document.getElementById('mainImage');
    const modalImage = document.getElementById('modalImage');

    document.querySelectorAll('.thumb-image').forEach(img => {
        img.addEventListener('click', () => {
            mainImage.src = img.src;
        });
    });

    // Open modal when clicking main image
    mainImage.addEventListener('click', () => {
        modalImage.src = mainImage.src;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    });

    // Slider controls
    const slider = document.querySelector('.thumbnail-slider');
    const btnPrev = document.querySelector('.slider-prev');
    const btnNext = document.querySelector('.slider-next');

    btnPrev.addEventListener('click', () => {
        slider.scrollBy({ left: -100, behavior: 'smooth' });
    });

    btnNext.addEventListener('click', () => {
        slider.scrollBy({ left: 100, behavior: 'smooth' });
    });
</script>
@endsection
