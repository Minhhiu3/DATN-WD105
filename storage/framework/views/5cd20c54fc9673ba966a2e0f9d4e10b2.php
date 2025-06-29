<?php $__env->startSection('title','Giỏ hàng'); ?>
<?php $__env->startSection('content'); ?>
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Giỏ hàng</h1>
                    <nav class="d-flex align-items-center">
                        <a href="<?php echo e(route('home')); ?>">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                        <a href="<?php echo e(route('cart')); ?>">Giỏ hàng</a>
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
                <?php if($cartItems->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Sản phẩm</th>
                                    <th scope="col">Giá</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Tổng</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $variant = $item->variant ?? $item['variant'];
                                        $product = $variant->product;
                                        $size = $variant->size;
                                        $quantity = $item->quantity ?? $item['quantity'];
                                        $price = $variant->price;
                                        $total = $price * $quantity;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="d-flex">
                                                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name_product); ?>" style="width: 100px; height: 100px; object-fit: cover;">
                                                </div>
                                                <div class="media-body">
                                                    <h4><?php echo e($product->name_product); ?></h4>
                                                    <p>Size: <?php echo e($size ? $size->name : 'Không xác định'); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5><?php echo e(number_format($price, 0, ',', '.')); ?> VNĐ</h5>
                                        </td>
                                        <td>
                                            <div class="product_count">
                                                <input type="text" name="qty" value="<?php echo e($quantity); ?>" 
                                                    class="input-text qty" 
                                                    data-variant-id="<?php echo e($variant->id_variant); ?>"
                                                    onchange="updateQuantity(<?php echo e($variant->id_variant); ?>, this.value)">
                                                <button onclick="changeQuantity(<?php echo e($variant->id_variant); ?>, 1)" 
                                                    class="increase items-count" type="button">
                                                    <i class="lnr lnr-chevron-up"></i>
                                                </button>
                                                <button onclick="changeQuantity(<?php echo e($variant->id_variant); ?>, -1)" 
                                                    class="reduced items-count" type="button">
                                                    <i class="lnr lnr-chevron-down"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <h5><?php echo e(number_format($total, 0, ',', '.')); ?> VNĐ</h5>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="removeFromCart(<?php echo e($variant->id_variant); ?>)">
                                                <i class="fa fa-trash"></i> Xóa
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row justify-content-end">
                        <div class="col-lg-4">
                            <div class="card_area">
                                <div class="checkout_btn_inner d-flex align-items-center">
                                    <a class="gray_btn" href="<?php echo e(route('products')); ?>">Tiếp tục mua sắm</a>
                                    <a class="primary-btn" href="<?php echo e(route('checkout')); ?>">Thanh toán</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <h3>Giỏ hàng trống</h3>
                        <p>Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                        <a href="<?php echo e(route('products')); ?>" class="primary-btn">Mua sắm ngay</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function changeQuantity(variantId, change) {
    const input = document.querySelector(`input[data-variant-id="${variantId}"]`);
    let newQuantity = parseInt(input.value) + change;
    if (newQuantity < 1) newQuantity = 1;
    input.value = newQuantity;
    updateQuantity(variantId, newQuantity);
}

function updateQuantity(variantId, quantity) {
    fetch('<?php echo e(route("cart.update")); ?>', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            variant_id: variantId,
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload để cập nhật tổng tiền
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật số lượng!');
    });
}

function removeFromCart(variantId) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        fetch('<?php echo e(route("cart.remove")); ?>', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                variant_id: variantId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm!');
        });
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\code\DATN-WD105\resources\views/client/pages/cart.blade.php ENDPATH**/ ?>