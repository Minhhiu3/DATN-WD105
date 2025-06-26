<?php $__env->startSection('title', 'Quản lý Mã Giảm Giá'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách mã giảm giá</h3>
    </div>

    <div class="card-header d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('discounts.create')); ?>" class="btn btn-primary">Thêm mới</a>
    </div>

    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã giảm giá</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                     <th>Giảm tối đa</th>
                      <th>Đơn tối thiểu</th>
                       <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>

                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $discounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $discount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($discount->discount_id); ?></td>
                        <td><?php echo e($discount->code); ?></td>
                        <td><?php if($discount->type == 0): ?>
                              %
                            <?php elseif($discount->type == 1): ?>
                                Giá trị
                            <?php else: ?>
                                Không xác định
                            <?php endif; ?></td>
                       <td><?php if($discount->type == 0): ?>
                              <?php echo e($discount->value); ?>( % )
                            <?php elseif($discount->type == 1): ?>
                                <?php echo e($discount->value); ?>

                            <?php else: ?>
                                Không xác định
                            <?php endif; ?></td>
                          <td><?php echo e($discount->max_discount); ?></td>
                            <td><?php echo e($discount->min_order_value); ?></td>
                              <td><?php echo e($discount->start_date); ?></td>
                                <td><?php echo e($discount->end_date); ?></td>
                                    <td><?php if($discount->is_active == 0): ?>
                               Không hoạt động
                            <?php elseif($discount->is_active == 1): ?>
                                 Hoạt động
                            <?php else: ?>
                                Không xác định
                            <?php endif; ?></td>

                        <td>
                            <a href="<?php echo e(route('discounts.edit', $discount->discount_id)); ?>"
                                class="btn btn-warning btn-sm">Sửa</a>
                            <form action="<?php echo e(route('discounts.destroy', $discount->discount_id)); ?>" method="POST"
                                style="display:inline-block;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button onclick="return confirm('Bạn có chắc muốn xóa?')"
                                    class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\code\DATN-WD105\resources\views/admin/discounts/index.blade.php ENDPATH**/ ?>