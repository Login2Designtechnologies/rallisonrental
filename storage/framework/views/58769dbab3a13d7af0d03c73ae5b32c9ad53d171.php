<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Property Details')); ?></li>
    
<?php $__env->stopSection(); ?>
<style>


        .property-dtl .property-header {
            background: rgb(30, 187, 88);
            position: relative;
            overflow: hidden;
        }

        .property-dtl .property-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

       .property-dtl .document-link:hover {
            text-decoration: underline;
        }

       .property-dtl .icon-wrapper {
            width: 50px;
            height: 50px;
            background: rgba(34, 197, 94, 0.2);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

   

</style>
<?php $__env->startSection('content'); ?>
<!--  -->
<div class="border w-100">
  <div class="property-dtl">
      <div class="row">
    
        <!-- Sidebar Navigation -->
        <div class="col-md-3 d-flex">
          <div class="card w-100">
              <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-property-tab" data-bs-toggle="pill" data-bs-target="#v-pills-property" type="button" role="tab" aria-controls="v-pills-property" aria-selected="true">
                  <i class="bi bi-building"></i> Property Info
                </button>
                <button class="nav-link" id="v-pills-lease-tab" data-bs-toggle="pill" data-bs-target="#v-pills-lease" type="button" role="tab" aria-controls="v-pills-lease" aria-selected="false">
                  <i class="bi bi-file-earmark-text"></i> Lease Agreement
                </button>
                <button class="nav-link" id="v-pills-financial-tab" data-bs-toggle="pill" data-bs-target="#v-pills-financial" type="button" role="tab" aria-controls="v-pills-financial" aria-selected="false">
                  <i class="bi bi-cash-stack"></i> Financial Info
                </button>
                <button class="nav-link" id="v-pills-status-tab" data-bs-toggle="pill" data-bs-target="#v-pills-status" type="button" role="tab" aria-controls="v-pills-status" aria-selected="false">
                  <i class="bi bi-activity"></i> Lease Status
                </button>
            </div>
          </div>
        </div>

        <!-- Tab Content -->
        <div class="col-md-9 d-flex">
          <div class="card w-100">
            <div class="card-body">
                <div class="tab-content" id="v-pills-tabContent">

                    <!-- Property Info -->
                    <div class="tab-pane fade show active" id="v-pills-property" role="tabpanel" aria-labelledby="v-pills-property-tab" tabindex="0">
                      <h3 class="mb-3"><i class="bi bi-building"></i> Property &amp; Unit Information</h3>
                      <div class="row g-3 mb-4">
                        <div class="col-md-6 d-flex">
                          <div class="card w-100">
                            <div class="card-body">
                              <div class="icon-wrapper"><i class="bi bi-building"></i></div>
                              <h6 class="text-muted">Property Name</h6>
                              <p class="mb-0 fw-bold">Sunset Gardens Apartments</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 d-flex">
                          <div class="card w-100">
                            <div class="card-body">
                              <div class="icon-wrapper"><i class="bi bi-door-open"></i></div>
                              <h6 class="text-muted">Unit Number</h6>
                              <p class="mb-0 fw-bold">Unit 24B</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="text-white p-4 rounded property-header">
                            <div class="row align-items-center">
                              <div class="col-md-8">
                                <h5 class="text-white">Full Address</h5>
                                <h2 class="h4 mb-2 text-white">Sunset Gardens Apartments - Unit 24B</h2>
                                <p class="mb-0"><i class="bi bi-geo-alt me-2"></i>123 Oak Street, Manhattan, New York 10001</p>
                              </div>
                              <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <span class="badge bg-dark"><i class="bi bi-check-circle"></i> Active Lease</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Lease Agreement -->
                    <div class="tab-pane fade" id="v-pills-lease" role="tabpanel" aria-labelledby="v-pills-lease-tab" tabindex="0">
                      <h3 class="mb-3"><i class="bi bi-file-earmark-text"></i> Lease Agreement</h3>
                      <div class="row g-3 mb-4">
                        <div class="col-md-6 d-flex">
                          <div class="card w-100">
                            <div class="card-body">
                              <div class="icon-wrapper"><i class="bi bi-calendar-range"></i></div>
                              <h6 class="text-muted">Lease Period</h6>
                              <p class="mb-0 fw-bold">January 15, 2024 - January 14, 2025</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 d-flex">
                          <div class="card w-100">
                            <div class="card-body">
                              <div class="icon-wrapper"><i class="bi bi-calendar-range"></i></div>
                              <h6 class="text-muted">Lease Document</h6>
                              <a href="#" class="d-block mb-2 text-decoration-none"><i class="bi bi-file-earmark-pdf"></i> Download Lease Agreement</a>
                              <a href="#" class="d-block text-decoration-none"><i class="bi bi-eye"></i> View Online</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Financial Info -->
                    <div class="tab-pane fade" id="v-pills-financial" role="tabpanel" aria-labelledby="v-pills-financial-tab" tabindex="0">
                      <h3 class="mb-3"><i class="bi bi-cash-stack"></i> Financial Information</h3>
                      <div class="row g-3 mb-3">
                        <div class="col-md-6 d-flex">
                          <div class="card bg-success text-white shadow-sm w-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                              <div>
                                <h5 class="ttl">Monthly Rent</h5>
                                <p class="h4 mb-0">$2,500</p>
                              </div>
                              <i class="bi bi-house-fill fs-1"></i>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 d-flex">
                          <div class="card bg-info text-white shadow-sm w-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                              <div>
                                <h5 class="ttl">Security Deposit</h5>
                                <p class="h4 mb-0">$5,000</p>
                              </div>
                              <i class="bi bi-shield-check fs-1"></i>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row g-3 mb-4">
                        <div class="col-md-4">
                          <div class="card shadow-sm">
                            <div class="card-body">
                              <div class="icon-wrapper"><i class="bi bi-check-circle"></i></div>
                              <h6 class="text-muted">Deposit Paid</h6>
                              <p class="mb-0 fw-bold text-success">$5,000</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="card shadow-sm">
                            <div class="card-body">
                              <div class="icon-wrapper"><i class="bi bi-exclamation-circle"></i></div>
                              <h6 class="text-muted">Balance Due</h6>
                              <p class="mb-0 fw-bold">$0</p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="card shadow-sm">
                            <div class="card-body">
                              <div class="icon-wrapper"><i class="bi bi-calendar-check"></i></div>
                              <h6 class="text-muted">Next Payment Due</h6>
                              <p class="mb-0 fw-bold">December 1, 2024</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Lease Status -->
                    <div class="tab-pane fade" id="v-pills-status" role="tabpanel" aria-labelledby="v-pills-status-tab" tabindex="0">
                      <h3 class="mb-3"><i class="bi bi-activity"></i> Lease Status &amp; Timeline</h3>
                      <div class="card shadow-sm w-100">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-6">
                              <h6 class="text-muted">Current Status</h6>
                              <span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>
                            </div>
                            <div class="col-6">
                              <h6 class="text-muted">Days Until Expiry</h6>
                              <p class="fw-bold mb-0">45 days</p>
                            </div>
                          </div>
                          <div class="row mt-3">
                            <div class="col-6">
                              <h6 class="text-muted">Lease Start</h6>
                              <p class="mb-0 fw-bold">January 15, 2024</p>
                            </div>
                            <div class="col-6">
                              <h6 class="text-muted">Lease End</h6>
                              <p class="mb-0 fw-bold">January 14, 2025</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
            </div>
          </div>
        </div>

      </div>
      <!--  -->
    
  </div>
</div>

<!-- ./ -->



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/tenant_dashboard/property-details.blade.php ENDPATH**/ ?>