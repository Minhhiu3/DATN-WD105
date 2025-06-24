<?php $__env->startSection('title', 'Thêm Biến Thể'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $id_product = request('product_id');
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm Biến Thể Sản Phẩm</h3>
    </div>

    <div class="card-body">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('variants.update', $variant->id_variant)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="form-group mb-3">
                <label for="product_id">Sản Phẩm:</label>
                <select name="product_id" id="product_id" class="form-control form-select" required>
                    <option value="">-- Chọn Sản Phẩm --</option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($product->id_product); ?>" 
                        <?php echo e($variant->product_id == $product->id_product ? 'selected' : ''); ?>>
                        <?php echo e($product->name_product); ?>

                    </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="size_id">Kích Cỡ (Size):</label>
                <select name="size_id" id="size_id" class="form-control form-select" required>
                    <option value="">-- Chọn Size --</option>
                    <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($size->id_size); ?>" 
                        <?php echo e($variant->size_id == $size->id_size ? 'selected' : ''); ?>>
                        <?php echo e($size->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="price">Giá:</label>
                <input type="number" name="price" id="price" class="form-control" value="<?php echo e($variant->price); ?>" min="0" required placeholder="Nhập giá biến thể">
            </div>

            <div class="form-group mb-3">
                <label for="quantity">Số Lượng:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="<?php echo e($variant->quantity); ?>" min="0" required placeholder="Nhập số lượng">
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>








<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/admin/variants/edit.blade.php ENDPATH**/ ?>