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
 <?php echo $__env->make('client.partials.related_product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </main>
    <?php echo $__env->make('client.partials.footer_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>





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
    <!-- add to cart scrip -->
<script>

function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const index = cart.findIndex(
        item => item.id === product.id && item.size === product.size
    );

    if (index !== -1) {
        cart[index].quantity += product.quantity;
    } else {
        cart.push(product);
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    console.log('Sản phẩm đã được thêm vào giỏ hàng:', product);
}

function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountEl = document.getElementById('cart-count');

    if (cartCountEl) {
        if (totalQuantity > 0) {
            cartCountEl.style.display = 'inline-block';
            cartCountEl.innerText = totalQuantity;
        } else {
            cartCountEl.style.display = 'none';
        }
    }

    renderMiniCart(cart);
}

function renderMiniCart(cart) {
    const container = document.getElementById('mini-cart-items');
    const totalEl = document.getElementById('mini-cart-total');

    if (!container || !totalEl) return;

    container.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
        container.innerHTML = '<p class="text-center">Giỏ hàng trống</p>';
        totalEl.innerText = '';
        return;
    }

    cart.forEach((item, index) => {
        total += item.price * item.quantity;
        const el = document.createElement('div');
        el.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2');
        el.innerHTML = `
            <div>
                <strong>${item.name}</strong><br>
                <small>SL: ${item.quantity} - Size: ${item.size}</small>
            </div>
            <div class="text-right">
                <small>${item.price.toLocaleString()}₫</small><br>
                <button class="btn btn-sm btn-danger btn-delete-item" data-index="${index}">&times;</button>
            </div>
        `;
        container.appendChild(el);
    });

    totalEl.innerText = `Tổng: ${total.toLocaleString()}₫`;

    document.querySelectorAll('.btn-delete-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            removeFromCart(index);
        });
    });
}

function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}

// Tự động hiển thị giỏ hàng từ localStorage khi load lại trang
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const product = {
                id: this.getAttribute('data-id'),
                name: this.getAttribute('data-name'),
                price: parseInt(this.getAttribute('data-price')),
                size: this.getAttribute('data-size'),
                quantity: 1
            };
            addToCart(product);
        });
    });
});
</script>




</body>

</html>
<?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_clone\DATN-WD105\resources\views/layouts/client_home.blade.php ENDPATH**/ ?>