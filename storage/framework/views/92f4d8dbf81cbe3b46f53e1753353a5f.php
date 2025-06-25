<?php $__env->startSection('title', 'Sửa sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa sản phẩm</h3>
    </div>
    <div class="card-body">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('products.update',$product->id_product)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
              <?php echo method_field('PUT'); ?>
            <div class="form-group mb-3">
                <label for="name_product">Tên Sản Phẩm:</label>
                <input type="text" name="name_product" id="name_product" class="form-control" value="<?php echo e(old('name_product',$product->name_product)); ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="price">Giá:</label>
                <input type="number" name="price" id="price" class="form-control" value="<?php echo e(old('price',$product->price)); ?>" required min="0">
            </div>

            <div class="form-group mb-3">
                <label for="category_id">Danh Mục:</label>
                <select name="category_id" id="category_id" class="form-control form-select" required>

                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id_category); ?>" <?php echo e(old('category_id', $product->category_id) == $category->id_category ? 'selected' : ''); ?>>
                            <?php echo e($category->name_category); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="description">Mô Tả:</label>
                <textarea name="description" id="description" class="form-control" rows="5"><?php echo e(old('description',$product->description)); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Ảnh sản phẩm</label> <br>
                <img src="<?php echo e(asset('/storage/'.$product->image)); ?>" alt="<?php echo e($product->image); ?>" width="50px" height="50px">
                <input type="file" name="image" id="image" class="form-control" value="<?php echo e(old('description',$product->image)); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Lưu Sản Phẩm</button>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\DATN-WD105\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>