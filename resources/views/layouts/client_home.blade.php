<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('assets/img/fav.png')}}">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>@yield('title')</title>
    <!--
		CSS
		============================================= -->
    <link rel="stylesheet" href="{{ asset('assets/css/linearicons.css') }}">

    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/nouislider.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/ion.rangeSlider.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/ion.rangeSlider.skinFlat.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">

    <!-- scrip -->

</head>
<style>
#mini-cart {
    display: none;
}

#mini-cart.show {
    display: block;
}
</style>

<script>
// ---------- Add to cart & Mini cart ----------
console.log('Add to cart script loaded');

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

    // Gửi cart lên server nếu có token
    const token = localStorage.getItem('jwt_token');
    if (token) {
        fetch('/api/cart-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({
                    items: cart
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log('Cart saved to server:', data);
            })
            .catch(err => {
                console.error('Lỗi khi lưu cart lên server:', err);
            });
    }
}


// Giả sử bạn lưu JWT vào localStorage khi login (nếu có)
const token = localStorage.getItem('jwt_token');
if (token) {
    fetch('/api/cart-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({
                items: cart
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log('Cart saved to server:', data);
        })
        .catch(err => {
            console.error('Lỗi khi lưu cart lên server:', err);
        });
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

// ---------- Hiển thị cart chính (cart page) ----------
function renderCartPage() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (cart.length === 0) {
        document.getElementById('cart-body').innerHTML = '<tr><td colspan="4">Giỏ hàng trống</td></tr>';
        return;
    }

    fetch(`/api/cart-data`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: cart
            })
        })
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('cart-body');
            tbody.innerHTML = '';
            let total = 0;

            data.products.forEach(product => {
                const itemTotal = product.price * product.quantity;
                total += itemTotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="media">
                            <div class="d-flex">
                                <img src="${product.image}" width="80" />
                            </div>
                            <div class="media-body">
                                <p>${product.name} (Size: ${product.size})</p>
                            </div>
                        </div>
                    </td>
                    <td><h5>${product.price.toLocaleString()}₫</h5></td>
                    <td><input type="text" value="${product.quantity}" class="input-text qty" readonly></td>
                    <td><h5>${itemTotal.toLocaleString()}₫</h5></td>
                `;
                tbody.appendChild(row);
            });

            const totalRow = document.createElement('tr');
            totalRow.innerHTML = `
                <td></td><td></td><td><strong>Tổng cộng</strong></td>
                <td><strong>${total.toLocaleString()}₫</strong></td>
            `;
            tbody.appendChild(totalRow);
        })
        .catch(err => {
            console.error('Lỗi khi tải dữ liệu giỏ hàng:', err);
            document.getElementById('cart-body').innerHTML =
                `<tr><td colspan="4">Không thể hiển thị giỏ hàng</td></tr>`;
        });
}

// ---------- Gắn DOM Ready ----------
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    renderCartPage();

    const buttons = document.querySelectorAll('.add-to-cart-btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const product = {
                id: this.dataset.id,
                name: this.dataset.name,
                price: parseInt(this.dataset.price),
                size: this.dataset.size,
                quantity: 1
            };
            addToCart(product);
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.querySelector('.cart');
    const miniCart = document.getElementById('mini-cart');

    if (cartBtn && miniCart) {
        cartBtn.addEventListener('click', function() {
            // Toggle hiển thị
            if (miniCart.style.display === 'none' || miniCart.style.display === '') {
                miniCart.style.display = 'block';
            } else {
                miniCart.style.display = 'none';
            }
        });

        // (Tùy chọn) Click ngoài vùng mini-cart để đóng lại
        document.addEventListener('click', function(e) {
            if (!miniCart.contains(e.target) && !cartBtn.contains(e.target)) {
                miniCart.style.display = 'none';
            }
        });
    }
});

miniCart.classList.toggle('show');
</script>



<body>


    @include('client.partials.header_home')
    <main>
        @yield('content')
        @include('client.partials.related_product')
    </main>
    @include('client.partials.footer_home')





    <script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/js/vendor/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.ajaxchimp.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.sticky.js')}}"></script>
    <script src="{{ asset('assets/js/nouislider.min.js')}}"></script>
    <script src="{{ asset('assets/js/countdown.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js')}}"></script>
    <!--gmaps Js-->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE">
    </script>
    <script src="{{ asset('assets/js/gmaps.min.js')}}"></script>
    <script src="{{ asset('assets/js/main.js')}}"></script>


</body>

</html>