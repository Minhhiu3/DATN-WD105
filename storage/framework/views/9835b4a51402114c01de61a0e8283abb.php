<!DOCTYPE html>
<html lang="vi" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Trang web'); ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('assets/img/fav.png')); ?>">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/linearicons.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/themify-icons.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/owl.carousel.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/nice-select.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/nouislider.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/ion.rangeSlider.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/ion.rangeSlider.skinFlat.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/magnific-popup.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/main.css')); ?>">

    <!-- Google Font: Roboto -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <style>
        #mini-cart {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            display: none;
        }

        .nav-item.position-relative:hover #mini-cart,
        .nav-item.position-relative:focus-within #mini-cart {
            display: block !important;
        }
        body {
    font-family: 'Roboto', sans-serif;
}

        /* Bỏ viền cho tất cả các nút Bootstrap */
    .btn {
        border: none !important;
        box-shadow: none !important;
    }

    /* Tuỳ chỉnh lại hover nếu cần */
    .btn:hover,
    .btn:focus {
        border: none !important;
        box-shadow: none !important;
    }


    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    
    <?php echo $__env->make('client.partials.header_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('client.partials.footer_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if(session('success')): ?>
        window.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Thành công!',
                text: "<?php echo e(session('success')); ?>",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
        <?php endif; ?>
        <?php if(session('error')): ?>
        window.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Thất bại!',
                    html: <?php echo json_encode(nl2br(session('error'))); ?>,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
        <?php endif; ?>
    </script>

    <!-- JS Libraries -->
    <script src="<?php echo e(asset('assets/js/vendor/jquery-2.2.4.min.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo e(asset('assets/js/vendor/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery.ajaxchimp.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery.nice-select.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery.sticky.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/nouislider.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/countdown.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery.magnific-popup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/owl.carousel.min.js')); ?>"></script>

    <!-- Google Maps -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
    <script src="<?php echo e(asset('assets/js/gmaps.min.js')); ?>"></script>

    <!-- Main JS -->
    <script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\DATN-WD105\resources\views/layouts/client_home.blade.php ENDPATH**/ ?>