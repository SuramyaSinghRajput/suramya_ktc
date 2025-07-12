<?php $__env->startSection('product', 'active menu-item-open'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h3 class="mb-0"><i class="bi bi-box-arrow-down me-2"></i> Export Lots</h3>
                </div>
                <div class="card-body p-4">

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>

                    <form method="GET" action="<?php echo e(route('admin.lots.export')); ?>">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Fields:</label>
                            <div class="row">
                                <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fields[]" value="<?php echo e($field); ?>" id="field_<?php echo e($field); ?>">
                                            <label class="form-check-label" for="field_<?php echo e($field); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $field))); ?>

                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($status); ?>"><?php echo e(ucfirst($status)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="warehouse_id" class="form-label fw-bold">Warehouse</label>
                                <select name="warehouse_id" class="form-control">
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($warehouse->id); ?>"><?php echo e($warehouse->store); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="product_id" class="form-label fw-bold">Product</label>
                                <select name="product_id" class="form-control">
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($product->id); ?>"><?php echo e($product->common_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="from_date" class="form-label fw-bold">Start Date</label>
                                <input type="date" name="from_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="to_date" class="form-label fw-bold">End Date</label>
                                <input type="date" name="to_date" class="form-control" required>
                            </div>
                        </div>

                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-download me-2"></i> Export Selected Data
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u791519547/domains/mrblogician.com/public_html/ktc/resources/views/export/lot-form.blade.php ENDPATH**/ ?>