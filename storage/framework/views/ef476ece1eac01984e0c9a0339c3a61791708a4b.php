<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Payment & Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Payment & Invoice')); ?></li>
    
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
                        <!-- <div class="table-responsive border rounded">
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
                        </div> -->
                        <!-- Tabs -->
                    
                            <!-- Tabs -->
                            <ul class="nav nav-pills mb-3 gap-2" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-rental-tab" data-bs-toggle="pill" data-bs-target="#pills-rental" type="button" role="tab">Rental</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-utilities-tab" data-bs-toggle="pill" data-bs-target="#pills-utilities" type="button" role="tab">Utilities</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-latefee-tab" data-bs-toggle="pill" data-bs-target="#pills-latefee" type="button" role="tab">Late fee</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-others-tab" data-bs-toggle="pill" data-bs-target="#pills-others" type="button" role="tab">Others</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab">ALL</button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="pills-tabContent">

                                <!-- Rental Tab -->
                                <div class="tab-pane fade show active" id="pills-rental" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle text-center">
                                        <thead class="table-dark">
                                            <tr>
                                            <th>MONTH</th>
                                            <th>RENT</th>
                                            <th>SECURITY</th>
                                            <th>LAST MONTH RENT</th>
                                            <th>AMENITIES</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td>April 2025</td>
                                            <td>$1,500.00</td>
                                            <td>$300.00</td>
                                            <td>$1,500.00</td>
                                            <td>$110.00</td>
                                            <td>
                                                <div class="dropdown">
                                                <button class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown">Pending</button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">Pending</a></li>
                                                    <li><a class="dropdown-item" href="#">Paid</a></li>
                                                </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></button>
                                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Download"><i class="bi bi-download"></i></button>
                                            </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Utilities Tab -->
                                <div class="tab-pane fade" id="pills-utilities" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0" id="custom-bg-table">
                                        <thead class="table-theme text-center">
                                            <tr>
                                            <th>Month</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td>April 2025</td>
                                                <td>₹10,000</td>
                                                <td>
                                                    <span class="badge bg-success">Delivered</span>
                                                    <!-- <span class="badge bg-warning">Pending</span> -->
                                                </td>
                                                <td>
                                                    <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                                                    <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td>May 2025</td>
                                                <td>₹8,000</td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                </td>
                                                <td>
                                                    <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                                                    <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Late Fee Tab -->
                                <div class="tab-pane fade" id="pills-latefee" role="tabpanel">
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
                                                    <a href="https://whitesmoke-jackal-127066.hostingersite.com/view-invoice"><i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i></a>
                                                    <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>Property 2</td>
                                                    <td>Mary Smith</td>
                                                    <td>08-03-25</td>
                                                    <td>08-03-25</td>
                                                    <td>$200</td>
                                                    <td>
                                                    <a href="https://whitesmoke-jackal-127066.hostingersite.com/view-invoice"><i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i></a>
                                                    <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>Property 3</td>
                                                    <td>Robert Brown</td>
                                                    <td>07-30-25</td>
                                                    <td>08-02-25</td>
                                                    <td>$500</td>
                                                    <td>
                                                    <a href="https://whitesmoke-jackal-127066.hostingersite.com/view-invoice"><i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i></a>
                                                    <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                                                </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                                <!-- Others Tab -->
                                <div class="tab-pane fade" id="pills-others" role="tabpanel">
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
                                                    <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                                                    <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>Los Angeles - Sunset Villa</td>
                                                    <td>Emily Davis</td>
                                                    <td>INV-1002</td>
                                                    <td>2025-09-06</td>
                                                    <td>$1,500</td>
                                                    <td>
                                                    <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                                                    <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                                                </td>
                                                </tr>
                                                <tr>
                                                    <td>Chicago - Lakeview Condo</td>
                                                    <td>Robert Brown</td>
                                                    <td>INV-1003</td>
                                                    <td>2025-09-07</td>
                                                    <td>$1,800</td>
                                                <td>
                                                    <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                                                    <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                                                </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- ALL Tab (combined data) -->
                                <div class="tab-pane fade" id="pills-all" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle text-center">
                                    <thead class="table-dark">
                                        <tr>
                                        <th>TYPE</th>
                                        <th>MONTH</th>
                                        <th>DETAILS</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Rental -->
                                        <tr>
                                        <td><span class="badge bg-primary">Rental</span></td>
                                        <td>April 2025</td>
                                        <td>Rent: $1,500.00 | Security: $300.00 | Last Rent: $1,500.00 | Amenities: $110.00</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Download"><i class="bi bi-download"></i></button>
                                        </td>
                                        </tr>
                                        <!-- Utilities -->
                                        <tr>
                                        <td><span class="badge bg-info">Utilities</span></td>
                                        <td>April 2025</td>
                                        <td>Water: $50.00 | Electricity: $90.00 | Gas: $30.00</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Download"><i class="bi bi-download"></i></button>
                                        </td>
                                        </tr>
                                        <!-- Late Fee -->
                                        <tr>
                                        <td><span class="badge bg-danger">Late Fee</span></td>
                                        <td>May 2025</td>
                                        <td>Amount: $50.00</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Download"><i class="bi bi-download"></i></button>
                                        </td>
                                        </tr>
                                        <!-- Others -->
                                        <tr>
                                        <td><span class="badge bg-secondary">Others</span></td>
                                        <td>April 2025</td>
                                        <td>Parking Charges: $100.00</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Download"><i class="bi bi-download"></i></button>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td><span class="badge bg-secondary">Others</span></td>
                                        <td>May 2025</td>
                                        <td>Club Membership Fee: $75.00</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Download"><i class="bi bi-download"></i></button>
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
    </div>
</div>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/tenant_dashboard/payment-section.blade.php ENDPATH**/ ?>