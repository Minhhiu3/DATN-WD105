<?php $__env->startSection('title', 'Quản lý banner'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Banner</h3>
    </div>

    <div class="card-header d-flex justify-content-between align-items-center">

        <a href="<?php echo e(route('banner.create')); ?>" class="btn btn-primary">Thêm mới</a>
    </div>


    <div class="card-body">
        <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Banner</th>
                    <th>Ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($banner->id_banner); ?></td>
                    <td><?php echo e($banner->name); ?></td>
                    <td>
                        <img src="<?php echo e(asset('storage/' . $banner->image)); ?>" alt="Banner Image"
                            style="width: 100px; height: auto;">
                    <td>
                        <a href="<?php echo e(route('banner.edit', $banner->id_banner)); ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="<?php echo e(route('banner.destroy', $banner->id_banner)); ?>" method="POST"
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\DATN-WD105\resources\views/admin/banner/index.blade.php ENDPATH**/ ?>