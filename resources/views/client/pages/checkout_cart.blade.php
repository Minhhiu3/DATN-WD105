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
                        <form class="row contact_form" action="{{ route('account.placeOrder.cart') }}" method="POST">
                            @csrf

                            <!-- Thông tin người dùng -->
                            <div class="form-group">
                                <label><b>Họ và tên</b></label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                            </div>

                            <div class="form-group">
                                <label><b>Số điện thoại</b></label>
                                <input type="text" class="form-control" value="{{ auth()->user()->phone_number }}"
                                    disabled>
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

                            <div class="col-md-12 form-group">
                                <label><b>Địa chỉ chi tiết</b></label>
                                <input type="text" name="address" class="form-control"
                                    placeholder="Ví dụ: Số 123, đường ABC..." required>
                            </div>

                            <!-- Dữ liệu giỏ hàng -->
                            @foreach ($cartItems as $item)
                                <input type="hidden" name="items[{{ $item->variant_id }}][variant_id]"
                                    value="{{ $item->variant_id }}">
                                <input type="hidden" name="items[{{ $item->variant_id }}][quantity]"
                                    value="{{ $item->quantity }}">
                            @endforeach

                            <div class="col-md-12 text-right mt-3 d-flex justify-content-between">
                                <button type="submit" name="payment_method" value="cod" class="btn btn-warning">
                                    ĐẶT HÀNG THANH TOÁN SAU <br><small>(Trả tiền khi nhận hàng)</small>
                                </button>

                                <button type="submit" name="payment_method" value="vnpay" class="btn btn-primary">
                                    THANH TOÁN ONLINE <br><small>(Thông qua VNPay)</small>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-6">
                        <div class="order_box">
                            <h2>Đơn hàng của bạn</h2>
                            <ul class="list">
                                @foreach ($cartItems as $item)
                                    @php
                                        $variant = $item->variant;
                                        $product = $variant->product;
                                        $size = $variant->size;
                                        $color = $variant->color;
                                    @endphp
                                    <li>
                                        <b>{{ $item->variant->product->name_product }} (Size
                                            {{ $item->variant->size->name ?? 'N/A' }}, Color:{{$item -> variant->color->name_color}})</b>
                                        <span class="middle">x {{ $item->quantity }}</span>
                                        <span
                                            class="last">{{ number_format($item->variant->price * $item->quantity, 0, ',', '.') }}
                                            VNĐ</span>
                                    </li>
                                @endforeach
                            </ul>

                            @php
                                $subTotal = $cartItems->sum(fn($item) => $item->variant->price * $item->quantity);
                                $shippingFee = 30000;
                                $grandTotal = $subTotal + $shippingFee;
                            @endphp

                            <ul class="list list_2">
                                <li><a href="#">Tạm tính <span>{{ number_format($subTotal, 0, ',', '.') }}
                                            VNĐ</span></a></li>
                                <li><a href="#">Phí vận chuyển <span>{{ number_format($shippingFee, 0, ',', '.') }}
                                            VNĐ</span></a></li>
                                <li><a href="#">Tổng thanh toán <span>{{ number_format($grandTotal, 0, ',', '.') }}
                                            VNĐ</span></a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--================End Checkout Area =================-->

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


