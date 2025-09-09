<?php $__env->startSection('title', 'Sản Phẩm'); ?>
<?php $__env->startSection('content'); ?>

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Cửa Hàng</h1>
                    <nav class="d-flex align-items-center">
                        <a href="<?php echo e(route('home')); ?>">Trang chủ<span class="lnr lnr-arrow-right"></span></a>
                        <a href="<?php echo e(route('products')); ?>">Cửa hàng</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-5">
                 <form method="GET" action="<?php echo e(route('products')); ?>">
                <div class="sidebar-categories">
                    <div class="head">Danh mục sản phẩm</div>
                    <ul>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <input type="checkbox" name="category[]" value="<?php echo e($category->id_category); ?>"
                               <?php echo e(in_array($category->id_category, (array) request('category')) ? 'checked' : ''); ?>>
                        <label><?php echo e($category->name_category); ?></label>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
                </div>
                <div class="sidebar-filter mt-50">
                    <div class="top-filter-head">Lọc</div>
                    <div class="common-filter">
                        <div class="head">Size</div>
                        <ul>
                <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <input type="checkbox" name="size[]" value="<?php echo e($size->name); ?>"
                               <?php echo e(in_array($size->name, (array) request('size')) ? 'checked' : ''); ?>>
                        <label><?php echo e($size->name); ?></label>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
                    </div>
                    <div class="common-filter">
                        <div class="head">Thương hiệu</div>
                         <ul>
                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <input type="checkbox" name="brand[]" value="<?php echo e($brand->id_brand); ?>"
                               <?php echo e(in_array($brand->id_brand, (array) request('brand')) ? 'checked' : ''); ?>>
                        <label><?php echo e($brand->name); ?></label>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
                    </div>
                    <div class="common-filter mb-5">
                        <div class="head">Giá</div>
                        <ul>
                <li><input type="checkbox" name="price[]" value="under_500000" <?php echo e(in_array('under_500000', (array) request('price')) ? 'checked' : ''); ?>> <label>Dưới 500,000₫</label></li>
                <li><input type="checkbox" name="price[]" value="500000_1000000" <?php echo e(in_array('500000_1000000', (array) request('price')) ? 'checked' : ''); ?>> <label>500,000₫ - 1,000,000₫</label></li>
                <li><input type="checkbox" name="price[]" value="1000000_2000000" <?php echo e(in_array('1000000_2000000', (array) request('price')) ? 'checked' : ''); ?>> <label>1,000,000₫ - 2,000,000₫</label></li>
                <li><input type="checkbox" name="price[]" value="2000000_3000000" <?php echo e(in_array('2000000_3000000', (array) request('price')) ? 'checked' : ''); ?>> <label>2,000,000₫ - 3,000,000₫</label></li>
                <li><input type="checkbox" name="price[]" value="over_3000000" <?php echo e(in_array('over_3000000', (array) request('price')) ? 'checked' : ''); ?>> <label>Trên 3,000,000₫</label></li>
            </ul>
                    </div>
                </div>
                 <button type="submit" class="btn btn-dark w-100 mt-3 mb-5">Lọc</button>
    </form>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-7">
                <!-- Start Filter Bar -->
                
                <!-- End Filter Bar -->
                <!-- Start Best Seller -->
                <section class="lattest-product-area pb-40 category-list">
                    <div class="row">
                        <!-- single product -->
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-lg-4 col-md-6">
                                <figure class="single-product">
                                    <div style="overflow: hidden; display: flex; justify-content: center; align-items: center; height: 250px;">
                                        <img style="height: 100%; width: auto" src="<?php echo e(asset('/storage/' . $product->image)); ?>"
                                             alt="<?php echo e($product->image); ?>">
                                    </div>
                                    <figcaption class="product-details" style="">
                                        <h6><?php echo e($product->name_product); ?></h6>
                                        <?php
                                            $sale = $product->advice_product;
                                            $now = \Carbon\Carbon::now();
                                            $start = \Carbon\Carbon::parse($sale->start_date ?? '')->startOfDay();
                                            $end = \Carbon\Carbon::parse($sale->end_date ?? '')->endOfDay();
 $minPrice = $product->variants->min('price');
                                            $maxPrice = $product->variants->max('price');
                                               if ($sale && $sale->status === "on" && $now->between($start, $end)) {
        $discount = $sale->value / 100;
        $minPrice = $minPrice - ($minPrice * $discount);
        $maxPrice = $maxPrice - ($maxPrice * $discount);
    }
                                        ?>
                                        <?php if(
                                            $sale &&
                                            $sale->status === "on" && $now->between($start, $end)
                                        ): ?>
                                            <div style="
                                                position: absolute;
                                                top: 40px;
                                                left: 30px;
                                                background: linear-gradient(135deg, #ff7e00, #ffb400);
                                                color: white;
                                                padding: 5px 8px;
                                                border-radius: 5px;
                                                font-weight: bold;
                                                font-size: 14px;
                                                z-index: 10;
                                                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                                            ">
                                                -<?php echo e($product->advice_product->value); ?>%
                                            </div>
                                        <?php endif; ?>
                                        <div class="price">
                                            <?php if($minPrice === null): ?>
                                                <h6>Hết hàng!</h6>
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
                           <div class="row">
        <div class="col-12 text-center my-5">
            <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp.</p>
        </div>
    </div>
                        <?php endif; ?>
                    </div>
                    <?php if($products->hasPages()): ?>
                        <div class="mt-3">
                            <?php echo $products->links('pagination::bootstrap-5'); ?>

                        </div>
                    <?php endif; ?>
                </section>
                <!-- End Best Seller -->
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* CSS cho Filter Form */
    .sidebar-filter .common-filter {
        margin-bottom: 20px;
    }

    .sidebar-filter .head {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .size-square {
        padding: 5px 10px;
        margin: 5px 0;
        text-align: center;
        border-radius: 4px;
    }

    .size-square.active {
        background-color: #f97316;
        color: white;
        border-color: #f97316;
    }

    .size-square:hover {
        background-color: #e65c00;
        color: white;
        text-decoration: none;
    }

    .filter-bar .sorting {
        margin-right: auto;
    }

    .filter-bar .form-control {
        width: 200px;
        display: inline-block;
    }

    .filter-bar .btn-outline-secondary {
        margin-left: 5px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.client_home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\DATN-WD105\resources\views/client/pages/products.blade.php ENDPATH**/ ?>