
<?php $__env->startSection('title', 'Lịch sử đơn hàng'); ?>
<?php $__env->startSection('content'); ?>
<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Lịch sử đơn hàng</h1>
                <nav class="d-flex align-items-center">
                    <a href="<?php echo e(route('home')); ?>">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="<?php echo e(route('account.profile')); ?>">Tài khoản<span class="lnr lnr-arrow-right"></span></a>
                    <a href="<?php echo e(route('account.orders')); ?>">Lịch sử đơn hàng</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!-- Start Orders Area -->
<section class="section_gap">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa fa-user-circle"></i> Tài khoản</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="<?php echo e(route('account.profile')); ?>" class="list-group-item list-group-item-action">
                                <i class="fa fa-user me-2"></i>Thông tin cá nhân
                            </a>
                            <a href="<?php echo e(route('account.edit')); ?>" class="list-group-item list-group-item-action">
                                <i class="fa fa-edit me-2"></i>Chỉnh sửa thông tin
                            </a>
                            <a href="<?php echo e(route('account.change-password')); ?>" class="list-group-item list-group-item-action">
                                <i class="fa fa-lock me-2"></i>Đổi mật khẩu
                            </a>
                            <a href="<?php echo e(route('account.orders')); ?>" class="list-group-item list-group-item-action active">
                                <i class="fa fa-shopping-bag me-2"></i>Lịch sử đơn hàng
                            </a>
                            <a href="<?php echo e(route('account.settings')); ?>" class="list-group-item list-group-item-action">
                                <i class="fa fa-cog me-2"></i>Cài đặt
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa fa-shopping-bag me-2"></i>Lịch sử đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <?php if($orders->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn hàng</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>#<?php echo e($order->id ?? 'N/A'); ?></td>
                                            <td><?php echo e($order->created_at ?? 'N/A'); ?></td>
                                            <td><?php echo e(number_format($order->total ?? 0)); ?> VNĐ</td>
                                            <td>
                                                <span class="badge badge-info">Đang xử lý</span>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i> Xem chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fa fa-shopping-bag fa-3x text-muted mb-3"></i>
                                <h5>Chưa có đơn hàng nào</h5>
                                <p class="text-muted">Bạn chưa có đơn hàng nào. Hãy mua sắm ngay!</p>
                                <a href="<?php echo e(route('products')); ?>" class="btn btn-primary">
                                    <i class="fa fa-shopping-cart me-2"></i>Mua sắm ngay
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Orders Area -->
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\code\DATN-WD105\resources\views/auth/orders.blade.php ENDPATH**/ ?>