<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="<?php echo e(route('home')); ?>"><img src="<?php echo e(asset('assets/img/logo.png')); ?>"
                        alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Chuyển đổi điều hướng">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <ul class="nav navbar-nav menu_nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('home')); ?>">Trang chủ</a></li>
                        <li class="nav-item "><a class="nav-link" href="<?php echo e(route('products')); ?>">Cửa hàng</a></li>
                        
                        
                        
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('blogs')); ?>">Tin tức</a></li>
                        
                        
                        
                        
                            <li class="nav-item"><a class="nav-link" href="contact.html">Liên hệ</a></li>
                          <li class="nav-item"><a class="nav-link" href="<?php echo e(route('login')); ?>">Đăng nhập</a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item position-relative">
    <?php
    $cart = session('cart', []);
    $cartCount = collect($cart)->sum('quantity');
?>

<a href="<?php echo e(route('cart.index')); ?>" class="cart position-relative" id="cart-icon">
    <span class="ti-bag" style="font-size: 20px;"></span>
    <?php if($cartCount > 0): ?>
        <span id="cart-count" class="badge "
              style="position:absolute;top:-7;right:0;font-size: 15px;">
            <?php echo e($cartCount); ?>

            
        </span>
    <?php endif; ?>
</a>

    <div id="mini-cart" style="display:none;position:absolute;right:0;top:40px;z-index:1000;background:#fff;border:1px solid #eee;width:300px;padding:15px;">
        <!-- cart item -->
         <?php
    $cart = session('cart', []);
    $total = collect($cart)->reduce(fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);
?>

<?php if(count($cart)): ?>
    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
   
    <div class="media mb-2 align-items-center">
        <img src="<?php echo e(asset('storage/' . $item['thumbnail'])); ?>" class="mr-2 rounded" style="width: 50px; height: 50px; object-fit: cover;">
        <div class="media-body">
              <p>ID: <?php echo e($id); ?></p>
            <h6 class="mb-0 text-sm"><?php echo e($item['name']); ?></h6>
            <small>SL: <?php echo e($item['quantity']); ?> × <?php echo e(number_format($item['price'])); ?>đ</small>
        </div>
        <form action="<?php echo e(route('cart.remove', $id)); ?>" method="POST" class="ml-2">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Xoá sản phẩm">
                ✕
            </button>
        </form>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php else: ?>
    <p class="text-muted text-center">Giỏ hàng trống</p>
<?php endif; ?>
        <!-- end cart item -->

        <!-- cart total -->
         <?php if(count($cart)): ?>
    <div class="d-flex justify-content-between font-weight-bold border-top pt-2">
        <span>Tổng:</span>
        <span><?php echo e(number_format($total)); ?>đ</span>
    </div>
    <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-sm btn-primary btn-block mt-2">Xem giỏ hàng</a>
<?php endif; ?>
        <!-- end cart total -->
    </div>
</li>
                        <li class="nav-item">
                            <button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <?php if(auth()->guard()->check()): ?>
                            <li class="nav-item">
                                <span class="nav-link">
                                    <!-- <a href="<?php echo e(route('account.profile')); ?>"> <i class="fa fa-user"></i> <?php echo e(Auth::user()->name); ?> </a> -->
                                     <a href="<?php echo e(route('account.profile')); ?>" style="color: black;">
                                        <i class="fa fa-user"></i> <?php echo e(Auth::user()->name); ?>

                                    </a>
                                </span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="search_input" id="search_input_box">
        <div class="container">
            <form class="d-flex justify-content-between">
                <input type="text" class="form-control" id="search_input" placeholder="Tìm kiếm tại đây">
                <button type="submit" class="btn"></button>
                <span class="lnr lnr-cross" id="close_search" title="Đóng tìm kiếm"></span>
            </form>
        </div>
    </div>
</header>
<!-- End Header Area -->



<!-- fe scrip -->
<?php /**PATH C:\laragon\www\DATN-WD105\resources\views/client/partials/header_home.blade.php ENDPATH**/ ?>