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
                    <!-- single-slide -->

                    @foreach ($banners as $banner)
                    <div class="row single-slide align-items-center d-flex">
                        <div class="col-lg-5 col-md-6">
                            <div class="banner-content">
                                <h1>{{ $banner->title ?? 'Bộ sưu tập mới của Nike!' }}</h1>
                                <p>Khám phá những mẫu giày mới nhất...</p>
                                <div class="add-bag d-flex align-items-center">
                                    <a class="add-btn" href=""><span class="lnr lnr-cross"></span></a>
                                    <span class="add-text text-uppercase">Thêm vào giỏ hàng</span>
                                </div>
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
                                <div class="deal-details">
                                    <h6 class="deal-title">Giày thể thao</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="single-deal">
                            <div class="overlay"></div>
                            <img class="img-fluid w-100" src="{{asset('assets/img/category/c2.jpg')}}" alt="">
                            <a href="{{asset('assets/img/category/c2.jpg')}}" class="img-pop-up" target="_blank">
                                <div class="deal-details">
                                    <h6 class="deal-title">Giày thể thao</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="single-deal">
                            <div class="overlay"></div>
                            <img class="img-fluid w-100" src="{{asset('assets/img/category/c3.jpg')}}" alt="">
                            <a href="{{asset('assets/img/category/c3.jpg')}}" class="img-pop-up" target="_blank">
                                <div class="deal-details">
                                    <h6 class="deal-title">Sản phẩm dành cho cặp đôi</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="single-deal">
                            <div class="overlay"></div>
                            <img class="img-fluid w-100" src="{{asset('assets/img/category/c4.jpg')}}" alt="">
                            <a href="{{asset('assets/img/category/c4.jpg')}}" class="img-pop-up" target="_blank">
                                <div class="deal-details">
                                    <h6 class="deal-title">Giày thể thao</h6>
                                </div>
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
                        <div class="deal-details">
                            <h6 class="deal-title">Giày thể thao</h6>
                        </div>
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



                <!-- single product -->



                <!-- single product -->
                @foreach ($products as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        {{-- Ảnh đại diện sản phẩm (nếu có ảnh trong album hoặc cột riêng) --}}
                        {{-- <img class="img-fluid" src="{{ asset('assets/img/product/default.jpg') }}"
                            alt="{{ $product->name_product }}"> --}}
                              {{-- <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @foreach($product->albumProducts as $album)
                                        <img class="img-fluid" src="{{ $album->image }}"
                                            alt="Ảnh của {{ $product->name_product }}"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    @endforeach
                                </div> --}}
                                @if ($product->albumProducts->count() > 0)
                            <img class="img-fluid" src="{{ $product->albumProducts->first()->image }}" alt="Ảnh của {{ $product->name_product }}">
                        @else
                            <img class="img-fluid" src="{{ asset('assets/img/product/default.jpg') }}" alt="Ảnh mặc định">
                        @endif

                        <div class="product-details">
                            <h6>{{ $product->name_product }}</h6>
                            <div class="price">
                                <h6>{{ number_format($product->price, 0, ',', '.') }} VNĐ</h6>
                                {{-- Giá gạch nếu có, có thể thêm cột `old_price` trong DB nếu muốn --}}
                                {{-- <h6 class="l-through">{{ number_format($product->old_price, 0, ',', '.') }}
                                VNĐ
                                </h6> --}}
                            </div>
                            <div class="prd-bottom">
                                <a href="#" class="social-info">
                                    <span class="ti-bag"></span>
                                    <p class="hover-text">Thêm vào giỏ hàng</p>
                                </a>
                                <a href="#" class="social-info">
                                    <span class="lnr lnr-heart"></span>
                                    <p class="hover-text">Danh sách yêu thích</p>
                                </a>
                                <a href="#" class="social-info">
                                    <span class="lnr lnr-sync"></span>
                                    <p class="hover-text">So sánh</p>
                                </a>
                                <a href="{{ route('client.product.show', $product->id_product) }}" class="social-info">
                                    <span class="lnr lnr-move"></span>
                                    <p class="hover-text">Chi tiết</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
    <!-- single product slide -->
    <div class="single-product-slider">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Sản phẩm sắp ra mắt</h1>
                        <p>Khám phá những mẫu giày sắp ra mắt, hứa hẹn mang đến phong cách và hiệu suất vượt trội.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- single product -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        <img class="img-fluid" src="{{asset('assets/img/product/p6.jpg')}}" alt="">
                        <div class="product-details">
                            <h6>Giày thể thao đế búa mới của Adidas</h6>
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <div class="prd-bottom">
                                <a href="" class="social-info">
                                    <span class="ti-bag"></span>
                                    <p class="hover-text">Thêm vào giỏ hàng</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-heart"></span>
                                    <p class="hover-text">Danh sách yêu thích</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-sync"></span>
                                    <p class="hover-text">So sánh</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-move"></span>
                                    <p class="hover-text">Xem thêm</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- single product -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        <img class="img-fluid" src="{{asset('assets/img/product/p8.jpg')}}" alt="">
                        <div class="product-details">
                            <h6>Giày thể thao đế búa mới của Adidas</h6>
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <div class="prd-bottom">
                                <a href="" class="social-info">
                                    <span class="ti-bag"></span>
                                    <p class="hover-text">Thêm vào giỏ hàng</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-heart"></span>
                                    <p class="hover-text">Danh sách yêu thích</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-sync"></span>
                                    <p class="hover-text">So sánh</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-move"></span>
                                    <p class="hover-text">Xem thêm</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- single product -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        <img class="img-fluid" src="{{asset('assets/img/product/p3.jpg')}}" alt="">
                        <div class="product-details">
                            <h6>Giày thể thao đế búa mới của Adidas</h6>
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <div class="prd-bottom">
                                <a href="" class="social-info">
                                    <span class="ti-bag"></span>
                                    <p class="hover-text">Thêm vào giỏ hàng</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-heart"></span>
                                    <p class="hover-text">Danh sách yêu thích</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-sync"></span>
                                    <p class="hover-text">So sánh</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-move"></span>
                                    <p class="hover-text">Xem thêm</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- single product -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        <img class="img-fluid" src="{{asset('assets/img/product/p5.jpg')}}" alt="">
                        <div class="product-details">
                            <h6>Giày thể thao đế búa mới của Adidas</h6>
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <div class="prd-bottom">
                                <a href="" class="social-info">
                                    <span class="ti-bag"></span>
                                    <p class="hover-text">Thêm vào giỏ hàng</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-heart"></span>
                                    <p class="hover-text">Danh sách yêu thích</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-sync"></span>
                                    <p class="hover-text">So sánh</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-move"></span>
                                    <p class="hover-text">Xem thêm</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- single product -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        <img class="img-fluid" src="{{asset('assets/img/product/p1.jpg')}}" alt="">
                        <div class="product-details">
                            <h6>Giày thể thao đế búa mới của Adidas</h6>
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <div class="prd-bottom">
                                <a href="" class="social-info">
                                    <span class="ti-bag"></span>
                                    <p class="hover-text">Thêm vào giỏ hàng</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-heart"></span>
                                    <p class="hover-text">Danh sách yêu thích</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-sync"></span>
                                    <p class="hover-text">So sánh</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-move"></span>
                                    <p class="hover-text">Xem thêm</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- single product -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        <img class="img-fluid" src="{{asset('assets/img/product/p4.jpg')}}" alt="">
                        <div class="product-details">
                            <h6>Giày thể thao đế búa mới của Adidas</h6>
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <div class="prd-bottom">
                                <a href="" class="social-info">
                                    <span class="ti-bag"></span>
                                    <p class="hover-text">Thêm vào giỏ hàng</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-heart"></span>
                                    <p class="hover-text">Danh sách yêu thích</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-sync"></span>
                                    <p class="hover-text">So sánh</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-move"></span>
                                    <p class="hover-text">Xem thêm</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- single product -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        <img class="img-fluid" src="{{asset('assets/img/product/p1.jpg')}}" alt="">
                        <div class="product-details">
                            <h6>Giày thể thao đế búa mới của Adidas</h6>
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <div class="prd-bottom">
                                <a href="" class="social-info">
                                    <span class="ti-bag"></span>
                                    <p class="hover-text">Thêm vào giỏ hàng</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-heart"></span>
                                    <p class="hover-text">Danh sách yêu thích</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-sync"></span>
                                    <p class="hover-text">So sánh</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-move"></span>
                                    <p class="hover-text">Xem thêm</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- single product -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        <img class="img-fluid" src="{{asset('assets/img/product/p8.jpg')}}" alt="">
                        <div class="product-details">
                            <h6>Giày thể thao đế búa mới của Adidas</h6>
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <div class="prd-bottom">
                                <a href="" class="social-info">
                                    <span class="ti-bag"></span>
                                    <p class="hover-text">Thêm vào giỏ hàng</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-heart"></span>
                                    <p class="hover-text">Danh sách yêu thích</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-sync"></span>
                                    <p class="hover-text">So sánh</p>
                                </a>
                                <a href="" class="social-info">
                                    <span class="lnr lnr-move"></span>
                                    <p class="hover-text">Xem thêm</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end product Area -->

<!-- Start exclusive deal Area -->
<section class="exclusive-deal-area">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 no-padding exclusive-left">
                <div class="row clock_sec clockdiv" id="clockdiv">
                    <div class="col-lg-12">
                        <h1>Ưu đãi hấp dẫn độc quyền sắp kết thúc!</h1>
                        <p>Dành cho những ai yêu thích các sản phẩm thân thiện với môi trường.</p>
                    </div>
                    <div class="col-lg-12">
                        <div class="row clock-wrap">
                            <div class="col clockinner1 clockinner">
                                <h1 class="days">150</h1>
                                <span class="smalltext">Ngày</span>
                            </div>
                            <div class="col clockinner clockinner1">
                                <h1 class="hours">23</h1>
                                <span class="smalltext">Giờ</span>
                            </div>
                            <div class="col clockinner clockinner1">
                                <h1 class="minutes">47</h1>
                                <span class="smalltext">Phút</span>
                            </div>
                            <div class="col clockinner clockinner1">
                                <h1 class="seconds">59</h1>
                                <span class="smalltext">Giây</span>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="" class="primary-btn">Mua ngay</a>
            </div>
            <div class="col-lg-6 no-padding exclusive-right">
                <div class="active-exclusive-product-slider">
                    <!-- single exclusive carousel -->
                    <div class="single-exclusive-slider">
                        <img class="img-fluid" src="{{asset('assets/img/product/e-p1.png')}}" alt="">
                        <div class="product-details">
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <h4>Giày thể thao đế búa mới của Adidas</h4>
                            <div class="add-bag d-flex align-items-center justify-content-center">
                                <a class="add-btn" href=""><span class="ti-bag"></span></a>
                                <span class="add-text text-uppercase">Thêm vào giỏ hàng</span>
                            </div>
                        </div>
                    </div>
                    <!-- single exclusive carousel -->
                    <div class="single-exclusive-slider">
                        <img class="img-fluid" src="{{asset('assets/img/product/e-p1.png')}}" alt="">
                        <div class="product-details">
                            <div class="price">
                                <h6>150.000 VNĐ</h6>
                                <h6 class="l-through">210.000 VNĐ</h6>
                            </div>
                            <h4>Giày thể thao đế búa mới của Adidas</h4>
                            <div class="add-bag d-flex align-items-center justify-content-center">
                                <a class="add-btn" href=""><span class="ti-bag"></span></a>
                                <span class="add-text text-uppercase">Thêm vào giỏ hàng</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End exclusive deal Area -->

<!-- Start brand Area -->
<section class="brand-area section_gap">
    <div class="container">
        <div class="row">
            <a class="col single-img" href="#">
                <img class="img-fluid d-block mx-auto" src="{{asset('assets/img/brand/1.png')}}" alt="">
            </a>
            <a class="col single-img" href="#">
                <img class="img-fluid d-block mx-auto" src="{{asset('assets/img/brand/2.png')}}" alt="">
            </a>
            <a class="col single-img" href="#">
                <img class="img-fluid d-block mx-auto" src="{{asset('assets/img/brand/3.png')}}" alt="">
            </a>
            <a class="col single-img" href="#">
                <img class="img-fluid d-block mx-auto" src="{{asset('assets/img/brand/4.png')}}" alt="">
            </a>
            <a class="col single single-img" href="#">
                <img class="img-fluid d-block mx-auto" src="{{asset('assets/img/brand/5.png')}}" alt="">
            </a>
        </div>
    </div>
</section>
<!-- End brand Area -->






<script>
$(document).ready(function() {
    var bannerSlider = $('.active-banner-slider').owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 5000,
        nav: false, // tắt nav mặc định
        dots: true,
        animateOut: 'fadeOut',
    });

    // Gán nút tùy chỉnh
    $('.btn-next').click(function() {
        bannerSlider.trigger('next.owl.carousel');
    });

    $('.btn-prev').click(function() {
        bannerSlider.trigger('prev.owl.carousel');
    });
});
</script>
@endsection
