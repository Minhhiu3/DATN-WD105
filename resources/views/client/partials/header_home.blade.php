<!-- Start Header Area -->
<header class="header_area sticky-header">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light main_box">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a class="navbar-brand logo_h" href="{{ route('home') }}"><img src="{{ asset('assets/img/logo.png') }}"
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
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="nav-item "><a class="nav-link" href="{{ route('products') }}">Cửa hàng</a></li>
                        {{-- <li class="nav-item submenu dropdown">
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
                        </li> --}}
                        {{-- <li class="nav-item submenu dropdown">
                            <a href="{{ route('blogs') }}" class="nav-link dropdown-toggle" data-toggle="dropdown"
                                role="button" aria-haspopup="true" aria-expanded="false">Tin tức</a> --}}
                        {{-- <ul class="dropdown-menu"> --}}
                        <li class="nav-item"><a class="nav-link" href="{{ route('blogs') }}">Tin tức</a></li>
                        {{-- <li class="nav-item"><a class="nav-link" href="{{ route('blog-detail') }}">Chi tiết bài
                                        viết</a></li> --}}
                        {{-- </ul> --}}
                        {{-- </li> --}}
                        {{-- <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">Trang</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                                <li class="nav-item"><a class="nav-link" href="tracking.html">Theo dõi đơn hàng</a></li>

                            </ul>
                        </li> --}}
                            <li class="nav-item"><a class="nav-link" href="contact.html">Liên hệ</a></li>
                          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item position-relative">
    <a href="{{ route('cart') }}" class="cart" id="cart-icon">
        <span class="ti-bag"></span>
        <span id="cart-count" class="badge" style="display:none;position:absolute;top:0;right:0;">0</span>
    </a>
    <div id="mini-cart" style="display:none;position:absolute;right:0;top:40px;z-index:1000;background:#fff;border:1px solid #eee;width:300px;padding:15px;">
        <div id="mini-cart-items"></div>
        <div id="mini-cart-total" class="mt-2"></div>
    </div>
</li>
                        <li class="nav-item">
                            <button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        @auth
                            <li class="nav-item">
                                <span class="nav-link">
                                    <!-- <a href="{{ route('account.profile') }}"> <i class="fa fa-user"></i> {{ Auth::user()->name }} </a> -->
                                     <a href="{{ route('account.profile') }}" style="color: black;">
                                        <i class="fa fa-user"></i> {{ Auth::user()->name }}
                                    </a>
                                </span>
                            </li>
                        @endauth
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

<script>
// Cập nhật số lượng giỏ hàng từ server
function updateCartCountFromServer() {
    fetch('{{ route("cart.count") }}')
    .then(response => response.json())
    .then(data => {
        const cartCountEl = document.getElementById('cart-count');
        if (cartCountEl) {
            if (data.count > 0) {
                cartCountEl.style.display = 'inline-block';
                cartCountEl.innerText = data.count;
            } else {
                cartCountEl.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

// Cập nhật khi trang load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCountFromServer();
});
</script>



<!-- fe scrip -->
