<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Add Other Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('ticket-support')); ?>"><?php echo e(__('Other List')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Add')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form id="invoiceForm">
        <div class="row g-3 mb-4">
          <!-- Invoice No -->
          <div class="col-md-3">
            <label class="form-label">Invoice #</label>
            <input type="text" class="form-control" id="invoiceNo" value="INV-1001" readonly>
          </div>
          <!-- Invoice Date -->
          <div class="col-md-3">
            <label class="form-label">Invoice Date</label>
            <input type="date" class="form-control" id="invoiceDate">
          </div>
          <!-- Terms -->
          <div class="col-md-3">
            <label class="form-label">Terms</label>
            <select class="form-select" id="terms">
              <option value="other">Other</option>
            </select>
          </div>
          <!-- Due Date -->
          <div class="col-md-3">
            <label class="form-label">Due Date</label>
            <input type="date" class="form-control" id="dueDate">
          </div>
          <!-- Property Select -->
            <div class="col-6">
                <label class="form-label">Select Property</label>
                <select id="propertySelect" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="nyc101">NYC - Times Square Apartment</option>
                    <option value="la202">Los Angeles - Sunset Villa</option>
                    <option value="chicago303">Chicago - Lakeview Condo</option>
                </select>
            </div>

            <!-- Tenant Select -->
            <div class="col-6">
                <label class="form-label">Select Tenant</label>
                <select id="tenantSelect" class="form-control" disabled>
                    <option value="">-- Select property first --</option>
                </select>
            </div>
          <!-- Subject -->
          <div class="col-12">
            <label class="form-label">Subject</label>
            <input type="text" class="form-control" placeholder="Invoice for rental payment...">
          </div>
        </div>

        <!-- Invoice Items Table -->
        <div class="table-responsive">
          <table class="table table-bordered align-middle" id="invoiceTable">
            <thead class="table-light">
              <tr>
                <th style="width: 60%">Detail</th>
                <th style="width: 30%">Amount ($)</th>
                <th style="width: 10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" class="form-control" placeholder="Enter detail"></td>
                <td><input type="number" class="form-control amount" value="0"></td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-danger removeRow"><i class="bi bi-x-lg"></i></button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="1" class="text-end fw-bold">Subtotal:</td>
                <td colspan="2">
                  <input type="text" class="form-control" id="subtotal" value="0" readonly>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Add Row Button -->
        <div class="mb-3">
          <button type="button" id="addRow" class="btn btn-outline-primary btn-sm">
            + Add New Row
          </button>
        </div>

        <!-- Submit -->
        <div class="text-end">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
            


    </div>
</div>

<script>
  // Property → Tenant mapping
  const tenantsData = {
    "nyc101": ["Michael Johnson", "Emily Davis"],
    "la202": ["Robert Brown", "Sophia Wilson"],
    "chicago303": ["David Miller", "Olivia Taylor"]
  };

  const propertySelect = document.getElementById("propertySelect");
  const tenantSelect = document.getElementById("tenantSelect");

  propertySelect.addEventListener("change", function() {
    const selectedProperty = this.value;
    tenantSelect.innerHTML = ""; // clear old options

    if (selectedProperty && tenantsData[selectedProperty]) {
      tenantSelect.disabled = false;
      tenantSelect.innerHTML = `<option value="">-- Select Tenant --</option>`;
      tenantsData[selectedProperty].forEach(tenant => {
        const opt = document.createElement("option");
        opt.value = tenant;
        opt.textContent = tenant;
        tenantSelect.appendChild(opt);
      });
    } else {
      tenantSelect.disabled = true;
      tenantSelect.innerHTML = `<option value="">-- Select property first --</option>`;
    }
  });
</script>
<script>
  // Auto set invoice & due date
  function formatDate(date) {
    const d = new Date(date);
    let mm = String(d.getMonth() + 1).padStart(2, '0');
    let dd = String(d.getDate()).padStart(2, '0');
    let yy = String(d.getFullYear()).slice(-2);
    return `${mm}-${dd}-${yy}`;
  }

  document.getElementById("invoiceDate").value = formatDate(new Date());
  let due = new Date();
  due.setDate(due.getDate() + 7); // default +7 days
  document.getElementById("dueDate").value = formatDate(due);

  // Add Row
  document.getElementById("addRow").addEventListener("click", () => {
    const tbody = document.querySelector("#invoiceTable tbody");
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td><input type="text" class="form-control" placeholder="Enter detail"></td>
      <td><input type="number" class="form-control amount" value="0"></td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-danger removeRow"><i class="bi bi-x-lg"></i></button>
      </td>
    `;
    tbody.appendChild(tr);
  });

  // Remove Row + Recalc
  document.addEventListener("click", (e) => {
    if (e.target.closest(".removeRow")) {
      e.target.closest("tr").remove();
      calculateSubtotal();
    }
  });

  // Calculate subtotal on input
  document.addEventListener("input", (e) => {
    if (e.target.classList.contains("amount")) {
      calculateSubtotal();
    }
  });

  function calculateSubtotal() {
    let total = 0;
    document.querySelectorAll(".amount").forEach(input => {
      total += parseFloat(input.value) || 0;
    });
    document.getElementById("subtotal").value = total.toFixed(2);
  }

  // Form submit
  document.getElementById("invoiceForm").addEventListener("submit", (e) => {
    e.preventDefault();
    calculateSubtotal();
    alert("Invoice saved! Subtotal: $" + document.getElementById("subtotal").value);
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/dashbaordpage/add-other.blade.php ENDPATH**/ ?>