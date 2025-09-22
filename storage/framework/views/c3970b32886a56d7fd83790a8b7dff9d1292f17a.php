<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Edit Fee')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('late-fees')); ?>"><?php echo e(__('Late Fee')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Edit')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form id="editForm" class="row g-3">
        <!-- Property Select -->
        <div class="col-md-6">
          <label for="propertySelect" class="form-label">Select Property</label>
          <select class="form-select" id="propertySelect" required>
            <option value="">Choose...</option>
            <option value="nyc101">NYC - Times Square Apartment</option>
            <option value="la202">Los Angeles - Sunset Villa</option>
            <option value="chicago303">Chicago - Lakeview Condo</option>
            <option value="houston404">Houston - Greenfield House</option>
          </select>
        </div>

        <!-- Tenant Select -->
        <div class="col-md-6">
          <label for="tenantSelect" class="form-label">Tenant Name</label>
          <select class="form-select" id="tenantSelect" required>
            <option value="">Select property first...</option>
          </select>
        </div>

        <!-- Actual Payment Date -->
        <div class="col-md-6">
          <label for="actualDate" class="form-label">Actual Payment Date</label>
          <input type="date" id="actualDate" class="form-control" required>
        </div>

        <!-- Payment Received Date -->
        <div class="col-md-6">
          <label for="receivedDate" class="form-label">Payment Received Date</label>
          <input type="date" id="receivedDate" class="form-control" required>
        </div>

        <!-- Fees Amount -->
        <div class="col-md-6">
          <label for="feesAmount" class="form-label">Fees Amount ($)</label>
          <input type="number" id="feesAmount" class="form-control" placeholder="0.00" min="0" required>
        </div>

        <!-- Action Buttons -->
        <div class="col-12 d-flex justify-content-end gap-2">
     
          <button type="submit" class="btn btn-primary"> Update
          </button>
        </div>
      </form>

    </div>
</div>


<script>
  // Dummy property wise tenant payment data
  const paymentData = {
    "nyc101": [
      { actual: "2025-09-01", received: "2025-09-03", tenant: "Michael Johnson", fees: 1200 },
      { actual: "2025-09-01", received: "2025-09-02", tenant: "Emily Davis", fees: 1300 }
    ],
    "la202": [
      { actual: "2025-09-02", received: "2025-09-05", tenant: "Robert Brown", fees: 1500 },
      { actual: "2025-09-02", received: "2025-09-04", tenant: "Sophia Wilson", fees: 1400 }
    ]
  };

  const tenantsData = {
    "nyc101": ["Michael Johnson", "Emily Davis"],
    "la202": ["Robert Brown", "Sophia Wilson"],
    "chicago303": ["David Miller", "Olivia Taylor"],
    "houston404": ["James Anderson", "Isabella Martinez"]
  };

  const propertySelect = document.getElementById("propertySelect");
  const tenantSelect = document.getElementById("tenantSelect");

  // Populate tenants when property is selected
  propertySelect.addEventListener("change", function() {
    const selectedProperty = this.value;
    tenantSelect.innerHTML = "";

    if (selectedProperty && tenantsData[selectedProperty]) {
      tenantSelect.disabled = false;
      tenantSelect.innerHTML = `<option value="">Choose tenant...</option>`;
      tenantsData[selectedProperty].forEach(tenant => {
        const opt = document.createElement("option");
        opt.value = tenant;
        opt.textContent = tenant;
        tenantSelect.appendChild(opt);
      });
    } else {
      tenantSelect.disabled = true;
      tenantSelect.innerHTML = `<option value="">Select property first...</option>`;
    }
  });

  // Handle form submit
  document.getElementById("editForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const property = propertySelect.value;
    const tenant = tenantSelect.value;
    const actualDate = document.getElementById("actualDate").value;
    const receivedDate = document.getElementById("receivedDate").value;
    const fees = document.getElementById("feesAmount").value;

    if (!property || !tenant || !actualDate || !receivedDate || !fees) {
      alert("⚠️ Please fill all fields");
      return;
    }

    alert(`✅ Record updated!\n
Property: ${property}\n
Tenant: ${tenant}\n
Actual Payment: ${actualDate}\n
Received: ${receivedDate}\n
Fees: $${fees}`);
    
    // Redirect back to listing page (optional)
    // window.location.href = "listing.html";
  });

  // Disable tenant dropdown initially
  tenantSelect.disabled = true;

  // 👉 Example: Auto-load data for editing (simulate record passed from listing)
  // In real app, you will pass record ID via query params
  const editRecord = { property: "nyc101", tenant: "Emily Davis", actual: "2025-09-01", received: "2025-09-02", fees: 1300 };

  // Pre-fill form
  window.addEventListener("DOMContentLoaded", () => {
    propertySelect.value = editRecord.property;
    propertySelect.dispatchEvent(new Event("change")); // load tenants
    setTimeout(() => {
      tenantSelect.value = editRecord.tenant;
    }, 100);
    document.getElementById("actualDate").value = editRecord.actual;
    document.getElementById("receivedDate").value = editRecord.received;
    document.getElementById("feesAmount").value = editRecord.fees;
  });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/dashbaordpage/edit-late-fee.blade.php ENDPATH**/ ?>