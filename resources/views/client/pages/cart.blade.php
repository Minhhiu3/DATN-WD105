@extends('layouts.client_home')
@section('title','Giỏ hàng')
@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Giỏ hàng</h1>
                    <nav class="d-flex align-items-center">
                        <a href="{{ route('home') }}">Trang thôi<span class="lnr lnr-arrow-right"></span></a>
                        <a href="{{ route('cart') }}">Giỏ hàng</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                @if($cartItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col"><input type="checkbox" id="select-all" checked> Chọn tất cả</th>
                                    <th scope="col">Sản phẩm</th>
                                    <th scope="col">Giá</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Tổng</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    @php
                                        $variant = $item->variant ?? $item['variant'] ?? null;
                                        $product = $variant?->product ?? null;
                                        $color = $variant?->color ?? null;
                                        $size = $variant?->size ?? null;
                                        $quantity = $item->quantity ?? $item['quantity'] ?? 1;
                                        $price = $variant->price ?? 0;
                                        $total = $price * $quantity;
                                        $img = $color->image ?? 'default.jpg';
                                    @endphp

                                    @if ($variant && $product)
                                        <tr data-variant-id="{{ $variant->id_variant }}">
                                            <td>
                                                <input type="checkbox" class="select-item" checked
                                                       data-variant-id="{{ $variant->id_variant }}"
                                                       onchange="updateCartTotals()">
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <div class="d-flex">
                                                        <img src="{{ asset('storage/' . $img) }}"
                                                             alt="{{ $product->name_product }}"
                                                             style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>
                                                    <div class="media-body">
                                                        <h4>{{ $product->name_product }}</h4>
                                                        <p>Size: {{ $size->name ?? 'Không xác định' }}</p>
                                                        <p>Màu: {{ $color->name_color ?? 'Không xác định' }}</p>
                                                        <small class="text-muted">Còn lại: {{ $variant->quantity }} sản phẩm</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><h5 class="item-price">{{ number_format($price, 0, ',', '.') }} VNĐ</h5></td>
                                            <td>
                                                <div class="product_count">
                                                    <input type="number" name="qty" value="{{ $quantity }}" class="input-text qty"
                                                           min="1" max="{{ $variant->quantity }}"
                                                           data-variant-id="{{ $variant->id_variant }}"
                                                           data-price="{{ $price }}"
                                                           onchange="updateQuantity({{ $variant->id_variant }}, this.value, {{ $variant->quantity }})">
                                                 
                                                </div>
                                                <div class="quantity-error text-danger" style="display: none; font-size: 12px;"></div>
                                            </td>
                                            <td><h5 class="item-total">{{ number_format($total, 0, ',', '.') }} VNĐ</h5></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm" onclick="removeFromCart({{ $variant->id_variant }})">
                                                    <i class="fa fa-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    @else
                                        <tr class="text-danger">
                                            <td colspan="6">
                                                Sản phẩm này không còn tồn tại hoặc đã bị xóa.
                                                <button class="btn btn-sm btn-danger"
                                                        onclick="removeFromCart({{ $item->variant_id ?? $item['variant_id'] ?? 0 }})">
                                                    Xóa khỏi giỏ hàng
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-lg-4">
                            <div class="card_area">
                                <div class="cart-summary">
                                    <h4>Tổng cộng giỏ hàng</h4>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tổng tiền hàng:</span>
                                        <span id="subtotal">
                                            {{ number_format($cartItems->sum(function($item) {
                                                $variant = $item->variant ?? $item['variant'] ?? null;
                                                $product = $variant?->product ?? null;
                                                if (!$variant || !$product) return 0;
                                                $quantity = $item->quantity ?? $item['quantity'];
                                                return $variant->price * $quantity;
                                            }), 0, ',', '.') }} VNĐ
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Phí vận chuyển:</span>
                                        <span id="shipping">30.000 VNĐ</span>
                                    </div>
                                    <hr>
                                    @php
                                        $totalAmount = $cartItems->sum(function($item) {
                                            $variant = $item->variant ?? $item['variant'] ?? null;
                                            $product = $variant?->product ?? null;
                                            if (!$variant || !$product) return 0;
                                            $quantity = $item->quantity ?? $item['quantity'];
                                            return $variant->price * $quantity;
                                        });
                                        $shippingFee = 30000;
                                        $finalTotal = $totalAmount + $shippingFee;
                                    @endphp
                                    <div class="d-flex justify-content-between mb-3">
                                        <strong>Tổng thanh toán:</strong>
                                        <strong id="total">{{ number_format($finalTotal, 0, ',', '.') }} VNĐ</strong>
                                    </div>
                                </div>
                                <div class="checkout_btn_inner d-flex align-items-center">
                                    <a class="primary-btn" href="{{ route('products') }}">Tiếp tục mua</a>
                                    <form id="checkout-form" action="{{ route('account.checkout.cart') }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <input type="hidden" name="selected_variants" id="selected-variants">
                                        <button type="submit" class="btn primary-btn">Thanh toán</button>
                                    </form>
                                </div>
                                <div class="text-center mt-3">
                                    <button class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                                        <i class="fa fa-trash"></i> Xóa tất cả
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h3>Giỏ hàng trống</h3>
                        <p>Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                        <a href="{{ route('products') }}" class="primary-btn">Mua sắm ngay</a>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->
@endsection

@push('scripts')
<script>
function changeQuantity(variantId, change, maxQuantity) {
    const input = document.querySelector(`input[data-variant-id="${variantId}"]`);
    let newQuantity = parseInt(input.value) + change;

    if (newQuantity < 1) newQuantity = 1;
    if (newQuantity > maxQuantity) {
        newQuantity = maxQuantity;
        showQuantityError(variantId, `Chỉ còn ${maxQuantity} sản phẩm trong kho`);
    } else {
        hideQuantityError(variantId);
    }

    input.value = newQuantity;
    updateQuantity(variantId, newQuantity, maxQuantity);
}

function updateQuantity(variantId, quantity, maxQuantity) {
    // Validate quantity
    if (quantity < 1) {
        showQuantityError(variantId, 'Số lượng phải lớn hơn 0');
        return;
    }

    if (quantity > maxQuantity) {
        showQuantityError(variantId, `Chỉ còn ${maxQuantity} sản phẩm trong kho`);
        return;
    }

    hideQuantityError(variantId);

    // Show loading state
    const input = document.querySelector(`input[data-variant-id="${variantId}"]`);
    input.disabled = true;

    fetch('{{ route("cart.update") }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            variant_id: variantId,
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the item total
            updateItemTotal(variantId, quantity);
            // Update cart totals
            updateCartTotals();
        } else {
            alert(data.message || 'Có lỗi xảy ra khi cập nhật số lượng!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật số lượng!');
    })
    .finally(() => {
        input.disabled = false;
    });
}

function updateItemTotal(variantId, quantity) {
    const row = document.querySelector(`tr[data-variant-id="${variantId}"]`);
    const priceElement = row.querySelector('.item-price');
    const totalElement = row.querySelector('.item-total');

    const price = parseFloat(priceElement.textContent.replace(/[^\d]/g, ''));
    const total = price * quantity;

    totalElement.textContent = total.toLocaleString('vi-VN') + ' VNĐ';
}

function updateCartTotals() {
    let subtotal = 0;
    const shippingFee = 30000;
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const checkbox = row.querySelector('.select-item');
        if (checkbox && checkbox.checked) {
            const totalElement = row.querySelector('.item-total');
            const total = parseFloat(totalElement.textContent.replace(/[^\d]/g, ''));
            subtotal += total;
        }
    });

    const finalTotal = subtotal + shippingFee;
    document.getElementById('subtotal').textContent = subtotal.toLocaleString('vi-VN') + ' VNĐ';
    document.getElementById('total').textContent = finalTotal.toLocaleString('vi-VN') + ' VNĐ';
}

function showQuantityError(variantId, message) {
    const row = document.querySelector(`tr[data-variant-id="${variantId}"]`);
    const errorDiv = row.querySelector('.quantity-error');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

function hideQuantityError(variantId) {
    const row = document.querySelector(`tr[data-variant-id="${variantId}"]`);
    const errorDiv = row.querySelector('.quantity-error');
    errorDiv.style.display = 'none';
}

function removeFromCart(variantId) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        const row = document.querySelector(`tr[data-variant-id="${variantId}"]`);
        row.style.opacity = '0.5';

        fetch('{{ route("cart.remove") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                variant_id: variantId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.remove();
                updateCartTotals();

                // Check if cart is empty
                const remainingItems = document.querySelectorAll('tbody tr');
                if (remainingItems.length === 0) {
                    location.reload(); // Reload to show empty cart message
                }
            } else {
                row.style.opacity = '1';
                alert(data.message || 'Có lỗi xảy ra khi xóa sản phẩm!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            row.style.opacity = '1';
            alert('Có lỗi xảy ra khi xóa sản phẩm!');
        });
    }
}

function clearCart() {
    if (confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi giỏ hàng?')) {
        fetch('{{ route("cart.clear") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to show empty cart message
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa giỏ hàng!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa giỏ hàng!');
        });
    }
}

// Xử lý checkbox "Chọn tất cả"
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.select-item');
    const quantityInputs = document.querySelectorAll('.qty');
    const checkoutForm = document.getElementById('checkout-form');

    // Xử lý sự kiện checkbox "Chọn tất cả"
    selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateCartTotals();
    });

    // Xử lý sự kiện thay đổi checkbox từng sản phẩm
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Nếu bỏ chọn một checkbox, bỏ chọn "Chọn tất cả"
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            }
            // Nếu tất cả checkbox được chọn, bật "Chọn tất cả"
            const allChecked = Array.from(itemCheckboxes).every(checkbox => checkbox.checked);
            if (allChecked) {
                selectAllCheckbox.checked = true;
            }
            updateCartTotals();
        });
    });

    // Xử lý sự kiện submit form thanh toán
    checkoutForm.addEventListener('submit', function(e) {
        const selectedVariants = [];
        const checkboxes = document.querySelectorAll('.select-item:checked');

        if (checkboxes.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
            return;
        }

        checkboxes.forEach(checkbox => {
            selectedVariants.push(checkbox.dataset.variantId);
        });

        document.getElementById('selected-variants').value = JSON.stringify(selectedVariants);
    });

    // Xử lý input số lượng khi blur
    quantityInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const variantId = this.dataset.variantId;
            const quantity = parseInt(this.value);
            const maxQuantity = parseInt(this.max);

            if (quantity < 1) {
                this.value = 1;
                updateQuantity(variantId, 1, maxQuantity);
            } else if (quantity > maxQuantity) {
                this.value = maxQuantity;
                updateQuantity(variantId, maxQuantity, maxQuantity);
            }
        });
    });

    // Cập nhật tổng tiền khi tải trang
    updateCartTotals();
});
</script>
@endpush

@push('styles')
<style>
.cart-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.quantity-error {
    margin-top: 5px;
    font-size: 12px;
    color: #dc3545;
}

.product_count {
    display: flex;
    align-items: center;
    max-width: 120px;
}

.product_count input {
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    width: 60px;
    margin: 0 5px;
}

.product_count input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.product_count button {
    border: 1px solid #ddd;
    background: #fff;
    padding: 5px 8px;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.product_count button:hover {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.product_count button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.table th {
    border-top: none;
    font-weight: 600;
    background: #f8f9fa;
    padding: 15px 10px;
}

.table td {
    padding: 15px 10px;
    vertical-align: middle;
}

.media img {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-danger {
    transition: all 0.3s ease;
    border-radius: 6px;
}

.btn-danger:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(220,53,69,0.3);
}

.btn-outline-danger {
    transition: all 0.3s ease;
    border-radius: 6px;
}

.btn-outline-danger:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(220,53,69,0.3);
}

.primary-btn, .gray_btn {
    border-radius: 6px;
    transition: all 0.3s ease;
}

.primary-btn:hover, .gray_btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.checkout_btn_inner {
    gap: 10px;
}

.checkout_btn_inner a {
    flex: 1;
    text-align: center;
}

/* Responsive design */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 14px;
    }

    .media img {
        width: 60px !important;
        height: 60px !important;
    }

    .product_count {
        max-width: 100px;
    }

    .product_count input {
        width: 50px;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }

    .cart-summary {
        padding: 15px;
    }
}

/* Loading animation */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Success/Error messages */
.alert {
    border-radius: 6px;
    margin-bottom: 15px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}
</style>
@endpush
