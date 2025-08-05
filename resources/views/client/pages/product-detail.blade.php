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

                    <h2>
                        <span id="dynamic-price">
                            @if ($product->variants->count() > 0)
                                {{ number_format($product->variants->min('price'), 0, ',', '.') }}
                            @else
                                <span class="text-danger">Đang cập nhật</span>
                            @endif
                        </span> <span>VNĐ</span>
                    </h2>


                    <ul class="list">
                        <li><span>Danh mục</span>: {{ $product->category->name_category ?? 'Chưa phân loại' }}</li>
                    </ul>



                    <p>{{ $product->description }}</p>

                    <h6 id="dynamic-stock" class="text-muted">Số lượng: <span id="stock-quantity">{{$product->variants->sum('quantity')}}</span> sản phẩm</h6>

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
                                <button type="submit" class="primary-btn" id="add-to-cart-btn">Add to Cart</button>
                            </div>

                            <div id="cart-message" class="alert alert-danger d-none mt-3"></div>
                        </form>
<!-- <button class="primary-btn">abc abc </button> -->
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
                    <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Mô tả</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                        aria-controls="contact" aria-selected="false">Bình luận</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="review-tab" data-toggle="tab" href="#review" role="tab"
                        aria-controls="review" aria-selected="false">Đánh giá</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <p></p>
                    <p></p>
                </div>

                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="comment_list">
                                <div class="review_item">
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="img/product/review-1.png" alt="">
                                        </div>
                                        <div class="media-body">

                                        </div>
                                    </div>

                                </div>
                                <div class="review_item reply">
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="img/product/review-2.png" alt="">
                                        </div>
                                        <div class="media-body">

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="review_box">
                                <h4>Gửi Bình Luận</h4>
                                <form class="row contact_form" action="contact_process.php" method="post"
                                    id="contactForm" novalidate="novalidate">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Your Full name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Email Address">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="number" name="number"
                                                placeholder="Phone Number">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="message" id="message" rows="1" placeholder="Message"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="submit" value="submit" class="btn primary-btn">Submit Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row total_rate">
                                <div class="col-6">
                                    <div class="box_total">
                                        <h5>Overall</h5>
                                        <h4>4.0</h4>
                                        <h6>(03 Reviews)</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rating_list">
                                        <h3>Based on 3 Reviews</h3>
                                        <ul class="list">
                                            <li><a href="#">5 Star <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                            <li><a href="#">4 Star <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                            <li><a href="#">3 Star <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                            <li><a href="#">2 Star <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                            <li><a href="#">1 Star <i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i><i
                                                        class="fa fa-star"></i><i class="fa fa-star"></i> 01</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="review_list">
                                <div class="review_item">
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="img/product/review-1.png" alt="">
                                        </div>
                                        <div class="media-body">
                                            <h4>Blake Ruiz</h4>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                    </div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt ut labore et
                                        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                        laboris nisi ut aliquip ex ea
                                        commodo</p>
                                </div>


                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="review_box">
                                <h4>Gửi đánh giá</h4>
                                <p>Your Rating:</p>
                                <ul class="list">
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                </ul>
                                <p>Outstanding</p>
                                <form class="row contact_form" action="contact_process.php" method="post"
                                    id="contactForm" novalidate="novalidate">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Your Full name" onfocus="this.placeholder = ''"
                                                onblur="this.placeholder = 'Your Full name'">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Email Address" onfocus="this.placeholder = ''"
                                                onblur="this.placeholder = 'Email Address'">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="number" name="number"
                                                placeholder="Phone Number" onfocus="this.placeholder = ''"
                                                onblur="this.placeholder = 'Phone Number'">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="message" id="message" rows="1" placeholder="Review"
                                                onfocus="this.placeholder = ''" onblur="this.placeholder = 'Review'"></textarea></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="submit" value="submit" class="primary-btn">Submit Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    // 👉 Chọn màu
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

// ✅ Auto chọn size đầu tiên còn hàng
const firstSize = Array.from(sizeButtons).find(btn => btn.dataset.colorId === colorId && !btn.disabled);
if (firstSize) {
    console.log("First size found:", firstSize.dataset.variantId);
    firstSize.click();
}

        });
    });

    // 👉 Chọn size
sizeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        // Bỏ active khỏi tất cả nút size
        sizeButtons.forEach(b => b.classList.remove('active', 'btn-dark'));
        // Gắn active cho nút đang chọn
        btn.classList.add('active', 'btn-dark');

        // Cập nhật giá
        const price = parseFloat(btn.dataset.price);
        const formattedPrice = price.toLocaleString('vi-VN');
        priceDisplay.innerText = formattedPrice;

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

    // // 👉 Auto chọn màu đầu tiên
    // if (colorButtons.length > 0) {
    //     colorButtons[0].click();
    // }

    // 👉 Mua ngay
// 👉 Mua ngay
const buyNowForm = document.querySelector('form[action="{{ route('account.checkout.form') }}"]');
if (buyNowForm) {
    buyNowForm.addEventListener('submit', function (e) {
        const variantId = document.getElementById('add-cart-variant-id')?.value;
        const quantity = document.getElementById('sst')?.value;

        console.log('🔍 Submit Buy Now Form');
        console.log('Variant ID:', variantId);
        console.log('Quantity:', quantity);

        // ✅ Validate giống addToCart
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

        // ✅ Gán dữ liệu vào input ẩn để submit
        document.getElementById('selectedQty').value = quantity;
        document.getElementById('selectedVariant').value = variantId;
    });
}


});

// 👉 Thêm vào giỏ hàng
function addToCart(event) {
    event.preventDefault();

   const variantId = document.getElementById('add-cart-variant-id')?.value;

    const quantity = document.getElementById('sst')?.value;

    // 👉 Log dữ liệu để debug
    console.log("🟢 [addToCart] variant_id =", variantId || "abc");
    console.log("🟢 [addToCart] quantity =", quantity);

    if (!variantId) {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa chọn kích thước',
            text: 'Vui lòng chọn size trước khi thêm vào giỏ!'
        });
        return;
    }

    if (quantity < 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Số lượng không hợp lệ',
            text: 'Số lượng phải lớn hơn 0!'
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

    // 👉 Log toàn bộ formData
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
            console.error("❌ JSON parse error:", err);
            Swal.fire({ icon: 'error', title: 'Lỗi máy chủ', text: text });
            return;
        }

        if (!response.ok) {
            console.warn("❌ Response not OK:", response.status, data);
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
            });
            updateCartCount();
        } else {
            Swal.fire({ icon: 'error', title: 'Thất bại', text: data.message || 'Thêm vào giỏ hàng thất bại!' });
        }
    })
    .catch(error => {
        console.error('❌ Lỗi khi gửi yêu cầu:', error);
        Swal.fire({ icon: 'error', title: 'Lỗi không xác định', text: 'Vui lòng thử lại sau.' });
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Add to Cart';
    });
}


// 👉 Cập nhật số lượng giỏ hàng
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



</script>
@endpush

