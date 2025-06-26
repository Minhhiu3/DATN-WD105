<?php $__env->startSection('title', 'Chi tiết người dùng'); ?>
<?php $__env->startSection('page_title', 'Chi tiết người dùng'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thông tin người dùng: <?php echo e($user->name); ?></h3>
        <div class="card-tools">
            <a href="<?php echo e(route('admin.users.edit', $user->id_user)); ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">ID:</th>
                        <td><?php echo e($user->id_user); ?></td>
                    </tr>
                    <tr>
                        <th>Họ tên:</th>
                        <td><?php echo e($user->name); ?></td>
                    </tr>
                    <tr>
                        <th>Tên tài khoản:</th>
                        <td><?php echo e($user->account_name); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo e($user->email); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Số điện thoại:</th>
                        <td><?php echo e($user->phone_number ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Vai trò:</th>
                        <td>
                            <?php if($user->role): ?>
                                <span class="badge badge-<?php echo e($user->role->name === 'Admin' ? 'danger' : 'info'); ?>">
                                    <?php echo e($user->role->name); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge badge-secondary">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td><?php echo e($user->created_at ? $user->created_at->format('d/m/Y H:i:s') : 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối:</th>
                        <td><?php echo e($user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : 'N/A'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/admin/users/show.blade.php ENDPATH**/ ?>