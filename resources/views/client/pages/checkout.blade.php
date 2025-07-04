@extends('layouts.client_home')
@section('title', 'Trang Chủ')
@section('content')

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Checkout</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="single-product.html">Checkout</a>
                    </nav>
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
            {{-- <div class="returning_customer">
                <div class="check_title">
                    <h2>Returning Customer? <a href="#">Click here to login</a></h2>
                </div>
                <p>If you have shopped with us before, please enter your details in the boxes below. If you are a new
                    customer, please proceed to the Billing & Shipping section.</p>
                <form class="row contact_form" action="#" method="post" novalidate="novalidate">
                    <div class="col-md-6 form-group p_star">
                        <input type="text" class="form-control" id="name" name="name">
                        <span class="placeholder" data-placeholder="Username or Email"></span>
                    </div>
                    <div class="col-md-6 form-group p_star">
                        <input type="password" class="form-control" id="password" name="password">
                        <span class="placeholder" data-placeholder="Password"></span>
                    </div>
                    <div class="col-md-12 form-group">
                        <button type="submit" value="submit" class="primary-btn">login</button>
                        <div class="creat_account">
                            <input type="checkbox" id="f-option" name="selector">
                            <label for="f-option">Remember me</label>
                        </div>
                        <a class="lost_pass" href="#">Lost your password?</a>
                    </div>
                </form>
            </div> --}}
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
                            {{-- <div class="col-md-6 form-group p_star">
                                <input type="text" class="form-control" id="last" name="name">
                                <span class="placeholder" data-placeholder="Last name"></span>
                            </div> --}}
                            {{-- <div class="col-md-12 form-group">
                                <input type="text" class="form-control" id="company" name="company" placeholder="Company name">
                            </div> --}}
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
                            <div class="col-md-12 form-group p_star">
                                <select class="form-control" id="provinceSelect">
                                    <option value="">Chọn Tỉnh/Thành</option>
                                </select>
                            </div>

                            <!-- District -->
                            <div class="col-md-12 form-group p_star">
                                <select class="form-control"   id="districtSelect">
                                    <option value="">Chọn Xã/Phường</option>
                                </select>
                            </div>
                            <div class="col-md-12 form-group p_star">
                                <input type="text" class="form-control" id="add2" name="add2">
                                <span class="placeholder" data-placeholder="Địa chỉ"></span>
                            </div>



                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="order_box">
                            <h2>Đơn hàng của bạn</h2>
                            <ul class="list">
                                <li>
                                    <a href="#">
                                        <b>{{ $variant->product->name_product }} (Size {{ $variant->size->name }})</b>
                                        <span class="middle">x {{ $quantity }}</span>
                                        <span class="last">{{ number_format($variant->price * $quantity, 0, ',', '.') }}
                                            VNĐ</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="list list_2">


                                {{-- <li><a href="#">Tổng tiền <span>$2160.00</span></a></li> --}}
                                <li><a href="#">Phí vận chuyển <span>0 VND</span></a></li>
                                <li><a href="#">Tổng tiền
                                        <span>{{ number_format($variant->price * $quantity, 0, ',', '.') }} VNĐ</span></a>
                                </li>
                            </ul>
                            {{-- <div class="payment_item">
                                <div class="radion_btn">
                                    <input type="radio" id="f-option5" name="payment_method" value="cod" checked>
                                    <label for="f-option5">Thanh toán khi nhận hàng</label>
                                    <div class="check"></div>
                                </div>
                                <p>Thanh toán trực tiếp khi nhận được hàng</p>
                            </div>
                            <div class="payment_item">
                                <div class="radion_btn">
                                    <input type="radio" id="f-option6" name="payment_method" value="vnpay">
                                    <label for="f-option6">Thanh toán online </label>
                                    <img src="{{ asset('img/product/card.jpg') }}" alt="">
                                    <div class="check"></div>
                                </div>
                                <p>VN Pay</p>
                            </div> --}}
                            {{-- <div class="creat_account">
                                <input type="checkbox" id="f-option4" name="selector">
                                <label for="f-option4">Tôi đã đọc và chấp nhận </label>
                                <a href="#">các điều khoản và điểu kiện</a>
                            </div> --}}
                            <form action="{{ route('account.placeOrder') }}" method="POST">
                                @csrf
                                <input type="hidden" name="variant_id" value="{{ $variant->id_variant }}">
                                <input type="hidden" name="quantity" value="{{ $quantity }}">

                                <div class="payment_item">
                                    <label>
                                        <input type="radio" name="payment_method" value="cod" checked>
                                        Thanh toán khi nhận hàng
                                    </label>
                                </div>
                                <div class="payment_item">
                                    <label>
                                        <input type="radio" name="payment_method" value="vnpay">
                                        Thanh toán qua VNPay
                                    </label>
                                </div>

                                <button type="submit" class="primary-btn w-100">Đặt Hàng</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
   document.addEventListener("DOMContentLoaded", function () {
    const provinceSelect = document.getElementById('provinceSelect');
    const districtSelect = document.getElementById('districtSelect');

    if (!provinceSelect || !districtSelect) {
        console.error("Không tìm thấy provinceSelect hoặc districtSelect");
        return;
    }

    async function loadProvinces() {
        try {
            provinceSelect.innerHTML = '<option value="">Đang tải...</option>';
            const res = await fetch('https://vietnamlabs.com/api/vietnamprovince');
            const data = await res.json();
            console.log(data); // Kiểm tra dữ liệu API
            provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành</option>';
            data.forEach(province => { // Sửa từ data.data thành data
                const opt = document.createElement("option");
                opt.value = province.id;
                opt.text = province.province;
                provinceSelect.appendChild(opt);
            });
        } catch (error) {
            console.error("Lỗi khi tải tỉnh/thành:", error);
            provinceSelect.innerHTML = '<option value="">Lỗi khi tải</option>';
        }
    }

    async function loadDistricts(provinceId) {
        try {
            districtSelect.innerHTML = '<option value="">Đang tải...</option>';
            const res = await fetch(`https://vietnamlabs.com/api/vietnamprovince/districts/${provinceId}`);
            const data = await res.json();
            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            data.data.forEach(district => { // Giả định API districts trả về data.data
                const opt = document.createElement("option");
                opt.value = district.name;
                opt.text = district.name;
                districtSelect.appendChild(opt);
            });
        } catch (error) {
            console.error("Lỗi khi tải quận/huyện:", error);
            districtSelect.innerHTML = '<option value="">Lỗi khi tải</option>';
        }
    }

    loadProvinces();
    provinceSelect.addEventListener('change', function () {
        const provinceId = this.value;
        if (provinceId) {
            loadDistricts(provinceId);
        } else {
            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        }
    });
});
    </script>
    <!--================End Checkout Area =================-->
@endsection
