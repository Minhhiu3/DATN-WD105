@extends('layouts.client_home')
@section('title', 'Chi tiết sản phẩm')
@section('content')
    <!-- ================= Start Product Detail Area ================= -->
    <div class="product_image_area my-5">
        <div class="container">
            <div class="row">
                <!-- Cột trái: Ảnh sản phẩm -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="main-image mb-3">
                        <img id="main-image" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name_product }}"
                            class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; max-height: 400px;">
                    </div>

                    <!-- Danh sách ảnh nhỏ (album) -->
                    <div class="album-images d-flex flex-wrap gap-2">
                        @if ($product->albumProducts && $product->albumProducts->count())
                            @foreach ($product->albumProducts as $album)
                                <div class="album-thumb border rounded p-1"
                                    style="width: 100px; height: 100px; overflow: hidden;">
                                    <img src="{{ asset('storage/' . $album->image) }}"
                                        alt="{{ $product->name_product }} - album" class="img-fluid h-100 w-100"
                                        style="object-fit: cover;">
                                </div>
                            @endforeach
                        @else
                            <img src="{{ asset('assets/img/product/default.jpg') }}" alt="{{ $product->name_product }}"
                                class="img-fluid">
                        @endif
                    </div>
                </div>

                <!-- Cột phải: Thông tin sản phẩm -->
                <div class="col-lg-6">
                    <div class="s_product_text" style="margin-left: 20px; margin-top: 20px; {{ $product->variants->sum('quantity') == 0 ? 'pointer-events: none;' : '' }}">
                        <h3>{{ $product->name_product }}</h3>
                        <div style="display: flex">
                            @if ($product->variants->sum('quantity') == 0)
                                <div class="alert alert-danger mt-3 text-center" style="
                                  opacity: 0.9 !important;
                                  background: linear-gradient(135deg,rgb(234, 162, 162),rgb(210, 108, 108));
                                  font-weight: 600;
                                  font-size: 1.1rem;
                                ">
                                    Sản phẩm đã hết hàng
                                </div>
                            @else
                                <h2>
                                    <span id="dynamic-price-sale">
                                        @if ($product->variants->count() > 0)
                                            @php
                                                $price = $product->variants->min('price');
                                                $advice = optional($product->advice_product);
                                                if ($advice->status === 'on') {
                                                    $sale = ($advice->value / 100) * $price;
                                                    $sale_price = $price - $sale;
                                                } else {
                                                    $sale_price = $price;
                                                }
                                            @endphp
                                            {{ number_format($sale_price, 0, ',', '.') }}
                                        @else
                                            <span class="text-danger">Đang cập nhật</span>
                                        @endif
                                    </span>
                                    <span>VNĐ</span>
                                </h2>

                                @if (optional($product->advice_product)->status === 'off')
                                    <span id="dynamic-price"></span>
                                @endif
                                    @php
                                        $sale = optional($product->advice_product);
                                        $now = \Carbon\Carbon::now();
                                        $start = \Carbon\Carbon::parse($sale->start_date ?? 0)->startOfDay();
                                        $end = \Carbon\Carbon::parse($sale->end_date ?? 0)->endOfDay();
                                    @endphp
                                @if ( $sale && $sale->status === "on" && $now->between($start, $end))
                                    <h4 style="margin-left: 15px; margin-top: 3px;">
                                        <span id="dynamic-price" style="text-decoration: line-through;">
                                            @if ($product->variants->count() > 0)
                                                {{ number_format($product->variants->min('price'), 0, ',', '.') }} VNĐ
                                            @else
                                                <span class="text-danger">Đang cập nhật</span>
                                            @endif
                                        </span>
                                    </h4>

                                    <div style="
                                        display: inline-block;
                                        height: 35px;
                                        line-height: 35px;
                                        background: linear-gradient(135deg, #ff7e00, #ffb400);
                                        color: #fff;
                                        border-radius: 8px;
                                        font-weight: 700;
                                        font-size: 14px;
                                        white-space: nowrap;
                                        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
                                        padding: 0 12px;
                                        vertical-align: middle;
                                        margin-left: 20px;
                                    ">
                                        -{{ $sale->value }}%
                                    </div>
                                @endif
                            @endif
                        </div>

                        <ul class="list" style="{{ $product->variants->sum('quantity') == 0 ? 'opacity: 1; pointer-events: auto;' : '' }}">
                            <li><a href="{{ route('products', ['category' => $product->category->id_category]) }}"><span>Danh mục</span>: {{ $product->category->name_category ?? 'Chưa phân loại' }}</a></li>
                        </ul>
                        <ul class="list" style="{{ $product->variants->sum('quantity') == 0 ? 'opacity: 1; pointer-events: auto;' : '' }}">
                            <li><a href="{{ route('products', ['brand' => $product->brand->id_brand]) }}"><span>Thương hiệu</span>: {{ $product->brand->name ?? 'Chưa phân loại' }}</a></li>
                        </ul>

                        <p>{{ $product->description }}</p>

                        <h6 id="dynamic-stock" class="text-muted">Số lượng: <span id="stock-quantity">{{ $product->variants->sum('quantity') }}</span> sản phẩm</h6>

                        @guest
                            <a href="{{ route('login') }}" class="primary-btn">Đăng nhập để thêm vào giỏ</a>
                        @else
                            <form onsubmit="addToCart(event)" class="mt-3" id="add-to-cart-form">
                                @csrf

                                <!-- Màu sắc -->
                                @foreach ($product->variants->groupBy('color_id') as $colorId => $variants)
                                    @php
                                        $color = $variants->first()->color ?? null;
                                        $totalQty = $variants->sum('quantity');
                                    @endphp
                                    @if ($color && $totalQty > 0)
                                        <button type="button" class="btn btn-outline-dark color-btn mr-2 mb-2"
                                            data-color-id="{{ $colorId }}"
                                            data-image="{{ asset('storage/' . $color->image) }}"
                                            data-quantity="{{ $totalQty }}"
                                            {{ $product->variants->sum('quantity') == 0 ? 'disabled' : '' }}>
                                            {{ $color->name_color }}
                                        </button>
                                    @endif
                                @endforeach

                                <!-- Kích thước -->
                                <div class="form-group mb-3">
                                    <label>Kích thước:</label>
                                    <div class="d-flex flex-wrap" id="size-options">
                                        @foreach ($product->variants as $variant)
                                            <button type="button"
                                                class="btn btn-outline-dark size-btn mr-2 mb-2 {{ $variant->quantity == 0 ? 'disabled' : '' }}"
                                                data-variant-id="{{ $variant->id_variant }}"
                                                data-color-id="{{ $variant->color_id }}"
                                                data-price="{{ $variant->price }}"
                                                data-quantity="{{ $variant->quantity }}"
                                                {{ $product->variants->sum('quantity') == 0 ? 'disabled' : '' }}>
                                                {{ $variant->size->name ?? 'N/A' }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Số lượng -->
                                <div class="product_count mb-3">
                                    <label for="sst">Số lượng:</label>
                                    <div class="d-flex align-items-center" style="gap: 12px;">
                                        <button class="qty-btn" type="button" id="decrease-btn" {{ $product->variants->sum('quantity') == 0 ? 'disabled' : '' }}>-</button>
                                        <input type="text" name="quantity" id="sst" min="1" value="1"
                                            class="qty-input" readonly {{ $product->variants->sum('quantity') == 0 ? 'disabled' : '' }}>
                                        <button class="qty-btn" type="button" id="increase-btn" {{ $product->variants->sum('quantity') == 0 ? 'disabled' : '' }}>+</button>
                                    </div>
                                </div>

                                <!-- Nút Thêm vào giỏ -->
                                <div class="card_area d-flex align-items-center gap-3">
                                    <input type="hidden" name="variant_id" id="add-cart-variant-id" value="">
                                    <input type="hidden" name="quantity" id="add-cart-quantity">
                                    <button type="submit" class="primary-btn {{ $product->variants->sum('quantity') == 0 ? 'out-of-stock-btn' : '' }}" id="add-to-cart-btn" style="{{ $product->variants->sum('quantity') == 0 ? 'opacity: 0.5;' : '' }}">Thêm vào giỏ hàng</button>
                                </div>

                                <div id="cart-message" class="alert alert-danger d-none mt-3"></div>
                            </form>

                            <!-- Nút Mua ngay -->
                            <form action="{{ route('account.checkout.form') }}" method="GET" class="mt-3"
                                id="buy-now-form">
                                @csrf
                                <input type="hidden" name="variant_id" id="selectedVariant">
                                <input type="hidden" name="quantity" id="selectedQty" value="1">
                                <div class="card_area d-flex align-items-center gap-3">
                                    <button type="submit" class="primary-btn {{ $product->variants->sum('quantity') == 0 ? 'out-of-stock-btn' : '' }}" style="{{ $product->variants->sum('quantity') == 0 ? 'opacity: 0.5;' : '' }}">Mua ngay</button>
                                </div>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ================= End Product Detail Area ================= -->

    <!--================Product Description Area =================-->
    <section class="product_description_area">
        <div class="container">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="review-tab" data-toggle="tab" href="#review" role="tab"
                        aria-controls="review" aria-selected="true">Đánh giá</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Tab đánh giá -->
                <div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
                    <div class="row">
                        <!-- Hiển thị đánh giá -->
                        <div class="col-lg-6">
                            <h4>Đánh giá từ người mua</h4>
                            <div class="average-rating mb-3">
                                <strong>Đánh giá trung bình:</strong>
                                @php
                                    $fullStars = floor($averageRating);
                                    $halfStar = $averageRating - $fullStars >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp

                                {{-- Sao đầy --}}
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="fa fa-star" style="color: gold;"></i>
                                @endfor

                                {{-- Nửa sao --}}
                                @if ($halfStar)
                                    <i class="fa fa-star-half-alt" style="color: gold;"></i>
                                @endif

                                {{-- Sao trống --}}
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="fa fa-star" style="color: #ccc;"></i>
                                @endfor

                                <span>({{ number_format($averageRating, 1) }}/5.0)</span>
                            </div>

                            @forelse ($product->productReviews->where('status', 'visible') as $review)
                                <div class="review_item mb-4">
                                    <div class="media">
                                        <div class="d-flex align-items-center mr-3">
                                            <img src="{{ asset('assets/img/deafault-avt.png') }}" width="50px"
                                                alt="User">
                                        </div>
                                        <div class="media-body">
                                            <h5>{{ $review->user->name ?? 'Ẩn danh' }}</h5>
                                            <div>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                            <p>{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                            @endforelse
                        </div>

                        <!-- Gửi đánh giá -->
                        <div class="col-lg-6">
                            <h4>Gửi đánh giá</h4>

                            @if ($canReview)
                                <form action="{{ route('product.reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->variants->sum('quantity') == 0 ? '' : $product->id_product }}">
                                    <input type="hidden" name="order_id" value="{{ $orderId }}">
                                    <input type="hidden" name="rating" id="selectedRating" value="0">
                                    <div class="form-group">
                                        <div class="star-rating" id="starRating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star" data-value="{{ $i }}" {{ $product->variants->sum('quantity') == 0 ? 'style="pointer-events: none;"' : '' }}></i>
                                            @endfor
                                        </div>
                                        {{-- <input type="text" name="rating" id="selectedRating" value="0" > --}}
                                    </div>

                                    <div class="form-group">
                                        <label for="comment">Nội dung đánh giá</label>
                                        <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Viết cảm nhận của bạn..." {{ $product->variants->sum('quantity') == 0 ? 'disabled' : '' }}></textarea>
                                    </div>

                                    <button type="submit" class="btn primary-btn" {{ $product->variants->sum('quantity') == 0 ? 'disabled' : '' }}>Gửi đánh giá</button>
                                </form>
                            @elseif($alreadyReviewed)
                                <p class="text-success">Bạn đã đánh giá sản phẩm này rồi.</p>
                            @else
                                <p class="text-warning">Bạn chỉ có thể đánh giá sau khi đơn hàng hoàn thành.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $variantMap = $product->variants->mapWithKeys(function ($v) {
            return [
                $v->id_variant => [
                    'price' => $v->price,
                    'quantity' => $v->quantity,
                    'color_id' => $v->color_id,
                    'size_id' => $v->size_id,
                ],
            ];
        });
    @endphp
@endsection

@push('styles')
    <style>
        .star-rating {
            display: flex;
            flex-direction: row;
            gap: 5px;
        }

        .star-rating i {
            font-size: 2rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating i.active {
            color: gold;
        }

        .fade-image {
            transition: opacity 0.3s ease, transform 0.3s ease;
            opacity: 1;
        }

        .fade-out {
            opacity: 0;
            transform: scale(0.97);
        }

        .qty-input:focus {
            outline: none;
            box-shadow: none;
        }

        .out-of-stock-btn:hover {
            cursor: not-allowed;
            position: relative;
            background-color: #bbbccc;
        }

        .out-of-stock-btn:hover::after {
            content: 'Hết hàng';
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($product->variants->sum('quantity') > 0)
                updateCartCount();
                const selectedVariantInput = document.getElementById('selectedVariant');
                const variantIdInput = document.getElementById('add-cart-variant-id');
                const selectedQtyInput = document.getElementById('selectedQty');
                const quantityInput = document.getElementById('add-cart-quantity');
                const colorButtons = document.querySelectorAll('.color-btn');
                const sizeButtons = document.querySelectorAll('.size-btn');
                sizeButtons.forEach(btn => btn.style.display = 'none');
                const mainImage = document.getElementById('main-image');
                const priceDisplay = document.getElementById('dynamic-price');
                const priceSaleDisplay = document.getElementById('dynamic-price-sale');
                const stockDisplay = document.getElementById('dynamic-stock');
                const input = document.getElementById('sst');
                const hiddenVariantInput = document.getElementById('variant_id');
                const btnMinus = document.getElementById('decrease-btn');
                const btnPlus = document.getElementById('increase-btn');
                const addToCartBtn = document.getElementById('add-to-cart-btn');
                const variants = @json($variantMap);

                function formatPrice(number) {
                    return new Intl.NumberFormat('vi-VN').format(number) + ' VNĐ';
                }

                function getMaxQuantity() {
                    const selectedBtn = document.querySelector('.size-btn.btn-dark');
                    return selectedBtn ? parseInt(selectedBtn.dataset.quantity) : Infinity;
                }

                function resetSelections() {
                    sizeButtons.forEach(btn => {
                        btn.classList.remove('active', 'btn-dark');
                    });
                    hiddenVariantInput.value = '';
                    selectedVariantInput.value = '';
                    priceDisplay.innerText = 'Vui lòng chọn kích thước';
                    stockDisplay.innerText = '';
                    input.value = 1;
                    addToCartBtn.disabled = true;
                }

                colorButtons.forEach(colorBtn => {
                    colorBtn.addEventListener('click', () => {
                        const colorId = colorBtn.dataset.colorId;
                        const imageUrl = colorBtn.dataset.image;
                        const colorQuantity = colorBtn.dataset.quantity || 0;

                        colorButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                        colorBtn.classList.add('active', 'btn-primary');

                        if (mainImage && imageUrl) {
                            mainImage.classList.add('fade-out');
                            setTimeout(() => {
                                mainImage.src = imageUrl;
                                mainImage.classList.remove('fade-out');
                            }, 200);
                        }

                        stockDisplay.innerText = `Số lượng : ${colorQuantity} sản phẩm`;
                        stockDisplay.classList.remove('text-danger');
                        stockDisplay.classList.add('text-muted');

                        sizeButtons.forEach(btn => {
                            if (btn.dataset.colorId === colorId) {
                                btn.style.display = 'inline-block';
                            } else {
                                btn.style.display = 'none';
                                btn.classList.remove('active', 'btn-dark');
                            }
                        });

                        resetSelections();

                        const firstSize = Array.from(sizeButtons).find(btn => btn.dataset.colorId === colorId && !btn.disabled);
                        if (firstSize) {
                            firstSize.click();
                        }
                    });
                });

                sizeButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        sizeButtons.forEach(b => b.classList.remove('active', 'btn-dark'));
                        btn.classList.add('active', 'btn-dark');
                        const adviceStatus = @json($product->advice_product->status ?? null);
                        const adviceValue = @json($product->advice_product->value ?? null);

                        const price = parseFloat(btn.dataset.price);
                        if (adviceValue && adviceValue > 0 && adviceStatus === "on") {
                            let finalPrice = price - (price * (adviceValue / 100));
                            finalPrice = Math.round(finalPrice);
                            priceSaleDisplay.innerText = finalPrice.toLocaleString('vi-VN');
                            priceDisplay.innerText = price.toLocaleString('vi-VN');
                            priceDisplay.style.textDecoration = "line-through";
                        } else {
                            priceSaleDisplay.innerText = price.toLocaleString('vi-VN');
                            priceDisplay.innerText = "";
                            priceDisplay.style.textDecoration = "none";
                        }

                        const qty = parseInt(btn.dataset.quantity);
                        stockDisplay.innerText = `Số lượng còn lại: ${qty} sản phẩm`;
                        stockDisplay.classList.remove('text-danger');
                        stockDisplay.classList.add('text-muted');

                        selectedVariantInput.value = btn.dataset.variantId;
                        variantIdInput.value = btn.dataset.variantId;
                    });
                });

                btnMinus.addEventListener('click', function() {
                    let val = parseInt(input.value) || 1;
                    if (val > 1) input.value = val - 1;
                });

                btnPlus.addEventListener('click', function() {
                    let val = parseInt(input.value) || 1;
                    const maxQty = getMaxQuantity();
                    if (val < maxQty) input.value = val + 1;
                });

                input.addEventListener('input', function() {
                    let val = parseInt(input.value);
                    const maxQty = getMaxQuantity();
                    if (isNaN(val) || val < 1) input.value = 1;
                    else if (val > maxQty) input.value = maxQty;
                });

                const buyNowForm = document.querySelector('form[action="{{ route('account.checkout.form') }}"]');
                if (buyNowForm) {
                    buyNowForm.addEventListener('submit', function(e) {
                        const selectedSizeBtn = document.querySelector('.size-btn.btn-dark');
                        const variantId = selectedSizeBtn ? selectedSizeBtn.dataset.variantId : '';
                        const quantity = document.getElementById('sst')?.value;

                        if (!variantId) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Chưa chọn kích thước',
                                text: 'Vui lòng chọn size trước khi mua ngay.'
                            });
                            return;
                        }

                        if (quantity < 1) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Số lượng không hợp lệ',
                                text: 'Số lượng phải lớn hơn 0!'
                            });
                            return;
                        }

                        document.getElementById('selectedQty').value = quantity;
                        document.getElementById('selectedVariant').value = variantId;
                    });
                }
            @endif

            // document.addEventListener("DOMContentLoaded", function() {
                const stars = document.querySelectorAll("#starRating i");
                const ratingInput = document.getElementById("selectedRating");

                stars.forEach((star) => {
                    star.addEventListener("click", function() {
                        @if ($product->variants->sum('quantity') > 0)
                            const rating = this.getAttribute("data-value");
                            ratingInput.value = rating;

                            stars.forEach(s => s.classList.remove("active"));
                            stars.forEach(s => {
                                if (s.getAttribute("data-value") <= rating) {
                                    s.classList.add("active");
                                }
                            });
                        @endif
                    });
                });
            // });
        });

        function addToCart(event) {
            event.preventDefault();
            const variantId = document.getElementById('add-cart-variant-id')?.value;
            const quantity = parseInt(document.getElementById('sst')?.value);
            const variants = @json($variantMap);
            const maxQty = variants[variantId]?.quantity ?? 0;

            if (!variantId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Chưa chọn kích thước',
                    text: 'Vui lòng chọn size trước khi thêm vào giỏ!'
                });
                return;
            }
            if (quantity < 1 || quantity > maxQty) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Số lượng không hợp lệ',
                    text: `Chỉ còn ${maxQty} sản phẩm trong kho!`
                });
                return;
            }

            const btn = document.getElementById('add-to-cart-btn');
            btn.disabled = true;
            btn.textContent = 'Đang thêm...';

            const formData = new FormData();
            formData.append('variant_id', variantId);
            formData.append('quantity', quantity);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(async response => {
                    const text = await response.text();
                    let data = {};
                    try {
                        data = JSON.parse(text);
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi máy chủ',
                            text: text
                        });
                        return;
                    }

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            const messages = Object.values(data.errors).flat().join(', ');
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi nhập liệu',
                                text: messages
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: data.message || 'Có lỗi xảy ra khi thêm vào giỏ!'
                            });
                        }
                        return;
                    }

                    if (data.require_login) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Cần đăng nhập',
                            text: data.message || 'Vui lòng đăng nhập để tiếp tục.',
                            confirmButtonText: 'Đăng nhập ngay'
                        }).then(() => {
                            window.location.href = '/login';
                        });
                        return;
                    }

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: data.message || 'Đã thêm vào giỏ hàng!',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                        updateCartCount();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại',
                            text: data.message || 'Thêm vào giỏ hàng thất bại!'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi không xác định',
                        text: 'Vui lòng thử lại sau.'
                    });
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = 'THÊM VÀO GIỎ HÀNG';
                });
        }

        function updateCartCount() {
            const cartCountEl = document.getElementById('cart-count');
            if (cartCountEl) {
                fetch('{{ route('cart.count') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.count > 0) {
                            cartCountEl.style.display = 'inline-block';
                            cartCountEl.innerText = data.count;
                        } else {
                            cartCountEl.style.display = 'none';
                        }
                    })
                    .catch(err => {
                        console.error('Lỗi cập nhật giỏ hàng:', err);
                    });
            }
        }

        function onVariantChange(variantId) {
            const variants = @json($variantMap);
            const maxQty = variants[variantId]?.quantity ?? 1;
            const qtyInput = document.getElementById('sst');
            qtyInput.max = maxQty;
            if (parseInt(qtyInput.value) > maxQty || parseInt(qtyInput.value) < 1) {
                qtyInput.value = 1;
            }
        }
    </script>
@endpush
