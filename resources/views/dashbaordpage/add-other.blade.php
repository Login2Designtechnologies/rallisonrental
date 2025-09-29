@extends('layouts.app')
@section('page-title')
    {{ __('Add Other Invoice') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('ticket-support') }}">{{ __('Other List') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Add') }}</li>
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form method="POST" action="{{ route('other_invoice.store') }}" id="invoiceForm">
        @csrf
        <div class="row g-3 mb-4">
          <!-- Invoice No -->
          <div class="col-md-3">
            <label class="form-label">Invoice #</label>
            <input type="text" class="form-control" name="invoice_no" value="{{ $invoice_no }}" readonly>
          </div>
          <!-- Invoice Date -->
          <div class="col-md-3">
            <label class="form-label">Invoice Date</label>
            <input type="date" class="form-control" name="invoice_date" value="{{ date('Y-m-d') }}" id="invoiceDate">
          </div>
          <!-- Terms -->
          <div class="col-md-3">
            <label class="form-label">Terms</label>
            <select class="form-select" id="terms" name="terms">
              <option value="other">Other</option>
            </select>
          </div>
          <!-- Due Date -->
          <div class="col-md-3">
            <label class="form-label">Due Date</label>
            <input type="date" class="form-control" id="dueDate" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
          </div>
          <!-- Property Select -->
            <div class="col-6">
                <label class="form-label">Select Property</label>
                <select name="property_id" id="propertySelect" class="form-control">
                  <option value="">-- Select --</option>
                  @foreach($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->id }} {{ $property->name }}</option>
                  @endforeach
              </select>
            </div>

            <!-- Tenant Select -->
            <div class="col-6">
                <label class="form-label">Select Tenant</label>
                <select name="tenant_id" id="tenantSelect" class="form-control" disabled>
                  <option value="">-- Select Tenant first --</option>
                </select>
            </div>
          <!-- Subject -->
          <div class="col-12">
            <label class="form-label">Subject</label>
            <input type="text" class="form-control" name="subject" placeholder="Invoice for rental payment...">
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
                <td><input type="text" class="form-control" name="items[0][detail]" placeholder="Enter detail"></td>
                <td><input type="number" class="form-control amount" name="items[0][amount]" value="0" step="0.01"></td>
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
  const tenantsData = @json(
    $tenants->groupBy('property_id')->map(function($group) {
        return $group->map(function($tenant) {
            return ['id' => $tenant->id, 'name' => $tenant->user->name];
        });
    })
  );

  console.log('tenantsData', tenantsData);

  const propertySelect = document.getElementById("propertySelect");
  const tenantSelect = document.getElementById("tenantSelect");

  propertySelect.addEventListener("change", function() {
    const propertyId = this.value;
    tenantSelect.innerHTML = "";

    if (propertyId && tenantsData[propertyId]) {
      tenantSelect.disabled = false;
      tenantSelect.innerHTML = `<option value="">-- Select Tenant --</option>`;
      tenantsData[propertyId].forEach(tenant => {
        const opt = document.createElement("option");
        opt.value = tenant.id; // ✅ tenant ID
        opt.textContent = tenant.name; // ✅ tenant Name
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
    calculateSubtotal();
  });
</script>
@endsection
