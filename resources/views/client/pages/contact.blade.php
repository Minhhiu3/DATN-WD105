@extends('layouts.client_home')
@section('title', 'Liên hệ')

@section('content')
<section class="contact-area" style="margin-top: 80px; padding: 50px 0;">
    <div class="container">
        <div class="row">
            <!-- Thông tin liên hệ -->
            <div class="col-lg-6">
                <h2>Liên hệ với chúng tôi</h2>
                <p>Hãy liên hệ qua email, số điện thoại hoặc các kênh mạng xã hội để được hỗ trợ và trải nghiệm những mẫu giày mới nhất.</p>
                
                <div class="contact-info mt-4">
                    <!-- Email -->
                    <div class="mb-3 d-flex align-items-center">
                        <span><strong>Email:</strong> <a href="mailto:shoemart@gmail.com">shoemart@gmail.com</a></span>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="mb-3 d-flex align-items-center">
                        <span><strong>Số điện thoại:</strong> <a href="tel:+8484985583">(+84) 84985583</a></span>
                    </div>

                    <!-- Zalo -->
                    <div class="mb-3 d-flex align-items-center">
                        <span><strong>Zalo:</strong> <a href="https://zalo.me/0847985583" target="_blank">0847985583</a></span>
                    </div>
                </div>

                <!-- Facebook Widget -->
                <div class="facebook-widget mt-4">
                    <h5 class="mb-2">Fanpage Facebook</h5>
                    <div style="
                        background: #fff;
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                        padding: 15px;
                        text-align: center;
                    ">
                        <div class="fb-page" 
                            data-href="https://www.facebook.com/profile.php?id=100095086464329&sk=about" 
                            data-tabs=""   {{-- chỉ để trống, không hiển thị timeline --}}
                            data-width="340" 
                            data-height="200" 
                            data-small-header="false" 
                            data-adapt-container-width="true" 
                            data-hide-cover="false" 
                            data-show-facepile="true">
                        </div>
                        <p class="mt-2">Theo dõi chúng tôi trên Facebook!</p>
                    </div>
                </div>
            </div>

            <!-- Bản đồ -->
            <div class="col-lg-6">
                <h2>Địa chỉ cửa hàng</h2>
                <p>2PQW+6JJ Tòa nhà FPT Polytechnic., Cổng số 2, 13 P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội 100000, Việt Nam</p>
                <div style="width: 100%; height: 400px; border-radius: 10px; overflow: hidden;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.863806019064!2d105.74468687448123!3d21.038134787458024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1754092245722!5m2!1svi!2s" 
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        // Tải Facebook SDK
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v10.0";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
@endpush
@endsection
