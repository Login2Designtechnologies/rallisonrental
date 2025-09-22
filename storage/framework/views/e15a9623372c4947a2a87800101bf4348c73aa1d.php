<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('other Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('other Invoice')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form action="" id="" class="search-form">
            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="form-group d-flex align-items-center">
                        <div class="search-button">
                            <input type="text" id="tableFilter" class="form-control" placeholder="Search by name..." />
                            <i class="ti ti-search"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 d-flex align-items-center justify-content-end">
                    <a href="<?php echo e(url('add-other')); ?>" class="btn btn-secondary text-white"><i class="ti ti-circle-plus align-text-bottom"></i> Add New</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Property Name</th>
                        <th>Tenant Name</th>
                        <th>Invoice No.</th>
                        <th>Invoice Date</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example row -->
                    <tr>
                        <td>NYC - Times Square Apartment</td>
                        <td>Michael Johnson</td>
                        <td>INV-1001</td>
                        <td>2025-09-05</td>
                        <td>$1,200</td>
                        <td>
                            <a href="<?php echo e(url('edit-other-invoice')); ?>">
                                <i class="ti ti-pencil editRow fs-4" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit"></i>
                            </a>
                            <a href="<?php echo e(url('send-invoice')); ?>">
                                <i class="ti ti-send sendRow fs-4" data-bs-toggle="tooltip" aria-label="Send Invoice" data-bs-original-title="Send Invoice"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Los Angeles - Sunset Villa</td>
                        <td>Emily Davis</td>
                        <td>INV-1002</td>
                        <td>2025-09-06</td>
                        <td>$1,500</td>
                        <td>
                            <a href="<?php echo e(url('edit-other-invoice')); ?>">
                                <i class="ti ti-pencil editRow fs-4" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit"></i>
                            </a>
                            <a href="<?php echo e(url('send-invoice')); ?>">
                                <i class="ti ti-send sendRow fs-4" data-bs-toggle="tooltip" aria-label="Send Invoice" data-bs-original-title="Send Invoice"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Chicago - Lakeview Condo</td>
                        <td>Robert Brown</td>
                        <td>INV-1003</td>
                        <td>2025-09-07</td>
                        <td>$1,800</td>
                        <td>
                            <a href="<?php echo e(url('edit-other-invoice')); ?>">
                                <i class="ti ti-pencil editRow fs-4" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit"></i>
                            </a>
                            <a href="<?php echo e(url('send-invoice')); ?>">
                                <i class="ti ti-send sendRow fs-4" data-bs-toggle="tooltip" aria-label="Send Invoice" data-bs-original-title="Send Invoice"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/dodgerblue-lapwing-476569.hostingersite.com/public_html/resources/views/dashbaordpage/other.blade.php ENDPATH**/ ?>