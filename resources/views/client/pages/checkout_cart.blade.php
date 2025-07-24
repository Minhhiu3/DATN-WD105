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
                    <input type="text" class="form-control" value="{{ auth()->user()->phone_number }}" disabled>
                </div>

                <div class="form-group">
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
                            <label><input type="radio" name="payment_method" value="cod" checked> Thanh toán khi nhận hàng</label><br>
                            <label><input type="radio" name="payment_method" value="vnpay"> Thanh toán qua VNPay</label>
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
            $subTotal = $cartItems->sum(fn($item) => $item->variant->price * $item->quantity);
            $shippingFee = 30000;
            $grandTotal = $subTotal + $shippingFee;
        @endphp

        <ul class="list list_2">
            <li><a href="#">Tạm tính <span>{{ number_format($subTotal, 0, ',', '.') }} VNĐ</span></a></li>
            <li><a href="#">Phí vận chuyển <span>{{ number_format($shippingFee, 0, ',', '.') }} VNĐ</span></a></li>
            <li><a href="#">Tổng thanh toán <span>{{ number_format($grandTotal, 0, ',', '.') }} VNĐ</span></a></li>
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
<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById("province");
    const districtSelect = document.getElementById("district");
    const wardSelect = document.getElementById("ward");

    console.log(" Script đã chạy");

    // 1. Gọi API lấy danh sách tỉnh/thành từ Laravel
       // 1. Gọi API lấy danh sách tỉnh/thành từ Laravel
    fetch("/api/vl/provinces")
        .then(res => {
            console.log(' Đã gọi API /provinces, status:', res.status);
            return res.json();
        })
        .then(response => {
            console.log(' Dữ liệu tỉnh:', response);

            const provinces = response?.data || [];

            provinces.forEach(p => {
                const option = new Option(p.province, p.province);
                option.dataset.code = p.id;
                provinceSelect.add(option);
            });
        })
        .catch(err => console.error(" Lỗi khi load tỉnhh:", err));

    // 2. Khi chọn tỉnh → gọi API lấy quận/huyện
    provinceSelect.addEventListener("change", function () {
        const selected = this.selectedOptions[0];
        const provinceCode = selected?.dataset.code;
        if (!provinceCode) return;

        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        districtSelect.disabled = true;
        wardSelect.disabled = true;

        fetch(`/api/vl/districts/${provinceCode}`)
            .then(res => res.json())
            .then(response => {
                console.log(' Dữ liệu quận/huyện:', response);
                const districts = response?.data || [];

                districts.forEach(d => {
                    const option = new Option(d.name, d.name);
                    option.dataset.code = d.idDistrict;
                    districtSelect.add(option);
                });

                districtSelect.disabled = false;
            })
            .catch(err => console.error(" Lỗi khi load quận/huyện:", err));
    });

    // 3. Khi chọn quận/huyện → gọi API lấy phường/xã
    districtSelect.addEventListener("change", function () {
        const selected = this.selectedOptions[0];
        const districtCode = selected?.dataset.code;
        if (!districtCode) return;

        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        wardSelect.disabled = true;

        fetch(`/api/vl/wards/${districtCode}`)
            .then(res => res.json())
            .then(response => {
                console.log(' Dữ liệu phường/xã:', response);
                const wards = response?.data || [];

                wards.forEach(w => {
                    const option = new Option(w.name, w.name);
                    wardSelect.add(option);
                });

                wardSelect.disabled = false;
            })
            .catch(err => console.error(" Lỗi khi load phường/xã:", err));
    });
});
</script> -->


@endpush
