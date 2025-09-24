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
        <!-- ===== HỘP VOUCHER (ngoại cảnh) ===== -->
<div class="smv-box">
  <div class="smv-box-left">
    <svg xmlns="http://www.w3.org/2000/svg" class="smv-box-icon" viewBox="0 0 24 24" fill="currentColor">
      <path d="M3 7a4 4 0 110 2 4 4 0 010-2zm18 0a4 4 0 110 2 4 4 0 010-2zM4 12h16v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8z"/>
    </svg>
    <span class="smv-box-title">ShoeMart Voucher</span>
  </div>

  <button id="smv-open-modal" class="smv-box-btn">Chọn ngay</button>
</div>
{{-- <div class="smv-box">
    <p id="coupon-message" class="mt-2 p-2 rounded text-sm font-medium"></p>
</div> --}}
<div class="coupon-box">
    <p id="coupon-message"
       class="coupon-msg items-center  gap-2 p-3 rounded-lg text-sm font-medium border shadow-sm transition-all duration-300">
    </p>
</div>

<style>
.coupon-msg {
  opacity: 0;
  transform: translateY(-10px);
  pointer-events: none;
  max-height: 0;
  overflow: hidden;
  transition: opacity 0.3s ease, transform 0.3s ease, max-height 0.3s ease;
}

.coupon-msg.show {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
  max-height: 200px;
}

.coupon-msg.success {
  background-color: #d1fae5;
  color: #065f46;
  border: 1px solid #10b981;
}

.coupon-msg.error {
  background-color: #fee2e2;
  color: #991b1b;
  border: 1px solid #ef4444;
}

</style>


<!-- ===== MODAL CHỌN VOUCHER ===== -->
<div id="smv-modal" class="smv-modal" aria-hidden="true">
  <div class="smv-modal-wrap" role="dialog" aria-modal="true" aria-labelledby="smv-modal-title">
    <div class="smv-modal-header">
      <h4 id="smv-modal-title">🎟 Chọn Voucher</h4>
      <button id="smv-close-modal" class="smv-close" aria-label="Đóng">&times;</button>
    </div>

    <!-- (Tuỳ: nếu không cần input, bỏ cả block này) -->
   <form id="voucherForm">
    <!-- Nhập mã tay -->
    <div class="smv-manual">
        <input id="smv-manual-input" name="coupon_code" class="smv-manual-input" placeholder="Nhập mã voucher (tuỳ chọn)" />
        <button type="button" id="smv-manual-apply" class="smv-manual-apply">Áp dụng</button>
    </div>

    <!-- Danh sách voucher -->
    <div class="smv-list" id="smv-list">
    @foreach ($userVouchers as $voucher)
        @php $d = $voucher->discount; @endphp
        @if ($d)
            @if ($d->type === '0')
                <div class="smv-item">
                    <div class="smv-item-left" style="background: linear-gradient(135deg,#f7971e,#ffd200);">
                        <div class="smv-item-left-text">{{ number_format($d->value) }}%</div>
                    </div>
                    <div class="smv-item-body">
                        <div class="smv-item-title">Giảm {{ number_format($d->value) }} %</div>
                        <div class="smv-item-sub">Đơn tối thiểu {{ number_format($d->min_order_value) }} đ</div>
                        <div class="smv-item-date">HSD: {{ \Carbon\Carbon::parse($d->end_date)->format('d/m/Y') }}</div>
                    </div>
                    <div class="smv-item-action">
                        <input type="radio" name="smv_selected" value="{{ $d->code }}" />
                    </div>
                </div>
            @elseif ($d->type === '1')
                <div class="smv-item">
                    <div class="smv-item-left" style="background: linear-gradient(135deg,#00c6ff,#0072ff);">
                        <div class="smv-item-left-text">{{ number_format($d->value) }} đ</div>
                    </div>
                    <div class="smv-item-body">
                        <div class="smv-item-title">Giảm {{ number_format($d->value) }} đ</div>
                        <div class="smv-item-sub">Đơn tối thiểu {{ number_format($d->min_order_value) }} đ</div>
                        <div class="smv-item-date">HSD: {{ \Carbon\Carbon::parse($d->end_date)->format('d/m/Y') }}</div>
                    </div>
                    <div class="smv-item-action">
                        <input type="radio" name="smv_selected" value="{{ $d->code }}" />
                    </div>
                </div>
            @else

            @endif

        @endif
    @endforeach
</div>


    <!-- Hidden fields cho Laravel -->
    <input type="hidden" id="variantId" name="variant_id" value="{{ $variant->id_variant }}">
    <input type="hidden" id="quantity" name="quantity" value="{{$quantity}}">
    <input type="hidden" id="discount_id" name="discount_id" value="">

    <div class="smv-footer">
        <button type="button" id="smv-cancel" class="smv-btn-cancel">Trở lại</button>
        <button type="submit" id="smv-ok" class="smv-btn-ok">OK</button>
    </div>
</form>
  </div>
</div>

<!-- ===== CSS (Đã đổi tên class, không trùng) ===== -->
<style>
/* Select2 Styles */
.select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #e6e6e6;
    border-radius: 8px;
    padding: 5px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
.select2-container--default .select2-results__option {
    padding: 8px 12px;
    font-size: 14px;
}
.select2-container--default .select2-results__option--highlighted {
    background-color: #ff4d4f;
    color: white;
}
/* Spinner styles */
.spinner {
    display: none;
    width: 20px;
    height: 20px;
    border: 2px solid #ff4d4f;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
}
.spinner.show {
    display: block;
}
@keyframes spin {
    0% { transform: translateY(-50%) rotate(0deg); }
    100% { transform: translateY(-50%) rotate(360deg); }
}
.select-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

/* --- HỘP NGOÀI --- */
.smv-box{
  display:flex;
  align-items:center;
  justify-content:space-between;
  background:#fff8f5;
  border:1.5px dashed #ee4d2d;
  border-radius:12px;
  padding:12px 16px;
  margin:10px 0;
  box-shadow:0 2px 6px rgba(0,0,0,0.03);
  font-family: Arial, sans-serif;
}
.smv-box-left{ display:flex; align-items:center; gap:10px; color:#ee4d2d; }
.smv-box-icon{ width:22px; height:22px; }
.smv-box-title{ font-size:15px; font-weight:500; color:#222; }
.smv-box-btn{
  background:#ee4d2d; border:none; color:#fff; padding:8px 14px; border-radius:8px; cursor:pointer; font-weight:600;
  transition:all .18s ease;
}
.smv-box-btn:hover{ background:#d74426; transform:translateY(-1px); }

/* --- MODAL (scoped) --- */
.smv-modal{ display:none; position:fixed; inset:0; background: rgba(0,0,0,0.45); z-index:2000; }
.smv-modal-wrap{
  width:560px; max-width:94%; margin:60px auto; background:#fff; border-radius:12px; overflow:hidden;
  box-shadow:0 12px 40px rgba(0,0,0,0.25); animation:smvFade .22s ease;
  display:flex; flex-direction:column;
}
@keyframes smvFade{ from{opacity:0; transform:translateY(-6px)} to{opacity:1; transform:none} }

.smv-modal-header{
  background:#fff6f4; padding:14px 16px; font-weight:700; display:flex; justify-content:space-between; align-items:center;
  border-bottom:1px solid #f0e0dd; font-size:15px;
}
.smv-close{ background:none; border:none; font-size:22px; color:#777; cursor:pointer; }
.smv-close:hover{ color:#000; }

/* manual input (optional) */
.smv-manual{ display:flex; gap:8px; padding:12px 16px; border-bottom:1px solid #f2f2f2; }
.smv-manual-input{ flex:1; padding:10px 12px; border:1px solid #e6e6e6; border-radius:8px; outline:none; font-size:14px; }
.smv-manual-apply{ background:#ff4d4f; color:#fff; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; font-weight:600; }

/* list */
.smv-list{ max-height:360px; overflow:auto; padding:8px 0; }
.smv-item{ display:flex; gap:12px; padding:12px 16px; align-items:center; border-bottom:1px dashed #eee; transition:background .12s; }
.smv-item:hover{ background:#fff9f0; }
.smv-item-left{ width:92px; min-width:92px; color:#fff; border-radius:8px; padding:12px 8px; display:flex; align-items:center; justify-content:center; font-weight:700; text-align:center; }
.smv-item-left-text{ font-size:13px; line-height:1; }
.smv-item-body{ flex:1; font-size:13px; color:#333; }
.smv-item-title{ font-weight:700; margin-bottom:4px; }
.smv-item-sub{ color:#777; font-size:13px; margin-bottom:6px; }
.smv-item-date{ color:#555; font-size:12px; margin-bottom:6px; }
.smv-item-cond{ color:#ff4d4f; font-size:12px; text-decoration:none; }
.smv-item-cond:hover{text-decoration:underline;}
.smv-item-action{ width:48px; text-align:center; }

/* footer */
.smv-footer{ display:flex; justify-content:flex-end; gap:10px; padding:12px 16px; border-top:1px solid #eee; background:#fafafa; }
.smv-btn-cancel{ background:#d0d0d0; border:none; padding:8px 16px; border-radius:8px; cursor:pointer; font-weight:600; }
.smv-btn-ok{ background:#ff4d4f; color:#fff; border:none; padding:8px 16px; border-radius:8px; cursor:pointer; font-weight:700; }
.smv-btn-ok:hover{ background:#e04143; }

/* responsive small screens */
@media (max-width:420px){
  .smv-modal-wrap{ margin:24px; }
  .smv-item-left{ display:none; } /* ẩn phần icon rộng trên màn nhỏ nếu cần */
}
</style>

<!-- ===== JS (đã dùng id/class mới) ===== -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('smv-modal');
  const openBtn = document.getElementById('smv-open-modal');
  const closeBtn = document.getElementById('smv-close-modal');
  const cancelBtn = document.getElementById('smv-cancel');
  const manualBtn = document.getElementById('smv-manual-apply');
  const manualInput = document.getElementById('smv-manual-input');
  const okBtn = document.getElementById('smv-ok');
  const couponMsgEl = document.getElementById('coupon-message') || createMessageEl();
  const discountEl = document.getElementById('discount-amount');
  const orderTotalEl = document.getElementById('order-total');

  const url = "{{ route('apply.coupon') }}"; // đảm bảo route tồn tại trên server
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? "{{ csrf_token() }}";
// function showCouponMsg() {
//     const msg = document.querySelector('.coupon-msg');
//     if (msg) {
//         msg.classList.add('show');

//         // Tự động ẩn sau 3 giây nếu muốn
//         setTimeout(() => {
//             msg.classList.remove('show');
//         }, 3000);
//     }
// }

function showCouponMessage(message, type = 'success') {
  const el = document.getElementById('coupon-message');
  if (!el) {
    console.error('Element with ID coupon-message not found.');
    return;
  }

  el.textContent = message;
  el.classList.remove('success', 'error');
  el.classList.add(type);
  el.classList.add('show');

  setTimeout(() => {
    el.classList.remove('show');
    setTimeout(() => {
      el.textContent = '';
    }, 300);
  }, 3000);
}







  function hideModal(){
    if(modal){ modal.style.display = 'none'; modal.setAttribute('aria-hidden','true'); }
  }

  openBtn?.addEventListener('click', ()=> {
    if(modal){ modal.style.display = 'block'; modal.setAttribute('aria-hidden','false'); }
  });
  closeBtn?.addEventListener('click', hideModal);
  cancelBtn?.addEventListener('click', hideModal);

  // Hàm gửi request và xử lý kết quả
  async function applyCoupon(code) {
  couponMsgEl.textContent = 'Đang áp dụng mã...';
  couponMsgEl.classList.remove('success', 'error');

  const variantEl = document.getElementById('variantId');
  const quantityEl = document.getElementById('quantity');
  if (!variantEl || !quantityEl) {
    console.error('variantId hoặc quantity không tìm thấy trong DOM.');
    showCouponMessage('Thiếu variantId hoặc quantity trên trang.', 'error');
    return;
  }

  const payload = {
    coupon_code: code,
    variant_id: variantEl.value,
    quantity: quantityEl.value
  };

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(payload)
    });

    if (!res.ok) {
      let body;
      try {
        body = await res.json();
      } catch {
        const text = await res.text();
        if (res.status === 419) {
          showCouponMessage('Phiên làm việc hết hạn (419). Vui lòng tải lại trang.', 'error');
        } else {
          showCouponMessage(text || `Lỗi server: ${res.status}`, 'error');
        }
        return;
      }

      if (res.status === 422 && body.errors) {
        const firstField = Object.keys(body.errors)[0];
        const firstMsg = body.errors[firstField][0];
        showCouponMessage(firstMsg, 'error');
      } else if (body.message) {
        showCouponMessage(body.message, 'error');
      } else {
        showCouponMessage(`Có lỗi khi áp mã (status ${res.status})`, 'error');
      }
      return;
    }

    const data = await res.json();
    if (data.success) {
      showCouponMessage(data.message || 'Áp dụng voucher thành công', 'success');
      if (orderTotalEl && data.final_total !== undefined) {
        orderTotalEl.textContent = new Intl.NumberFormat('vi-VN').format(data.final_total) + ' VNĐ';
      }
      if (discountEl && data.discount !== undefined) {
        discountEl.textContent = 'Bạn được giảm: ' + new Intl.NumberFormat('vi-VN').format(data.discount) + ' VNĐ';
      }
      hideModal();
    } else {
      showCouponMessage(data.message || 'Áp dụng không thành công', 'error');
    }
  } catch (err) {
    console.error('Lỗi fetch apply-coupon:', err);
    showCouponMessage('Có lỗi xảy ra, vui lòng thử lại!', 'error');
  }
}


  // Bấm "Áp dụng" — gửi ngay (ưu tiên input nhập tay, nếu rỗng lấy radio)
  manualBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    const codeFromInput = manualInput.value.trim();
    const selected = document.querySelector('input[name="smv_selected"]:checked');
    const code = codeFromInput || (selected ? selected.value : '');
    if (!code) {
      alert('Vui lòng nhập hoặc chọn mã voucher');
      return;
    }
    // nếu nhập tay, bỏ chọn radio
    if (codeFromInput) document.querySelectorAll('input[name="smv_selected"]').forEach(r => r.checked = false);
    applyCoupon(code);
  });

  // Bấm "OK" (nếu bạn vẫn muốn dùng nút OK để áp voucher đã chọn)
  okBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    const selected = document.querySelector('input[name="smv_selected"]:checked');
    if (!selected) {
      alert('Vui lòng chọn voucher trước khi bấm OK');
      return;
    }
    applyCoupon(selected.value);
  });

});
</script>
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
                    <label><b>Họ và tên người nhận hàng</b></label>
                    <input
                    type="text"
                    name="user_name"
                    class="form-control"
                    value="{{ auth()->user()->name }}"
                    required oninvalid="this.setCustomValidity('Vui lòng nhập họ tên')"
                    oninput="this.setCustomValidity('')">
                </div>

                <div class="form-group">
                    <label><b>Số điện thoại</b></label>
                    <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="{{ auth()->user()->phone_number }}"
                    required oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại')"
                    oninput="this.setCustomValidity('')">
                </div>

                <div class="form-group">
    <label><b>Email nhận đơn hàng</b></label>
    <input
        type="email"
        name="email"
        class="form-control"
        value="{{ old('email', auth()->user()->email) }}"
        required oninvalid="this.setCustomValidity('Vui lòng nhập email')"
        oninput="this.setCustomValidity('')">
</div>


                <!-- email nguoi nhan hang
                             <div class="mb-3">
    <label for="email">Email nhận đơn hàng:</label>
    <input type="email" name="email" class="form-control" required>
</div> -->

               <!-- Địa chỉ -->
<div class="col-md-12 form-group">
    <label><b>Tỉnh/Thành</b></label>
    <div class="select-wrapper">
        <select id="province" name="province" class="form-control select2" required oninvalid="this.setCustomValidity('Vui lòng chọn Tỉnh/Thành phố')" oninput="this.setCustomValidity('')">
            <option value="">-- Chọn Tỉnh/Thành phố --</option>
        </select>
        <span class="spinner" id="province-spinner"></span>
    </div>
</div>
<div class="col-md-12 form-group">
    <label><b>Xã/Phường</b></label>
    <div class="select-wrapper">
        <select id="ward" name="ward" class="form-control select2" required disabled oninvalid="this.setCustomValidity('Vui lòng chọn Xã/Phường')" oninput="this.setCustomValidity('')">
            <option value="">-- Chọn Xã/Phường --</option>
        </select>
        <span class="spinner" id="ward-spinner"></span>
    </div>
</div>

                <div class="form-group">
                    <label>Địa chỉ chi tiết</label>
                    <input type="text" name="address" class="form-control" placeholder="Ví dụ: Số 123, đường ABC..." required oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ chi tiết')" oninput="this.setCustomValidity('')">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="order_box">
                    <h2>Đơn hàng của bạn</h2>
                    <ul class="list">
                        <li>
                            <b>{{ $variant->product->name_product }} (Size: {{ $variant->size->name }}, Color: {{$variant->color->name_color}})</b>
                            <span class="middle">x {{ $quantity }}</span>
                            @php
                                if ($variant->adviceProduct) {
                                    $priceSale= $variant->price - ($variant->price*($variant->adviceProduct->value/100 ));
                                }else {
                                  $priceSale = $variant->price;
                                }
                            @endphp
                            <span class="last">{{ number_format($priceSale * $quantity, 0, ',', '.') }} VNĐ</span>
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
                                <span id="order-total">{{ number_format($priceSale * $quantity + $shippingFee, 0, ',', '.') }} VNĐ</span></a>
                        </li>
                    </ul>

    <div class="form-check my-3">
        <input class="form-check-input" type="checkbox" id="agreePolicy" name="terms" required oninvalid="this.setCustomValidity('Bạn cần đồng ý với chính sách mua hàng')" oninput="this.setCustomValidity('')">
        <label class="form-check-label" for="agreePolicy">
            Tôi đã đọc và đồng ý với 
            <a href="{{ route('polyc') }}" target="_blank">Chính sách mua hàng</a>
        </label>
    </div>

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

            //  Hiển thị số tiền giảm
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

    //  Gọi API lấy danh sách tỉnh + xã/phường
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

    //  Khi chọn tỉnh -> load xã/phường từ wards
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

// Thêm các thư viện cần thiết
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Khởi tạo Select2
    $('#province').select2({
        placeholder: "-- Chọn Tỉnh/Thành phố --",
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 1,
    });
    $('#ward').select2({
        placeholder: "-- Chọn Xã/Phường --",
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 1,
    });

    let provincesData = [];
    const provinceSpinner = document.getElementById('province-spinner');
    const wardSpinner = document.getElementById('ward-spinner');

    // Hiển thị/ẩn spinner
    const showSpinner = (spinner) => spinner?.classList.add('show');
    const hideSpinner = (spinner) => spinner?.classList.remove('show');

    // Fetch danh sách tỉnh
    showSpinner(provinceSpinner);
    fetch("https://vietnamlabs.com/api/vietnamprovince")
        .then(res => res.json())
        .then(response => {
            provincesData = response.data || [];
            provincesData.forEach(p => {
                const option = new Option(p.province, p.province, false, false);
                $('#province').append(option);
            });
            $('#province').trigger('change');
            hideSpinner(provinceSpinner);
        })
        .catch(err => {
            console.error("Lỗi load tỉnh:", err);
            hideSpinner(provinceSpinner);
        });

    // Khi chọn tỉnh -> load xã/phường
    $('#province').on('change', function() {
        const provinceName = this.value;
        $('#ward').html('<option value="">-- Chọn Xã/Phường --</option>').trigger('change');
        if (!provinceName) {
            $('#ward').prop('disabled', true);
            hideSpinner(wardSpinner);
            return;
        }
        showSpinner(wardSpinner);
        const selectedProvince = provincesData.find(p => p.province === provinceName);
        if (selectedProvince && selectedProvince.wards) {
            selectedProvince.wards.forEach(w => {
                const option = new Option(w.name, w.name, false, false);
                $('#ward').append(option);
            });
            $('#ward').trigger('change');
            $('#ward').prop('disabled', false);
            hideSpinner(wardSpinner);
        } else {
            hideSpinner(wardSpinner);
        }
    });
});
</script>
</script>
@endpush
