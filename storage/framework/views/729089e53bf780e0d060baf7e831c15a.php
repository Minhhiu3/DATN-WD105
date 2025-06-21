<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="<?php echo e(asset('assets/img/fav.png')); ?>">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title><?php echo $__env->yieldContent('title'); ?></title>
	<!--
		CSS
		============================================= -->
<link rel="stylesheet" href="<?php echo e(asset('assets/css/linearicons.css')); ?>">

	<link rel="stylesheet" href="<?php echo e(asset('assets/css/font-awesome.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/css/themify-icons.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/css/owl.carousel.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/css/nice-select.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/css/nouislider.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/css/ion.rangeSlider.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/css/ion.rangeSlider.skinFlat.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/css/magnific-popup.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/css/main.css')); ?>">
</head>

<body>


    <?php echo $__env->make('client.partials.header_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <main>
        <?php echo $__env->yieldContent('content'); ?>
 <?php echo $__env->make('client.partials.related_product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </main>
        <?php echo $__env->make('client.partials.footer_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>





	<script src="<?php echo e(asset('assets/js/vendor/jquery-2.2.4.min.js')); ?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="<?php echo e(asset('assets/js/vendor/bootstrap.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/js/jquery.ajaxchimp.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/js/jquery.nice-select.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/js/jquery.sticky.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/js/nouislider.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/js/countdown.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/js/jquery.magnific-popup.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/js/owl.carousel.min.js')); ?>"></script>
	<!--gmaps Js-->
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="<?php echo e(asset('assets/js/gmaps.min.js')); ?>"></script>
	<script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>
</body>

</html>
<?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_New\DATN-WD105\resources\views/layouts/client_home.blade.php ENDPATH**/ ?>