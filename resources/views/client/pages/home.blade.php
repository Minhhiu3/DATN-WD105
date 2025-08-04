@extends('layouts.client_home')
@section('title', 'Trang Chủ') <style>
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
    justify-content: center;  /* ✅ Căn giữa nội dung */
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
    /* border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); */
}


/* Nút điều hướng */
.custom-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.4);
    color: white;
    border: none;
    font-size: 2rem;
    padding: 0 15px;
    cursor: pointer;
    z-index: 99;
}

.custom-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;       /* 👉 không có nền */
    border: none;
    color: #111;                   /* Màu icon */
    font-size: 3rem;               /* Lớn hơn */
    padding: 0 10px;
    cursor: pointer;
    z-index: 10;
    transition: color 0.3s ease, transform 0.3s ease;
    opacity: 0.6;
}

.custom-nav:hover {
    color: #f97316;               /* Màu cam khi hover (hoặc màu thương hiệu của bạn) */
    transform: scale(1.2);        /* Phóng to nhẹ khi hover */
    opacity: 1;
}

.custom-nav.prev {
    left: 10px;
}

.custom-nav.next {
    right: 10px;
}


</style>
@endpush
@section('content')
<!-- start banner Area -->

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
                                            <h1>{{ $banner->name ?? 'Bộ sưu tập mới!' }}</h1>
                                            @if ($banner->product_id)
                                                <a href="{{ route('client.product.show', $banner->product_id) }}" class="primary-btn">
                                                    Xem sản phẩm
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-6 d-flex justify-content-center">
                                        <div class="banner-img">
                                            <img class="img-fluid" src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->name }}">
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
<!-- End banner Area -->


<!-- start features Area -->
<section class="features-area section_gap">
    <div class="container">
        <div class="row features-inner">
            <!-- single features -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="{{asset('assets/img/features/f-icon1.png')}}" alt="">
                    </div>
                    <h6>Giao hàng miễn phí</h6>
                    <p>Miễn phí vận chuyển cho tất cả đơn hàng</p>
                </div>
            </div>
            <!-- single features -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="{{asset('assets/img/features/f-icon2.png')}}" alt="">
                    </div>
                    <h6>Chính sách đổi trả</h6>
                    <p>Đổi trả dễ dàng trong 30 ngày</p>
                </div>
            </div>
            <!-- single features -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="{{asset('assets/img/features/f-icon3.png')}}" alt="">
                    </div>
                    <h6>Hỗ trợ 24/7</h6>
                    <p>Luôn sẵn sàng hỗ trợ bạn bất cứ lúc nào</p>
                </div>
            </div>
            <!-- single features -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-features">
                    <div class="f-icon">
                        <img src="{{asset('assets/img/features/f-icon4.png')}}" alt="">
                    </div>
                    <h6>Thanh toán an toàn</h6>
                    <p>Bảo mật tuyệt đối cho mọi giao dịch</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end features Area -->

<!-- Start category Area -->
<section class="category-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-12">
                <div class="row">
                    <div class="col-lg-8 col-md-8">
                        <div class="single-deal">
                            <div class="overlay"></div>
                            <img class="img-fluid w-100" src="{{asset('assets/img/category/c1.jpg')}}" alt="">
                            <a href="{{asset('assets/img/category/c1.jpg')}}" class="img-pop-up" target="_blank">
                                {{-- <div class="deal-details">
                                    <h6 class="deal-title">Giày thể thao</h6>
                                </div> --}}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="single-deal">
                            <div class="overlay"></div>
                            <img class="img-fluid w-100" src="{{asset('assets/img/category/c2.jpg')}}" alt="">
                            <a href="{{asset('assets/img/category/c2.jpg')}}" class="img-pop-up" target="_blank">
                                {{-- <div class="deal-details">
                                    <h6 class="deal-title">Giày thể thao</h6>
                                </div> --}}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="single-deal">
                            <div class="overlay"></div>
                            <img class="img-fluid w-100" src="{{asset('assets/img/category/c3.jpg')}}" alt="">
                            <a href="{{asset('assets/img/category/c3.jpg')}}" class="img-pop-up" target="_blank">
                                {{-- <div class="deal-details">
                                    <h6 class="deal-title">Sản phẩm dành cho cặp đôi</h6>
                                </div> --}}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="single-deal">
                            <div class="overlay"></div>
                            <img class="img-fluid w-100" src="{{asset('assets/img/category/c4.jpg')}}" alt="">
                            <a href="{{asset('assets/img/category/c4.jpg')}}" class="img-pop-up" target="_blank">
                                {{-- <div class="deal-details">
                                    <h6 class="deal-title">Giày thể thao</h6>
                                </div> --}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6">
                <div class="single-deal">
                    <div class="overlay"></div>
                    <img class="img-fluid w-100" src="{{asset('assets/img/category/c5.jpg')}}" alt="">
                    <a href="{{asset('assets/img/category/c5.jpg')}}" class="img-pop-up" target="_blank">
                        {{-- <div class="deal-details">
                            <h6 class="deal-title">Giày thể thao</h6>
                        </div> --}}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End category Area -->

<!-- start product Area -->
<section class=" active-product-area section_gap">
    <!-- single product slide -->
    <div class="single-product-slider">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Sản phẩm mới nhất</h1>
                        <p>Khám phá những mẫu giày mới nhất, được thiết kế để mang lại sự thoải mái và phong cách
                            tối ưu
                            cho bạn.</p>
                    </div>
                </div>
            </div>
            <div class="row">

                {{-- {{ dd($products) }} --}}
                  <!-- single product -->
                        @foreach ($products as $product)
                            <div class="col-lg-3 col-md-6">
                                <div class="single-product">
                                    <img src="{{ asset('/storage/' . $product->image) }}" alt="{{ $product->image }}">
                                    <div class="product-details">
                                        <h6>{{ $product->name_product }}</h6>
                                                                          @php
    $minPrice = $product->variants->min('price');
    $maxPrice = $product->variants->max('price');
@endphp

<div class="price">
    @if ($minPrice === null)
        <h6>Đang cập nhật</h6>
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
                                                <p class="hover-text">view more</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach


            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        // Tự động chạy và dừng khi hover
        slider.parentElement.addEventListener('mouseenter', stopAutoSlide);
        slider.parentElement.addEventListener('mouseleave', startAutoSlide);

        startAutoSlide();
    });
</script>
@endpush
