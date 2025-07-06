<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- trang chinh -->
        <li class="nav-item">
            <a href="<?php echo e(url('admin/dashboard')); ?>" class="nav-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <!-- ql san pham -->
        <li class="nav-item">
            <a href="<?php echo e(url('/admin/products')); ?>" class="nav-link <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                <i class="fa-brands fa-product-hunt"></i>
                <p>Quản lý sản phẩm</p>
            </a>
        </li>
        <!-- ql danh muc -->
        <li class="nav-item">
            <a href="<?php echo e(url('/admin/categories')); ?>" class="nav-link <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                <i class="fa-solid fa-layer-group"></i>
                <p>Quản lý danh mục</p>
            </a>
        </li>

        <!-- ql size -->
        <li class="nav-item">
            <a href="<?php echo e(url('/admin/sizes')); ?>"
                class="nav-link <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                <i class="fa-solid fa-layer-group"></i>
                <p>Quản lý size</p>
            </a>
        </li>
        
        <!-- ql nguoi dung -->
        <li class="nav-item">
            <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Quản lý người dùng</p>
            </a>    
        </li>
        
        <!-- ql banner -->
        <li class="nav-item">
            <a href="<?php echo e(url('/admin/banner')); ?>"
                class="nav-link <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                <i class="fa-solid fa-layer-group"></i>
                <p>Quản lý banner</p>
            </a>
        </li>

        <!-- ql đơn hànghàng -->
        <li class="nav-item">
            <a href="<?php echo e(url('/admin/orders')); ?>" class="nav-link <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                <i class="fa-solid fa-truck"></i>
                <p>Quản lý đơn hàng</p>
            </a>
        </li>
        <!-- ql voucher -->
        <li class="nav-item">
            <a href="<?php echo e(url('/admin/discounts')); ?>" class="nav-link <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                <i class="fa-solid fa-ticket"></i>
                <p>Quản lý mã giảm giá</p>
            </a>
        </li>
        <!-- ql đánh giá -->
        <li class="nav-item">
            <a href="<?php echo e(url('/admin/reviews')); ?>" class="nav-link <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                <i class="fa-solid fa-comment-slash"></i>
                <p>Quản lý đánh giá</p>
            </a>
        </li>


        <li class="nav-item">
            <a href="<?php echo e(url('/settings')); ?>" class="nav-link <?php echo e(request()->is('settings') ? 'active' : ''); ?>">
                <i class="nav-icon fas fa-cog"></i>
                <p>Settings</p>
            </a>
        </li>
    </ul>
</nav>
<!-- /.sidebar-menu -->
<?php /**PATH C:\laragon\www\DATN-WD105\resources\views/admin/partials/sidebar.blade.php ENDPATH**/ ?>