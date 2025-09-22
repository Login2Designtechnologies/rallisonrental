<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Late Fee')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Late Fee')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form action="" id="" class="search-form">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="form-group d-flex align-items-center">
                            <div class="search-button">
                                <input type="text" id="tableFilter" class="form-control" placeholder="Search by name...">
                                <i class="ti ti-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 d-flex align-items-center justify-content-end">
                        

                        <a href="<?php echo e(url('add-fee')); ?>" class="btn btn-secondary text-white"><i class="ti ti-circle-plus align-text-bottom"></i> Add New</a>
                    </div>
                </div>
            </form>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-theme">
                    <tr>
                    <th>Property Name</th>
                    <th>Tenant Name</th>
                    <th>Payment Due Date</th>
                    <th>Actual Payment Date</th>
                    <th>Fees Amount ($)</th>
                    <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                 <tbody>
                    <tr>
                        <td>Property 1</td>
                        <td>John Doe</td>
                        <td>08-01-25</td>
                        <td>08-05-25</td>
                        <td>$300</td>
                        <td>
                            <a href="<?php echo e(url('edit-late-fee')); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Send Invoice">
                                <i class="ti ti-file-invoice"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Property 2</td>
                        <td>Mary Smith</td>
                        <td>08-03-25</td>
                        <td>08-03-25</td>
                        <td>$200</td>
                        <td>
                            <a href="<?php echo e(url('edit-late-fee')); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Send Invoice">
                                <i class="ti ti-file-invoice"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Property 3</td>
                        <td>Robert Brown</td>
                        <td>07-30-25</td>
                        <td>08-02-25</td>
                        <td>$500</td>
                        <td>
                            <a href="<?php echo e(url('edit-late-fee')); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Send Invoice">
                                <i class="ti ti-file-invoice"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>

            </table>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/dodgerblue-lapwing-476569.hostingersite.com/public_html/resources/views/dashbaordpage/late-fees.blade.php ENDPATH**/ ?>