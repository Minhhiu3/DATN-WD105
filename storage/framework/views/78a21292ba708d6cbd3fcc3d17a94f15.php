<?php $__env->startSection('title', 'Thêm size sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $product_id= $_GET['id']; // Lấy giá trị từ query string

?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm anh sản phẩm </h3>
    </div>
    <div class="card-body">

        <form action="<?php echo e(route('Ablum_products.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="product_id" value="<?php echo e($product_id); ?>">
            <div class="mb-3">
                <label for="product_id" class="form-label">Ma san pham</label>
                <input type="input" name="product_id" id="product_id" class="form-control" value="<?php echo e($product_id); ?>" placeholder="Nhập tên size mới" disabled >
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Anh san pham</label>
                <input type="file" name="image" id="image" class="form-control" value="<?php echo e(old('image')); ?>" placeholder="Nhập tên size mới" required >
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="<?php echo e(route('Ablum_products.show', $product_id)); ?>" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/admin/Album_product/create.blade.php ENDPATH**/ ?>