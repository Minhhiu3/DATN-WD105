<?php $__env->startSection('title', 'Quản lý Sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách sản phẩm</h3>
        </div>

        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">Thêm Sản Phẩm Mới</a>

        </div>

        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Ảnh Sản Phẩm</th> 
                        <th>Tên Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Danh Mục</th>
                        <th>Biến thể</th>
                        <th>Ablum Ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($product->id_product); ?></td>
                            <td><img src="<?php echo e(asset('/storage/'.$product->image)); ?>" alt="<?php echo e($product->image); ?>" width="50px" height="50px"></td>

                            <td><?php echo e($product->name_product); ?></td>
                            <td><?php echo e(number_format($product->price, 0, ',', '.')); ?> VND</td>
                            <td><?php echo e($product->category->name_category ?? 'N/A'); ?></td>
                            <th><a href="<?php echo e(route('variants.show', $product->id_product)); ?>" class="btn btn-info btn-sm">Xem</a></th>
                            <th><a href="<?php echo e(route('Ablum_products.show', $product->id_product)); ?>" class="btn btn-info btn-sm">Xem</a></th>
                            <td>
                                <a href="<?php echo e(route('products.show', $product->id_product)); ?>" class="btn btn-info btn-sm">Xem</a>
                                <a href="<?php echo e(route('products.edit', $product->id_product)); ?>"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <form action="<?php echo e(route('products.destroy', $product->id_product)); ?>" method="POST"
                                    style="display:inline-block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button onclick="return confirm('Bạn có chắc muốn chuyển sản phẩm này vào thùng rác?')"
                                        type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">Không có sản phẩm nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
         <?php if($products->hasPages()): ?>
    <div class="d-flex justify-content-center mt-3 ">
         <?php echo $products->links('pagination::bootstrap-5'); ?>

    </div>
<?php endif; ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_New\DATN-WD105\resources\views/admin/products/index.blade.php ENDPATH**/ ?>