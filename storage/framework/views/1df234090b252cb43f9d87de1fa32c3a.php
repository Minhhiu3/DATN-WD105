<?php $__env->startSection('title', 'Chi tiết đơn hàng'); ?>
<?php $__env->startSection('content'); ?>
<section class="section_gap">
    <div class="container">
        <h2 class="mb-4">Chi tiết đơn hàng #<?php echo e($order->id_order); ?></h2>
        <p><strong>Ngày đặt:</strong> <?php echo e($order->created_at->format('d/m/Y H:i')); ?></p>
        <p><strong>Trạng thái:</strong>
            <?php if($order->status == 'chờ xác nhận'): ?>
                <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                  <?php elseif($order->status == 'đã xác nhận'): ?>
                <span class="badge bg-success text-white">Đã xác nhận</span>
            <?php elseif($order->status == 'đang giao'): ?>
                <span class="badge bg-primary text-white">Đang giao</span>
            <?php elseif($order->status == 'đã giao'): ?>
                <span class="badge bg-success">Đã giao</span>
            <?php elseif($order->status == 'đã hủy'): ?>
                <span class="badge bg-danger text-white">Đã hủy</span>
            <?php else: ?>
                <span class="badge"><?php echo e($order->status); ?></span>
            <?php endif; ?>
        </p>
<p><?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <strong>Phương thức thanh toán:</strong> <?php echo e($item->payment_method); ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></p>
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td><?php echo e($item->variant->product->name_product ?? 'Không có sản phẩm'); ?></td>
    <td><?php echo e($item->quantity); ?></td>
    <td><?php echo e(number_format($item->variant->product->price)); ?> VNĐ</td>
    <td><?php echo e(number_format($item->total_amount)); ?> VNĐ</td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <p class="mt-3 text-end">
            <strong>Tổng tiền:</strong> <?php echo e(number_format($order->orderItems->sum('total_amount'))); ?> VNĐ
        </p>

        <a href="<?php echo e(route('account.orders')); ?>" class="btn btn-secondary mt-3">
            <i class="fa fa-arrow-left"></i> Quay lại danh sách đơn hàng
        </a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_clone\DATN-WD105\resources\views/auth/order_detail.blade.php ENDPATH**/ ?>