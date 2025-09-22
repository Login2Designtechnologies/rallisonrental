<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tenant Notices')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Tenant Notices')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border w-100">
  <div class="card-body default-card">
    <div class="row justify-content-center">
      <div class="col-12">

        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
          <div>
            <h3 class="mb-1">Tenant Notices</h3>
            <p class="text-muted mb-0">All important updates & reminders from management</p>
          </div>
          <!-- Buttons -->
         
        </div>


        <div class="row g-4">
          <!-- Notice Card -->
          <div class="col-12 col-md-6">
            <div class="card notice-card p-3 mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-1">Rent Payment Reminder</h5>
                <span class="badge bg-warning">Pending</span>
              </div>
              <p class="text-muted mb-2">
                Your rent for <strong>September 2025</strong> is due on <strong>5th Sept</strong>.
              </p>
              <div class="d-flex justify-content-between">
                <small class="text-muted"><i class="bi bi-calendar"></i> Sent: Aug 25, 2025</small>
                <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
              </div>
            </div>
          </div>

          <!-- Notice Card -->
          <div class="col-12 col-md-6">
            <div class="card notice-card p-3 mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-1">Maintenance Work</h5>
                <span class="badge bg-info">Info</span>
              </div>
              <p class="text-muted mb-2">
                Scheduled maintenance of water supply on <strong>10th Sept, 9 AM - 12 PM</strong>.
              </p>
              <div class="d-flex justify-content-between">
                <small class="text-muted"><i class="bi bi-calendar"></i> Sent: Sept 1, 2025</small>
                <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
              </div>
            </div>
          </div>

          <!-- Notice Card -->
          <div class="col-12 col-md-6">
            <div class="card notice-card p-3 mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-1">Lease Renewal</h5>
                <span class="badge bg-danger">Action Required</span>
              </div>
              <p class="text-muted mb-2">
                Your lease is expiring on <strong>Jan 14, 2025</strong>. Please confirm renewal decision.
              </p>
              <div class="d-flex justify-content-between">
                <small class="text-muted"><i class="bi bi-calendar"></i> Sent: Aug 20, 2025</small>
                <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Respond</a>
              </div>
            </div>
          </div>
        </div>




      </div>
    </div>
  </div>
</div>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/tenant_dashboard/tenant-notices.blade.php ENDPATH**/ ?>