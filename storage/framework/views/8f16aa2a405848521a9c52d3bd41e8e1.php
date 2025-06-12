<?php $__env->startSection('title', 'Quản lý Danh mục sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách album_product</h3>
        </div>

       <div class="card-header d-flex justify-content-between align-items-center">
        <?php
            $id = basename(request()->url());
        ?>
        <a href="<?php echo e(route('Ablum_products.create', ['id' => $id])); ?>" class="btn btn-primary">
            Thêm mới
        </a>
    </div>


        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ma san pham</th>
                        <th>Anh san pham</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $album_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $album_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($album_product->id_album_product); ?></td>
                            <td><?php echo e($album_product->product_id); ?></td>
                            <td><img src="<?php echo e(asset('/storage/'.$album_product->image)); ?>" alt="<?php echo e($album_product->image); ?>" width="50px" height="50px"></td>
                            <td>
                                <form action="<?php echo e(route('Ablum_products.destroy', $album_product->id_album_product)); ?>" method="POST"
                                    style="display:inline-block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button onclick="return confirm('Bạn có chắc muốn xóa?')"
                                        class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\DATN-WD105\resources\views/admin/Album_product/index.blade.php ENDPATH**/ ?>