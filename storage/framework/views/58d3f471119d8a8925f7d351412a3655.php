<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="<?php echo e(route('home')); ?>"><img src="<?php echo e(asset('assets/img/logo.png')); ?>"
                        alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Chuyển đổi điều hướng">
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
                                
                            
                        
                        
                            
                          <li class="nav-item"><a class="nav-link" href="<?php echo e(route('login')); ?>">Đăng nhập</a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item"><a href="<?php echo e(route('cart')); ?>" class="cart"><span class="ti-bag"></span></a></li>
                        <li class="nav-item">
                            <button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
                        </li>
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
});
</script>

<?php /**PATH C:\laragon\www\DATN-WD105\resources\views/client/partials/header_home.blade.php ENDPATH**/ ?>