<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('page_title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Welcome</h3>
    </div>
    <div class="card-body">
        <p>Chào mừng bạn đến với trang quản trị Admin!</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\DATN-WD105\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>