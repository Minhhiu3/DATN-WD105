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

                 <div class="common-filter">
    <div class="head">Size</div>

    <div class="d-flex flex-wrap gap-2">
        @foreach ($sizes as $size)
            <button type="button" class="btn btn-outline-dark size-square">
                {{ $size->name }}
            </button>
        @endforeach
    </div>
</div>
                    <div class="common-filter mb-5">
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
                            {{-- <option value="1">Default sorting</option>
                            <option value="1">Default sorting</option>
                            <option value="1">Default sorting</option> --}}
                        </select>
                    </div>
                    <div class="sorting mr-auto">
                        <select>
                            {{-- <option value="1">Show 12</option>
                            <option value="1">Show 12</option>
                            <option value="1">Show 12</option> --}}
                        </select>
                    </div>

                </div>
                <!-- End Filter Bar -->
                <!-- Start Best Seller -->
                <section class="lattest-product-area pb-40 category-list">
                    <div class="row">
                        <!-- single product -->
                         @forelse($products as $product)
                            <div class="col-lg-4 col-md-6">
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

                                            <a href="" class="social-info">
                                                <span class="ti-bag"></span>
                                                <p class="hover-text">add to bag</p>
                                            </a>
                                            <a href="" class="social-info">
                                                <span class="lnr lnr-heart"></span>
                                                <p class="hover-text">Wishlist</p>
                                            </a>
                                            <a href="" class="social-info">
                                                <span class="ti-shopping-cart"></span>
                                                <p class="hover-text" type="submit">Add to cart</p>
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
                        @empty
            <div class="col-12">
                <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp.</p>
            </div>
        @endforelse


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


@endsection
