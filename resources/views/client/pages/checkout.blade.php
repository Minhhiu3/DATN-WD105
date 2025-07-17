@extends('layouts.client_home')
@section('title', 'Trang Chủ')
@section('content')

<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Checkout</h1>
                <nav class="d-flex align-items-center">
                    <a href="{{ route('home') }}">Home<span class="lnr lnr-arrow-right"></span></a>
                    <a href="#">Checkout</a>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="checkout_area section_gap">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="container">
        <form id="checkoutForm" action="" method="POST" class="row contact_form">
            @csrf
            <input type="hidden" name="variant_id" value="{{ $variant->id_variant }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">
            <input type="hidden" name="amount" value="{{ $variant->price * $quantity }}">
            <input type="hidden" name="order_info" value="Thanh toán đơn hàng: {{ $variant->product->name_product }}">
            <input type="hidden" name="payment_method" id="payment_method" value="">

            <div class="col-lg-6">
                <h3>Chi tiết thanh toán</h3>

                <div class="form-group">
                    <label><b>Họ và tên</b></label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                </div>

                <div class="form-group">
                    <label><b>Số điện thoại</b></label>
                    <input type="text" class="form-control" value="{{ auth()->user()->phone_number }}" disabled>
                </div>

                <div class="form-group">
                    <label><b>Email</b></label>
                    <input type="text" class="form-control" value="{{ auth()->user()->email }}" disabled>
                </div>

                <div class="form-group">
                    <label>Tỉnh/Thành</label>
                    <input type="text" name="province" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Quận/Huyện</label>
                    <input type="text" name="district" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Xã/Phường</label>
                    <input type="text" name="ward" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Địa chỉ chi tiết</label>
                    <input type="text" name="address" class="form-control" required>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="order_box">
                    <h2>Đơn hàng của bạn</h2>
                    <ul class="list">
                        <li>
                            <b>{{ $variant->product->name_product }} (Size {{ $variant->size->name }})</b>
                            <span class="middle">x {{ $quantity }}</span>
                            <span class="last">{{ number_format($variant->price * $quantity, 0, ',', '.') }} VNĐ</span>
                        </li>
                    </ul>
                    <ul class="list list_2">
                        <li><a href="#">Phí vận chuyển <span>0 VND</span></a></li>
                        <li><a href="#">Tổng tiền <span>{{ number_format($variant->price * $quantity, 0, ',', '.') }} VNĐ</span></a></li>
                    </ul>

                    {{-- 2 nút submit --}}
                    <button type="submit" name="submit_method" value="cod" class="primary-btn w-100 mt-2 mb-2">Đặt hàng (COD)</button>
                    <button type="submit" name="submit_method" value="vnpay" class="primary-btn w-100">Thanh toán qua VNPay</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('checkoutForm');

        form.addEventListener('submit', function (e) {
            const method = e.submitter.value;
            const methodInput = document.getElementById('payment_method');
            const action = method === 'cod'
                ? "{{ route('account.placeOrder') }}"
                : "{{ route('payment.vnpay.request') }}";

            methodInput.value = method;
            form.action = action;
        });
    });
</script>

@endsection
