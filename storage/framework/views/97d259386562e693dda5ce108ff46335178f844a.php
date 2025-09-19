<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Edit Other Invoice')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('ticket-support')); ?>"><?php echo e(__('Other List')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Edit')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form id="invoiceForm">
        <div class="row g-3 mb-4">
          <!-- Invoice No -->
          <div class="col-md-3">
            <label class="form-label">Invoice #</label>
            <input type="text" class="form-control" id="invoiceNo" readonly>
          </div>
          <!-- Invoice Date -->
          <div class="col-md-3">
            <label class="form-label">Invoice Date</label>
            <input type="date" class="form-control" id="invoiceDate" required>
          </div>
          <!-- Terms -->
          <div class="col-md-3">
            <label class="form-label">Terms</label>
            <select class="form-select" id="terms" required>
              <option value="other">Other</option>
              <option value="net7">Net 7</option>
              <option value="net15">Net 15</option>
              <option value="net30">Net 30</option>
            </select>
          </div>
          <!-- Due Date -->
          <div class="col-md-3">
            <label class="form-label">Due Date</label>
            <input type="date" class="form-control" id="dueDate" required>
          </div>

          <!-- Property Select -->
          <div class="col-6">
            <label class="form-label">Select Property</label>
            <select id="propertySelect" class="form-control" required>
              <option value="">-- Select --</option>
              <option value="nyc101">NYC - Times Square Apartment</option>
              <option value="la202">Los Angeles - Sunset Villa</option>
              <option value="chicago303">Chicago - Lakeview Condo</option>
            </select>
          </div>

          <!-- Tenant Select -->
          <div class="col-6">
            <label class="form-label">Select Tenant</label>
            <select id="tenantSelect" class="form-control" required disabled>
              <option value="">-- Select property first --</option>
            </select>
          </div>

          <!-- Subject -->
          <div class="col-12">
            <label class="form-label">Subject</label>
            <input type="text" class="form-control" id="subject">
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
            <tbody></tbody>
            <tfoot>
              <tr>
                <td colspan="1" class="text-end fw-bold">Subtotal:</td>
                <td colspan="2">
                  <input type="text" class="form-control" id="subtotal" readonly>
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

        <!-- Action Buttons -->
        <div class="text-end">
          <a href="invoices.html" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
          </a>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Save Changes
          </button>
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
  const tbody = document.querySelector("#invoiceTable tbody");

  propertySelect.addEventListener("change", function() {
    const selectedProperty = this.value;
    tenantSelect.innerHTML = "";

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

  // Add Row
  document.getElementById("addRow").addEventListener("click", () => {
    addInvoiceRow({ detail: "", amount: 0 });
  });

  // Remove Row
  document.addEventListener("click", (e) => {
    if (e.target.closest(".removeRow")) {
      e.target.closest("tr").remove();
      calculateSubtotal();
    }
  });

  // Recalculate subtotal on amount input
  document.addEventListener("input", (e) => {
    if (e.target.classList.contains("amount")) {
      calculateSubtotal();
    }
  });

  function addInvoiceRow(item) {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td><input type="text" class="form-control" value="${item.detail}"></td>
      <td><input type="number" class="form-control amount" value="${item.amount}"></td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-danger removeRow"><i class="bi bi-x-lg"></i></button>
      </td>
    `;
    tbody.appendChild(tr);
  }

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
    alert("✅ Invoice updated! Subtotal: $" + document.getElementById("subtotal").value);
  });

  // -----------------
  // Prefill Example Record (Simulate edit mode)
  // -----------------
  const editInvoice = {
    invoiceNo: "INV-1001",
    invoiceDate: "2025-09-01",
    terms: "net7",
    dueDate: "2025-09-08",
    property: "nyc101",
    tenant: "Emily Davis",
    subject: "Rent payment for September",
    items: [
      { detail: "September Rent", amount: 1200 },
      { detail: "Maintenance Fee", amount: 100 }
    ]
  };

  window.addEventListener("DOMContentLoaded", () => {
    // Fill main fields
    document.getElementById("invoiceNo").value = editInvoice.invoiceNo;
    document.getElementById("invoiceDate").value = editInvoice.invoiceDate;
    document.getElementById("terms").value = editInvoice.terms;
    document.getElementById("dueDate").value = editInvoice.dueDate;
    document.getElementById("propertySelect").value = editInvoice.property;

    // Trigger tenants load
    propertySelect.dispatchEvent(new Event("change"));
    setTimeout(() => {
      document.getElementById("tenantSelect").value = editInvoice.tenant;
    }, 100);

    document.getElementById("subject").value = editInvoice.subject;

    // Fill items
    tbody.innerHTML = "";
    editInvoice.items.forEach(item => addInvoiceRow(item));
    calculateSubtotal();
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/dashbaordpage/edit-other-invoice.blade.php ENDPATH**/ ?>