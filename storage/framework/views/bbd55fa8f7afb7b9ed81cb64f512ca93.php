<?php $__env->startSection('title', 'Thêm Banner'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm banner mới</h3>
    </div>
    <div class="card-body">

        <form action="<?php echo e(route('admin.banner.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Tên banner</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name')); ?>"
                    placeholder="Nhập tên banner" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh banner</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Thêm mới</button>
                <a href="<?php echo e(route('admin.banner.index')); ?>" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_clone\DATN-WD105\resources\views/admin/banner/create.blade.php ENDPATH**/ ?>