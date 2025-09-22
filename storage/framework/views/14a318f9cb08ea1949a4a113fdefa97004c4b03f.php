<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Payments & Account Summary')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Payments & Account Summary')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <div class="row">
            <!-- Select Property -->
            <div class="col-md-6">
                <label for="property" class="form-label fw-bold">Select Property</label>
                <select id="property" class="form-control form-select">
                    <option value="">-- Select --</option>
                    <option value="property1">Property 1</option>
                    <option value="property2">Property 2</option>
                </select>
            </div>

            <!-- Select Module -->
            <div class="col-md-6">
                <label for="company" class="form-label fw-bold">Select Module</label>
                <select id="company" class="form-control form-select">
                    <option value="">-- Select --</option>
                    <option value="property_view">View Property</option>
                    <option value="tenant_view">View Tenant</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Views -->
<div class="card card-view d-none">
    <div class="card-body">
        <!-- Property View -->
        <div class="property_view d-none view-select">
            <h4 class="ttl">Property Name: Property 1</h4>
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-custom radius-40 bg-1 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avtar bg-light-secondary">
                                        <i class="ti ti-ticket f-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1">How Many Days Late : 9</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">Pay Status : <span class="badge bg-warning">Pending</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card bg-custom radius-40 bg-2 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avtar bg-light-warning">
                                        <i class="ti ti-3d-cube-sphere f-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1">Total Unit</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">5</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card bg-custom radius-40 bg-3 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avtar bg-light-primary">
                                        <i class="ti ti-file-invoice f-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1">Total Invoice</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">$<span class="count">0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card bg-custom radius-40 bg-4 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avtar bg-light-danger">
                                        <i class="ti ti-exposure f-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1">Total Expense</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">$<span class="count">0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tenant View -->
        <div class="tenant_view d-none view-select">
            <h4 class="ttl">View Tenant</h4>
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="card bg-custom radius-40 bg-1 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avtar bg-light-secondary">
                                        <i class="ti ti-ticket f-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1">Amount</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">9</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card bg-custom radius-40 bg-2 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avtar bg-light-warning">
                                        <i class="ti ti-3d-cube-sphere f-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1">Current Amount Due</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">5</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card bg-custom radius-40 bg-3 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avtar bg-light-primary">
                                        <i class="ti ti-file-invoice f-24"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1">Past Amount Due</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">$<span class="count">0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
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
                            <div class="form-group d-flex align-items-center">
                                <label for="filterDate" class="me-2 text-nowrap">Filter:</label>
                                <input type="date" id="filterDate" class="form-control form-control-sm">
                            </div>

                            <button class="btn btn-sm btn-secondary text-nowrap ms-2">Export PDF</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="custom-bg-table">
                        <thead class="table-theme text-center">
                            <tr>
                                <th>Invoice No.</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Charge</th>
                                <th>Payment</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <td>INV-101</td>
                                <td>09-01-25</td>
                                <td>Cement Supply</td>
                                <td>$5,000</td>
                                <td>$2,500</td>
                                <td>$2,500</td>
                            </tr>
                            <tr>
                                <td>INV-102</td>
                                <td>09-03-25</td>
                                <td>Steel Rods</td>
                                <td>$12,000</td>
                                <td>$6,000</td>
                                <td>$6,000</td>
                            </tr>
                            <tr>
                                <td>INV-103</td>
                                <td>09-05-25</td>
                                <td>Bricks Delivery</td>
                                <td>$3,500</td>
                                <td>$3,500</td>
                                <td>$0</td>
                            </tr>
                            <tr>
                                <td>INV-104</td>
                                <td>09-08-25</td>
                                <td>Labor Charges</td>
                                <td>$7,000</td>
                                <td>$4,000</td>
                                <td>$3,000</td>
                            </tr>
                            <tr>
                                <td>INV-105</td>
                                <td>09-10-25</td>
                                <td>Sand Supply</td>
                                <td>$2,800</td>
                                <td>$1,500</td>
                                <td>$1,300</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    document.getElementById("company").addEventListener("change", function () {
        let moduleValue = this.value;
        let propertyValue = document.getElementById("property").value;

        // Check if property is selected
        if (!propertyValue) {
            alert("⚠️ Please select a property first!");
            this.value = ""; // reset module selection
            return;
        }

        // Hide all views
        document.querySelectorAll(".view-select").forEach(el => el.classList.add("d-none"));
        document.querySelector(".card-view").classList.add("d-none");

        // Show selected view + parent card
        if (moduleValue) {
            document.querySelector(".card-view").classList.remove("d-none");
            document.querySelector("." + moduleValue).classList.remove("d-none");
        }
    });
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/dashbaordpage/payments.blade.php ENDPATH**/ ?>