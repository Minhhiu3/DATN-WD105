@extends('layouts.client_home')
@section('title', 'Chi tiết sản phẩmphẩm')

@section('content')
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Product Details Page</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="#">Shop<span class="lnr lnr-arrow-right"></span></a>
                        <a href="single-product.html">product-details</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================detail Product Area =================-->
<div class="product_image_area">
    <div class="container">
        <div class="row s_product_inner">
            <!-- Ảnh sản phẩm -->
            <div class="col-lg-6">
                <div class="main-image mb-3">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name_product }}"
                        class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; max-height: 400px;">
                </div>
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

            <!-- Thông tin chi tiết -->
            <div class="col-lg-5 offset-lg-1">
                <div class="s_product_text">
                    <h3>{{ $product->name_product }}</h3>
                    <h2>{{ number_format($product->price, 0, ',', '.') }} VNĐ</h2>
                    <ul class="list">
                        <li>
                            <span>Danh mục</span> :
                            {{ $product->category->name_category ?? 'Chưa phân loại' }}
                        </li>
                        <li>
                            <span>Tình trạng</span> :
                            @if ($product->variants->sum('quantity') > 0)
                                Còn hàng ({{ $product->variants->sum('quantity') }} sản phẩm)
                            @else
                                Hết hàng
                            @endif
                        </li>
                    </ul>
                    <p>{{ $product->description }}</p>

                    {{-- Size --}}
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                        @csrf

                        <div class="form-group d-flex align-items-center mb-3">
                            <label for="size" class="mr-2 mb-0">Size:</label>
                            <select name="variant_id" id="size" class="form-control w-auto" required>
                                <option value="">-- Chọn Size --</option>
                                @foreach ($product->variants as $variant)
                                    <option value="{{ $variant->id_variant }}"
                                        data-quantity="{{ $variant->quantity }}"
                                        {{ $variant->quantity == 0 ? 'disabled' : '' }}>
                                        Size {{ $variant->size->name ?? 'Không xác định' }}
                                        {{ $variant->quantity > 0 ? "- Còn $variant->quantity" : '(Hết hàng)' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Số lượng --}}
                        <div class="product_count mb-3">
                            <label for="qty">Số lượng:</label>
                            <input type="number" name="quantity" id="sst" min="1" value="1"
                                class="input-text qty form-control d-inline-block w-auto">
                        </div>

                        {{-- Nút thêm vào giỏ hàng + mua ngay --}}
                        <div class="card_area d-flex align-items-center gap-3">
                            <button type="submit" id="add-to-cart-btn" onclick="addToCart()" class="primary-btn">Add to Cart</button>
                        </div>
                        <div id="cart-message" class="alert alert-danger d-none mt-3"></div>

                    </form>

                    {{-- Mua ngay --}}
                    <form action="{{ route('account.checkout.form') }}" method="GET" class="mt-2">
                        @csrf
                        <input type="hidden" name="variant_id" id="selectedVariant"
                            value="{{ $product->variants->first()->id_variant ?? '' }}">
                        <input type="hidden" name="quantity" id="selectedQty" value="1">
                        <button type="submit" class="primary-btn">Mua ngay</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

    <!--================End detail Product Area =================-->

    <!--================Product Description Area =================-->
    <section class="product_description_area">
        <div class="container">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                        aria-selected="true">Mô tả</a>
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
                                            {{-- <h4>Blake Ruiz</h4>
                                            <h5>12th Feb, 2018 at 05:56 pm</h5>
                                            <a class="reply_btn" href="#">Reply</a> --}}
                                        </div>
                                    </div>
                                    {{-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt ut labore et
                                        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                        laboris nisi ut aliquip ex ea
                                        commodo</p> --}}
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
    </section>
    <script>
        document.querySelector('form[action="{{ route('account.checkout.form') }}"]').addEventListener('submit', function(
            e) {
            const qty = document.getElementById('sst').value;
            document.getElementById('selectedQty').value = qty;

            const variantId = document.getElementById('size').value;
            document.getElementById('selectedVariant').value = variantId;
        });
    </script>

    <!--================End Product Description Area =================-->
@endsection
@push('scripts')
<script>
function addToCart() {
    const variantId = document.getElementById('size').value;
    const quantity = document.getElementById('sst').value;

    if (!variantId) {
        alert('Vui lòng chọn size!');
        return;
    }

    if (quantity < 1) {
        alert('Số lượng phải lớn hơn 0!');
        return;
    }

    const btn = document.getElementById('add-to-cart-btn');
    btn.disabled = true;
    btn.textContent = 'Đang thêm...';

    const formData = new FormData();
    formData.append('variant_id', variantId);
    formData.append('quantity', quantity);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        let data = {};
        try {
            data = await response.json();
        } catch (err) {
            console.error('Không thể parse JSON:', err);
        }

        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                const messages = Object.values(data.errors).flat().join(', ');
                alert(messages);
            } else {
                alert(data.message || 'Có lỗi xảy ra khi thêm vào giỏ!');
            }
            return;
        }

        if (data.require_login) {
            alert(data.message || 'Bạn cần đăng nhập!');
            setTimeout(() => window.location.href = '/login', 1000);
            return;
        }

        if (data.success) {
            alert(data.message || 'Đã thêm vào giỏ hàng!');
            updateCartCount();
        } else {
            alert(data.message || 'Thêm vào giỏ hàng thất bại!');
        }
    })
    .catch(error => {
        console.error('Lỗi khi gửi yêu cầu:', error);
        alert('Lỗi không xác định. Vui lòng thử lại.');
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Add to Cart';
    });
}

function updateCartCount() {
    const cartCountEl = document.getElementById('cart-count');
    if (cartCountEl) {
        fetch('{{ route("cart.count") }}')
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

document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
@endpush



