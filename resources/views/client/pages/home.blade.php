@extends('layouts.client_home')
@section('title', 'Trang Chủ')
<style>
    .owl-nav-custom .btn {
        margin: 0 5px;
        padding: 6px 12px;
        font-weight: bold;
    }
</style>
@push('styles')
<style>
    .custom-banner-slider-wrapper {
        position: relative;
        overflow: hidden;
        height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-banner-slider {
        display: flex;
        transition: transform 0.6s ease-in-out;
        width: 100%;
        height: 100%;
    }

    .single-slide {
        min-width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .banner-content {
        flex: 1;
        padding-right: 20px;
    }

    .banner-img {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .banner-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Nút điều hướng */
    .custom-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #111;
        font-size: 3rem;
        padding: 0 10px;
        cursor: pointer;
        z-index: 10;
        transition: color 0.3s ease, transform 0.3s ease;
        opacity: 0.6;
    }

    .custom-nav:hover {
        color: #f97316;
        transform: scale(1.2);
        opacity: 1;
    }

    .custom-nav.prev {
        left: 10px;
    }

    .custom-nav.next {
        right: 10px;
    }

    /* CSS cho Category Area với Swiper */
    .category-area {
        padding: 20px 0;
    }

    .mySwiper {
        width: 100%;
        height: 250px;
    }

    .swiper-slide {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #fff;
    }

    .category-item img {
        width: 220px;
        height: 220px;
        object-fit: contain;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .wiper-pagination{
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }
    .category-item img:hover {
        transform: scale(1.1);
    }

    .category-item h5 {
        font-size: 16px;
        font-weight: 500;
        margin-top: 10px;
        color: #333;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .mySwiper {
            --swiper-slides-per-view: 1;
        }
        .category-item img {
            width: 80px;
            height: 80px;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .mySwiper {
            --swiper-slides-per-view: 2;
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush
@section('content')
<!-- Start banner Area -->
<section class="banner-area" style="margin-top: 80px">
    <div class="container">
        <div class="row fullscreen align-items-center justify-content-start">
            <div class="col-lg-12">
                <div class="custom-banner-slider-wrapper">
                    <div class="custom-banner-slider">
                        @forelse ($banners as $banner)
                            <div class="single-slide">
                                <div class="row align-items-center d-flex">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="banner-content">
                                            <h1 class="-ml-3">{{ $banner->name ?? 'Bộ sưu tập mới!' }}</h1>
                                            @if ($banner->product_id)
                                                <a href="{{ route('client.product.show', $banner->product_id) }}" class="primary-btn">
                                                    Xem sản phẩm
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6 d-flex justify-content-center">
                                        <div class="banner-img">
                                            <img class="img-fluid" src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->name }} ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="single-slide">
                                <div class="row align-items-center d-flex">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="banner-content">
                                            <h1>Bộ sưu tập mới!</h1>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6">
                                        <div class="banner-img">
                                            <img class="img-fluid" src="{{ asset('assets/img/banner/default-banner.jpg') }}" alt="Default Banner">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <!-- Navigation -->
                    <button class="custom-nav prev">&#10094;</button>
                    <button class="custom-nav next">&#10095;</button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End banner Area -->

<!-- Start features Area -->
<section class="features-area section_gap">
    <div class="container">
        <div class="row features-inner">
            <!-- Single features -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="{{ asset('assets/img/features/f-icon1.png') }}" alt="">
                    </div>
                    <h6>Giao hàng nhanh chóng</h6>
                    <p>Giao vận trong 3 ngày cho tất cả đơn hàng</p>
                </div>
            </div>
            <!-- Single features -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="{{ asset('assets/img/features/f-icon2.png') }}" alt="">
                    </div>
                    <h6>Chính sách đổi trả</h6>
                    <p>Đổi trả dễ dàng trong 30 ngày</p>
                </div>
            </div>
            <!-- Single features -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="{{ asset('assets/img/features/f-icon3.png') }}" alt="">
                    </div>
                    <h6>Hỗ trợ 24/7</h6>
                    <p>Luôn sẵn sàng hỗ trợ bạn bất cứ lúc nào</p>
                </div>
            </div>
            <!-- Single features -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="{{ asset('assets/img/features/f-icon4.png') }}" alt="">
                    </div>
                    <h6>Thanh toán an toàn</h6>
                    <p>Bảo mật tuyệt đối cho mọi giao dịch</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End features Area -->

<!-- Start category Area -->
<section class="category-area">
    <div class="container">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($brands as $brand)
                    <div class="swiper-slide" style="margin-bottom: 10px;">
                        <div class="category-item">
                            <a href="{{ route('products', ['brand' => $brand->id_brand]) }}">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid width:auto">
                                @else
                                    <img src="{{ asset('assets/img/brands/default-logo.png') }}" alt="Default Logo" class="img-fluid width:auto">
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <br>
            <div class="swiper-pagination" style=""></div>
            <!-- Thêm nút điều hướng (tùy chọn) -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>
<!-- End category Area -->

<!-- Start product Area -->
<section class="active-product-area section_gap">
    <div class="single-product-slider">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Sản phẩm mới nhất</h1>
                        <p>Khám phá những mẫu giày mới nhất, được thiết kế để mang lại sự thoải mái và phong cách tối ưu cho bạn.</p>
                    </div>
                </div>
            </div>
            <div class="row">

                {{-- {{ dd($products) }} --}}
                  <!-- single product -->
                @foreach ($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <figure class="single-product">
                            <div style="overflow: hidden; display: flex; justify-content: center; align-items: center; height: 250px;">
                                <img style="height: 100%; width: auto" src="{{ asset('/storage/' . $product->image) }}" alt="{{ $product->image }}">
                            </div>
                            <figcaption class="product-details" style="text-align: center;">
                                <h6>{{ $product->name_product }}</h6>
                                @php
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');

                                    $sale = $product->advice_product;
                                    $now = \Carbon\Carbon::now();
                                    $start = \Carbon\Carbon::parse($sale->start_date ?? 0)->startOfDay();
                                    $end = \Carbon\Carbon::parse($sale->end_date ?? 0)->endOfDay();
                                     if ($sale && $sale->status === "on" && $now->between($start, $end)) {
        $discount = $sale->value / 100;
        $minPrice = $minPrice - ($minPrice * $discount);
        $maxPrice = $maxPrice - ($maxPrice * $discount);
    }
                                @endphp

                                @if (
                                    $sale &&
                                    $sale->status === "on" && $now->between($start, $end)
                                )
                                {{-- Ô vuông % giảm giá tông vàng-cam --}}
                                <div style="
                                    position: absolute;
                                    top: 10px;
                                    left: 22px;
                                    background: linear-gradient(135deg, #ff7e00, #ffb400);
                                    color: white;
                                    padding: 5px 8px;
                                    border-radius: 5px;
                                    font-weight: bold;
                                    font-size: 14px;
                                    z-index: 10;
                                    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                                ">
                                    -{{$product->advice_product->value}}%
                                </div>
                                @endif
                                <div class="price">
                                    @if ($minPrice === null)
                                        <h6>Hết hàng!</h6>
                                    @elseif ($minPrice == $maxPrice)
                                        <h6>{{ number_format($minPrice, 0, ',', '.') }} VNĐ</h6>
                                    @else
                                        <h6>{{ number_format($minPrice, 0, ',', '.') }} – {{ number_format($maxPrice, 0, ',', '.') }} VNĐ</h6>
                                    @endif
                                </div>
                                <div class="prd-bottom">
                                    <a href="{{ route('client.product.show', $product->id_product) }}"
                                        class="social-info">
                                        <span class="lnr lnr-move"></span>
                                        <p class="hover-text">Xem chi tiết</p>
                                    </a>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Sản phẩm top --}}
<section class=" active-product-area section_gap">
    <!-- single product slide -->
    <div class="single-product-slider">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Sản phẩm bán chạy</h1>
                        <p>Khám phá những mẫu giày được bán chạy nhất, được thiết kế để mang lại sự thoải mái và phong cách
                            tối ưu
                            cho bạn.</p>
                    </div>
                </div>
            </div>
            <div class="row">

                {{-- {{ dd($products) }} --}}
                  <!-- single product -->
                @foreach ($topProducts as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <figure class="single-product">
                            <div style="overflow: hidden; display: flex; justify-content: center; align-items: center; height: 250px;">
                                <img style="height: 100%; width: auto" src="{{ asset('/storage/' . $product->image) }}" alt="{{ $product->image }}">
                            </div>
                            <figcaption class="product-details" style="text-align: center;">
                                <h6>{{ $product->name_product }}</h6>
                                @php
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');

                                    $sale = $product->advice_product;
                                    $now = \Carbon\Carbon::now();
                                    $start = \Carbon\Carbon::parse($sale->start_date ?? 0)->startOfDay();
                                    $end = \Carbon\Carbon::parse($sale->end_date ?? 0)->endOfDay();
                                     if ($sale && $sale->status === "on" && $now->between($start, $end)) {
        $discount = $sale->value / 100;
        $minPrice = $minPrice - ($minPrice * $discount);
        $maxPrice = $maxPrice - ($maxPrice * $discount);
    }
                                @endphp

                                @if (
                                    $sale &&
                                    $sale->status === "on" && $now->between($start, $end)
                                )
                                {{-- Ô vuông % giảm giá tông vàng-cam --}}
                                <div style="
                                    position: absolute;
                                    top: 10px;
                                    left: 22px;
                                    background: linear-gradient(135deg, #ff7e00, #ffb400);
                                    color: white;
                                    padding: 5px 8px;
                                    border-radius: 5px;
                                    font-weight: bold;
                                    font-size: 14px;
                                    z-index: 10;
                                    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                                ">
                                    -{{$product->advice_product->value}}%
                                </div>
                                @endif
                                <div class="price">
                                    @if ($minPrice === null)
                                        <h6>Hết hàng!</h6>
                                    @elseif ($minPrice == $maxPrice)
                                        <h6>{{ number_format($minPrice, 0, ',', '.') }} VNĐ</h6>
                                    @else
                                        <h6>{{ number_format($minPrice, 0, ',', '.') }} – {{ number_format($maxPrice, 0, ',', '.') }} VNĐ</h6>
                                    @endif
                                </div>
                                <div class="prd-bottom">
                                    <a href="{{ route('client.product.show', $product->id_product) }}"
                                        class="social-info">
                                        <span class="lnr lnr-move"></span>
                                        <p class="hover-text">Xem chi tiết</p>
                                    </a>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- End product Area -->
@endsection
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Banner Slider
        const slider = document.querySelector('.custom-banner-slider');
        const slides = document.querySelectorAll('.single-slide');
        const totalSlides = slides.length;
        let currentIndex = 0;
        let interval;

        function showSlide(index) {
            if (index >= totalSlides) currentIndex = 0;
            else if (index < 0) currentIndex = totalSlides - 1;
            else currentIndex = index;

            const offset = -currentIndex * 100;
            slider.style.transform = `translateX(${offset}%)`;
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
        }

        document.querySelector('.custom-nav.next').addEventListener('click', nextSlide);
        document.querySelector('.custom-nav.prev').addEventListener('click', prevSlide);

        function startAutoSlide() {
            interval = setInterval(() => {
                nextSlide();
            }, 5000);
        }

        function stopAutoSlide() {
            clearInterval(interval);
        }

        slider.parentElement.addEventListener('mouseenter', stopAutoSlide);
        slider.parentElement.addEventListener('mouseleave', startAutoSlide);

        startAutoSlide();

        // Category Slider
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 3,
            spaceBetween: 30,
            grabCursor: true, // Cho phép kéo bằng chuột
            allowTouchMove: true, // Cho phép kéo trên cảm ứng
            loop:true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    });
</script>
@endpush
