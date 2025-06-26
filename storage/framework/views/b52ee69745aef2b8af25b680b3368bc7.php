<?php $__env->startSection('title', 'Quản lý người dùng'); ?>
<?php $__env->startSection('page_title', 'Quản lý người dùng'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách người dùng</h3>
        <div class="card-tools">
            <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm người dùng
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ tên</th>
                    <th>Tên tài khoản</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($user->id_user); ?></td>
                    <td><?php echo e($user->name); ?></td>
                    <td><?php echo e($user->account_name); ?></td>
                    <td><?php echo e($user->email); ?></td>
                    <td><?php echo e($user->phone_number ?? 'N/A'); ?></td>
                    <td>
                        <?php if($user->role): ?>
                            <span class="badge badge-<?php echo e($user->role->name === 'Admin' ? 'danger' : 'info'); ?>">
                                <?php echo e($user->role->name); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge badge-secondary">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A'); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.users.show', $user->id_user)); ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?php echo e(route('admin.users.edit', $user->id_user)); ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if($user->id_user !== auth()->id()): ?>
                        <form action="<?php echo e(route('admin.users.destroy', $user->id_user)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center">Không có người dùng nào</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            <?php echo e($users->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\code\DATN-WD105\resources\views/admin/users/index.blade.php ENDPATH**/ ?>