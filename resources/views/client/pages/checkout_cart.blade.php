@extends('layouts.client_home')
@section('title', 'Checkout Cart')
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
            <!-- Debug: In $cartItemz để kiểm tra -->
            <!-- <div class="alert alert-info">
                <p><strong>Debug cartItemz:</strong> {{ count($selectedVariants) }} sản phẩm</p>
                
                @foreach ($cartItemz as $item)
                    <p>Variant ID: {{ $item->variant_id }} - {{ $item->variant->product->name_product }}</p>
                @endforeach
            </div> -->

            <div class="cupon_area">
                <div class="check_title">
                    <h2>Nhập mã giảm giá của bạn <a href="#">Các sự kiện nhận mã giảm giá</a></h2>
                </div>
                <form id="apply-coupon-form">
                    @csrf
                    <input type="text" id="coupon_code" name="coupon_code" placeholder="Nhập mã giảm giá">
                    <input type="hidden" id="selected_variants" name="selected_variants" value="{{ json_encode($selectedVariants ?? []) }}">
                    <button type="submit" class="tp_btn">OK</button>
                </form>
                <p id="coupon-message" class="mt-2 text-success"></p>
            </div>
            <div class="billing_details">
                <div class="row">
                    <div class="col-lg-6">
                        <h3>Chi tiết thanh toán</h3>
                        <form class="row contact_form" action="{{ route('account.placeOrder.cart') }}" method="POST">
                            @csrf
                            <input type="hidden" name="selected_variants" value="{{ json_encode($selectedVariants ?? []) }}">
                            <!-- Thông tin người dùng -->
                            <div class="form-group">
                                <label><b>Họ và tên</b></label>
                                <input 
                                    type="text" 
                                    name="user_name"
                                    class="form-control" 
                                    value="{{ auth()->user()->name }}" 
                                    required>
                            </div>

                            <div class="form-group">
                                <label><b>Số điện thoại</b></label>
                                <input 
                                    type="text"
                                    name="phone" 
                                    class="form-control" 
                                    value="{{ auth()->user()->phone_number }}"
                                    required>
                            </div>

                            <!-- Email nhận đơn hàng -->
                            <div class="form-group">
                                <label><b>Email nhận đơn hàng</b></label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    required>
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
                            @if ($cartItemz->isEmpty())
                                <p class="text-danger">Không có sản phẩm nào được chọn để thanh toán.</p>
                            @else
                                @foreach ($cartItemz as $item)
                                    <input type="hidden" name="items[{{ $item->variant_id }}][variant_id]"
                                        value="{{ $item->variant_id }}">
                                    <input type="hidden" name="items[{{ $item->variant_id }}][quantity]"
                                        value="{{ $item->quantity }}">
                                @endforeach
                            @endif

                            <div class="col-md-12 text-right mt-3 d-flex justify-content-between">
                                <button type="submit" name="payment_method" value="cod" class="btn primary-btn">
                                    ĐẶT HÀNG <br><small>(Trả tiền khi nhận hàng)</small>
                                </button>

                                <button type="submit" name="payment_method" value="vnpay" class="btn primary-btn">
                                    THANH TOÁN ONLINE <br><small>(Thông qua VNPay)</small>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-6">
                        <div class="order_box">
                            <h2>Đơn hàng của bạn</h2>
                            <!-- Đơn hàng của bạn -->
                            <ul class="list">
                                @if ($cartItemz->isEmpty())
                                    <li><p>Không có sản phẩm được chọn để thanh toán.</p></li>
                                @else
                                    @foreach ($cartItemz as $item)
                                        @php
                                            $variant = $item->variant;
                                            $product = $variant->product;
                                            $size = $variant->size;
                                            $color = $variant->color;
                                        @endphp
                                        <li>
                                            <b>{{ $product->name_product }} (Size
                                                {{ $size->name ?? 'N/A' }}, Color: {{ $color->name_color ?? 'N/A' }}) [Mã sản phẩm: {{ $item->variant_id }}]</b>
                                            <span class="middle">x {{ $item->quantity }}</span>
                                            <span
                                                class="last">{{ number_format($variant->price * $item->quantity, 0, ',', '.') }}
                                                VNĐ</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                            @php
                                $subTotal = $cartItemz->sum(fn($item) => $item->variant->price * $item->quantity);
                                $shippingFee = 30000;
                                $discount = session('discount');
                                $discountAmount = $discount['amount'] ?? 0;
                                $finalTotal = $discount ? max(0, $discount['final_total']) : $subTotal;
                                $grandTotal = $finalTotal + $shippingFee;
                            @endphp

                            <ul class="list list_2">
                                <li><a href="#">Tạm tính <span>{{ number_format($subTotal, 0, ',', '.') }}
                                            VNĐ</span></a></li>
                                <li><a href="#">Phí vận chuyển <span>{{ number_format($shippingFee, 0, ',', '.') }}
                                            VNĐ</span></a></li>
                                <li><a href="#">Tiền giảm giá <span id="discount-amount">{{ number_format($discountAmount, 0, ',', '.') }}
                                            VNĐ</span></a></li>
                                <li><a href="#">Tổng thanh toán <span id="order-total">{{ number_format($grandTotal, 0, ',', '.') }}
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
    const wardSelect = document.getElementById("ward");

    let provincesData = []; // Lưu dữ liệu API để dùng sau

    // Gọi API lấy danh sách tỉnh + xã/phường
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

    // Khi chọn tỉnh -> load xã/phường từ wards
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

    // Xử lý áp mã giảm giá
    document.getElementById('apply-coupon-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const couponCode = document.getElementById('coupon_code').value;
        const selectedVariants = document.getElementById('selected_variants').value;
        const messageEl = document.getElementById('coupon-message');

        fetch("{{ route('apply.couponCart') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                coupon_code: couponCode,
                selected_variants: selectedVariants
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

                const discountEl = document.getElementById('discount-amount');
                if (discountEl && data.discount !== undefined) {
                    discountEl.textContent = new Intl.NumberFormat('vi-VN').format(data.discount) + ' VNĐ';
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

    // Debug: Kiểm tra có AJAX call nào cập nhật danh sách sản phẩm không
    // console.log('Checking for AJAX calls to update cart items...');
});
</script>
@endpush