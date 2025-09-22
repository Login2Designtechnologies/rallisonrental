<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('View Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('view Invoice')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
    
        <div>
            <div class="invoice-box">
    <!-- Header -->
    <div class="invoice-header d-flex justify-content-between align-items-center">
      <div>
        <h2 class="invoice-title"><i class="bi bi-receipt me-2"></i> Invoice</h2>
        <p class="mb-1">Invoice #: <strong>INV-1002</strong></p>
        <p class="mb-1">Invoice Date: <strong>09-05-25</strong></p>
        <p class="mb-0">Due Date: <strong>09-12-25</strong></p>
      </div>
      <div class="text-end">
        <span class="badge badge-status">Late Payment</span>
      </div>
    </div>

    <!-- Property & Tenant -->
    <div class="row mb-4">
      <div class="col-md-6">
        <h6 class="fw-bold">Property</h6>
        <p class="mb-0">NYC - Times Square Apartment</p>
      </div>
      <div class="col-md-6 text-md-end">
        <h6 class="fw-bold">Tenant</h6>
        <p class="mb-0">Michael Johnson</p>
      </div>
    </div>

    <!-- Subject -->
    <div class="mb-4">
      <h6 class="fw-bold">Subject</h6>
      <p class="mb-0 text-danger">Late Fees Invoice (Payment not received on time)</p>
    </div>

    <!-- Invoice Items -->
    <div class="table-responsive mb-4">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th>Detail</th>
            <th class="text-end">Amount ($)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Monthly Rent</td>
            <td class="text-end">1200.00</td>
          </tr>
          <tr>
            <td>Maintenance Fee</td>
            <td class="text-end">100.00</td>
          </tr>
          <tr class="table-danger">
            <td><strong>Late Fee</strong></td>
            <td class="text-end"><strong>50.00</strong></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td class="text-end">Subtotal</td>
            <td class="text-end">$1350.00</td>
          </tr>
          <tr>
            <td class="text-end">Late Fees</td>
            <td class="text-end text-danger">$50.00</td>
          </tr>
          <tr>
            <td class="text-end fw-bold">Total Due</td>
            <td class="text-end fw-bold text-danger">$1400.00</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Footer -->
    <div class="d-flex justify-content-between">
      <div>
        <p class="mb-1"><strong>Terms:</strong> Other</p>
        <p class="mb-0"><strong>Payment Received Date:</strong> Not Paid</p>
      </div>
      <div class="text-end">
        <button class="btn btn-danger">
          <i class="bi bi-send me-1"></i> Send Late Fee Invoice
        </button>
      </div>
    </div>
  </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/tenant_dashboard/view-invoice.blade.php ENDPATH**/ ?>