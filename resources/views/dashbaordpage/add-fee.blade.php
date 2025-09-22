@extends('layouts.app')
@section('page-title')
    {{ __('Add Fee') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('late-fees') }}">{{ __('Late Fee') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Add') }}</li>
    
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <!-- Property Select -->
        <div class="mb-3">
          <label for="propertySelect" class="form-label">Select Property</label>
          <select class="form-select" id="propertySelect" required>
            <option value="">Choose...</option>
            <option value="nyc101">NYC - Times Square Apartment</option>
            <option value="la202">Los Angeles - Sunset Villa</option>
            <option value="chicago303">Chicago - Lakeview Condo</option>
            <option value="houston404">Houston - Greenfield House</option>
          </select>
        </div>

        <!-- Table -->
        <div class="mt-4">
          <h5>Payment Records</h5>
          <div class="table-responsive">
            <table class="table table-bordered" id="recordsTable">
              <thead class="table-light">
                <tr>
                  <th>Actual Payment Date</th>
                  <th>Payment Received Date</th>
                  <th>Tenant Name</th>
                  <th>Fees Amount ($)</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="5" class="text-center text-muted">No records yet</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

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
    ],
    "chicago303": [
      { actual: "2025-09-03", received: "2025-09-03", tenant: "David Miller", fees: 1000 },
      { actual: "2025-09-03", received: "2025-09-04", tenant: "Olivia Taylor", fees: 1100 }
    ],
    "houston404": [
      { actual: "2025-09-04", received: "2025-09-06", tenant: "James Anderson", fees: 1250 },
      { actual: "2025-09-04", received: "2025-09-05", tenant: "Isabella Martinez", fees: 1350 }
    ]
  };

  const propertySelect = document.getElementById("propertySelect");
  const recordsTable = document.getElementById("recordsTable").querySelector("tbody");

  // When property is selected, show its records in the table
  propertySelect.addEventListener("change", function() {
    const selectedProperty = this.value;
    recordsTable.innerHTML = "";

    if (selectedProperty && paymentData[selectedProperty]) {
      paymentData[selectedProperty].forEach((record, index) => {
        const newRow = document.createElement("tr");
        newRow.innerHTML = `
          <td>${record.actual}</td>
          <td>${record.received}</td>
          <td>${record.tenant}</td>
          <td>${record.fees.toFixed(2)}</td>
          <td>
            <button class="btn btn-sm btn-primary send-invoice-btn" data-tenant="${record.tenant}" data-amount="${record.fees}">
              <i class="bi bi-send-fill me-1"></i> Send Invoice
            </button>
          </td>
        `;
        recordsTable.appendChild(newRow);
      });

      // Add event listeners for send invoice buttons
      document.querySelectorAll(".send-invoice-btn").forEach(btn => {
        btn.addEventListener("click", function() {
          const tenant = this.getAttribute("data-tenant");
          const amount = this.getAttribute("data-amount");
          alert(`Invoice sent to ${tenant} for $${amount}`);
        });
      });

    } else {
      recordsTable.innerHTML = `
        <tr>
          <td colspan="5" class="text-center text-muted">No records yet</td>
        </tr>
      `;
    }
  });
</script>

@endsection
