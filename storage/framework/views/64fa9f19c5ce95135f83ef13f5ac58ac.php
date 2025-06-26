
<?php $__env->startSection('title', 'Đổi mật khẩu'); ?>
<?php $__env->startSection('content'); ?>
<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Đổi mật khẩu</h1>
                <nav class="d-flex align-items-center">
                    <a href="<?php echo e(route('home')); ?>">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="<?php echo e(route('account.profile')); ?>">Tài khoản<span class="lnr lnr-arrow-right"></span></a>
                    <a href="<?php echo e(route('account.change-password')); ?>">Đổi mật khẩu</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!-- Start Change Password Area -->
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
                            <a href="<?php echo e(route('account.change-password')); ?>" class="list-group-item list-group-item-action active">
                                <i class="fa fa-lock me-2"></i>Đổi mật khẩu
                            </a>
                            <a href="<?php echo e(route('account.orders')); ?>" class="list-group-item list-group-item-action">
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
                        <h5><i class="fa fa-lock me-2"></i>Đổi mật khẩu</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('account.update-password')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            
                            <div class="form-group mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="current_password" name="current_password" required>
                                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="password" name="password" required>
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                <strong>Lưu ý:</strong> Mật khẩu mới phải có ít nhất 6 ký tự và khác với mật khẩu hiện tại.
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-2"></i>Đổi mật khẩu
                                </button>
                                <a href="<?php echo e(route('account.profile')); ?>" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left me-2"></i>Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Change Password Area -->
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\code\DATN-WD105\resources\views/auth/change-password.blade.php ENDPATH**/ ?>