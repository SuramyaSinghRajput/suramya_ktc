<?php $__env->startSection('settings','active menu-item-open'); ?>
<?php $__env->startSection('content'); ?>
<div class="card card-custom">

    <div class="card-body">
        <div class="mb-7">
            <div class="row align-items-center">

                <form method="POST" action="" class="w-100">
                    <?php echo e(csrf_field()); ?>

                    <div class="col-lg-9 col-xl-12">
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label>Registered Office Address</label>
                                <input type="text" name="registered_office_address" value="<?php echo e($details->registered_office_address ?? $details->registered_office_address); ?>" isrequired="required" class="form-control" placeholder="Enter Registered Office Address"> <br />
                              <input type="text" name="registered_office_address2" value="<?php echo e($details->registered_office_address2 ?? $details->registered_office_address2); ?>"  class="form-control" placeholder="Enter Registered Office Address2">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Office Address</label>
                                <input type="text" name="office_address" value="<?php echo e($details->office_address ?? $details->office_address); ?>" isrequired="required" class="form-control" placeholder="Enter Office Address"> <br />
                                <input type="text" name="office_address2" value="<?php echo e($details->office_address2 ?? $details->office_address2); ?>" class="form-control" placeholder="Enter Office Address2">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Phone Number</label>
                                <div><input type="text" name="phone_number" value="<?php echo e($details->phone_number ?? $details->phone_number); ?>" isrequired="required" class="form-control" placeholder="Enter Phone Number"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Email Address</label>
                                <div><input type="email" name="email_id" value="<?php echo e($details->email_id ?? $details->email_id); ?>" isrequired="required" class="form-control" placeholder="Enter Email Address"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>WhatsApp No</label>
                                <div>
                                    <input type="text" name="whatsapp" value="<?php echo e($details->whatsapp ?? $details->whatsapp); ?>" isrequired="required" class="form-control" placeholder="Enter WhatsApp No">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Customer Care</label>
                                <div>
                                    <input type="text" name="customer_care" value="<?php echo e($details->customer_care ?? $details->customer_care); ?>" isrequired="required" class="form-control" placeholder="Enter Customer Care">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>GST No.</label>
                                <div>
                                    <input type="hidden" name="setting_id" value="<?php echo e($details->id ?? $details->id); ?>" class="form-control">
                                    <input type="text" name="gst_number" value="<?php echo e($details->gst_number ?? $details->gst_number); ?>" class="form-control" placeholder="Enter GST No.">
                                </div>
                            </div>
                            <!-- <div class="form-group col-md-12">
                                <label>GST No.</label>
                                <div>
                                    <input type="text" name="gst_number" value="<?php echo e($details->gst_number ?? $details->gst_number); ?>"  class="form-control" placeholder="Enter GST No.">
                                </div>
                            </div> -->


                            <div class="form-group col-md-12">
                                <center><button class="btn btn-success">Update</button></center>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u791519547/domains/mrblogician.com/public_html/ktc/resources/views/admin/pages/settings/settings.blade.php ENDPATH**/ ?>