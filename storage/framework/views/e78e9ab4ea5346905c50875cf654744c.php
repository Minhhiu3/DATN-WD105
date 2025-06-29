<?php $__env->startSection('title', 'Sản Phẩm'); ?>
<?php $__env->startSection('content'); ?>
    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Shop Category page</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="#">Shop<span class="lnr lnr-arrow-right"></span></a>
                        <a href="category.html">Fashon Category</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-5">
                <div class="sidebar-categories">
                    <div class="head">Danh mục sản phẩm</div>

                    <ul class="main-categories">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="main-nav-list"><a data-toggle="collapse" aria-expanded="false"
                                    aria-controls="fruitsVegetable"></span><?php echo e($category->name_category); ?></a>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </ul>
                </div>
                <div class="sidebar-filter mt-50 ">
                    <div class="top-filter-head ">Lọc</div>
                    
                    <div class="common-filter ">
                        <div class="head">Size</div>

                        <ul class="main-categories sidebar-categories">
                            <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="main-nav-list">
                                    <a href="#">
                                        <?php echo e($size->name); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <div class="common-filter">
                        <div class="head">Price</div>
                        <form method="get" action="<?php echo e(route('products.filterByPrice')); ?>">
                            <select name="price_range" onchange="this.form.submit()" id="">
                                <option value="">--Chọn Mức Giá--</option>
                                <option value="under_500000" <?php echo e(request('price_range') == 'under_500000'? 'selected':''); ?>>Dưới 500.000 VNĐ</option>
                                <option value="500000_2000000" <?php echo e(request('price_range') == '500000_2000000'? 'selected':''); ?>>Từ 500.000 VNĐ đến 2.000.000 VNĐ</option>
                                <option value="over_2000000" <?php echo e(request('price_range') == 'over_2000000'? 'selected':''); ?>>Trên 2.000.000 VNĐ</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-7">
                <!-- Start Filter Bar -->
                <div class="filter-bar d-flex flex-wrap align-items-center">
                    <div class="sorting">
                        <select>
                            <option value="1">Default sorting</option>
                            <option value="1">Default sorting</option>
                            <option value="1">Default sorting</option>
                        </select>
                    </div>
                    <div class="sorting mr-auto">
                        <select>
                            <option value="1">Show 12</option>
                            <option value="1">Show 12</option>
                            <option value="1">Show 12</option>
                        </select>
                    </div>

                </div>
                <!-- End Filter Bar -->
                <!-- Start Best Seller -->
                <section class="lattest-product-area pb-40 category-list">
                    <div class="row">
                        <!-- single product -->
                       <!-- single product -->
<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-lg-4 col-md-6">
        <div class="single-product">
            <img src="<?php echo e(asset('/storage/' . $product->image)); ?>" alt="<?php echo e($product->image); ?>">
            <div class="product-details">
                <h6><?php echo e($product->name_product); ?></h6>
                <div class="price">
                    <h6><?php echo e(number_format($product->price, 0, ',', '.')); ?> VNĐ</h6>
                </div>
                <div class="prd-bottom">

                    
                    <form action="<?php echo e(route('cart.addAjax')); ?>" method="POST" class="social-info" style="display: inline-block;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->id_product); ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" style="border: none; background: none;">
                            <span class="ti-bag"></span>
                            <p class="hover-text">Thêm vào giỏ</p>
                        </button>
                    </form>

                    
                    <a href="#" class="social-info">
                        <span class="lnr lnr-heart"></span>
                        <p class="hover-text">Yêu thích</p>
                    </a>

                    
                    <a href="#" class="social-info">
                        <span class="lnr lnr-sync"></span>
                        <p class="hover-text">So sánh</p>
                    </a>

                    
                    <a href="<?php echo e(route('client.product.show', $product->id_product)); ?>" class="social-info">
                        <span class="lnr lnr-move"></span>
                        <p class="hover-text">Xem chi tiết</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                    </div>
                    <?php if($products->hasPages()): ?>
                        <div class="mt-3 ">
                            <?php echo $products->links('pagination::bootstrap-5'); ?>

                        </div>
                    <?php endif; ?>

                </section>

                <!-- End Best Seller -->
                <!-- Start Filter Bar -->

                <!-- End Filter Bar -->
            </div>

        </div>
    </div>

    <!-- Start related-product Area -->
    
    <!-- End related-product Area -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/client/pages/products.blade.php ENDPATH**/ ?>