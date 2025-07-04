@extends('layouts.client_home')
@section('title', 'Trang Chủ') <style>
.owl-nav-custom .btn {
    margin: 0 5px;
    padding: 6px 12px;
    font-weight: bold;
}
</style>
@section('content')
<!-- start banner Area -->
<section class="banner-area">
    <div class="container">
        <div class="row fullscreen align-items-center justify-content-start">
            <div class="col-lg-12">
                <div class="active-banner-slider owl-carousel">
                    @foreach ($banners as $banner)
                        <div class="single-slide row align-items-center d-flex">
                            <div class="col-lg-5 col-md-6">
                                <div class="banner-content">
                                    <h1>{{ $banner->name ?? 'Bộ sưu tập mới!' }}</h1>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="banner-img">
                                    <img class="img-fluid" src="{{ asset('storage/' . $banner->image) }}"
                                        style="max-height: 400px; object-fit: cover;" alt="{{ $banner->name }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
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
<section class="owl-carousel active-product-area section_gap">
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
                                        <div class="price">
                                            <h6>{{ number_format($product->price, 0, ',', '.') }} VNĐ</h6>
                                        </div>
                                        <div class="prd-bottom">

                                            <a href="" class="social-info">
                                                <span class="ti-bag"></span>
                                                <p class="hover-text">add to bag</p>
                                            </a>
                                            <a href="" class="social-info">
                                                <span class="lnr lnr-heart"></span>
                                                <p class="hover-text">Wishlist</p>
                                            </a>
                                            <a href="" class="social-info">
                                                <span class="lnr lnr-sync"></span>
                                                <p class="hover-text">compare</p>
                                            </a>
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

@endsection
