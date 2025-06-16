<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="{{ route('home') }}"><img src="{{asset('assets/img/logo.png')}}"
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
                        <li class="nav-item active"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">Cửa hàng</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('products') }}">Sản phẩm</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('checkout') }}">Thanh toán</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('cart') }}">Giỏ hàng</a></li>
                                <li class="nav-item"><a class="nav-link" href="confirmation.html">Xác nhận đơn hàng</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item submenu dropdown">
                            <a href="{{ route('blogs') }}" class="nav-link dropdown-toggle" data-toggle="dropdown"
                                role="button" aria-haspopup="true" aria-expanded="false">Tin tức</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('blogs') }}">Blog</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('blog-detail') }}">Chi tiết bài
                                        viết</a></li>
                            </ul>
                        </li>
                        <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">Trang</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                                <li class="nav-item"><a class="nav-link" href="tracking.html">Theo dõi đơn hàng</a></li>

                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="contact.html">Liên hệ</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">

                        <!-- add to cart -->
                        <li class="nav-item dropdown-cart position-relative">
                            <a href="javascript:void(0)" class="cart">
                                <span class="ti-bag"></span>
                                <span id="cart-count" class="badge badge-danger"
                                    style="position:absolute; top:-5px; right:-10px; font-size: 12px; display: none;">0</span>
                            </a>
                            <div id="mini-cart" class="mini-cart shadow p-3 bg-white rounded"
                                style="display: none; position: absolute; right: 0; top: 120%; width: 300px; z-index: 1000;">
                                <div id="mini-cart-items"></div>
                                <div class="text-center mt-2">
                                    <a href="{{ route('cart') }}" class="btn btn-primary btn-sm">Xem giỏ hàng</a>
                                </div>
                            </div>
                        </li>
                        <!-- mini cart -->
                        <div id="mini-cart-items"></div>
                        <div id="mini-cart-total" class="text-right font-weight-bold mt-2"></div>



                        <!-- search -->
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