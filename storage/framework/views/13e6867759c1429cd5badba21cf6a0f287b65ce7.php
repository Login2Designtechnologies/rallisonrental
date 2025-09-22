<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Ticket List')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Ticket List')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
       
        <div>
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
                        

                        <a href="<?php echo e(url('add-tenant-ticket')); ?>" class="btn btn-secondary text-white"><i class="ti ti-circle-plus align-text-bottom"></i> Create New Ticket</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle" data-sorting="true">
            <thead class="table-light">
                <tr>
                <th>Subject</th>
                <th data-breakpoints="lg" data-type="number">Ticket ID</th>
                <th data-breakpoints="lg" data-type="number">Sending Date</th>
                <th data-breakpoints="lg" data-type="number">User</th>
                <th data-breakpoints="lg" data-type="number">Status</th>
                <th data-breakpoints="lg" data-type="number">Last reply</th>
                <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>Password Reset</td>
                <td>1002</td>
                <td>08-25-2025</td>
                <td>Priya Singh</td>
                <td><span class="badge bg-warning text-dark">Pending</span></td>
                <td>08-25-2025</td>
                <td>
                    <div class="d-flex align-items-center gap-2 action-button">
                    
                    <a href="<?php echo e(url('tenant-view-ticket')); ?>">
                                <i class="ti ti-eye me-2 editRow fs-4" data-bs-toggle="tooltip" title="Edit"></i>
                            </a>
                    </div>
                </td>
                </tr>
                <tr>
                <td>Login Issue</td>
                <td>1003</td>
                <td>08-24-2025</td>
                <td>Amit Sharma</td>
                <td><span class="badge bg-success">Resolved</span></td>
                <td>08-24-2025</td>
                <td>
                    <div class="d-flex align-items-center gap-2 action-button">
                    <a href="<?php echo e(url('tenant-view-ticket')); ?>">
                                <i class="ti ti-eye me-2 editRow fs-4" data-bs-toggle="tooltip" title="Edit"></i>
                            </a>
                    </div>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
        
    </div>
</div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/tenant_dashboard/tenant-ticket-support.blade.php ENDPATH**/ ?>