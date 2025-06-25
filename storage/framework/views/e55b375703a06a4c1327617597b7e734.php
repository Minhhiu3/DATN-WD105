<?php $__env->startSection('title', 'Quản lý Danh mục sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa danh mục sản phẩm</h3>
    </div>
    <div class="card-body">

        <form action="<?php echo e(route('categories.update', $category->id_category)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="mb-3">
                <label for="name_category" class="form-label">Tên danh mục</label>
                <input type="text" name="name_category" id="name_category" class="form-control" value="<?php echo e(old('name_category', $category->name_category)); ?>" placeholder="Nhập tên danh mục mới" required >
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_New\DATN-WD105\resources\views/admin/categories/edit.blade.php ENDPATH**/ ?>