@extends('layouts.client_home')
@section('title', 'Trang Chủ')
@section('content')

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Checkout-Cart</h1>
                    {{-- <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="single-product.html">Checkout</a>
                    </nav> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Checkout Area =================-->
    <section class="checkout_area section_gap">
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="container">

            <div class="cupon_area">
                <div class="check_title">
                    <h2>Nhập mã giảm giá của bạn <a href="#">Các sự kiện nhận mã giảm giá</a></h2>
                </div>
                <input type="text" placeholder="Nhập mã giảm giá">
                <a class="tp_btn" href="#">OK</a>
            </div>
            <div class="billing_details">
                <div class="row">
                    <div class="col-lg-6">
                        <h3>Chi tiết thanh toán</h3>
                        <form class="row contact_form" action="#" method="post" novalidate="novalidate">
                            <div class="col-md-6 form-group p_star">
                                <label for="name"><b>Họ và tên</b></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', auth()->user()->name ?? '') }}" disabled>
                                {{-- <span class="placeholder" data-placeholder="Họ và tên"></span> --}}
                            </div>

                            <div class="col-md-6 form-group p_star">
                                <label for="name"><b>Số điện thoại</b></label>
                                <input type="text" class="form-control" id="number" name="number"
                                    value="{{ old('number', auth()->user()->phone_number ?? '') }}" disabled>
                                {{-- <span class="placeholder" data-placeholder="Số điện thoại"></span> --}}
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <label for="name"><b>Email</b></label>
                                <input type="text" class="form-control" id="email" name="compemailany"
                                    value="{{ old('email', auth()->user()->email ?? '') }}" disabled>
                                {{-- <span class="placeholder" data-placeholder="Email"></span> --}}
                            </div>
                            <!-- Province -->
                            {{-- <div class="col-md-12 form-group p_star">
                                <select class="form-control" id="provinceSelect">
                                    <option value="">Chọn Tỉnh/Thành</option>
                                </select>
                            </div>

                            <!-- District -->
                            <div class="col-md-12 form-group p_star">
                                <select class="form-control" id="districtSelect">
                                    <option value="">Chọn Xã/Phường</option>
                                </select>
                            </div> --}}
                            <div class="col-md-12 form-group p_star">
                                <label for="name"><b>Địa chỉ</b></label>
                                <input type="text" class="form-control" id="add2" name="add2">
                                <span class="placeholder" data-placeholder=""></span>
                            </div>



                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="order_box">
                            <h2>Đơn hàng của bạn</h2>

                            <ul class="list">
                                @foreach ($cartItems as $item)
                                    <li>
                                        <a href="#">
                                            <b>{{ $item->variant->product->name_product }} (Size
                                                {{ $item->variant->size->name ?? 'N/A' }})</b>
                                            <span class="middle">x {{ $item->quantity }}</span>
                                            <span
                                                class="last">{{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }}
                                                VNĐ</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @php
                                $total = $cartItems->sum(function ($item) {
                                    return $item->variant->price * $item->quantity;
                                });
                            @endphp
                            <ul class="list list_2">
                                <li><a href="#">Tổng cộng <span>{{ number_format($total, 0, ',', '.') }}
                                            VNĐ</span></a></li>
                            </ul>

                           <form action="{{ route('account.placeOrder.cart') }}" method="POST">
    @csrf

    @foreach($cartItems as $item)
        <input type="hidden" name="items[{{ $item->variant_id }}][variant_id]" value="{{ $item->variant_id }}">
        <input type="hidden" name="items[{{ $item->variant_id }}][quantity]" value="{{ $item->quantity }}">
    @endforeach

    <div class="payment_item">
        <label><input type="radio" name="payment_method" value="cod" checked> Thanh toán khi nhận hàng</label>
    </div>

    <div class="payment_item">
        <label><input type="radio" name="payment_method" value="vnpay"> Thanh toán qua VNPay</label>
    </div>

    <button type="submit" class="primary-btn">Xác nhận đặt hàng</button>
</form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--================End Checkout Area =================-->
@endsection
