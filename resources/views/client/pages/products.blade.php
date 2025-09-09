@extends('layouts.client_home')
@section('title', 'Sản Phẩm')
@section('content')

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Cửa Hàng</h1>
                    <nav class="d-flex align-items-center">
                        <a href="{{ route('home') }}">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                        <a href="{{ route('products') }}">Cửa hàng</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-5">
                 <form method="GET" action="{{ route('products') }}">
                <div class="sidebar-categories">
                    <div class="head">Danh mục sản phẩm</div>
                    <ul>
                @foreach ($categories as $category)
                    <li>
                        <input type="checkbox" name="category[]" value="{{ $category->id_category }}"
                               {{ in_array($category->id_category, (array) request('category')) ? 'checked' : '' }}>
                        <label>{{ $category->name_category }}</label>
                    </li>
                @endforeach
            </ul>
                </div>
                <div class="sidebar-filter mt-50">
                    <div class="top-filter-head">Lọc</div>
                    <div class="common-filter">
                        <div class="head">Size</div>
                        <ul>
                @foreach ($sizes as $size)
                    <li>
                        <input type="checkbox" name="size[]" value="{{ $size->name }}"
                               {{ in_array($size->name, (array) request('size')) ? 'checked' : '' }}>
                        <label>{{ $size->name }}</label>
                    </li>
                @endforeach
            </ul>
                    </div>
                    <div class="common-filter">
                        <div class="head">Thương hiệu</div>
                         <ul>
                @foreach ($brands as $brand)
                    <li>
                        <input type="checkbox" name="brand[]" value="{{ $brand->id_brand }}"
                               {{ in_array($brand->id_brand, (array) request('brand')) ? 'checked' : '' }}>
                        <label>{{ $brand->name }}</label>
                    </li>
                @endforeach
            </ul>
                    </div>
                    <div class="common-filter mb-5">
                        <div class="head">Giá</div>
                        <ul>
                <li><input type="checkbox" name="price[]" value="under_500000" {{ in_array('under_500000', (array) request('price')) ? 'checked' : '' }}> <label>Dưới 500,000₫</label></li>
                <li><input type="checkbox" name="price[]" value="500000_1000000" {{ in_array('500000_1000000', (array) request('price')) ? 'checked' : '' }}> <label>500,000₫ - 1,000,000₫</label></li>
                <li><input type="checkbox" name="price[]" value="1000000_2000000" {{ in_array('1000000_2000000', (array) request('price')) ? 'checked' : '' }}> <label>1,000,000₫ - 2,000,000₫</label></li>
                <li><input type="checkbox" name="price[]" value="2000000_3000000" {{ in_array('2000000_3000000', (array) request('price')) ? 'checked' : '' }}> <label>2,000,000₫ - 3,000,000₫</label></li>
                <li><input type="checkbox" name="price[]" value="over_3000000" {{ in_array('over_3000000', (array) request('price')) ? 'checked' : '' }}> <label>Trên 3,000,000₫</label></li>
            </ul>
                    </div>
                </div>
                 <button type="submit" class="btn btn-dark w-100 mt-3 mb-5">Lọc</button>
    </form>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-7">
                <!-- Start Filter Bar -->
                {{-- <div class="filter-bar d-flex flex-wrap align-items-center">
                    <div class="sorting mr-auto">
                        <form method="get" action="{{ route('products') }}" class="d-inline">
                            <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="Tìm kiếm sản phẩm..."
                                   class="form-control mb-2">
                            <button type="submit" class="btn btn-outline-secondary">Tìm</button>
                        </form>
                    </div>
                </div> --}}
                <!-- End Filter Bar -->
                <!-- Start Best Seller -->
                <section class="lattest-product-area pb-40 category-list">
                    <div class="row">
                        <!-- single product -->
                        @forelse($products as $product)
                            <div class="col-lg-4 col-md-6">
                                <figure class="single-product">
                                    <div style="overflow: hidden; display: flex; justify-content: center; align-items: center; height: 250px;">
                                        <img style="height: 100%; width: auto" src="{{ asset('/storage/' . $product->image) }}"
                                             alt="{{ $product->image }}">
                                    </div>
                                    <figcaption class="product-details" style="">
                                        <h6>{{ $product->name_product }}</h6>
                                        @php
                                            $sale = $product->advice_product;
                                            $now = \Carbon\Carbon::now();
                                            $start = \Carbon\Carbon::parse($sale->start_date ?? '')->startOfDay();
                                            $end = \Carbon\Carbon::parse($sale->end_date ?? '')->endOfDay();
 $minPrice = $product->variants->min('price');
                                            $maxPrice = $product->variants->max('price');
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
                                            <div style="
                                                position: absolute;
                                                top: 40px;
                                                left: 30px;
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
                        @empty
                           <div class="row">
        <div class="col-12 text-center my-5">
            <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp.</p>
        </div>
    </div>
                        @endforelse
                    </div>
                    @if ($products->hasPages())
                        <div class="mt-3">
                            {!! $products->links('pagination::bootstrap-5') !!}
                        </div>
                    @endif
                </section>
                <!-- End Best Seller -->
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    /* CSS cho Filter Form */
    .sidebar-filter .common-filter {
        margin-bottom: 20px;
    }

    .sidebar-filter .head {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .size-square {
        padding: 5px 10px;
        margin: 5px 0;
        text-align: center;
        border-radius: 4px;
    }

    .size-square.active {
        background-color: #f97316;
        color: white;
        border-color: #f97316;
    }

    .size-square:hover {
        background-color: #e65c00;
        color: white;
        text-decoration: none;
    }

    .filter-bar .sorting {
        margin-right: auto;
    }

    .filter-bar .form-control {
        width: 200px;
        display: inline-block;
    }

    .filter-bar .btn-outline-secondary {
        margin-left: 5px;
    }
</style>
@endpush
