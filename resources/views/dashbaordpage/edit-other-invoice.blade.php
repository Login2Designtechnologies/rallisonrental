@extends('layouts.app')
@section('page-title')
    {{ __('Edit Other Invoice') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('ticket-support') }}">{{ __('Other List') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Edit') }}</li>
    
@endsection

@section('content')
<div class="card border bg-custom w-100">
  <div class="card-body">
    <form action="{{ route('update_other_invoice', $otherInvoice->id) }}" method="POST">
    @csrf
      <div class="row g-3 mb-4">
        <!-- Invoice No -->
        <div class="col-md-3">
          <label class="form-label">Invoice #</label>
          <input type="text" class="form-control" name="invoice_no" value="{{ $otherInvoice->invoice_no }}" readonly>
        </div>
        <!-- Invoice Date -->
        <div class="col-md-3">
          <label class="form-label">Invoice Date</label>
          <input type="date" class="form-control" name="invoice_date" value="{{ $otherInvoice->invoice_date?->format('Y-m-d') }}" required>
          @error('invoice_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Terms -->
        <div class="col-md-3">
          <label class="form-label">Terms</label>
          <select name="terms" class="form-select" id="terms" required>
            <option value="other" {{ $otherInvoice->terms == 'other' ? 'selected' : '' }}>Other</option>
          </select>
          @error('terms')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!-- Due Date -->
        <div class="col-md-3">
          <label class="form-label">Due Date</label>
          <input type="date" class="form-control" name="due_date" value="{{ $otherInvoice->due_date?->format('Y-m-d') }}" required>
          @error('due_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Property Select -->
        <div class="col-6">
          <label class="form-label">Select Property</label>
          <select name="property_id" id="propertySelect" class="form-control" required>
            <option value="">-- Select --</option>
            @foreach($properties as $property)
              <option value="{{ $property->id }}" {{ $otherInvoice->property_id == $property->id ? 'selected' : '' }}>
                {{ $property->name }}
              </option>
            @endforeach
          </select>
          @error('property_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Tenant Select -->
        <div class="col-6">
          <label class="form-label">Select Tenant</label>          
          <select name="tenant_id" class="form-control" required>
            <option value="">-- Select Tenant --</option>
            @foreach($tenants as $tenant)
              <option value="{{ $tenant->id }}" {{ $otherInvoice->tenant_id == $tenant->id ? 'selected' : '' }}>
                {{ $tenant->name }}
              </option>
            @endforeach
          </select>
          @error('tenant_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Subject -->
        <div class="col-12">
          <label class="form-label">Subject</label>
          <input type="text" class="form-control" name="subject" value="{{ $otherInvoice->subject }}">
          @error('subject')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
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
            @forelse($otherInvoice->items as $index => $detail)
              <tr>
                <td>
                  <input type="text" name="items[{{ $index }}][item]" class="form-control" value="{{ $detail->item }}">
                </td>
                <td>
                  <input type="number" name="items[{{ $index }}][price]" 
                        class="form-control amount"  {{-- ✅ added .amount --}}
                        value="{{ $detail->price }}" step="0.01">
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-danger removeRow">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td><input type="text" class="form-control" name="items[0][item]" placeholder="Enter detail"></td>
                <td><input type="number" class="form-control amount" name="items[0][price]" value="0" step="0.01"></td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-danger removeRow"><i class="bi bi-x-lg"></i></button>
                </td>
              </tr>
            @endforelse
          </tbody>
          <tfoot>
            <tr>
              <td class="text-end fw-bold">Subtotal:</td>
              <td colspan="2">
                <input type="text" class="form-control" id="subtotal" name="subtotal" value="{{ $otherInvoice->amount ?? 0 }}" readonly>
              </td>
            </tr>
          </tfoot>
        </table>
        @error('items')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Add Row Button -->
      <div class="mb-3">
        <button type="button" id="addRow" class="btn btn-outline-primary btn-sm">
          + Add New Row
        </button>
      </div>

      <!-- Action Buttons -->
      <div class="text-end">
        <a href="{{ route('other') }}" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Back
        </a>

        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#removeInvoiceModal">
          <i class="bi bi-trash"></i> Remove Invoice
        </button>

        <button type="submit" class="btn btn-success">
          <i class="bi bi-save"></i> Save Changes
        </button>        
      </div>
    </form>
  </div>
</div>

<!-- Remove Invoice Modal -->
<div class="modal fade" id="removeInvoiceModal" tabindex="-1" aria-labelledby="removeInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('remove_other_invoice', $otherInvoice->id) }}" method="POST" id="removeInvoiceForm">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title" id="removeInvoiceModalLabel">Remove Other Invoice</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">          
          <textarea name="removal_message" class="form-control" rows="4" placeholder="Enter message to tenant..." required></textarea>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-check-circle"></i> Confirm Remove
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('script')
<script>
window.addEventListener("load", function () {  // 👈 wait until full page rendered
    const tbody = document.querySelector("#invoiceTable tbody");
    const subtotalInput = document.getElementById("subtotal");
    const addRowBtn = document.getElementById("addRow");

    // ✅ Calculate subtotal
    function calculateSubtotal() {
        const amountInputs = tbody.querySelectorAll(".amount");
        let total = 0;
        amountInputs.forEach(input => {
            const val = parseFloat(input.value);
            if (!isNaN(val)) total += val;
        });
        subtotalInput.value = total.toFixed(2);
        console.log("Amounts:", [...amountInputs].map(i => i.value));
        console.log("Subtotal:", total);
    }

    // ✅ Add new row
    addRowBtn.addEventListener("click", function () {
        const index = tbody.querySelectorAll("tr").length;
        const newRow = document.createElement("tr");
        newRow.innerHTML = `
            <td><input type="text" name="items[${index}][item]" class="form-control" placeholder="Enter detail"></td>
            <td><input type="number" step="0.01" name="items[${index}][price]" class="form-control amount" value="0.00"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger removeRow"><i class="bi bi-x-lg"></i></button>
            </td>
        `;
        tbody.appendChild(newRow);
        calculateSubtotal();
    });

    // ✅ Delete row
    tbody.addEventListener("click", function (e) {
        if (e.target.closest(".removeRow")) {
            e.target.closest("tr").remove();
            calculateSubtotal();
        }
    });

    // ✅ Live update
    tbody.addEventListener("input", function (e) {
        if (e.target.classList.contains("amount")) {
            calculateSubtotal();
        }
    });

    // ✅ Initial subtotal once everything loaded
    calculateSubtotal();
});
</script>

<script>
const tenantsData = @json(
  $tenants->groupBy('property_id')->map(function($group) {
      return $group->map(function($tenant) {
          return [
              'id' => $tenant->id,
              'name' => $tenant->user->name ?? $tenant->name,
          ];
      });
  })
);

const propertySelect = document.getElementById("propertySelect");
const tenantSelect   = document.querySelector('select[name="tenant_id"]'); // ✅ your actual select
const selectedTenantId = "{{ $otherInvoice->tenant_id }}";                 // ✅ store preselected ID
const selectedPropertyId = "{{ $otherInvoice->property_id }}";             // ✅ store preselected property

function populateTenants(propertyId, preselectId = null) {
  tenantSelect.innerHTML = "";
  if (propertyId && tenantsData[propertyId]) {
    tenantSelect.disabled = false;
    tenantSelect.innerHTML = `<option value="">-- Select Tenant --</option>`;
    tenantsData[propertyId].forEach(tenant => {
      const opt = document.createElement("option");
      opt.value = tenant.id;
      opt.textContent = tenant.name;
      if (preselectId && parseInt(preselectId) === parseInt(tenant.id)) {
        opt.selected = true; // ✅ preselect tenant
      }
      tenantSelect.appendChild(opt);
    });
  } else {
    tenantSelect.disabled = true;
    tenantSelect.innerHTML = `<option value="">-- Select property first --</option>`;
  }
}

// ✅ Populate on property change
propertySelect.addEventListener("change", function() {
  populateTenants(this.value);
});

// ✅ Populate once on page load with existing property + tenant
window.addEventListener("DOMContentLoaded", () => {
  if (selectedPropertyId) {
    populateTenants(selectedPropertyId, selectedTenantId);
  }
});
</script>
<script>
  document.getElementById('removeInvoiceForm').addEventListener('submit', function(e) {
    if (!confirm('Are you sure you want to permanently remove this invoice?')) {
      e.preventDefault();
    }
  });
</script>
@endpush
