<?php $__env->startSection('title', 'Quản lý Đơn hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Chi tiết đơn hàng</h3>
    </div>

    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

            <div class="border p-3 mb-4 rounded shadow-sm">
                
                <h5>🧍 Thông tin khách hàng</h5>
                <ul>
                    <li><strong>Tên:</strong> <?php echo e($user->name ?? 'N/A'); ?></li>
                    <li><strong>Email:</strong> <?php echo e($user->email ?? 'N/A'); ?></li>
                    <li><strong>Số điện thoại:</strong> <?php echo e($user->phone_number); ?></li>
                    <li><strong>Địa chỉ:</strong> <?php echo e($user->phone_number); ?></li>
                </ul>
                
            </div>

            <div class="border p-3 mb-4 rounded shadow-sm">
                
                <h5 class="mt-3">📦 Sản phẩm đã đặt</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th></th>
                                <th>Sản phẩm</th>
                                <th>Size</th>
                                <th>Số Lượng</th>
                                <th>Giá</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $tongTien = 0; 
                            ?>
                            <?php $__currentLoopData = $order_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $price = optional($item->variant)->price ?? 0;
                                    $quantity = $item->quantity ?? 0;
                                    $thanhTien = $price * $quantity;
                                    $tongTien += $thanhTien;
                                ?>
                                <tr>
                                    <td><?php echo e($item->variant->id_variant ?? 'Không rõ'); ?></td>
                                    <td><?php echo e($item->variant->product->name_product ?? 'Không rõ'); ?></td>
                                    <td><?php echo e($item->variant->size->name ?? 'Không rõ'); ?></td>
                                    <td><?php echo e($item->quantity ?? 'Không rõ'); ?></td>
                                    <td><?php echo e(number_format($item->variant->price ?? 0, 0, ',', '.')); ?> VND</td>
                                    <td><?php echo e(number_format($thanhTien, 0, ',', '.')); ?> VND</td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <tr class="table-success">
                                    <td colspan="5" class="text-end"><strong>Tổng tiền:</strong></td>
                                    <td><strong><?php echo e(number_format($tongTien, 0, ',', '.')); ?> VND</strong></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ACER\OneDrive\Desktop\DATN_SU2025\ShoeMart_clone\DATN-WD105\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>