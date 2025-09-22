<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Payment Section')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Payment Section')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <div class="row g-4">

            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-3">
                    <h2 class="h4 fw-semibold mb-0">Overview</h2>
                </div>

                <div class="row g-4">
                    <!-- Next Payment Due -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-primary border-opacity-25 position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center pb-2">
                                <h5 class="card-title fw-medium mb-0">Next Payment Due</h5>
                                <i class="bi bi-calendar text-primary fs-3"></i>
                            </div>
                            <div class="card-body position-relative">
                                <div class="fs-2 fw-bold">Sep 10, 2025</div>
                                <p class="text-muted small mb-2">3 days remaining</p>
                                <span class="badge bg-warning text-dark">Due Soon</span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Payment -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-success border-opacity-25 position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-success bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center pb-2">
                                <h5 class="card-title fw-medium mb-0">Next Payment</h5>
                                <i class="bi bi-currency-dollar text-success fs-3"></i>
                            </div>
                            <div class="card-body position-relative">
                                <div class="fs-2 fw-bold">$1,250.00</div>
                                <p class="text-muted small mb-2">Monthly rent</p>
                                <button type="button" class="btn btn-primary btn-sm fs-6">Pay Now</button>
                            </div>
                        </div>
                    </div>

                    <!-- Outstanding -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-danger border-opacity-25 position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-danger bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center pb-2">
                                <h5 class="card-title fw-medium mb-0">Outstanding</h5>
                                <i class="bi bi-exclamation-triangle text-danger fs-3"></i>
                            </div>
                            <div class="card-body position-relative">
                                <div class="fs-2 fw-bold text-danger">$75.00</div>
                                <p class="text-muted small mb-2">Late fees included</p>
                                <span class="badge bg-danger">Overdue</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-secondary border-opacity-25 position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-secondary bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center pb-2">
                                <h5 class="card-title fw-medium mb-0">Payment Method</h5>
                                <i class="bi bi-credit-card text-muted fs-3"></i>
                            </div>
                            <div class="card-body position-relative">
                                <div class="fs-4 fw-bold">•••• 4532</div>
                                <p class="text-muted small mb-2">Expires 12/26</p>
                                <button type="button" class="btn btn-outline-secondary btn-sm fs-6">Update</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
           
            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-3">
                    <h2 class="h4 fw-semibold mb-0">
                        
                    </h2>
                </div>
                <div class="card h-100">
                    <div class="bg-transparent border-0 d-flex justify-content-between align-items-start">
                        <div data-component-content="%7B%7D">
                            <h5 class="card-title mb-2">
                                Payment History
                            </h5>
                            <p class="text-muted small mb-0">
                                View all your rent payments and transactions
                            </p>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-download me-2"></i>
                            Export
                        </button>
                    </div>
                    <div class="mt-3">
                        <div class="table-responsive border rounded">
                            <table class="table table-hover mb-0">
                                <thead class="bg-dark">
                                    <tr>
                                        <th class="text-white py-3 px-4">MONTH</th>
                                        <th class="text-white py-3 px-4">RENT</th>
                                        <th class="text-white py-3 px-4">SECURITY</th>
                                        <th class="text-white py-3 px-4">LAST MONTH RENT</th>
                                        <th class="text-white py-3 px-4">AMENITIES</th>
                                        <th class="text-white py-3 px-4">STATUS</th>
                                        <th class="text-white py-3 px-4">ACTION</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td class="fw-medium py-3 px-4">April 2025</td>
                                        <td class="fw-semibold py-3 px-4">$1,500.00</td>
                                        <td class="py-3 px-4">$300.00</td>
                                        <td class="py-3 px-4">$1,500.00</td>
                                        <td class="py-3 px-4">$110.00</td>
                                        <td class="py-3 px-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle btn-warning" type="button" data-bs-toggle="dropdown" style="min-width: 100px;">
                                                    Pending
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">Paid</a></li>
                                                    <li><a class="dropdown-item" href="#">Pending</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium py-3 px-4">April 2025</td>
                                        <td class="fw-semibold py-3 px-4">$1,500.00</td>
                                        <td class="py-3 px-4">$300.00</td>
                                        <td class="py-3 px-4">$1,500.00</td>
                                        <td class="py-3 px-4">$110.00</td>
                                        <td class="py-3 px-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle btn-warning" type="button" data-bs-toggle="dropdown" style="min-width: 100px;">
                                                    Pending
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">Paid</a></li>
                                                    <li><a class="dropdown-item" href="#">Pending</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="table-light">
                                        <td class="fw-medium py-3 px-4">May 2025</td>
                                        <td class="fw-semibold py-3 px-4">$1,500.00</td>
                                        <td class="py-3 px-4">$300.00</td>
                                        <td class="py-3 px-4">$1,500.00</td>
                                        <td class="py-3 px-4">$110.00</td>
                                        <td class="py-3 px-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle btn-success" type="button" data-bs-toggle="dropdown" style="min-width: 100px;">
                                                    Paid
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">Paid</a></li>
                                                    <li><a class="dropdown-item" href="#">Pending</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="fw-medium py-3 px-4">May 2025</td>
                                        <td class="fw-semibold py-3 px-4">$1,500.00</td>
                                        <td class="py-3 px-4">$300.00</td>
                                        <td class="py-3 px-4">-</td>
                                        <td class="py-3 px-4">$110.00</td>
                                        <td class="py-3 px-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle btn-success" type="button" data-bs-toggle="dropdown" style="min-width: 100px;">
                                                    Paid
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">Paid</a></li>
                                                    <li><a class="dropdown-item" href="#">Pending</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/tenant_dashboard/payment-section.blade.php ENDPATH**/ ?>