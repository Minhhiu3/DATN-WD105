@extends('layouts.client_home')
@section('title', 'Chi tiết sản phẩmphẩm')
<!-- @push('styles')
<style>
    .size-btn, .color-btn {
        min-width: 50px;
        padding: 6px 12px;
    }
</style>

@endpush -->
@section('content')
   <!-- ================= Start Product Detail Area ================= -->
<div class="product_image_area my-5 ">
    <div class="container">
        <div class="row">
            <!-- Cột trái: Ảnh sản phẩm -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="main-image mb-3">
                    <img id="main-image"
                         src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name_product }}"
                         class="img-fluid rounded shadow-sm w-100"
                         style="object-fit: cover; max-height: 400px;">
                </div>

                <!-- Danh sách ảnh nhỏ (album) -->
                <div class="album-images d-flex flex-wrap gap-2">
                    @if ($product->albumProducts && $product->albumProducts->count())
                        @foreach ($product->albumProducts as $album)
                            <div class="album-thumb border rounded p-1"
                                 style="width: 100px; height: 100px; overflow: hidden;">
                                <img src="{{ asset('storage/' . $album->image) }}"
                                     alt="{{ $product->name_product }} - album"
                                     class="img-fluid h-100 w-100"
                                     style="object-fit: cover;">
                            </div>
                        @endforeach
                    @else
                        <img src="{{ asset('assets/img/product/default.jpg') }}"
                             alt="{{ $product->name_product }}" class="img-fluid">
                    @endif
                </div>
            </div>

            <!-- Cột phải: Thông tin sản phẩm -->
            <div class="col-lg-6 " >
                <div class="s_product_text" style="margin-left: 20px; margin-top: 20px;">
                    <h3>{{ $product->name_product }}</h3>
                    <div style="display: flex">
                    <h2> 
                        {{-- @php
                            if($product->advice_product->status == "on"){
                                $dynamic_price-sale
                            }
                        @endphp --}}
                        <span id="dynamic-price-sale">
                            @if ($product->variants->count() > 0)
                                @php
                                   $price = $product->variants->min('price');
                                   if($product->advice_product->status == "on"){
                                    $sale = ($product->advice_product->value/100)*$product->variants->min('price');
                                    $sale_price =  $price - $sale;
                                   }else {
                                     $sale_price =  $price;
                                   }
                                   
                                @endphp
                                {{ number_format(($sale_price), 0, ',', '.') }}
                            @else
                                <span class="text-danger">Đang cập nhật</span>
                            @endif
                        </span> <span>VNĐ</span>
                    </h2>
                    @if ($product->advice_product->status == "off")
                        <span id="dynamic-price"></span>
                    @endif
                    @if ($product->advice_product->status === "on")
                        <h4 style="margin-left: 15px; margin-top: 3px;">
                        <span id="dynamic-price" style="text-decoration: line-through;">
                            @if ($product->variants->count() > 0)
                                {{ number_format(($product->variants->min('price')), 0, ',', '.') }} VNĐ
                            @else
                                <span class="text-danger">Đang cập nhật</span>
                            @endif
                        </span>
                    </h4>
                            @php 
                                $sale = $product->advice_product;
                            $now = \Carbon\Carbon::now();
                                $start = \Carbon\Carbon::parse($sale->start_date ?? 0)->startOfDay();
                            $end = \Carbon\Carbon::parse($sale->end_date ?? 0)->endOfDay();
                            @endphp
                            {{-- Ô vuông % giảm giá tông vàng-cam --}}
                            <div style="
                                display: inline-block;
                                height: 35px;
                                line-height: 35px; /* canh giữa dọc bằng chính chiều cao */
                                background: linear-gradient(135deg, #ff7e00, #ffb400);
                                color: #fff;
                                border-radius: 8px;
                                font-weight: 700;
                                font-size: 14px;
                                white-space: nowrap;
                                box-shadow: 0 3px 6px rgba(0,0,0,0.15);
                                padding: 0 12px;   /* có thể để 0 nếu bạn thực sự không muốn padding */
                                vertical-align: middle; 
                                margin-left: 20px;
                                ">

                                -{{$product->advice_product->value}}%
                            </div>
                    
                    @else
                        
                    @endif
                   


                </div>
                    
                    
                    <ul class="list">
                        <li><a href="{{ route('products', ['category' => $product->category->id_category]) }}"><span>Danh mục</span>: {{ $product->category->name_category ?? 'Chưa phân loại' }}</a></li>
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
                        <button type="button"
                                class="btn btn-outline-dark color-btn mr-2 mb-2"
                                data-color-id="{{ $colorId }}"
                                data-image="{{ asset('storage/' . $color->image) }}"
                                data-quantity="{{ $totalQty }}">
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
                                    {{ $variant->quantity == 0 ? 'disabled' : '' }}>
                                {{ $variant->size->name ?? 'N/A' }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Số lượng -->
                <div class="product_count mb-3">
                    <label for="sst">Số lượng:</label>
                    <div class="d-flex align-items-center" style="gap: 12px;">
                        <button class="qty-btn" type="button" id="decrease-btn">-</button>
                        <input type="text" name="quantity" id="sst" min="1" value="1" class="qty-input" readonly>
                        <button class="qty-btn" type="button" id="increase-btn">+</button>
                    </div>
                </div>

                <!-- Nút Thêm vào giỏ -->
                <div class="card_area d-flex align-items-center gap-3">
                    <input type="hidden" name="variant_id" id="add-cart-variant-id" value="">
                    <input type="hidden" name="quantity" id="add-cart-quantity">
                    <button type="submit" class="primary-btn" id="add-to-cart-btn">Thêm vào giỏ hàng</button>
                </div>

                <div id="cart-message" class="alert alert-danger d-none mt-3"></div>
            </form>

            <!-- Nút Mua ngay -->
            <form action="{{ route('account.checkout.form') }}" method="GET" class="mt-3" id="buy-now-form">
                @csrf
                <input type="hidden" name="variant_id" id="selectedVariant">
                <input type="hidden" name="quantity" id="selectedQty" value="1">
                <div class="card_area d-flex align-items-center gap-3">
                    <button type="submit" class="primary-btn">Mua ngay</button>
                </div>
            </form>
        @endguest
    </div>
</div>
        </div>
    </div>
</div>
<!-- ================= End Product Detail Area ================= -->

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
        $halfStar = ($averageRating - $fullStars) >= 0.5;
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

    <span>({{ number_format($averageRating, 1) }}/5)</span>
</div>

                        @forelse ($product->productReviews->where('status', 'visible') as $review)
                            <div class="review_item mb-4">
                                <div class="media">
                                    <div class="d-flex align-items-center mr-3">
                                        <img src="{{ asset('assets/img/deafault-avt.png') }}" width="50px" alt="User">
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

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        @if($canReview)
                            <form action="{{ route('product.reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                                <input type="hidden" name="order_id" value="{{ $orderId }}">
                                  <input type="hidden" name="rating" id="selectedRating" value="0">
<div class="form-group">

        <div class="star-rating" id="starRating">
            @for ($i = 1; $i <= 5; $i++)
                <i class="fa fa-star" data-value="{{ $i }}"></i>
            @endfor
        </div>
    </div>

                                <div class="form-group">
                                    <label for="comment">Nội dung đánh giá</label>
                                    <textarea name="comment" id="comment" class="form-control" rows="3"
                                              placeholder="Viết cảm nhận của bạn..."></textarea>
                                </div>

                                <button type="submit" class="btn primary-btn">Gửi đánh giá</button>
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
    $variantMap = $product->variants->mapWithKeys(function($v) {
        return [
            $v->id_variant => [
                'price' => $v->price,
                'quantity' => $v->quantity,
                'color_id' => $v->color_id,
                'size_id' => $v->size_id,
            ]
        ];
    });
@endphp

    <!-- </section>
    {{-- <script>
        document.querySelector('form[action="{{ route('account.checkout.form') }}"]').addEventListener('submit', function (e) {
        const qty = document.getElementById('sst').value;
        document.getElementById('selectedQty').value = qty;

        const variantId = document.getElementById('variant_id').value;

        document.getElementById('selectedVariant').value = variantId;
    });
    </script> --}} -->

    <!--================End Product Description Area =================-->
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
        box-shadow: none; /* bỏ viền khi click vào input */
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    updateCartCount();
    const selectedVariantInput = document.getElementById('selectedVariant');        // form Mua ngay
const variantIdInput = document.getElementById('add-cart-variant-id');          // form Add to cart
const selectedQtyInput = document.getElementById('selectedQty');                // form Mua ngay
const quantityInput = document.getElementById('add-cart-quantity');             // form Add to cart

    const colorButtons = document.querySelectorAll('.color-btn');
    const sizeButtons = document.querySelectorAll('.size-btn');
    // Ẩn toàn bộ size khi trang load
sizeButtons.forEach(btn => btn.style.display = 'none');

    const mainImage = document.getElementById('main-image');
    const priceDisplay = document.getElementById('dynamic-price');
    const priceSaleDisplay = document.getElementById('dynamic-price-sale');
    const stockDisplay = document.getElementById('dynamic-stock');
    const input = document.getElementById('sst');
    const hiddenVariantInput = document.getElementById('variant_id');
    // const selectedVariantInput = document.getElementById('selectedVariant');
    const btnMinus = document.getElementById('decrease-btn');
    const btnPlus = document.getElementById('increase-btn');
    const addToCartBtn = document.getElementById('add-to-cart-btn');

    const variants = @json($variantMap); // { id_variant: { price, quantity, color_id } }
    // const variantIdInput = hiddenVariantInput;
// console.log("abv ",document.body.innerHTML)
// console.log({
//     colorButtons,
//     sizeButtons,
//     mainImage,
//     priceDisplay,
//     stockDisplay,
//     input,
//     hiddenVariantInput,
//     selectedVariantInput,
//     btnMinus,
//     btnPlus,
//     addToCartBtn
// });
// console.log("mainImage:", document.getElementById("main-image"));
// console.log("color variants:", selectedVariantInput);

// console.log("variant_id:", document.getElementById("variant_id"));





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

    //  Chọn màu
    colorButtons.forEach(colorBtn => {
        colorBtn.addEventListener('click', () => {

            const colorId = colorBtn.dataset.colorId;
            const imageUrl = colorBtn.dataset.image;
            const colorQuantity = colorBtn.dataset.quantity || 0;
            console.log("colorid:", colorId, "imageUrl:", imageUrl, "colorQuantity:", colorQuantity, "size id :", colorBtn.dataset.sizeId);

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

            // Lọc size
            sizeButtons.forEach(btn => {
                if (btn.dataset.colorId === colorId) {
                    btn.style.display = 'inline-block';
                } else {
                    btn.style.display = 'none';
                    btn.classList.remove('active', 'btn-dark');
                }
            });

            resetSelections();

            // Lọc size
sizeButtons.forEach(btn => {
    if (btn.dataset.colorId === colorId) {
        btn.style.display = 'inline-block';
    } else {
        btn.style.display = 'none';
        btn.classList.remove('active', 'btn-dark');
    }
});

// ⚠️ Reset trước khi chọn size
resetSelections();

//  Auto chọn size đầu tiên còn hàng
const firstSize = Array.from(sizeButtons).find(btn => btn.dataset.colorId === colorId && !btn.disabled);
if (firstSize) {
    console.log("First size found:", firstSize.dataset.variantId);
    firstSize.click();
}

        });
    });

    //  Chọn size
sizeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        // Bỏ active khỏi tất cả nút size
        sizeButtons.forEach(b => b.classList.remove('active', 'btn-dark'));
        // Gắn active cho nút đang chọn
        btn.classList.add('active', 'btn-dark');
        const adviceStatus= @json($product->advice_product->status ?? null);
        const adviceValue= @json($product->advice_product->value ?? null);

        // Cập nhật giá
        const price = parseFloat(btn.dataset.price);
        
           if (adviceValue && adviceValue > 0 && adviceStatus === "on") {
            let finalPrice = price - (price * (adviceValue / 100));
            finalPrice = Math.round(finalPrice);
            priceSaleDisplay.innerText = finalPrice.toLocaleString('vi-VN');
            priceDisplay.innerText = price.toLocaleString('vi-VN') + " VNĐ"; // gạch ngang
            priceDisplay.style.textDecoration = "line-through";
        } else {
            // Không giảm giá → hiển thị giá bình thường
            priceSaleDisplay.innerText = price.toLocaleString('vi-VN') + " VNĐ";
            priceDisplay.innerText = "";
            priceDisplay.style.textDecoration = "none";
        }
        // const formattedPrice = price.toLocaleString('vi-VN') + " VNĐ";
        // const roundedPrice = Math.round(finalPrice); 
        // const formattedPriceSale = roundedPrice.toLocaleString('vi-VN');

        // priceDisplay.innerText = formattedPrice;
        // priceSaleDisplay.innerText = formattedPriceSale;

        // Cập nhật tồn kho
        const qty = parseInt(btn.dataset.quantity);
        stockDisplay.innerText = `Số lượng còn lại: ${qty} sản phẩm`;
        stockDisplay.classList.remove('text-danger');
        stockDisplay.classList.add('text-muted');

        // Lưu biến thể đã chọn
        selectedVariantInput.value = btn.dataset.variantId;
        variantIdInput.value = btn.dataset.variantId;
    });
});


    // Tăng giảm số lượng
    btnMinus.addEventListener('click', function () {
        let val = parseInt(input.value) || 1;
        if (val > 1) input.value = val - 1;
    });

    btnPlus.addEventListener('click', function () {
        let val = parseInt(input.value) || 1;
        const maxQty = getMaxQuantity();
        if (val < maxQty) input.value = val + 1;
    });

    input.addEventListener('input', function () {
        let val = parseInt(input.value);
        const maxQty = getMaxQuantity();

        if (isNaN(val) || val < 1) input.value = 1;
        else if (val > maxQty) input.value = maxQty;
    });

    // //  Auto chọn màu đầu tiên
    // if (colorButtons.length > 0) {
    //     colorButtons[0].click();
    // }

    //  Mua ngay
const buyNowForm = document.querySelector('form[action="{{ route('account.checkout.form') }}"]');
if (buyNowForm) {
    buyNowForm.addEventListener('submit', function (e) {
        // Lấy variantId và quantity từ nút size đang chọn và input số lượng
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

        // Gán dữ liệu vào input ẩn để submit
        document.getElementById('selectedQty').value = quantity;
        document.getElementById('selectedVariant').value = variantId;
    });
}


});

//  Thêm vào giỏ hàng
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

    //  Log toàn bộ formData
    for (let [key, value] of formData.entries()) {
        console.log(`📦 FormData: ${key} = ${value}`);
    }

    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();

        console.log("📨 Response text:", text); // log phản hồi từ server

        let data = {};
        try {
            data = JSON.parse(text);
        } catch (err) {
            console.error(" JSON parse error:", err);
            Swal.fire({ icon: 'error', title: 'Lỗi máy chủ', text: text });
            return;
        }

        if (!response.ok) {
            console.warn(" Response not OK:", response.status, data);
            if (response.status === 422 && data.errors) {
                const messages = Object.values(data.errors).flat().join(', ');
                Swal.fire({ icon: 'error', title: 'Lỗi nhập liệu', text: messages });
            } else {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: data.message || 'Có lỗi xảy ra khi thêm vào giỏ!' });
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
            Swal.fire({ icon: 'error', title: 'Thất bại', text: data.message || 'Thêm vào giỏ hàng thất bại!' });
        }
    })
    .catch(error => {
        console.error(' Lỗi khi gửi yêu cầu:', error);
        Swal.fire({ icon: 'error', title: 'Lỗi không xác định', text: 'Vui lòng thử lại sau.' });
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'THÊM VÀO GIỎ HÀNG';
    });
}


//  Cập nhật số lượng giỏ hàng
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


 document.addEventListener("DOMContentLoaded", function () {
        const stars = document.querySelectorAll("#starRating i");
        const ratingInput = document.getElementById("selectedRating");

        stars.forEach((star) => {
            star.addEventListener("click", function () {
                const rating = this.getAttribute("data-value");
                ratingInput.value = rating;

                // Xóa class active khỏi tất cả sao
                stars.forEach(s => s.classList.remove("active"));

                // Thêm lại class active cho các sao <= rating
                stars.forEach(s => {
                    if (s.getAttribute("data-value") <= rating) {
                        s.classList.add("active");
                    }
                });
            });
        });
    });

// Khi chọn biến thể mới
function onVariantChange(variantId) {
    const variants = @json($variantMap); // $variantMap là mảng variant_id => {quantity: ...}
    const maxQty = variants[variantId]?.quantity ?? 1;
    const qtyInput = document.getElementById('sst');
    qtyInput.max = maxQty;
    if (parseInt(qtyInput.value) > maxQty || parseInt(qtyInput.value) < 1) {
        qtyInput.value = 1;
    }
}
</script>
@endpush

