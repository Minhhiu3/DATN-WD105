<?php $__env->startSection('title', 'Thêm size sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm size sản phẩm </h3>
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
        <form action="<?php echo e(route('admin.sizes.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Tên size</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name')); ?>" placeholder="Nhập tên size mới" required >
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="<?php echo e(route('admin.sizes.index')); ?>" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_clone\DATN-WD105\resources\views/admin/size/create.blade.php ENDPATH**/ ?>