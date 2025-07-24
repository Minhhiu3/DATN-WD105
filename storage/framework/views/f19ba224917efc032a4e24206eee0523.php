<?php $__env->startSection('title', 'Chi tiết sản phẩmphẩm'); ?>

<?php $__env->startSection('content'); ?>
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

    <!-- ================= Start Product Detail Area ================= -->
    <div class="product_image_area">
        <div class="container">
            <div class="row s_product_inner">
                <!-- Ảnh sản phẩm -->
                <div class="col-lg-6">
                    <div class="main-image mb-3">
                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name_product); ?>"
                            class="img-fluid rounded shadow-sm w-100" style="object-fit: cover; max-height: 400px;">
                    </div>
                    <div class="album-images d-flex flex-wrap gap-2">
                        <?php if($product->albumProducts && $product->albumProducts->count()): ?>
                            <?php $__currentLoopData = $product->albumProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $album): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="album-thumb border rounded p-1"
                                    style="width: 100px; height: 100px; overflow: hidden;">
                                    <img src="<?php echo e(asset('storage/' . $album->image)); ?>"
                                        alt="<?php echo e($product->name_product); ?> - album" class="img-fluid h-100 w-100"
                                        style="object-fit: cover;">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <img src="<?php echo e(asset('assets/img/product/default.jpg')); ?>" alt="<?php echo e($product->name_product); ?>"
                                class="img-fluid">
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Thông tin chi tiết -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="s_product_text">
                        <h3><?php echo e($product->name_product); ?></h3>
                        <h2 id="dynamic-price">
                            <?php if($product->variants->count() > 0): ?>
                                <?php echo e(number_format($product->variants->min('price'), 0, ',', '.')); ?> VNĐ
                                <h6 id="dynamic-stock" class="text-muted">Vui lòng chọn kích thước</h6>
                            <?php else: ?>
                                <span class="text-danger">Đang cập nhật</span>
                            <?php endif; ?>
                        </h2>


                        <ul class="list">
                            <li>
                                <span>Danh mục</span> :
                                <?php echo e($product->category->name_category ?? 'Chưa phân loại'); ?>

                            </li>
                            
                        </ul>
                        <p><?php echo e($product->description); ?></p>

                        <?php if(auth()->guard()->guest()): ?>
                            
                            <a href="<?php echo e(route('login')); ?>" class="primary-btn">Đăng nhập để thêm vào giỏ</a>
                        <?php else: ?>
                            
                            <form onsubmit="addToCart(event)" class="mt-3">
                                <?php echo csrf_field(); ?>

                                <!-- Size dạng nút -->
                                <div class="form-group mb-3">
                                    <label for="size">Kích thước:</label>
                                    <div class="d-flex gap-2 flex-wrap" id="size-options">
                                        <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $size = $variant->size->name ?? 'N/A';
                                                $qty = $variant->quantity;
                                            ?>
                                            <button type="button"
                                                class="btn btn-outline-dark size-btn <?php echo e($qty == 0 ? 'disabled' : ''); ?>"
                                                data-variant-id="<?php echo e($variant->id_variant); ?>"
                                                data-quantity="<?php echo e($qty); ?>" <?php echo e($qty == 0 ? 'disabled' : ''); ?>>
                                                <?php echo e($size); ?>

                                            </button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <input type="hidden" name="variant_id" id="variant_id" required>
                                </div>

                                <div class="product_count mb-3">
                                    <label for="sst">Số lượng:</label>
                                    <div class="input-group" style="width: 140px;">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary" type="button"
                                                id="decrease-btn">−</button>
                                        </div>
                                        <input type="text" name="quantity" id="sst" min="1" value="1"
                                            class="form-control text-center">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                id="increase-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                <?php if($product->variants->count() > 0): ?>
                                    <div class="card_area d-flex align-items-center gap-3">
                                        <button type="submit" class="primary-btn" id="add-to-cart-btn">Add to Cart</button>
                                    </div>

                                    <div id="cart-message" class="alert alert-danger d-none mt-3"></div>
                            </form>
                            
                            <form action="<?php echo e(route('account.checkout.form')); ?>" method="GET" class="mt-2">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="variant_id" id="selectedVariant" value="">
                                <input type="hidden" name="quantity" id="selectedQty" value="1">
                                <div class="card_area d-flex align-items-center gap-3">
                                    <button type="submit" class="primary-btn">Mua ngay</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="card_area d-flex align-items-center gap-3">
                                <button type="button" class="primary-btn disabled">Hết hàng</button>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>




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
        <?php
    $variantMap = $product->variants->mapWithKeys(function($v) {
        return [
            $v->id_variant => [
                'price' => $v->price,
                'quantity' => $v->quantity,
            ]
        ];
    });
?>
    </section>
    

    <!--================End Product Description Area =================-->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function addToCart(event) {
        event.preventDefault();

        const variantId = document.getElementById('variant_id')?.value;
        const quantity = document.getElementById('sst')?.value;

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
        formData.append('_token', '<?php echo e(csrf_token()); ?>');

        fetch('<?php echo e(route('cart.add')); ?>', {
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
            console.error('Lỗi khi gửi yêu cầu:', error);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi không xác định',
                text: 'Vui lòng thử lại sau.'
            });
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Add to Cart';
        });
    }

    function updateCartCount() {
        const cartCountEl = document.getElementById('cart-count');
        if (cartCountEl) {
            fetch('<?php echo e(route('cart.count')); ?>')
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

    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();

        const sizeButtons = document.querySelectorAll('.size-btn');
        const hiddenVariantInput = document.getElementById('variant_id');
        const selectedVariantInput = document.getElementById('selectedVariant');
        const priceDisplay = document.getElementById('dynamic-price');
        const stockDisplay = document.getElementById('dynamic-stock');
        const input = document.getElementById('sst');

        const btnMinus = document.getElementById('decrease-btn');
        const btnPlus = document.getElementById('increase-btn');

        const variants = <?php echo json_encode($variantMap, 15, 512) ?>;

        function formatPrice(number) {
            return new Intl.NumberFormat('vi-VN').format(number) + ' VNĐ';
        }

        function getMaxQuantity() {
            const selectedBtn = document.querySelector('.size-btn.btn-dark');
            return selectedBtn ? parseInt(selectedBtn.dataset.quantity) : Infinity;
        }

        sizeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                sizeButtons.forEach(b => b.classList.remove('active', 'btn-dark'));
                btn.classList.add('active', 'btn-dark');

                const variantId = btn.dataset.variantId;
                const variant = variants[variantId];

                hiddenVariantInput.value = variantId;
                selectedVariantInput.value = variantId;

                // Cập nhật giá
                if (priceDisplay && variant?.price) {
                    priceDisplay.innerText = formatPrice(variant.price);
                }

                // Cập nhật tồn kho
                if (stockDisplay) {
                    if (variant?.quantity > 0) {
                        stockDisplay.innerText = `Còn lại: ${variant.quantity} sản phẩm`;
                        stockDisplay.classList.remove('text-danger');
                        stockDisplay.classList.add('text-muted');
                    } else {
                        stockDisplay.innerText = 'Hết hàng';
                        stockDisplay.classList.remove('text-muted');
                        stockDisplay.classList.add('text-danger');
                    }
                }

                // Reset số lượng về 1 khi chọn size mới
                input.value = 1;
            });
        });

        // Nút -
        btnMinus.addEventListener('click', function() {
            let val = parseInt(input.value) || 1;
            if (val > 1) input.value = val - 1;
        });

        // Nút +
        btnPlus.addEventListener('click', function() {
            let val = parseInt(input.value) || 1;
            const maxQty = getMaxQuantity();
            if (val < maxQty) input.value = val + 1;
        });

        // Nhập tay: giới hạn số lượng
        input.addEventListener('input', function() {
            let val = parseInt(input.value);
            const maxQty = getMaxQuantity();

            if (isNaN(val) || val < 1) input.value = 1;
            else if (val > maxQty) input.value = maxQty;
        });

        // Gán lại số lượng cho nút "Mua ngay"
        const buyNowForm = document.querySelector('form[action="<?php echo e(route('account.checkout.form')); ?>"]');
        if (buyNowForm) {
            buyNowForm.addEventListener('submit', function(e) {
                const variant = document.getElementById('variant_id').value;
                const qty = document.getElementById('sst').value;

                if (!variant) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Chưa chọn size',
                        text: 'Vui lòng chọn kích thước trước khi mua ngay.'
                    });
                    return;
                }

                document.getElementById('selectedQty').value = qty;
                document.getElementById('selectedVariant').value = variant;
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/client/pages/product-detail.blade.php ENDPATH**/ ?>