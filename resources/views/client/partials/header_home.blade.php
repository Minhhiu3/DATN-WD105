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

                        <li class="nav-item"><a class="nav-link" href="{{ route('blogs') }}">Tin tức</a></li>

                        <li class="nav-item"><a class="nav-link" href="contact.html">Liên hệ</a></li>
                        @guest
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                        @endguest
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
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item position-relative">
                            <a href="{{ route('cart') }}" class="cart" id="cart-icon">
                                <span class="ti-bag"></span>
                                <span id="cart-count" class="badge"
                                    style="display:none;position:absolute;top:0;right:0;">0</span>
                            </a>

                            <!-- mini cảt -->
                            <div id="mini-cart"
                                style="display:none;position:absolute;right:0;top:40px;z-index:1000;background:#fff;border:1px solid #eee;width:300px;padding:15px;">
                                @if ($cartItems->count() > 0)
                                    @php $total = 0; @endphp
                                    @foreach ($cartItems as $item)
                                        @php
                                            $variant = $item->variant ?? $item['variant'];
                                            $product = $variant->product ?? null;
                                            $size = $variant->size ?? null;
                                            $quantity = $item->quantity ?? $item['quantity'];
                                            $price = $variant->price ?? 0;
                                            $itemTotal = $price * $quantity;
                                            $total += $itemTotal;
                                        @endphp
                                        <div
                                            class="d-flex justify-content-between align-items-start mb-2 border-bottom pb-2">
                                            <div>
                                                <strong>{{ $product->name_product ?? 'Sản phẩm' }}</strong><br>
                                                <small>SL: {{ $quantity }} - Size:
                                                    {{ $size->name ?? 'N/A' }}</small>
                                            </div>
                                            <div class="text-right">
                                                <small>{{ number_format($price, 0, ',', '.') }}₫</small>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="mt-2 font-weight-bold text-right">
                                        Tổng: {{ number_format($total, 0, ',', '.') }}₫
                                    </div>
                                    <div class="text-right mt-2">
                                        <a href="{{ route('cart') }}" class="btn btn-sm btn-primary">Xem giỏ hàng</a>
                                    </div>
                                @else
                                    <p class="text-center mb-0">Giỏ hàng trống</p>
                                @endif
                            </div>


                        </li>
                        <li class="nav-item">
                            <button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">

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
        fetch('{{ route('cart.count') }}')
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
