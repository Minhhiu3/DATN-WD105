<?php $__env->startSection('title', 'Quản lý Danh mục sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách variant</h3>
        </div>

       <div class="card-header d-flex justify-content-between align-items-center">
        <?php
            $id = basename(request()->url());
        ?>
        <a href="<?php echo e(route('admin.variants.create', ['product_id' => $id])); ?>" class="btn btn-primary">
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
                        <th>size</th>
                        <th>gia</th>
                        <th>so luong</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($variant->id_variant); ?></td>
                            <td><?php echo e($variant->product->name_product); ?></td>
                            <td><?php echo e($variant->size->name); ?></td>
                            <td><?php echo e($variant->price); ?></td>
                            <td><?php echo e($variant->quantity); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.variants.edit', $variant->id_variant)); ?>"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <form action="<?php echo e(route('admin.variants.destroy', $variant->id_variant)); ?>" method="POST"
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_clone\DATN-WD105\resources\views/admin/variants/index.blade.php ENDPATH**/ ?>