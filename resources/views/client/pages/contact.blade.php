@extends('layouts.client_home')
@section('title', 'Liên hệ')

@section('content')
<section class="contact-area" style="margin-top: 80px; padding: 50px 0;">
    <div class="container">
        <div class="row">
            <!-- Thông tin liên hệ -->
            <div class="col-lg-6">
                <h2>Liên hệ với chúng tôi</h2>
                <p>Hãy gửi tin nhắn cho chúng tôi hoặc ghé thăm cửa hàng để trải nghiệm những mẫu giày mới nhất.</p>

                <form action="#" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Họ và tên</label>
                        <input type="text" name="name" class="form-control" placeholder="Nhập họ tên của bạn">
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Nhập email của bạn">
                    </div>
                    <div class="form-group mb-3">
                        <label>Nội dung</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Nhập nội dung"></textarea>
                    </div>
                    <button type="submit" class="primary-btn">Gửi liên hệ</button>
                </form>
            </div>

            <!-- Bản đồ -->
            <div class="col-lg-6">
                <h2>Địa chỉ cửa hàng</h2>
                <p>2PQW+6JJ Tòa nhà FPT Polytechnic., Cổng số 2, 13 P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội 100000, Việt Nam</p>
                <div style="width: 100%; height: 400px; border-radius: 10px; overflow: hidden;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.863806019064!2d105.74468687448123!3d21.038134787458024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1754092245722!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
