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
                                <a href="<?php echo e(route('account.change-password')); ?>"
                                    class="list-group-item list-group-item-action">
                                    <i class="fa fa-lock me-2"></i>Đổi mật khẩu
                                </a>
                                <a href="<?php echo e(route('account.orders')); ?>"
                                    class="list-group-item list-group-item-action active">
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
                            <?php if($orders->isNotEmpty()): ?>
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
                                                    <td>#<?php echo e($order->id_order); ?></td>
                                                    <td><?php echo e($order->created_at?->format('d/m/Y H:i') ?? 'N/A'); ?></td>
                                                    <td><?php echo e(number_format($order->orderItems->sum('total_amount'))); ?> VNĐ
                                                    </td>
                                                    <td>
                                                        <?php $status = $order->status; ?>
                                                        <?php if($status == 'pending'): ?>
                                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                                        <?php elseif($status == 'confirmed'): ?>
                                                            <span class="badge bg-success text-white">Đã xác nhận</span>
                                                        <?php elseif($status == 'shipping'): ?>
                                                            <span class="badge bg-primary text-white">Đang giao</span>
                                                        <?php elseif($status == 'delivered'): ?>
                                                            <span class="badge bg-success text-white">Đã giao</span>
                                                        <?php elseif($status == 'canceled'): ?>
                                                            <span class="badge bg-danger text-white">Đã hủy</span>
                                                        <?php else: ?>
                                                            <span class="badge "><?php echo e($status); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('account.orderDetail', $order->id_order)); ?>"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fa fa-eye"></i> Xem chi tiết
                                                        </a>
                                                        <?php if($order->status == 'chờ xác nhận'): ?>
                                                            <form
                                                                action="<?php echo e(route('account.cancelOrder', $order->id_order)); ?>"
                                                                method="POST"
                                                                onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('PUT'); ?>
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-recycle"></i> Hủy đơn hàng
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <div class="mt-3">
                                        <?php echo e($orders->links()); ?>

                                    </div>
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

<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\DATN-WD105\resources\views/auth/orders.blade.php ENDPATH**/ ?>