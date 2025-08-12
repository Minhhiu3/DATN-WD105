<!DOCTYPE html>
<html lang="vi" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang web')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/fav.png') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/css/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ion.rangeSlider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ion.rangeSlider.skinFlat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    @stack('styles')
</head>

<body>
    {{-- Header --}}
    @include('client.partials.header_home')

    {{-- Nội dung --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('client.partials.footer_home')

    {{-- Thông báo thành công nếu có --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
        window.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Thành công!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
        @endif
        @if(session('error'))
        window.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Thất bại!',
                    html: {!! json_encode(nl2br(session('error'))) !!},
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
        @endif
    </script>

    <!-- JS Libraries -->
    <script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('assets/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('assets/js/countdown.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>

    <!-- Google Maps -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
    <script src="{{ asset('assets/js/gmaps.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- Scripts riêng từng trang --}}
    @stack('scripts')
</body>
</html>
