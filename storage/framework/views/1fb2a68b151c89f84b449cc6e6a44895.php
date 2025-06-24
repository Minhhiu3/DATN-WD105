<?php $__env->startSection('title', 'Quản lý banner'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa banner</h3>
    </div>
    <div class="card-body">

        <form action="<?php echo e(route('banners.update', ['banner' => $banner->id_banner])); ?>" method="POST"
            enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name', $banner->name)); ?>"
                    placeholder="Nhập tên danh mục mới" required>
            </div>
            <div>
                <label for="image" class="form-label">Ảnh banner</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <?php if($banner->image): ?>
                <img src="<?php echo e(asset('storage/' . $banner->image)); ?>" alt="Banner Image"
                    style="width: 100px; height: auto; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo e(route('banners.index')); ?>" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\DATN-WD105\resources\views/admin/banner/edit.blade.php ENDPATH**/ ?>