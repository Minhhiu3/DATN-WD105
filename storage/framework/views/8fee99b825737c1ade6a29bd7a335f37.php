<?php $__env->startSection('title', 'Trang Chủ'); ?>
<?php $__env->startSection('content'); ?>

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Checkout</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="single-product.html">Checkout</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Checkout Area =================-->
    <section class="checkout_area section_gap">
        <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p><?php echo e($error); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
        <div class="container">
            
            <div class="cupon_area">
                <div class="check_title">
                    <h2>Nhập mã giảm giá của bạn <a href="#">Các sự kiện nhận mã giảm giá</a></h2>
                </div>
                <input type="text" placeholder="Nhập mã giảm giá">
                <a class="tp_btn" href="#">OK</a>
            </div>
            <div class="billing_details">
                <div class="row">
                    <div class="col-lg-6">
                        <h3>Chi tiết thanh toán</h3>
                        <form class="row contact_form" action="#" method="post" novalidate="novalidate">
                            <div class="col-md-6 form-group p_star">
                                <label for="name"><b>Họ và tên</b></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo e(old('name', auth()->user()->name ?? '')); ?>" disabled>
                                
                            </div>
                            
                            
                            <div class="col-md-6 form-group p_star">
                                <label for="name"><b>Số điện thoại</b></label>
                                <input type="text" class="form-control" id="number" name="number"
                                    value="<?php echo e(old('number', auth()->user()->phone_number ?? '')); ?>" disabled>
                                
                            </div>
                            <div class="col-md-6 form-group p_star">
                                <label for="name"><b>Email</b></label>
                                <input type="text" class="form-control" id="email" name="compemailany"
                                    value="<?php echo e(old('email', auth()->user()->email ?? '')); ?>" disabled>
                                
                            </div>
                            
                            
                            
                            
                            
                            
                            
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="order_box">
                            <h2>Đơn hàng của bạn</h2>
                            <ul class="list">
                                <li>
                                    <a href="#">
                                        <b><?php echo e($variant->product->name_product); ?> (Size <?php echo e($variant->size->name); ?>)</b>
                                        <span class="middle">x <?php echo e($quantity); ?></span>
                                        <span class="last"><?php echo e(number_format($variant->price * $quantity, 0, ',', '.')); ?>

                                            VNĐ</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="list list_2">


                                
                                <li><a href="#">Phí vận chuyển <span>0 VND</span></a></li>
                                <li><a href="#">Tổng tiền
                                        <span><?php echo e(number_format($variant->price * $quantity, 0, ',', '.')); ?> VNĐ</span></a>
                                </li>
                            </ul>
                            
                            
                            <form action="<?php echo e(route('account.placeOrder')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="variant_id" value="<?php echo e($variant->id_variant); ?>">
                                <input type="hidden" name="quantity" value="<?php echo e($quantity); ?>">

                                <div class="payment_item">
                                    <label>
                                        <input type="radio" name="payment_method" value="cod" checked>
                                        Thanh toán khi nhận hàng
                                    </label>
                                </div>
                                <div class="payment_item">
                                    <label>
                                        <input type="radio" name="payment_method" value="vnpay">
                                        Thanh toán qua VNPay
                                    </label>
                                </div>

                                <button type="submit" class="primary-btn w-100">Đặt Hàng</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Checkout Area =================-->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_clone\DATN-WD105\resources\views/client/pages/checkout.blade.php ENDPATH**/ ?>