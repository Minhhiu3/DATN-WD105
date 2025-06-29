@extends('layouts.client_home')
@section('title', 'Sản Phẩm')
@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Shop Category page</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="#">Shop<span class="lnr lnr-arrow-right"></span></a>
                        <a href="category.html">Fashon Category</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="sidebar-categories">
                    <div class="head">Danh mục sản phẩm</div>

                    <ul class="main-categories">
                        @foreach ($categories as $category)
                            <li class="main-nav-list"><a data-toggle="collapse" aria-expanded="false"
                                    aria-controls="fruitsVegetable"></span>{{ $category->name_category }}</a>

                            </li>
                        @endforeach

                    </ul>
                </div>
                <div class="sidebar-filter mt-50 ">
                    <div class="top-filter-head ">Lọc</div>
                    {{-- <div class="common-filter">
						<div class="head">Brand</div>
						<form action="#">
							<ul>
								<li class="filter-list"><input class="pixel-radio" type="radio" id="apple" name="brand"><label for="apple">Apple<span>(29)</span></label></li>
								<li class="filter-list"><input class="pixel-radio" type="radio" id="asus" name="brand"><label for="asus">Asus<span>(29)</span></label></li>
								<li class="filter-list"><input class="pixel-radio" type="radio" id="gionee" name="brand"><label for="gionee">Gionee<span>(19)</span></label></li>
								<li class="filter-list"><input class="pixel-radio" type="radio" id="micromax" name="brand"><label for="micromax">Micromax<span>(19)</span></label></li>
								<li class="filter-list"><input class="pixel-radio" type="radio" id="samsung" name="brand"><label for="samsung">Samsung<span>(19)</span></label></li>
							</ul>
						</form>
					</div> --}}
                    <div class="common-filter ">
                        <div class="head">Size</div>

                        <ul class="main-categories sidebar-categories">
                            @foreach ($sizes as $size)
                                <li class="main-nav-list">
                                    <a href="#">
                                        {{ $size->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="common-filter">
                        <div class="head">Price</div>
                        <form method="get" action="{{route('products.filterByPrice')}}">
                            <select name="price_range" onchange="this.form.submit()" id="">
                                <option value="">--Chọn Mức Giá--</option>
                                <option value="under_500000" {{request('price_range') == 'under_500000'? 'selected':'' }}>Dưới 500.000 VNĐ</option>
                                <option value="500000_2000000" {{request('price_range') == '500000_2000000'? 'selected':'' }}>Từ 500.000 VNĐ đến 2.000.000 VNĐ</option>
                                <option value="over_2000000" {{request('price_range') == 'over_2000000'? 'selected':'' }}>Trên 2.000.000 VNĐ</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-7">
                <!-- Start Filter Bar -->
                <div class="filter-bar d-flex flex-wrap align-items-center">
                    <div class="sorting">
                        <select>
                            <option value="1">Default sorting</option>
                            <option value="1">Default sorting</option>
                            <option value="1">Default sorting</option>
                        </select>
                    </div>
                    <div class="sorting mr-auto">
                        <select>
                            <option value="1">Show 12</option>
                            <option value="1">Show 12</option>
                            <option value="1">Show 12</option>
                        </select>
                    </div>

                </div>
                <!-- End Filter Bar -->
                <!-- Start Best Seller -->
                <section class="lattest-product-area pb-40 category-list">
                    <div class="row">
                        <!-- single product -->
                       <!-- single product -->
@foreach ($products as $product)
    <div class="col-lg-4 col-md-6">
        <div class="single-product">
            <img src="{{ asset('/storage/' . $product->image) }}" alt="{{ $product->image }}">
            <div class="product-details">
                <h6>{{ $product->name_product }}</h6>
                <div class="price">
                    <h6>{{ number_format($product->price, 0, ',', '.') }} VNĐ</h6>
                </div>
                <div class="prd-bottom">

                    {{-- Thêm vào giỏ hàng --}}
                    <form action="{{ route('cart.addAjax') }}" method="POST" class="social-info" style="display: inline-block;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" style="border: none; background: none;">
                            <span class="ti-bag"></span>
                            <p class="hover-text">Thêm vào giỏ</p>
                        </button>
                    </form>

                    {{-- Yêu thích --}}
                    <a href="#" class="social-info">
                        <span class="lnr lnr-heart"></span>
                        <p class="hover-text">Yêu thích</p>
                    </a>

                    {{-- So sánh --}}
                    <a href="#" class="social-info">
                        <span class="lnr lnr-sync"></span>
                        <p class="hover-text">So sánh</p>
                    </a>

                    {{-- Xem chi tiết --}}
                    <a href="{{ route('client.product.show', $product->id_product) }}" class="social-info">
                        <span class="lnr lnr-move"></span>
                        <p class="hover-text">Xem chi tiết</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach



                    </div>
                    @if ($products->hasPages())
                        <div class="mt-3 ">
                            {!! $products->links('pagination::bootstrap-5') !!}
                        </div>
                    @endif

                </section>

                <!-- End Best Seller -->
                <!-- Start Filter Bar -->

                <!-- End Filter Bar -->
            </div>

        </div>
    </div>

    <!-- Start related-product Area -->
    {{-- <section class="related-product-area section_gap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h1>Deals of the Week</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore
                            magna aliqua.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 mb-20">
                            <div class="single-related-product d-flex">
                                <a href="#"><img src="{{ 'assets/img/r1.jpg' }}" alt=""></a>
                                <div class="desc">
                                    <a href="#" class="title">Black lace Heels</a>
                                    <div class="price">
                                        <h6>$189.00</h6>
                                        <h6 class="l-through">$210.00</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="single-related-product d-flex">
                                <a href="#"><img src="{{ 'assets/img/r9.jpg' }}" alt=""></a>
                                <div class="desc">
                                    <a href="#" class="title">Black lace Heels</a>
                                    <div class="price">
                                        <h6>$189.00</h6>
                                        <h6 class="l-through">$210.00</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="ctg-right">
                        <a href="#" target="_blank">
                            <img class="img-fluid d-block mx-auto" src="img/category/c5.jpg" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- End related-product Area -->
@endsection
