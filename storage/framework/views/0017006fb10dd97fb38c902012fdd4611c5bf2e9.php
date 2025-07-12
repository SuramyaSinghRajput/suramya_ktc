<?php $__env->startSection('product', 'active menu-item-open'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h3 class="mb-0">📤 Export Products</h3>
                </div>
                <div class="card-body p-4">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('admin.export.products')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Fields:</label>
                            <div class="row">
                                <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4">
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

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label fw-bold">Start Date:</label>
                                <input type="date" name="start_date" class="form-control" id="start_date">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-bold">End Date:</label>
                                <input type="date" name="end_date" class="form-control" id="end_date">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-download"></i> Export Selected Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u791519547/domains/mrblogician.com/public_html/ktc/resources/views/export/product-form.blade.php ENDPATH**/ ?>