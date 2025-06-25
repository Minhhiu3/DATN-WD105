<?php $__env->startSection('title', 'Thêm Mới Mã Giảm Giá'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm mã giảm giá mới</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('discounts.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="code" class="form-label">Mã giảm giá</label>
                <input type="text" name="code" id="code" class="form-control" value="<?php echo e(old('code')); ?>" placeholder="Nhập mã giảm giá" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Loại</label>
                <input type="text" name="type" id="type" class="form-control" value="<?php echo e(old('type')); ?>" placeholder="Nhập loại (ví dụ:0:percentage, 1:fixed)" required>
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">Giá trị</label>
                <input type="number" step="0.01" name="value" id="value" class="form-control" value="<?php echo e(old('value')); ?>" placeholder="Nhập giá trị" required>
            </div>
            <div class="mb-3">
                <label for="max_discount" class="form-label">Giảm tối đa</label>
                <input type="number" step="0.01" name="max_discount" id="max_discount" class="form-control" value="<?php echo e(old('max_discount')); ?>" placeholder="Nhập giảm tối đa" required>
            </div>
            <div class="mb-3">
                <label for="min_order_value" class="form-label">Giá trị đơn tối thiểu</label>
                <input type="number" step="0.01" name="min_order_value" id="min_order_value" class="form-control" value="<?php echo e(old('min_order_value')); ?>" placeholder="Nhập giá trị đơn tối thiểu" required>
            </div>
            
            <div class="mb-3">
                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo e(old('start_date')); ?>" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Ngày kết thúc</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo e(old('end_date')); ?>" required>
            </div>
            <div class="mb-3">
                <label for="is_active" class="form-label">Hoạt động</label>
                <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', 1) ? 'checked' : ''); ?>>
            </div>
            <button type="submit" class="btn btn-primary">Thêm mới</button>
            <a href="<?php echo e(route('discounts.index')); ?>" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/admin/discounts/create.blade.php ENDPATH**/ ?>