<?php $__env->startSection('title', 'Sản Phẩm'); ?>
<?php $__env->startSection('content'); ?>

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Cửa Hàng</h1>
                    <nav class="d-flex align-items-center">
                        <a href="#">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                        <a href="#">Cửa hàng</a>

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
        <li class="main-nav-list">
            <a href="<?php echo e(route('products', ['category' => $category->id_category])); ?>">
                <?php echo e($category->name_category); ?>

            </a>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
                </div>
                <div class="sidebar-filter mt-50 ">
                    <div class="top-filter-head ">Lọc</div>

      <div class="common-filter">
    <div class="head">Size</div>
    <div class="d-flex flex-wrap gap-2">
        <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('products', ['size' => $size->name])); ?>"
               class="btn btn-outline-dark size-square <?php echo e(request('size') == $size->name ? 'active' : ''); ?>">
                <?php echo e($size->name); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
                    <div class="common-filter mb-5">
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
                    
                            
                        
                    <div class="sorting mr-auto">
                        
                            
                        
                    </div>

                </div>
                <!-- End Filter Bar -->
                <!-- Start Best Seller -->
                <section class="lattest-product-area pb-40 category-list">
                    <div class="row">
                        <!-- single product -->
                         <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-lg-4 col-md-6">
                                <figure class="single-product">
                                    <div style="overflow: hidden; display: flex; justify-content: center; align-items: center; height: 250px;">
                                        <img style="height: 100%; width: auto" src="<?php echo e(asset('/storage/' . $product->image)); ?>" alt="<?php echo e($product->image); ?>">
                                    </div>
                                    <figcaption class="product-details" stype="">
                                        <h6><?php echo e($product->name_product); ?></h6>
                                       <?php
    $minPrice = $product->variants->min('price');
    $maxPrice = $product->variants->max('price');
?>

<div class="price">
    <?php if($minPrice === null): ?>
        <h6>Đang cập nhật</h6>
    <?php elseif($minPrice == $maxPrice): ?>
        <h6><?php echo e(number_format($minPrice, 0, ',', '.')); ?> VNĐ</h6>
    <?php else: ?>
        <h6><?php echo e(number_format($minPrice, 0, ',', '.')); ?> – <?php echo e(number_format($maxPrice, 0, ',', '.')); ?> VNĐ</h6>
    <?php endif; ?>
</div>
                                        <div class="prd-bottom">

                                            <a href="<?php echo e(route('client.product.show', $product->id_product)); ?>"
                                                class="social-info">
                                                <span class="lnr lnr-move"></span>
                                                <p class="hover-text">Xem chi tiết</p>
                                            </a>

                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp.</p>
            </div>
        <?php endif; ?>


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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\DATN-WD105\resources\views/client/pages/products.blade.php ENDPATH**/ ?>