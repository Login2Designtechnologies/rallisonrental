<!-- Company Details -->
<div id="companyDetails" property_id="{{ $property->id }}">
  <input type="hidden" id="invoiceMonth" value="{{ $invoiceMonth ?? date('Y-m') }}">
  @php
    function getUtilityAmount($amounts, $utilityId, $category = '') {
        $key = ($utilityId ?: 'null') . '|' . ($category ?: '');
        return isset($amounts[$key]) ? number_format($amounts[$key]->total_amount, 2) : '0.00';
    }
  @endphp
  @php
    $utilitiescatg = DB::table('utilities_catg')->where('property_id',$property->id)->where('user_id',auth()->id())->where('status','1')->get()->groupBy('name');;
  @endphp
  @forelse($utilitiescatg as $utilityName => $subcategories)
    <div class="card table-custom-card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
          <h4 class="mt-2">{{ $utilityName }}</h4>
          <div class="d-flex">
            <div class="me-2">
              <label class="form-label mb-1">Start Date</label>
              <input type="text" class="form-control" style="max-width:150px;"
                    value="{{ now()->format('m-d-Y') }}">
            </div>
            <div>
              <label class="form-label mb-1">End Date</label>
              <input type="text" class="form-control" style="max-width:150px;"
                    value="{{ now()->format('m-d-Y') }}">
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered custom-bg-table">
            <thead>
              <tr>
                <th>Category</th>
                <th>Price ($)</th>
                @php
                  $tenantsuserdata = \App\Models\Tenant::with('user')
                    ->where('property_id', $property->id)
                    ->where('parent_id', auth()->id())
                    ->get()
                    ->pluck('user');
                @endphp
                @foreach($tenantsuserdata as $tenant)
                  <th>{{ $tenant->first_name }} {{ $tenant->last_name }} ($)</th>
                @endforeach
                <th>Total (%)</th>
              </tr>
            </thead>

            <tbody>
              @foreach($subcategories as $sub)
                @php
                  $key = ($sub->id ?: 'null').'|'.($sub->sub_category_name ?: '');
                  $existing = isset($amounts) && $amounts->has($key)
                      ? number_format((float)$amounts->get($key)->total_amount, 2)
                      : '0.00';
                @endphp
                <tr data-utility_id="{{ $sub->id }}">
                  <td>{{ $sub->sub_category_name }}</td>
                  @php
                    $savedPrice = isset($utilityPrices[$sub->id]) ? number_format($utilityPrices[$sub->id], 2) : $existing;
                  @endphp
                  <td contenteditable="true" class="price-cell">${{ $savedPrice }}</td>
                  @foreach($tenantsuserdata as $tenant)
                    @php
                      $pct = isset($tenantShares[$sub->id][$tenant->id])
                          ? number_format($tenantShares[$sub->id][$tenant->id], 0)
                          : 0;
                    @endphp
                    <td contenteditable="true"
                        data-renter-id="{{ $tenant->id }}"
                        class="renter-cell-{{ $tenant->id }}">
                        {{ $pct }}$
                    </td>
                  @endforeach
                  <td class="cell-red total-row-cell">0%</td>
                </tr>
              @endforeach

              <tr class="fw-bold bg-light">
                <td>Total</td>
                <td class="column-total">$0.00</td>
                @foreach($tenantsuserdata as $tenant)
                  <td class="renter-total-cell-{{ $tenant->id }}">0%</td>
                @endforeach
                <td class="table-total-cell">0%</td>
              </tr>
            </tbody>
          </table>
        </div>


      <div class="row">
        @php
          $billRecord = $uploadedBills[$sub->id] ?? null;
          $billFilePath = $billRecord ? $billRecord->file_path : null;
          $hasBill = !empty($billFilePath);
        @endphp
        <div class="col-md-6">
            <div id="upload-section-{{ $sub->id }}" class="{{ $hasBill ? 'd-none' : '' }}">         
              <input type="file" id="billUpload-{{ $sub->id }}" class="billUpload d-none" data-utility-id="{{ $sub->id }}" accept=".pdf,.jpg,.jpeg,.png">
              <label for="billUpload-{{ $sub->id }}" class="btn btn-info">Upload Bill</label>
            </div>

            <!-- After upload -->
            <div id="after-upload-{{ $sub->id }}" class="mt-3 {{ !$hasBill ? 'd-none' : '' }}">
              @if($hasBill)
                <button type="button"
                        class="btn btn-info btn-sm btn-preview"
                        data-utility-id="{{ $sub->id }}"
                        data-file-path="{{ $billFilePath }}">
                  Preview Bill
                </button>
              @endif
            </div>
        </div>
        <div class="col-md-6">
          <div class="text-end">
            
              @php                  
                  $key = ($sub->id ?: 'null').'|'.($sub->sub_category_name ?: '');
                  $hasAmount = isset($amounts) && $amounts->has($key) && (float)$amounts->get($key)->total_amount > 0;
                  $initialMode = $hasAmount ? 'edit' : 'save';
              @endphp

              <!-- <button class="btn btn-primary utility-toggle-btn"
                    data-utility-id="{{ $sub->id }}"
                    data-mode="{{ $initialMode }}">
              {{ $initialMode === 'save' ? 'SAVE' : 'EDIT / UPDATE' }}
            </button> -->
            <button class="btn btn-primary utility-toggle-btn" data-mode="save">
              SAVE
            </button>
          </div>
        </div>
      </div>
    </div>
  @empty
    <p class="text-muted">No utilities found.</p>
  @endforelse
</div>

 
<!-- Grand Total Table -->
<div id="grandTotalTable" class="mt-4">
  <table class="table table-bordered table-striped mt-3 grand-total-table">
    <thead>
      <tr>
        <th>Grand Total Price ($)</th>
        @if (!empty($property))
          @foreach($tenantsuserdata as $renter)
            <th>{{ $renter->first_name }} {{ $renter->last_name }} Total %</th>
          @endforeach
        @endif
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="grand-price-cell grand_total">$0.00</td>
        @if (!empty($property))
          @foreach($tenantsuserdata as $renter)
            <td class="grand-renter-total-{{ $renter->id }}">0%</td>
          @endforeach
        @endif
      </tr>
    </tbody>
  </table>
</div>

<div id="generateInvoiceBtnContainer" class="mt-3 d-flex justify-content-center gap-2 {{ $existingInvoice ? 'disabled' : '' }}" style="display:none;">
    <input type="date" name="due_date" value="{{ date('Y-m-d') }}" class="form-control" style="max-width:150px;" {{ $existingInvoice ? 'disabled' : '' }}>
    <button id="generateInvoiceBtn" class="btn btn-success" {{ $existingInvoice ? 'disabled' : '' }}>
        Generate Invoice
    </button>
</div>
</div>

<div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Invoice Preview</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="invoicePreviewContent" style="max-height:80vh; overflow:auto;">
        <!-- dynamic HTML will be injected here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmGenerateInvoice" class="btn btn-success">
          Confirm & Send
        </button>
      </div>
    </div>
  </div>
</div>

@if ($existingInvoice)
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll("td[contenteditable]").forEach(td => td.setAttribute("contenteditable", "false"));
      document.querySelectorAll("input[type='file']").forEach(inp => inp.disabled = true);
      document.getElementById("generateInvoiceBtn")?.setAttribute("disabled", "true");
      document.getElementById("generateInvoiceBtnContainer")?.classList.add("disabled");
    });
  </script>
@endif



<script>

function bindInlineEditing() {
  document.removeEventListener("input", inlineInputHandler);
  document.addEventListener("input", inlineInputHandler);
}

function inlineInputHandler(ev) {
  const t = ev.target;
  if (!t.matches("td.price-cell, td[data-renter-id]")) return;

  // preserve cursor position as best-effort
  const sel = window.getSelection();
  const range = sel.rangeCount ? sel.getRangeAt(0) : null;
  const cursor = range ? range.startOffset : 0;

  // sanitize text
  let text = t.textContent.replace(/[^\d.%]/g, "");
  if (t.matches("td[data-renter-id]")) {
    const pct = parseFloat(text.replace("%","")) || 0;
    text = `${pct}%`;
  }
  t.textContent = text;

  // restore cursor if possible
  if (range) {
    const newRange = document.createRange();
    newRange.setStart(t.firstChild || t, Math.min(cursor, text.length));
    newRange.collapse(true);
    sel.removeAllRanges();
    sel.addRange(newRange);
  }

  // recalc only the table that contains this cell
  const table = t.closest("table.custom-bg-table");
  if (table) recalcTable(table);
}

function initRecalcSystem() {
  // bind handler once
  bindInlineEditing();

  // initial pass (in case tables are already in DOM)
  document.querySelectorAll("table.custom-bg-table").forEach(table => {
    recalcTable(table);
  });
}

document.addEventListener("DOMContentLoaded", initRecalcSystem);

// Handle inline editing with cursor preservation
document.addEventListener("input", (ev) => {
  const t = ev.target;
  if (!t.matches("td.price-cell, td[data-renter-id]")) return;

  const sel = window.getSelection();
  const range = sel.rangeCount ? sel.getRangeAt(0) : null;
  const cursor = range ? range.startOffset : 0;

  let text = t.textContent.replace(/[^\d.%]/g, "");
  if (t.matches("td[data-renter-id]")) {
    const pct = parseFloat(text.replace("%", "")) || 0;
    text = pct + "%";
  }
  t.textContent = text;

  if (range) {
    const newRange = document.createRange();
    newRange.setStart(t.firstChild || t, Math.min(cursor, text.length));
    newRange.collapse(true);
    sel.removeAllRanges();
    sel.addRange(newRange);
  }

  const table = t.closest("table.custom-bg-table");
  if (table) recalcTable(table);
});

// Initialize on page load
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    document.querySelectorAll("table.custom-bg-table").forEach((table) => {
      recalcTable(table);
    });
    recalcGrandTotals();
  }, 300);
});
</script>

<script>
document.addEventListener("click", async function (e) {
  const t = e.target.closest(".utility-toggle-btn");
  if (!t) return; // Ignore other clicks
  e.preventDefault();

  const mode = t.dataset.mode || save;
  const card = t.closest(".card");
  if (!card) return;
  const table = card.querySelector("table.custom-bg-table");
  if (!table) return;

  if (mode === "save") {
    // Collect data
    const utilities = [];
    table.querySelectorAll("tbody tr[data-utility_id]").forEach((row) => {
      const utility_id = row.getAttribute("data-utility_id");
      const price = parseFloat(
        (row.querySelector(".price-cell")?.textContent || "").replace(/[^\d.]/g, "")
      ) || 0;

      const renters = {};
      row.querySelectorAll("td[data-renter-id]").forEach((cell) => {
        const rid = cell.getAttribute("data-renter-id");
        const pct = parseFloat(cell.textContent.replace("%", "")) || 0;
        renters[rid] = pct;
      });

      utilities.push({ utility_id, price, renters });
    });

    // 🔹 Send to backend
    try {
      const response = await fetch("{{ route('utility-invoices.save-shares') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          property_id: "{{ $property->id }}",
          invoice_month: "{{ $invoiceMonth }}",
          utilities
        })
      });

      const data = await response.json();

      if (response.ok && (data.status === true || data.status === "success")) {
        // alert("Utilities saved successfully!");
        toastrs(data.status, data.message, data.status);

        // 🔒 Lock editing
        table.querySelectorAll('td[contenteditable="true"]').forEach((cell) => {
          cell.setAttribute("contenteditable", "false");
        });

        t.dataset.mode = "edit";
        t.textContent = "EDIT / UPDATE";

        // NEW: Check if this company's rows are all complete
        setTimeout(() => {
          const card = table.closest(".card.table-custom-card");
          if (card) {
            const rows = card.querySelectorAll("tbody tr[data-utility_id]");
            const allGreen = Array.from(rows).every(
              (r) => r.querySelector(".total-row-cell")?.classList.contains("cell-green")
            );

            // Hide or show Save/Edit button based on completion
            if (allGreen) {
              t.classList.add("d-none");
            } else {
              t.classList.remove("d-none");
            }
          }
        }, 200);
      } else {
        console.error("Save failed:", data);
        alert(data.message || "Failed to save utilities.");
      }
    } catch (err) {
      console.error("Network error:", err);
      alert("Network error while saving. Please try again.");
    }
  } 
  else if (mode === "edit") {
    t.classList.remove("d-none");
    // 🔓 Enable editing again
    table.querySelectorAll("td.price-cell, td[data-renter-id]").forEach((cell) => {
      cell.setAttribute("contenteditable", "true");
    });
    t.dataset.mode = "save";
    t.textContent = "SAVE";
  }
});
</script>
<script>
  document.addEventListener('change', async function (e) {
  const fileInput = e.target.closest('.billUpload');
  if (!fileInput) return;

  const utilityId = fileInput.getAttribute('data-utility-id');
  const propertyId = "{{ $property->id }}";
  const invoiceMonth = "{{ $invoiceMonth }}";

  const formData = new FormData();
  formData.append('bill_file', fileInput.files[0]);
  formData.append('property_id', propertyId);
  formData.append('utility_id', utilityId);
  formData.append('invoice_month', invoiceMonth);

  try {
    const response = await fetch("{{ route('utility-invoices.upload-bill') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: formData
    });

    const data = await response.json();

    if (response.ok && data.status === 'success') {
      toastrs(data.status, data.message, data.status);
      
      const afterUploadDiv = document.getElementById(`after-upload-${utilityId}`);
      const uploadSection = document.getElementById(`upload-section-${utilityId}`);

      if (afterUploadDiv) {
        afterUploadDiv.classList.remove('d-none');
        // Optional: hide upload button after success
        if (uploadSection) uploadSection.classList.add('d-none');
      }

      // ✅ Set file path for preview button
      const previewBtn = document.querySelector(`.btn-preview[data-utility-id="${utilityId}"]`);
      if (previewBtn) {
        previewBtn.dataset.filePath = data.path;
      }
    } else {
      toastrs(data.status, data.message || 'Failed to upload bill.', data.status);
    }
  } catch (err) {
    console.error(err);
    alert('Network error while uploading bill.');
  }
});
// Preview logic
document.addEventListener('click', (e) => {
  const previewBtn = e.target.closest('.btn-preview');
  if (!previewBtn) return;
  const filePath = previewBtn.dataset.filePath;
  if (filePath) {
    window.open(`/${filePath}`, '_blank');
  } else {
    alert('Bill file not found.');
  }
});
</script>

<script>
  const generateContainer = document.getElementById("generateInvoiceBtnContainer");
  const generateBtn = document.getElementById("generateInvoiceBtn"); 
  generateBtn.addEventListener('click', () => {
    if(!confirm("Generate invoice preview?")) return;

    const dueDate = document.querySelector('input[name="due_date"]').value;
    const invoiceMonth = document.getElementById('invoiceMonth')?.value || new Date().toISOString().slice(0,7);

    const invoicesMap = {};
    document.querySelectorAll('tr[data-utility_id]').forEach(row => {
      const utilityId = parseInt(row.getAttribute('data-utility_id')) || null;
      const price = parseFloat((row.querySelector('.price-cell')?.textContent || '').replace('$','')) || 0;
      const category = row.querySelector('td:first-child')?.textContent.trim() || '';
      const dateInputs = row.closest('.card-body')?.querySelectorAll('input[type="text"]');
      const startDate = dateInputs?.[0]?.value || null;
      const endDate = dateInputs?.[1]?.value || null;

      row.querySelectorAll('td[class^="renter-cell-"]').forEach(cell => {
        const renterId = parseInt(cell.getAttribute('data-renter-id'));
        const pct = parseFloat((cell.textContent || '').replace('%','')) || 0;
        if (!renterId || pct <= 0) return;
        if(!invoicesMap[renterId]){
          invoicesMap[renterId] = { tenant_id: renterId, amount: 0, details: [] };
        }
        const share = +(price * (pct/100)).toFixed(2);
        invoicesMap[renterId].amount += share;
        invoicesMap[renterId].details.push({
          property_utility_id: utilityId,
          category, amount: share,
          start_date: startDate,
          end_date: endDate
        });
      });
    });

    const payload = {
      property_id: '{{ $property->id }}',
      invoice_month: invoiceMonth,
      due_date: dueDate,
      invoices: Object.values(invoicesMap)
    };

    // 🟢 Show preview modal before sending
    showInvoicePreview(payload);

    // Store globally for Confirm action
    window.pendingInvoicePayload = payload;
  });
  document.addEventListener('click', function (e) {
    if (e.target && e.target.id === 'confirmGenerateInvoice') {
      const payload = window.pendingInvoicePayload;
      if (!payload) {
        alert("Payload missing!");
        return;
      }
      fetch(`/utility_invoicesgenerate`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
      })
      .then(async res => {
        const data = await res.json().catch(() => ({}));
        if (res.ok && data.ok) {
          Swal.fire({
            icon: 'success',
            title: 'Invoice Sent',
            html: `${data.created?.length || 0} created, ${data.updated?.length || 0} updated`
          }).then(() => window.location.reload());
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: data.message || 'Could not generate invoices.'
          });
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Network Error', text: err.message });
      });
    }
  });
</script>

<script>
  async function showInvoicePreview(payload) {
    const modalBody = document.getElementById('invoicePreviewContent');
    modalBody.innerHTML = `<div class="text-center p-4">Loading preview...</div>`;

    try {
      const response = await fetch(`/get-invoice-preview`, { // new endpoint
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
      });

      const html = await response.text();
      modalBody.innerHTML = html;
    } catch (error) {
      console.error(error);
      modalBody.innerHTML = `<div class="alert alert-danger">Failed to load preview.</div>`;
    }

    const modal = new bootstrap.Modal(document.getElementById('invoicePreviewModal'));
    modal.show();
  }
  // Generate Invoice
  generateInvoiceBtn.addEventListener('click', () => {

    const dueDate = document.querySelector('input[name="due_date"]').value;
    const monthMap = {January:'01',February:'02',March:'03',April:'04',May:'05',June:'06',July:'07',August:'08',September:'09',October:'10',November:'11',December:'12'};
    const label = (document.getElementById('monthLabel')?.textContent || '').trim();
    const parts = label.split(/\s+/);
    const invoiceMonth = (parts.length === 2 && monthMap[parts[0]]) ? `${parts[1]}-${monthMap[parts[0]]}` : new Date().toISOString().slice(0,7);

    // Build invoices payload from tables
    const invoicesMap = {};
    document.querySelectorAll('tr[data-utility_id]').forEach(row => {
      const utilityId = parseInt(row.getAttribute('data-utility_id')) || null;
      const table = row.closest('table');
      const price = parseFloat((row.querySelector('.price-cell')?.textContent || '').replace('$','')) || 0;
      const category = row.querySelector('td:first-child')?.textContent.trim() || '';
      const dateInputs = table.closest('div').querySelectorAll('input[type="text"]');
      const startDate = dateInputs[0]?.value || null;
      const endDate = dateInputs[1]?.value || null;

      row.querySelectorAll('td[class^="renter-cell-"]').forEach(cell => {
        const renterId = parseInt(cell.getAttribute('data-renter-id'));
        const pct = parseFloat((cell.textContent || '').replace('%','')) || 0;
        if (!renterId || pct <= 0) return;
        if(!invoicesMap[renterId]){
            invoicesMap[renterId] = { tenant_id: renterId, amount: 0, details: [] };
        }
        const share = +(price * (pct/100)).toFixed(2);
        invoicesMap[renterId].amount += share;
        invoicesMap[renterId].details.push({
            property_utility_id: utilityId,
            category: category,
            amount: share,
            start_date: startDate,
            end_date: endDate
        });
      });
    });

    const payload = {
      property_id: '{{ $property->id }}',
      invoice_month: invoiceMonth,
      due_date: dueDate,
      invoices: Object.values(invoicesMap)
    };

    fetch(`/utility_invoicesgenerate`, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(payload)
    })
    .then(async (res) => {
        let data;
        try { data = await res.json(); } catch(e) { data = { ok:false, message: 'Invalid server response' }; }
        if (res.ok && data && data.ok){
          alert("Invoice has been generated and sent. No further changes allowed.");

          // Lock all tables and buttons
          toggleButtons.forEach(btn => {
              const table = document.querySelector(`tr[data-utility_id="${btn.dataset.utilityId}"]`)?.closest('table');
              if(!table) return;
              table.querySelectorAll('td[contenteditable="true"]').forEach(cell => cell.setAttribute('contenteditable','false'));
              btn.disabled = true;
          });

          // Disable file uploads
          document.querySelectorAll('input[type="file"]').forEach(fileInput => fileInput.disabled = true);

          // Disable Generate Invoice button
          generateInvoiceBtn.disabled = true;
        } else {
          const errors = (data && data.errors) ? JSON.stringify(data.errors) : '';
          console.error('Generate invoice error:', data);
          alert((data && data.message) ? data.message : 'Failed to create/update invoices. Check console.');
        }
    })
    .catch(err => {
      console.error('Network error generating invoice:', err);
      alert("Invoice generation error: " + err.message);
    });
  });
</script>
<script>
  window.generateContainer = window.generateContainer || document.getElementById("generateInvoiceBtnContainer");
</script>



<script>
function checkCompanyCompletion(card) {
  const saveBtn = card.querySelector(".utility-toggle-btn");
  if (!saveBtn) return;

  const rows = card.querySelectorAll("tbody tr[data-utility_id]");
  if (!rows.length) return;

  const allGreen = Array.from(rows).every(row => {
    const totalCell = row.querySelector(".total-row-cell");
    return totalCell && totalCell.classList.contains("cell-green");
  });

  const allBillsUploaded = Array.from(rows).every(row => {
    const utilityId = row.getAttribute("data-utility_id");
    const afterUpload = document.getElementById(`after-upload-${utilityId}`);
    return afterUpload && !afterUpload.classList.contains("d-none");
  });

  // Determine if pre-filled (not user editing)
  const isPrefilled = saveBtn.dataset.mode === "edit" || saveBtn.textContent.trim().toUpperCase().includes("EDIT");

  // ✅ Hide Save/Edit button for completed + prefilled company
  if ((allGreen && allBillsUploaded) && isPrefilled) {
    saveBtn.classList.add("d-none");
  } else {
    saveBtn.classList.remove("d-none");
  }
}

/* Hook into recalcTable */
const originalRecalcTable = window.recalcTable;
window.recalcTable = function (table) {
  if (typeof originalRecalcTable === "function") originalRecalcTable(table);
  const card = table.closest(".card.table-custom-card");
  if (card) checkCompanyCompletion(card);
};

/* Run once after DOM ready */
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    document.querySelectorAll(".card.table-custom-card").forEach(checkCompanyCompletion);
  }, 1000);
});
</script>

<script>
function checkIfInvoiceCanBeGenerated() {
  const container = document.getElementById("generateInvoiceBtnContainer");
  const button = document.getElementById("generateInvoiceBtn");
  if (!container || !button) return;

  const cards = document.querySelectorAll(".card.table-custom-card");
  if (!cards.length) return;

  let ready = true;

  cards.forEach(card => {
    const rows = card.querySelectorAll("tbody tr[data-utility_id]");
    rows.forEach(row => {
      const totalCell = row.querySelector(".total-row-cell");
      const utilityId = row.getAttribute("data-utility_id");
      const afterUpload = document.getElementById(`after-upload-${utilityId}`);

      const isGreen = totalCell && totalCell.classList.contains("cell-green");
      const billUploaded = afterUpload && !afterUpload.classList.contains("d-none");

      if (!isGreen || !billUploaded) ready = false;
    });
  });

  if (ready) {
    container.style.display = "flex";
    container.classList.remove("disabled");
    button.disabled = false;
    console.log("✅ Invoice generation enabled");
  } else {
    container.style.display = "flex";
    container.classList.add("disabled");
    button.disabled = true;
    console.log("⚠️ Invoice generation disabled");
  }
}

/* Trigger checks */
document.addEventListener("DOMContentLoaded", () => setTimeout(checkIfInvoiceCanBeGenerated, 1000));
document.addEventListener("billUploadSuccess", checkIfInvoiceCanBeGenerated);
document.addEventListener("utilitySaveSuccess", checkIfInvoiceCanBeGenerated);

/* Hook into recalcTable */
const prevRecalc = window.recalcTable;
window.recalcTable = function (table) {
  if (typeof prevRecalc === "function") prevRecalc(table);
  checkIfInvoiceCanBeGenerated();
};
</script>
