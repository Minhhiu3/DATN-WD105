@extends('layouts.client_home')
@section('title','Bài Viết')
@section('content')

<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Trang Bài Viết</h1>
                <nav class="d-flex align-items-center">
                    <a href="{{ route('home') }}">Trang Chủ<span class="lnr lnr-arrow-right"></span></a>
                    <a href="{{ route('blogs') }}">Bài Viết</a>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!--================Danh mục Blog =================-->
<section class="blog_categorie_area">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="categories_post">
                    <img src="{{ asset('assets/img/blog/cat-post/cat-post-3.jpg') }}" alt="post">
                    <div class="categories_details">
                        <div class="categories_text">
                            <a href="#">
                                <h5>Phong Cách Thời Trang</h5>
                            </a>
                            <div class="border_line"></div>
                            <p>Cập nhật xu hướng thời trang giày mới nhất</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Thêm nhiều danh mục nếu muốn -->
        </div>
    </div>
</section>

<!--================Khu vực Blog =================-->
<section class="blog_area">
    <div class="container">
        <div class="row">
            <!-- Bài viết -->
            <div class="col-lg-8">
                <div class="blog_left_sidebar">
                    <article class="row blog_item">
                        <div class="col-md-3">
                            <div class="blog_info text-right">
                                <div class="post_tag">
                                    <a href="#">Giày Sneaker,</a>
                                    <a class="active" href="#">Thời trang,</a>
                                    <a href="#">Giảm giá,</a>
                                    <a href="#">Sự kiện</a>
                                </div>
                                <ul class="blog_meta list">
                                    <li><a href="#">Shoe Mart <i class="lnr lnr-user"></i></a></li>
                                    <li><a href="#">29 Tháng 7, 2025 <i class="lnr lnr-calendar-full"></i></a></li>
                                    <li><a href="#">10K Lượt xem <i class="lnr lnr-eye"></i></a></li>
                                    <li><a href="#">15 Bình luận <i class="lnr lnr-bubble"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="blog_post">
                                <img src="{{ asset('img/blog/main-blog/m-blog-1.jpg') }}" alt="">
                                <div class="blog_details">
                                    <a href="{{ route('blog-detail') }}">
                                        <h2>Top 5 Mẫu Sneaker Mới Nhất 2025</h2>
                                    </a>
                                    <p>Khám phá những đôi sneaker hot trend đang làm mưa làm gió năm nay. Từ thiết kế năng động, màu sắc nổi bật đến độ bền bỉ khi sử dụng mỗi ngày.</p>
                                    <a href="{{ route('blog-detail') }}" class="white_bg_btn">Xem thêm</a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <nav class="blog-pagination justify-content-center d-flex">
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" class="page-link" aria-label="Previous">
                                    <span aria-hidden="true"><span class="lnr lnr-chevron-left"></span></span>
                                </a>
                            </li>
                            <li class="page-item"><a href="#" class="page-link">01</a></li>
                            <li class="page-item active"><a href="#" class="page-link">02</a></li>
                            <li class="page-item"><a href="#" class="page-link">03</a></li>
                            <li class="page-item">
                                <a href="#" class="page-link" aria-label="Next">
                                    <span aria-hidden="true"><span class="lnr lnr-chevron-right"></span></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Sidebar bên phải -->
            <div class="col-lg-4">
                <div class="blog_right_sidebar">
                    <aside class="single_sidebar_widget search_widget">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm kiếm bài viết...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="lnr lnr-magnifier"></i></button>
                            </span>
                        </div>
                    </aside>

                    <aside class="single_sidebar_widget author_widget">
                        <img class="author_img rounded-circle" src="{{ asset('img/blog/author.png') }}" alt="">
                        <h4>Team 105</h4>
                        <p>Chuyên gia thời trang giày</p>
                        <div class="social_icon">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-tiktok"></i></a>
                        </div>
                        <p>Chia sẻ các xu hướng thời trang giày mới nhất, mẹo phối đồ và đánh giá sản phẩm từ Shoe Mart.</p>
                    </aside>

                    <aside class="single_sidebar_widget popular_post_widget">
                        <h3 class="widget_title">Bài viết nổi bật</h3>
                        <div class="media post_item">
                            <img src="{{ asset('assets/img/blog/popular-post/post1.jpg') }}" alt="post">
                            <div class="media-body">
                                <a href="blog-details.html">
                                    <h3>10 Tips Giữ Giày Trắng Luôn Như Mới</h3>
                                </a>
                                <p>2 giờ trước</p>
                            </div>
                        </div>
                    </aside>

                    <aside class="single_sidebar_widget post_category_widget">
                        <h4 class="widget_title">Danh mục</h4>
                        <ul class="list cat-list">
                            <li>
                                <a href="#" class="d-flex justify-content-between">
                                    <p>Sneaker</p>
                                    <p>24</p>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="d-flex justify-content-between">
                                    <p>Khuyến mãi</p>
                                    <p>10</p>
                                </a>
                            </li>
                        </ul>
                    </aside>

                    <aside class="single-sidebar-widget newsletter_widget">
                        <h4 class="widget_title">Đăng ký nhận tin</h4>
                        <p>Nhận thông tin khuyến mãi, bài viết mới và xu hướng thời trang giày nhanh nhất!</p>
                        <div class="form-group d-flex flex-row">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                                </div>
                                <input type="text" class="form-control" placeholder="Nhập email của bạn">
                            </div>
                            <a href="#" class="bbtns">Đăng ký</a>
                        </div>
                        <p>Bạn có thể hủy đăng ký bất kỳ lúc nào.</p>
                    </aside>

                    <aside class="single-sidebar-widget tag_cloud_widget">
                        <h4 class="widget_title">Thẻ phổ biến</h4>
                        <ul class="list">
                            <li><a href="#">Sneaker</a></li>
                            <li><a href="#">Giày thể thao</a></li>
                            <li><a href="#">Phong cách</a></li>
                            <li><a href="#">Giảm giá</a></li>
                            <li><a href="#">Nam</a></li>
                            <li><a href="#">Nữ</a></li>
                            <li><a href="#">Trẻ em</a></li>
                            <li><a href="#">Sự kiện</a></li>
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
