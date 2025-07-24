<?php $__env->startSection('title', 'Thêm danh mục sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm danh mục sản phẩm mới</h3>
    </div>
    <div class="card-body">

        <form action="<?php echo e(route('categories.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="name_category" class="form-label">Tên danh mục</label>
                <input type="text" name="name_category" id="name_category" class="form-control" value="<?php echo e(old('name_category')); ?>" placeholder="Nhập tên danh mục mới" required >
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\DATN-WD105\resources\views/admin/categories/create.blade.php ENDPATH**/ ?>