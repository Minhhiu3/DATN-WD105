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

    <!-- scrip -->

</head>
<style>

     #mini-cart {
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        display: none;
    }
    .nav-item.position-relative:hover #mini-cart,
    .nav-item.position-relative:focus-within #mini-cart {
        display: block !important;
    }
</style>

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
</script>



    <script src="<?php echo e(asset('assets/js/vendor/jquery-2.2.4.min.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
    </script>
    <script src="<?php echo e(asset('assets/js/vendor/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery.ajaxchimp.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery.nice-select.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery.sticky.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/nouislider.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/countdown.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery.magnific-popup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/owl.carousel.min.js')); ?>"></script>
    <!--gmaps Js-->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE">
    </script>
    <script src="<?php echo e(asset('assets/js/gmaps.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- search bar -->
     <script>
    $(document).ready(function () {
        // Mở khung tìm kiếm
        $('#search').on('click', function (e) {
            e.preventDefault();
            $('#search_input_box').fadeIn(200).css('display', 'block');
            $('#search_input').focus();
        });

        // Đóng khung tìm kiếm
        $('#close_search').on('click', function () {
            $('#search_input_box').fadeOut(200);
        });
    });
</script>



    <!-- add to cart scrip -->
    <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.dataset.id;

            fetch("<?php echo e(route('cart.addAjax')); ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('#mini-cart').innerHTML = data.html;
                    document.querySelector('#cart-count').textContent = data.count;
                    document.querySelector('#mini-cart').style.display = 'block';
                }
            });
        });
    });
});
</script>


</body>

</html>
<?php /**PATH C:\laragon\www\DATN-WD105\resources\views/layouts/client_home.blade.php ENDPATH**/ ?>