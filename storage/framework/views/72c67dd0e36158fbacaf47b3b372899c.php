<?php $__env->startSection('title', 'Quản lý Đơn hàng'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Danh sách đơn hàng</h3>

            <form action="<?php echo e(route('admin.orders.index')); ?>" 
                    method="GET" 
                    class="d-flex align-items-center ms-auto w-50 gap-2" style="margin-left: 50%">
                <input type="date"
                    name="date"
                    class="form-control "
                    value="<?php echo e(request('date', $date)); ?>" style=" width: 25%; height: 100%; margin-left: 1% " > 

                <input type="text"
                    name="code"
                    class="form-control  "
                    placeholder="Mã đơn"
                    value="<?php echo e(request('code', $code ?? '')); ?>" style=" width: 30%; height: 100%; margin-left: 1% ">

                <button type="submit" class="btn btn-primary  " style=" width: 50px;  margin-left: 1% ">Lọc</button>
            </form>
        </div>

    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>#<?php echo e($order->id_order); ?></td>
                            <td><?php echo e($order->user->name ?? 'N/A'); ?></td>
                            <td><?php echo e(number_format($order->total_amount, 0, ',', '.')); ?> VND</td>
                            <td><?php echo e(\Carbon\Carbon::parse($order->created_at)->format('d/m/Y')); ?></td>
                           <?php
                                $statusLevels = [
                                    'pending' => 1,
                                    'processing' => 2,
                                    'shipping' => 3,
                                    'completed' => 4,
                                    'canceled' => 5,
                                ];

                                $currentStatus = $order->status;
                            ?>

                            <td>
                                <select class="form-control form-control-sm order-status" data-id="<?php echo e($order->id_order); ?>">
                                    <?php $__currentLoopData = $statusLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            // Logic loại bỏ các trạng thái không hợp lệ
                                            $isInvalid = false;

                                            // Không cho chọn trạng thái thấp hơn hiện tại
                                            if ($level < $statusLevels[$currentStatus]) $isInvalid = true;

                                            // Nếu đã completed thì không được quay lại canceled
                                            if ($currentStatus === 'completed' && $status === 'canceled') $isInvalid = true;

                                            // Nếu đã canceled thì không cho đổi gì nữa
                                            if ($currentStatus === 'canceled' && $status !== 'canceled') $isInvalid = true;
                                        ?>

                                        <?php if(!$isInvalid): ?>
                                            <option value="<?php echo e($status); ?>" <?php echo e($currentStatus == $status ? 'selected' : ''); ?>>
                                                <?php switch($status):
                                                    case ('pending'): ?> Chờ xử lý <?php break; ?>
                                                    <?php case ('processing'): ?> Đang xử lý <?php break; ?>
                                                    <?php case ('shipping'): ?> Đang giao <?php break; ?>
                                                    <?php case ('completed'): ?> Hoàn thành <?php break; ?>
                                                    <?php case ('canceled'): ?> Đã hủy <?php break; ?>
                                                <?php endswitch; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>



                            <td>
                                <a href="<?php echo e(route('admin.orders.show', $order->id_order )); ?>" class="btn btn-info btn-sm">Chi tiết</a>
                                
                             <?php if(in_array($order->status, ['pending', 'processing'])): ?>
                                <a href="javascript:void(0);" 
                                class="btn btn-danger btn-sm cancel-order-btn" 
                                data-id="<?php echo e($order->id_order); ?>">
                                Hủy
                                </a>
                            <?php endif; ?>


                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center">Không có đơn hàng nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($orders->hasPages()): ?>
            <div class="d-flex justify-content-center mt-3">
                <?php echo e($orders->links('pagination::bootstrap-5')); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Xử lý đổi trạng thái
    document.querySelectorAll('.order-status').forEach(select => {
        select.addEventListener('change', function () {
            const status = this.value;
            const orderId = this.dataset.id;

            fetch("<?php echo e(route('admin.orders.updateStatus')); ?>", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: orderId,
                    status: status,
                }),
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            })
            .catch(error => {
                alert("Lỗi khi cập nhật!");
                console.error(error);
            });
        });
    });

    // Xử lý hủy đơn hàng
    document.querySelectorAll('.cancel-order-btn').forEach(button => {
        button.addEventListener('click', function () {
            if (!confirm("Bạn có chắc chắn muốn hủy đơn hàng này không?")) return;

            const orderId = this.dataset.id;

            fetch("<?php echo e(route('admin.orders.cancel')); ?>", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ id: orderId })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            })
            .catch(error => {
                alert("Lỗi khi hủy đơn!");
                console.error(error);
            });
        });
    });
});
</script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>