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
            <input type="text" placeholder="Nhập mã giảm giá">
            <a class="tp_btn" href="#">OK</a>
        </div>

        <form action="{{ route('account.placeOrder') }}" method="POST" class="row contact_form">
            @csrf
            <input type="hidden" name="variant_id" value="{{ $variant->id_variant }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">

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
                    <input type="text" name="province" class="form-control" placeholder="Nhập tỉnh/thành" required>
                </div>

                <div class="form-group">
                    <label>Quận/Huyện</label>
                    <input type="text" name="district" class="form-control" placeholder="Nhập quận/huyện" required>
                </div>

                <div class="form-group">
                    <label>Xã/Phường</label>
                    <input type="text" name="ward" class="form-control" placeholder="Nhập xã/phường" required>
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
                            <b>{{ $variant->product->name_product }} (Size {{ $variant->size->name }})</b>
                            <span class="middle">x {{ $quantity }}</span>
                            <span class="last">{{ number_format($variant->price * $quantity, 0, ',', '.') }} VNĐ</span>
                        </li>
                    </ul>
                    <ul class="list list_2">
                        <li><a href="#">Phí vận chuyển <span>0 VND</span></a></li>
                        <li><a href="#">Tổng tiền
                                <span>{{ number_format($variant->price * $quantity, 0, ',', '.') }} VNĐ</span></a>
                        </li>
                    </ul>

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

                    <button type="submit" class="primary-btn w-100 mt-3">Đặt Hàng</button>
                </div>
            </div>
        </form>
    </div>
</section>
<!--================End Checkout Area =================-->


<!--     
  <script>
document.addEventListener("DOMContentLoaded", function () {
    const provinceSelect = document.getElementById('provinceSelect');
    const districtSelect = document.getElementById('districtSelect');
    const wardSelect = document.getElementById('wardSelect'); // Thêm nếu bạn có dropdown phường/xã

    // Load danh sách tỉnh/thành
    async function loadProvinces() {
        try {
            provinceSelect.innerHTML = '<option value="">Đang tải...</option>';
            const res = await fetch('/api/vl/provinces');
            const result = await res.json();

            if (result.success && Array.isArray(result.data)) {
                provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành</option>';
                result.data.forEach(province => {
                    const opt = document.createElement("option");
                    opt.value = province.id; // Hoặc province.code nếu API dùng code
                    opt.textContent = province.province;
                    provinceSelect.appendChild(opt);
                });
            } else {
                throw new Error("Dữ liệu tỉnh không hợp lệ");
            }
        } catch (error) {
            console.error("Lỗi khi tải tỉnh/thành:", error);
            provinceSelect.innerHTML = '<option value="">Lỗi khi tải</option>';
        }
    }

    // Load danh sách quận/huyện theo tỉnh
    async function loadDistricts(provinceId) {
        try {
            districtSelect.innerHTML = '<option value="">Đang tải...</option>';
            const res = await fetch(`/api/vl/districts/${provinceId}`);
            const result = await res.json();

            if (result.success && Array.isArray(result.data)) {
                districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                result.data.forEach(district => {
                    const opt = document.createElement("option");
                    opt.value = district.id; // Hoặc district.code
                    opt.textContent = district.district;
                    districtSelect.appendChild(opt);
                });
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>'; // Reset nếu dùng
            } else {
                throw new Error("Dữ liệu quận/huyện không hợp lệ");
            }
        } catch (error) {
            console.error("Lỗi khi tải quận/huyện:", error);
            districtSelect.innerHTML = '<option value="">Lỗi khi tải</option>';
        }
    }

    // Load danh sách phường/xã theo quận
    async function loadWards(districtId) {
        try {
            wardSelect.innerHTML = '<option value="">Đang tải...</option>';
            const res = await fetch(`/api/vl/wards/${districtId}`);
            const result = await res.json();

            if (result.success && Array.isArray(result.data)) {
                wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                result.data.forEach(ward => {
                    const opt = document.createElement("option");
                    opt.value = ward.id; // Hoặc ward.code
                    opt.textContent = ward.commune;
                    wardSelect.appendChild(opt);
                });
            } else {
                throw new Error("Dữ liệu phường/xã không hợp lệ");
            }
        } catch (error) {
            console.error("Lỗi khi tải phường/xã:", error);
            wardSelect.innerHTML = '<option value="">Lỗi khi tải</option>';
        }
    }

    // Event khi chọn tỉnh
    provinceSelect.addEventListener('change', function () {
        const provinceId = this.value;
        if (provinceId) {
            loadDistricts(provinceId);
        } else {
            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        }
    });

    // Event khi chọn quận
    districtSelect.addEventListener('change', function () {
        const districtId = this.value;
        if (districtId) {
            loadWards(districtId);
        } else {
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        }
    });

    // Gọi hàm khởi tạo
    loadProvinces();
});
</script> -->


    <!--================End Checkout Area =================-->
@endsection
