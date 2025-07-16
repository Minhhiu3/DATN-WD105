@extends('layouts.client_home')
@section('title', 'Trang Chủ')
@section('content')

<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Checkout-Cart</h1>
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
        <div class="billing_details">
            <div class="row">
                <div class="col-lg-6">
                    <h3>Chi tiết thanh toán</h3>
                    <form id="checkout-form" class="row contact_form" action="{{ route('account.placeOrder.cart') }}" method="POST">
                        @csrf

                        <!-- Thông tin người dùng -->
                        <div class="col-md-6 form-group p_star">
                            <label><b>Họ và tên</b></label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                        </div>

                        <div class="col-md-6 form-group p_star">
                            <label><b>Số điện thoại</b></label>
                            <input type="text" class="form-control" value="{{ auth()->user()->phone_number }}" disabled>
                        </div>

                        <div class="col-md-12 form-group p_star">
                            <label><b>Email</b></label>
                            <input type="text" class="form-control" value="{{ auth()->user()->email }}" disabled>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="col-md-12 form-group">
                            <label><b>Tỉnh/Thành</b></label>
                            <input type="text" name="province" class="form-control" placeholder="Nhập tỉnh/thành" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label><b>Quận/Huyện</b></label>
                            <input type="text" name="district" class="form-control" placeholder="Nhập quận/huyện" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label><b>Xã/Phường</b></label>
                            <input type="text" name="ward" class="form-control" placeholder="Nhập xã/phường" required>
                        </div>

                        <div class="col-md-12 form-group">
                            <label><b>Địa chỉ chi tiết</b></label>
                            <input type="text" name="address" class="form-control" placeholder="Ví dụ: Số 123, đường ABC..." required>
                        </div>

                        <!-- Dữ liệu giỏ hàng -->
                        @foreach($cartItems as $item)
                            <input type="hidden" name="items[{{ $item->variant_id }}][variant_id]" value="{{ $item->variant_id }}">
                            <input type="hidden" name="items[{{ $item->variant_id }}][quantity]" value="{{ $item->quantity }}">
                        @endforeach

                        <!-- Thanh toán -->
                        <div class="col-md-12 form-group mt-4">
                            <label><b>Phương thức thanh toán</b></label><br>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" checked>
                                <label class="form-check-label" for="payment_cod">
                                    Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_vnpay" value="vnpay">
                                <label class="form-check-label" for="payment_vnpay">
                                    Thanh toán qua VNPay
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12 text-right mt-3">
                            <button type="submit" class="primary-btn">Xác nhận đặt hàng</button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-6">
                    <div class="order_box">
                        <h2>Đơn hàng của bạn</h2>
                        <ul class="list">
                            @foreach ($cartItems as $item)
                                <li>
                                    <b>{{ $item->variant->product->name_product }} (Size {{ $item->variant->size->name ?? 'N/A' }})</b>
                                    <span class="middle">x {{ $item->quantity }}</span>
                                    <span class="last">{{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }} VNĐ</span>
                                </li>
                            @endforeach
                        </ul>

                        @php
                            $total = $cartItems->sum(fn($item) => $item->variant->price * $item->quantity);
                        @endphp
                        <ul class="list list_2">
                            <li><a href="#">Tổng cộng <span>{{ number_format($total, 0, ',', '.') }} VNĐ</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script xử lý thanh toán -->
<script>
    document.getElementById("checkout-form").addEventListener("submit", function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const paymentMethod = formData.get("payment_method");

        if (paymentMethod === "vnpay") {
            fetch("{{ route('payment.vnpay.request') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data?.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    alert("Có lỗi khi khởi tạo thanh toán VNPay");
                }
            })
            .catch(() => alert("Không thể kết nối tới máy chủ."));
        } else {
            form.submit();
        }
    });
</script>
@endsection
