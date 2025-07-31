@extends('layouts.client_home')
@section('title', 'Trang Chủ')
@section('content')
@php
    $variantId= request('variant_id');
    $quantity= request('quantity');
@endphp
<!-- Start Banner Area -->
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
            <form id="apply-coupon-form">
                @csrf
                <input type="hidden" id="variantId" name="variantId" value="{{$variantId}}" >
                <input type="hidden" id="quantity" name="quantity" value="{{$quantity}}" >
                <input type="text" id="coupon_code" name="coupon_code" placeholder="Nhập mã giảm giá">

                <button type="submit" class="tp_btn">OK</button>
            </form>
            <p id="coupon-message" class="mt-2 text-success"></p>

        </div>
 {{-- <form  method="POST">
            @csrf

            <button type="submit" class="primary-btn w-100 mt-3" formaction="{{ route('account.vnpay.payment') }}">VN PAY</button>
        </form> --}}

        <form action="{{ route('account.placeOrder') }}" method="POST" class="row contact_form">
            @csrf
            <input type="hidden" name="variant_id" value="{{ $variant->id_variant }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">

            <div class="col-lg-6">
                <h3>Chi tiết thanh toán</h3>

                      <!-- Thông tin người dùng -->
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

               <!-- Địa chỉ -->
  <div class="col-md-12 form-group">
    <label><b>Tỉnh/Thành</b></label>
    <select id="province" name="province" class="form-control" required>
        <option value="">-- Chọn Tỉnh/Thành phố --</option>
    </select>
</div>

<div class="col-md-12 form-group">
    <label><b>Xã/Phường</b></label>
    <select id="ward" name="ward" class="form-control" required disabled>
        <option value="">-- Chọn Xã/Phường --</option>
    </select>
</div>

                <div class="form-group">
                    <label>Địa chỉ chi tiết</label>
                    <input type="text" name="address" class="form-control" placeholder="Ví dụ: Số 123, đường ABC..." required>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="order_box">
                    <h2>Đơn hàng của bạn</h2>
                    <ul class="list">
                        <li>
                            <b>{{ $variant->product->name_product }} (Size: {{ $variant->size->name }}, Color: {{$variant->color->name_color}})</b>
                            <span class="middle">x {{ $quantity }}</span>
                            <span class="last">{{ number_format($variant->price * $quantity, 0, ',', '.') }} VNĐ</span>
                        </li>
                    </ul>
                            @php
            $subTotal = $cartItems->sum(fn($item) => $item->variant->price * $item->quantity);
            $shippingFee = 30000;
            $grandTotal = $subTotal + $shippingFee;
        @endphp
                    <ul class="list list_2">
                        <li><a href="#">Phí vận chuyển <span>{{ number_format($shippingFee, 0, ',', '.') }} VNĐ</span></a></li>
                        <li><a href="#">Tiền giảm giá <span id="discount-amount">{{ number_format(0, 0, ',', '.') }} VNĐ</span></a></li>
                        <li><a href="#">Tổng tiền
                                <span id="order-total">{{ number_format($variant->price * $quantity + $shippingFee, 0, ',', '.') }} VNĐ</span></a>
                        </li>
                    </ul>

                   <div class="col-md-12 text-right mt-3 d-flex justify-content-between">
                                <button type="submit" name="payment_method" value="cod" class="btn primary-btn mr-2">
                                    ĐẶT HÀNG<br><small>(Trả tiền khi nhận hàng)</small>
                                </button>

                                <button type="submit" name="payment_method" value="vnpay" class="btn primary-btn">
                                    THANH TOÁN ONLINE <br><small>(Thông qua VNPay)</small>
                                </button>
                            </div>
                </div>
            </div>
        </form>



    </div>
</section>
<script>
document.getElementById('apply-coupon-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const couponCode = document.getElementById('coupon_code').value;
    const variantId = document.getElementById('variantId').value;
    const quantity = document.getElementById('quantity').value;
    const messageEl = document.getElementById('coupon-message');

    fetch("{{ route('apply.coupon') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            coupon_code: couponCode,
            variant_id: variantId,
            quantity: quantity
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            messageEl.textContent = data.message;
            messageEl.classList.remove('text-danger');
            messageEl.classList.add('text-success');

            const orderTotalElement = document.getElementById('order-total');
            if (orderTotalElement && data.final_total !== undefined) {
                orderTotalElement.textContent = new Intl.NumberFormat('vi-VN').format(data.final_total) + ' VNĐ';
            }

            // ✅ Hiển thị số tiền giảm
            const discountEl = document.getElementById('discount-amount');
            if (discountEl && data.discount !== undefined) {
                discountEl.textContent = 'Bạn được giảm: ' + new Intl.NumberFormat('vi-VN').format(data.discount) + ' VNĐ';
            }

        } else {
            messageEl.textContent = data.message;
            messageEl.classList.remove('text-success');
            messageEl.classList.add('text-danger');
        }
    })


    .catch(error => {
        console.error("Lỗi khi áp mã:", error);
        messageEl.textContent = 'Lỗi hệ thống khi áp mã.';
        messageEl.classList.remove('text-success');
        messageEl.classList.add('text-danger');
    });
});
</script>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById("province");
    const districtSelect = document.getElementById("district");
    const wardSelect = document.getElementById("ward");

    let provincesData = []; // Lưu dữ liệu API để dùng sau

    // ✅ Gọi API lấy danh sách tỉnh + xã/phường
    fetch("https://vietnamlabs.com/api/vietnamprovince")
        .then(res => res.json())
        .then(response => {
            provincesData = response.data || [];
            provincesData.forEach(p => {
                const option = new Option(p.province, p.province);
                provinceSelect.add(option);
            });
        })
        .catch(err => console.error("Lỗi load tỉnh:", err));

    // ✅ Khi chọn tỉnh -> load xã/phường từ wards
    provinceSelect.addEventListener("change", function() {
        const provinceName = this.value;
        wardSelect.innerHTML = '<option value="">-- Chọn Xã/Phường --</option>';

        if (!provinceName) return;

        const selectedProvince = provincesData.find(p => p.province === provinceName);
        if (selectedProvince && selectedProvince.wards) {
            selectedProvince.wards.forEach(w => {
                const option = new Option(w.name, w.name);
                wardSelect.add(option);
            });
        }

        wardSelect.disabled = false;
    });
});
</script>
@endpush
