<?php $__env->startSection('title', 'Sửa sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cập nhập trạng thái đơn hàng</h3>
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

        <form action="<?php echo e(route('admin.orders.update', $order->id_order)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-group mb-3">
                <label for="customer_name">Khách hàng:</label>
                <input type="text" id="customer_name" class="form-control" value="<?php echo e(old('name', $order->user->name)); ?>" disabled>
            </div>

            <div class="form-group mb-3">
                <label for="total_amount">Tổng tiền:</label>
                <input type="text" id="total_amount" class="form-control" value="<?php echo e(number_format($order->total_amount, 0, ',', '.')); ?> VND" disabled>
            </div>

            <div class="form-group mb-3">
                <label for="created_at">Ngày đặt:</label>
                <input type="text" id="created_at" class="form-control" value="<?php echo e(optional($order->created_at)->format('d/m/Y')); ?>" disabled>
            </div>
            
            <div class="form-group mb-3">
                <label for="status">Trạng thái đơn hàng:</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" <?php echo e($order->status == 'pending' ? 'selected' : ''); ?>>Chờ xử lý</option>
                    <option value="processing" <?php echo e($order->status == 'processing' ? 'selected' : ''); ?>>Đang xử lý</option>
                    <option value="shipping" <?php echo e($order->status == 'shipping' ? 'selected' : ''); ?>>Đang giao</option>
                    <option value="completed" <?php echo e($order->status == 'completed' ? 'selected' : ''); ?>>Hoàn thành</option>
                    <option value="canceled" <?php echo e($order->status == 'canceled' ? 'selected' : ''); ?>>Đã hủy</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="note">Ghi chú:</label>
                <textarea name="note" id="note" class="form-control">nhập lý do</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
        </form>


    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/admin/orders/edit.blade.php ENDPATH**/ ?>