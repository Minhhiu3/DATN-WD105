<?php $__env->startSection('title', $product->name_product); ?>

<?php $__env->startSection('content'); ?>
<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Chi tiết sản phẩm</h1>
                <nav class="d-flex align-items-center">
                    <a href="<?php echo e(route('home')); ?>">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="#"><?php echo e($product->name_product); ?></a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<section class="product-details-area mt-5">
    <div class="container">
        <div class="row">
            <!-- Image Section -->
            <div class="col-lg-6 col-md-6">
                <div class="main-image mb-3">
                    <img id="main-product-image" src="<?php echo e(asset('storage/' . ($product->albumProducts->first()->image ?? ''))); ?>"
                        alt="<?php echo e($product->name_product); ?>" class="img-fluid">
                </div>
                <div class="image-thumbnails d-flex flex-wrap gap-2">
                    <?php $__currentLoopData = $product->albumProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $album): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/' . $album->image)); ?>" width="80" height="80"
                            class="img-thumbnail thumbnail-image" alt="Ảnh phụ">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Info Section -->
            <div class="col-lg-6 col-md-6">
                <h3><?php echo e($product->name_product); ?></h3>
                <p><?php echo e($product->description); ?></p>

                <h4 id="dynamic-price">Vui lòng chọn kích thước</h4>
                <p id="dynamic-stock" class="text-success fw-bold"></p>

                <form action="<?php echo e(route('add.to.cart')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="variant_id" id="variant_id">

                    <!-- Màu sắc -->
                    <div class="form-group mb-3">
                        <label>Màu sắc:</label>
                        <div class="d-flex gap-2 flex-wrap" id="color-options">
                            <?php $__currentLoopData = $product->variants->groupBy('color_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colorId => $variantsGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $color = $variantsGroup->first()->color;
                                ?>
                                <button type="button"
                                    class="btn btn-outline-primary color-btn"
                                    data-color-id="<?php echo e($colorId); ?>"
                                    data-image="<?php echo e(asset('storage/' . $color->image)); ?>">
                                    <?php echo e($color->name_color); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Size -->
                    <div class="form-group mb-3">
                        <label>Kích thước:</label>
                        <div class="d-flex gap-2 flex-wrap" id="size-options">
                            <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button"
                                    class="btn btn-outline-dark size-btn"
                                    data-variant-id="<?php echo e($variant->id_variant); ?>"
                                    data-size="<?php echo e($variant->size->name_size); ?>"
                                    data-price="<?php echo e(number_format($variant->price, 0, ',', '.')); ?>₫"
                                    data-quantity="<?php echo e($variant->quantity); ?>"
                                    data-color-id="<?php echo e($variant->color_id); ?>">
                                    <?php echo e($variant->size->name_size); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-3" id="btn-add-cart" disabled>Thêm vào giỏ hàng</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const colorButtons = document.querySelectorAll('.color-btn');
        const sizeButtons = document.querySelectorAll('.size-btn');
        const mainImage = document.getElementById('main-product-image');
        const priceDisplay = document.getElementById('dynamic-price');
        const stockDisplay = document.getElementById('dynamic-stock');
        const variantInput = document.getElementById('variant_id');
        const btnAddCart = document.getElementById('btn-add-cart');

        let selectedColorId = null;

        // Click chọn ảnh nhỏ
        document.querySelectorAll('.thumbnail-image').forEach(img => {
            img.addEventListener('click', () => {
                mainImage.src = img.src;
            });
        });

        // Xử lý khi chọn màu
        colorButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                selectedColorId = btn.dataset.colorId;
                const image = btn.dataset.image;

                // Đổi ảnh chính
                if (image) mainImage.src = image;

                // Reset màu active
                colorButtons.forEach(b => b.classList.remove('active', 'btn-primary'));
                btn.classList.add('active', 'btn-primary');

                // Ẩn size không khớp
                sizeButtons.forEach(sizeBtn => {
                    if (sizeBtn.dataset.colorId === selectedColorId) {
                        sizeBtn.style.display = 'inline-block';
                    } else {
                        sizeBtn.style.display = 'none';
                        sizeBtn.classList.remove('active', 'btn-dark');
                    }
                });

                // Reset khi đổi màu
                priceDisplay.innerText = 'Vui lòng chọn kích thước';
                stockDisplay.innerText = '';
                variantInput.value = '';
                btnAddCart.disabled = true;
            });
        });

        // Xử lý khi chọn size
        sizeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                sizeButtons.forEach(b => b.classList.remove('active', 'btn-dark'));
                btn.classList.add('active', 'btn-dark');

                const price = btn.dataset.price;
                const quantity = btn.dataset.quantity;
                const variantId = btn.dataset.variantId;

                priceDisplay.innerText = `Giá: ${price}`;
                stockDisplay.innerText = `Tồn kho: ${quantity}`;
                variantInput.value = variantId;
                btnAddCart.disabled = false;
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/client/pages/product-detail.blade.php ENDPATH**/ ?>