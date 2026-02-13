@php
  use Carbon\Carbon;

  $currentMonth = Carbon::now()->format('Y-m');
  $previousMonth = Carbon::now()->subMonth()->format('Y-m');
  $isEditableMonth = in_array($invoiceMonth, [$currentMonth, $previousMonth]);
@endphp
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
    $utilitiescatg = DB::table('utilities_main as m')
        ->join('utilities_sub as s', 'm.id', '=', 's.utility_main_id')
        ->where('m.property_id', $property->id)
        ->where('m.user_id', auth()->id())
        ->where('m.status', '1')
        ->where('s.status', '1')
        ->select('m.id as main_id', 'm.name as main_name', 's.id as sub_id', 's.sub_category_name')
        ->get()
        ->groupBy('main_name');
  @endphp
  @forelse($utilitiescatg as $utilityName => $subcategories)
    <div class="card table-custom-card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
          <h4 class="mt-2">{{ $utilityName }}</h4>

          @php
            $mainId = optional($subcategories->first())->main_id;
          @endphp
          <div class="d-flex">
            <div class="me-2">
              <label class="form-label mb-1">Start Date</label>
              <!-- <input type="date" class="form-control start-date" data-main-id="{{ $mainId }}" value="{{ $uploadedBills[$mainId]->start_date ?? now()->format('m-d-Y') }}"> -->
              <input type="date" class="form-control start-date" data-main-id="{{ $mainId }}" value="{{ optional($uploadedBills[$mainId] ?? null)->start_date ? \Carbon\Carbon::parse($uploadedBills[$mainId]->start_date)->format('Y-m-d') : now()->format('Y-m-d') }}">
            </div>
            <div>
              <label class="form-label mb-1">End Date</label>
              <!-- <input type="date" class="form-control end-date" data-main-id="{{ $mainId }}" value="{{ $uploadedBills[$mainId]->end_date ?? now()->format('m-d-Y') }}"> -->
              <input type="date" class="form-control end-date" data-main-id="{{ $mainId }}" value="{{ optional($uploadedBills[$mainId] ?? null)->end_date ? \Carbon\Carbon::parse($uploadedBills[$mainId]->end_date)->format('Y-m-d') : now()->format('Y-m-d') }}">
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
                    ->get();
                @endphp
                @foreach($tenantsuserdata as $tenant)
                  <th>{{ $tenant->user->first_name }} {{ $tenant->user->last_name }} ($)</th>
                @endforeach
                <th>Total ($)</th>
              </tr>
            </thead>

            <tbody>
              @foreach($subcategories as $sub)
                @php
                  $key = ($sub->sub_id ?: 'null').'|'.($sub->sub_category_name ?: '');
                  $existing = isset($amounts) && $amounts->has($key)
                      ? number_format((float)$amounts->get($key)->total_amount, 2)
                      : '0.00';
                @endphp
                <tr data-utility_id="{{ $sub->sub_id }}">
                  <td>{{ $sub->sub_category_name }}</td>
                  @php
                    $savedPrice = isset($utilityPrices[$sub->sub_id]) ? number_format($utilityPrices[$sub->sub_id], 2) : $existing;
                  @endphp
                  <td contenteditable="true" class="price-cell">${{ $savedPrice }}</td>
                  @foreach($tenantsuserdata as $tenant)
                    @php
                      $pct = isset($tenantShares[$sub->sub_id][$tenant->id])
                          ? number_format($tenantShares[$sub->sub_id][$tenant->id], 0)
                          : 0;
                    @endphp
                    <td contenteditable="true"
                        data-renter-id="{{ $tenant->id }}"
                        class="renter-cell-{{ $tenant->id }}">
                        {{ $pct }}$
                    </td>
                  @endforeach
                  <td class="cell-red total-row-cell"></td>
                </tr>
              @endforeach

              <tr class="fw-bold bg-light">
                <td>Total</td>
                <td class="column-total">$0.00</td>
                @foreach($tenantsuserdata as $tenant)
                  <td class="renter-total-cell-{{ $tenant->id }}">0$</td>
                @endforeach
                <td class="table-total-cell"></td>
              </tr>
            </tbody>
          </table>
        </div>


      <div class="row">
        @php
          $billRecord = $uploadedBills[$sub->main_id] ?? null;
          $billFilePath = $billRecord ? $billRecord->file_path : null;
          $hasBill = !empty($billFilePath);
        @endphp
        <div class="col-md-6">
            @if($isEditableMonth)
              <div id="upload-section-{{ $sub->main_id }}" class="{{ $hasBill ? 'd-none' : '' }}">
                <input type="file" id="billUpload-{{ $sub->main_id }}" class="billUpload d-none"
                      data-utility-id="{{ $sub->main_id }}" accept=".pdf,.jpg,.jpeg,.png">
                <label for="billUpload-{{ $sub->main_id }}" class="btn btn-info">Upload Bill</label>
              </div>
            @endif

            <!-- After upload -->
            <div id="after-upload-{{ $sub->main_id }}" class="mt-3 {{ !$hasBill ? 'd-none' : '' }}">
              @if($hasBill)
                <button type="button" class="btn btn-info btn-sm btn-preview" data-utility-id="{{ $sub->main_id }}" data-file-path="{{ $billFilePath }}">
                  Preview Bill
                </button>
                @if($isEditableMonth)
                  <button type="button" class="btn btn-warning btn-sm btn-modify mt-1" data-utility-id="{{ $sub->main_id }}">
                    Modify Bill
                  </button>
                @endif
              @endif
            </div>
        </div>
        <div class="col-md-6">
          <div class="text-end">
            
              @php                  
                  $key = ($sub->sub_id ?: 'null').'|'.($sub->sub_category_name ?: '');
                  $hasAmount = isset($amounts) && $amounts->has($key) && (float)$amounts->get($key)->total_amount > 0;
                  $initialMode = $hasAmount ? 'edit' : 'save';
              @endphp

              <!-- <button class="btn btn-primary utility-toggle-btn"
                    data-utility-id="{{ $sub->sub_id }}"
                    data-mode="{{ $initialMode }}">
              {{ $initialMode === 'save' ? 'SAVE' : 'EDIT / UPDATE' }}
            </button> -->
            @if($isEditableMonth)
              <button class="btn btn-primary utility-toggle-btn" data-mode="save">
                SAVE
              </button>
            @endif
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
            <th>{{ $renter->user->first_name }} {{ $renter->user->last_name }} Total ($)</th>
          @endforeach
        @endif
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="grand-price-cell grand_total">$0.00</td>
        @if (!empty($property))
          @foreach($tenantsuserdata as $renter)
            <td class="grand-renter-total-{{ $renter->id }}">0$</td>
          @endforeach
        @endif
      </tr>
    </tbody>
  </table>
</div>

<!-- <input type="date" name="due_date" value="{{ date('Y-m-d') }}" class="form-control" style="max-width:150px;" {{ $existingInvoice ? 'disabled' : '' }}> -->
@if($isEditableMonth)
  <div id="generateInvoiceBtnContainer" class="mt-3 d-flex justify-content-center gap-2 {{ $existingInvoice ? 'disabled' : '' }}" style="display:none;">
    <button id="generateInvoiceBtn" class="btn btn-success" {{ $existingInvoice ? 'disabled' : '' }}>
      Generate Invoice
    </button>
  </div>
@endif
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
<!-- 
@if ($existingInvoice)
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      console.log("🟡 Existing invoice detected for {{ $invoiceMonth }} — locking UI");

      // Disable inline editing
      document.querySelectorAll("td[contenteditable]").forEach(td => {
        td.setAttribute("contenteditable", "false");
        td.classList.add("bg-light");
      });

      // Disable and hide Save/Edit buttons
      document.querySelectorAll(".utility-toggle-btn").forEach(btn => {
        btn.classList.add("d-none");
        btn.disabled = true;
      });

      // Disable and hide upload sections
      document.querySelectorAll("input[type='file']").forEach(inp => inp.disabled = true);
      document.querySelectorAll("[id^='upload-section-']").forEach(div => div.classList.add("d-none"));

      // Disable Generate Invoice
      const genBtn = document.getElementById("generateInvoiceBtn");
      const genContainer = document.getElementById("generateInvoiceBtnContainer");
      if (genBtn) genBtn.disabled = true;
      if (genContainer) genContainer.classList.add("disabled", "d-none");

      // Add visual indicator
      document.querySelectorAll(".card.table-custom-card h4").forEach(h4 => {
        h4.classList.add("text-secondary");
        h4.insertAdjacentHTML(
          'beforeend',
          ' <small class="text-muted">(Locked - Invoice Generated)</small>'
        );
      });
    });
  </script>
@endif -->



<script>
if (false) {
// ===============================
// 🧩 INLINE EDITING FIXED (Cursor + $ symbol only)
// ===============================

function formatCellValue(cell, rawText, isTenant) {
  const clean = rawText.replace(/[^\d.]/g, "");
  const num = parseFloat(clean) || 0;
  return isTenant ? `${num.toFixed(2)}$` : `$${num.toFixed(2)}`;
}

function restoreCursor(cell, position) {
  const selection = window.getSelection();
  if (!selection) return;
  const range = document.createRange();
  range.setStart(cell.firstChild || cell, Math.min(position, cell.textContent.length));
  range.collapse(true);
  selection.removeAllRanges();
  selection.addRange(range);
}

function handleInlineEdit(ev) {
  const cell = ev.target;
  if (!cell.matches("td.price-cell, td[data-renter-id]")) return;

  const isTenant = cell.hasAttribute("data-renter-id");

  const selection = window.getSelection();
  const range = selection.rangeCount ? selection.getRangeAt(0) : null;
  const cursor = range ? range.startOffset : 0;

  // Reformat value while typing
  const formatted = formatCellValue(cell, cell.textContent, isTenant);
  cell.textContent = formatted;

  restoreCursor(cell, cursor);

  // Update totals
  const table = cell.closest("table.custom-bg-table");
  if (table) recalcTable(table);
}

// Attach event safely
if (!window.inlineHandlerBoundV2) {
  document.addEventListener("input", handleInlineEdit);
  window.inlineHandlerBoundV2 = true;
}

// ===============================
// 🧮 ROW & GRAND TOTAL REWORKED
// ===============================
window.recalcTable = function (table) {
  const rows = table.querySelectorAll("tbody tr[data-utility_id]");
  const renterTotals = {};
  let grandTotal = 0;

  rows.forEach(row => {
    const price = parseFloat((row.querySelector(".price-cell")?.textContent || "").replace(/[^\d.]/g, "")) || 0;
    let rowTotal = 0;

    row.querySelectorAll("td[data-renter-id]").forEach(td => {
      const renterId = td.dataset.renterId;
      const val = parseFloat(td.textContent.replace(/[^\d.]/g, "")) || 0;
      renterTotals[renterId] = (renterTotals[renterId] || 0) + val;
      rowTotal += val;
    });

    const totalCell = row.querySelector(".total-row-cell");
    if (totalCell) {
      totalCell.textContent = `$${rowTotal.toFixed(2)}`;
      totalCell.classList.toggle("cell-green", Math.abs(price - rowTotal) < 0.01);
      totalCell.classList.toggle("cell-red", Math.abs(price - rowTotal) >= 0.01);
    }

    grandTotal += rowTotal;
  });

  // Update footer total row
  const totalRow = table.querySelector("tr.fw-bold.bg-light");
  if (totalRow) {
    const colTotal = totalRow.querySelector(".column-total");
    if (colTotal) colTotal.textContent = `$${grandTotal.toFixed(2)}`;

    Object.entries(renterTotals).forEach(([rid, val]) => {
      const cell = totalRow.querySelector(`.renter-total-cell-${rid} div:last-child`);
      if (cell) cell.textContent = `${val.toFixed(2)}$`;
    });

    const lastTotal = totalRow.querySelector(".table-total-cell");
    if (lastTotal) lastTotal.textContent = `$${grandTotal.toFixed(2)}`;
  }

  recalcGrandTotals();
};

window.recalcGrandTotals = function () {
  const tables = document.querySelectorAll("table.custom-bg-table");
  let overall = 0;
  const renterGrand = {};

  tables.forEach(table => {
    table.querySelectorAll("tr[data-utility_id]").forEach(row => {
      const total = parseFloat((row.querySelector(".total-row-cell")?.textContent || "").replace(/[^\d.]/g, "")) || 0;
      overall += total;
      row.querySelectorAll("td[data-renter-id]").forEach(td => {
        const rid = td.dataset.renterId;
        const val = parseFloat(td.textContent.replace(/[^\d.]/g, "")) || 0;
        renterGrand[rid] = (renterGrand[rid] || 0) + val;
      });
    });
  });

  const grandTotalEl = document.querySelector(".grand_total");
  if (grandTotalEl) grandTotalEl.textContent = `$${overall.toFixed(2)}`;

  Object.entries(renterGrand).forEach(([rid, val]) => {
    const cell = document.querySelector(`.grand-renter-total-${rid}`);
    if (cell) cell.textContent = `${val.toFixed(2)}$`;
  });
};

// Initialize on page load
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    document.querySelectorAll("table.custom-bg-table").forEach((table) => {
      recalcTable(table);
    });
    recalcGrandTotals();
  }, 300);
});
}
</script>

<script>
// ✅ Prevent re-attaching the same handler multiple times
if (!window.utilityButtonHandlerBound) {
  window.utilityButtonHandlerBound = true;

  document.addEventListener("click", async function (e) {
    const t = e.target.closest(".utility-toggle-btn");
    if (!t) return;
    e.preventDefault();

    // ✅ Prevent multiple clicks while one save/edit is running
    if (t.classList.contains("busy")) return;
    t.classList.add("busy");

    const mode = t.dataset.mode || "save";
    const card = t.closest(".card");
    if (!card) {
      t.classList.remove("busy");
      return;
    }

    const table = card.querySelector("table.custom-bg-table");
    if (!table) {
      t.classList.remove("busy");
      return;
    }

    if (mode === "save") {
      // 🧩 Collect data
      const utilities = [];
      table.querySelectorAll("tbody tr[data-utility_id]").forEach((row) => {
        const utility_id = row.getAttribute("data-utility_id");
        const price = parseFloat(
          (row.querySelector(".price-cell")?.textContent || "").replace(/[^\d.]/g, "")
        ) || 0;

        const renters = {};
        row.querySelectorAll("td[data-renter-id]").forEach((cell) => {
          const rid = cell.getAttribute("data-renter-id");
          const pct = parseFloat(cell.textContent.replace("$", "")) || 0;
          renters[rid] = pct;
        });

        utilities.push({ utility_id, price, renters });
      });

      const invoiceMonth =
        document.getElementById("invoiceMonth")?.value ||
        new Date().toISOString().slice(0, 7);

      try {
        const response = await fetch("{{ route('utility-invoices.save-shares') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
          },
          body: JSON.stringify({
            property_id: "{{ $property->id }}",
            invoice_month: invoiceMonth,
            utilities,
          }),
        });

        const data = await response.json();

        if (response.ok && (data.status === true || data.status === "success")) {
          toastrs(data.status, data.message, data.status);

          // Lock editing
          table.querySelectorAll('td[contenteditable="true"]').forEach((cell) => {
            cell.setAttribute("contenteditable", "false");
          });

          t.dataset.mode = "edit";
          t.textContent = "EDIT / UPDATE";
        } else {
          console.error("Save failed:", data);
          toastrs("error", data.message || "Failed to save utilities.", "error");
        }
      } catch (err) {
        console.error("Network error:", err);
        toastrs("error", "Network error while saving.", "error");
      } finally {
        setTimeout(() => t.classList.remove("busy"), 500);
      }
    } else if (mode === "edit") {
      // 🧩 Enable editing again
      table.querySelectorAll("td.price-cell, td[data-renter-id]").forEach((cell) => {
        cell.setAttribute("contenteditable", "true");
      });
      t.dataset.mode = "save";
      t.textContent = "SAVE";
      t.classList.remove("busy");
    }
  });
}

</script>
<script>
/* 🧩 Improved: Prevent multiple triggers and handle cancel safely */
if (!window.billUploadHandlerBound) {
  window.billUploadHandlerBound = true;

  document.addEventListener('change', async function (e) {
    const fileInput = e.target.closest('.billUpload');
    if (!fileInput) return;

    // 🛑 Skip if no file selected (user canceled)
    if (!fileInput.files || !fileInput.files.length) {
      console.log('File selection canceled.');
      return;
    }

    // 🧩 Find main utility id
    const card = fileInput.closest('.card.table-custom-card');
    const mainId = card?.querySelector('.start-date')?.dataset.mainId;
    if (!mainId) {
      console.error('Main Utility ID not found for bill upload.');
      return;
    }

    // 🧠 Disable input temporarily to prevent double-trigger
    fileInput.disabled = true;

    const propertyId = "{{ $property->id }}";
    const invoiceMonth = document.getElementById("invoiceMonth")?.value || new Date().toISOString().slice(0, 7);

    const formData = new FormData();
    formData.append('bill_file', fileInput.files[0]);
    formData.append('property_id', propertyId);
    formData.append('utility_id', mainId); // ✅ main_id for upload
    formData.append('invoice_month', invoiceMonth);

    try {
      const response = await fetch("{{ route('utility-invoices.upload-bill') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
      });

      const data = await response.json();

      if (response.ok && data.status === 'success') {
        toastrs('success', data.message, 'success');

        // ✅ Update UI for this main_id only
        const afterUploadDiv = document.getElementById(`after-upload-${mainId}`);
        const uploadSection = document.getElementById(`upload-section-${mainId}`);

        if (afterUploadDiv) {
          afterUploadDiv.classList.remove('d-none');
          afterUploadDiv.innerHTML = `
            <button type="button" class="btn btn-info btn-sm btn-preview"
                    data-utility-id="${mainId}"
                    data-file-path="${data.path}">
              Preview Bill
            </button>
            <button type="button" class="btn btn-warning btn-sm btn-modify mt-1"
                    data-utility-id="${mainId}">
              Modify Bill
            </button>`;
        }

        if (uploadSection) uploadSection.classList.add('d-none');

        document.dispatchEvent(new Event("billUploadSuccess"));
      } else {
        toastrs('error', data.message || 'Failed to upload bill.', 'error');
      }
    } catch (err) {
      console.error('Bill upload failed:', err);
      toastrs('error', 'Network error while uploading bill.', 'error');
    } finally {
      // ✅ Always clear file input and re-enable
      fileInput.value = "";
      fileInput.disabled = false;
    }
  });

  // 🧾 Preview Bill Logic
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

  // 📝 Modify Bill Logic
  document.addEventListener('click', (e) => {
    const modifyBtn = e.target.closest('.btn-modify');
    if (!modifyBtn) return;

    const utilityId = modifyBtn.dataset.utilityId;
    const uploadSection = document.getElementById(`upload-section-${utilityId}`);
    if (uploadSection) {
      // Don’t show the section visually — just trigger the hidden input
      const fileInput = uploadSection.querySelector('input[type="file"]');
      if (fileInput) {
        fileInput.click();
      }
    }
  });
}
</script>


<script>
document.addEventListener("click", async (e) => {
  const generateBtn = e.target.closest("#generateInvoiceBtn");
  if (!generateBtn) return; // only run for the right button

  const invoiceMonth = document.getElementById("invoiceMonth")?.value || new Date().toISOString().slice(0, 7);
  const invoicesMap = {};

  document.querySelectorAll("tr[data-utility_id]").forEach(row => {
    const utilityId = parseInt(row.getAttribute("data-utility_id")) || null;
    const category = row.querySelector("td:first-child")?.textContent.trim() || "";
    const price = parseFloat((row.querySelector(".price-cell")?.textContent || "").replace(/[^\d.]/g, "")) || 0;
    const dateInputs = row.closest(".card-body")?.querySelectorAll('input[type="date"]');
    const startDate = dateInputs?.[0]?.value || null;
    const endDate = dateInputs?.[1]?.value || null;

    row.querySelectorAll("td[data-renter-id]").forEach(cell => {
      const renterId = parseInt(cell.getAttribute("data-renter-id"));
      const val = parseFloat((cell.textContent || "").replace(/[^\d.]/g, "")) || 0;
      if (!renterId || val <= 0) return;

      if (!invoicesMap[renterId]) {
        invoicesMap[renterId] = { tenant_id: renterId, amount: 0, details: [] };
      }

      invoicesMap[renterId].amount += val;
      invoicesMap[renterId].details.push({
        property_utility_id: utilityId,
        category,
        amount: val,
        start_date: startDate,
        end_date: endDate,
      });
    });
  });

  const payload = {
    property_id: document.querySelector("#companyDetails")?.getAttribute("property_id"),
    invoice_month: invoiceMonth,
    invoices: Object.values(invoicesMap),
  };

  window.pendingInvoicePayload = payload;
  const modalBody = document.getElementById("invoicePreviewContent");
  modalBody.innerHTML = `<div class="text-center p-4">Loading preview...</div>`;

  const modal = new bootstrap.Modal(document.getElementById("invoicePreviewModal"));

  try {
    const response = await fetch(`/get-invoice-preview`, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
      },
      body: JSON.stringify(payload),
    });
    const html = await response.text();
    modalBody.innerHTML = html;
  } catch (err) {
    console.error("Preview failed:", err);
    modalBody.innerHTML = `<div class="alert alert-danger">Failed to load preview.</div>`;
  }

  modal.show();
});
</script>

<script>
/* 🧩 Fix: Prevent multiple "Confirm & Send" triggers */
if (!window.confirmInvoiceHandlerBound) {
  window.confirmInvoiceHandlerBound = true;

  document.addEventListener("click", async (e) => {
    const confirmBtn = e.target.closest("#confirmGenerateInvoice");
    if (!confirmBtn) return;

    // 🛑 Prevent multiple clicks
    if (confirmBtn.classList.contains("busy")) return;
    confirmBtn.classList.add("busy");

    const payload = window.pendingInvoicePayload;
    if (!payload) {
      toastrs('error', 'No preview data found.', 'error');
      confirmBtn.classList.remove("busy");
      return;
    }

    try {
      const res = await fetch(`/utility_invoicesgenerate`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        body: JSON.stringify(payload),
      });

      const data = await res.json();
      if (res.ok && data.ok) {
        Swal.fire({
          icon: "success",
          title: "Invoice Sent",
          html: `${data.created?.length || 0} created, ${data.updated?.length || 0} updated`,
        }).then(() => window.location.reload());
      } else {
        Swal.fire({
          icon: "error",
          title: "Failed",
          text: data.message || "Could not generate invoices.",
        });
      }
    } catch (err) {
      Swal.fire({ icon: "error", title: "Network Error", text: err.message });
    } finally {
      confirmBtn.classList.remove("busy");
    }
  });
}
</script>

<script>
  window.generateContainer = window.generateContainer || document.getElementById("generateInvoiceBtnContainer");
</script>



<script>
function checkCompanyCompletion(card) {
  const saveBtn = card.querySelector(".utility-toggle-btn");
  if (!saveBtn) return;

  const mainId = card.querySelector('.start-date')?.dataset.mainId;
  if (!mainId) return;

  const rows = card.querySelectorAll("tbody tr[data-utility_id]");
  if (!rows.length) return;

  const allGreen = Array.from(rows).every(row => {
    const totalCell = row.querySelector(".total-row-cell");
    return totalCell && totalCell.classList.contains("cell-green");
  });

  const afterUpload = document.getElementById(`after-upload-${mainId}`);
  const allBillsUploaded = afterUpload && !afterUpload.classList.contains("d-none");

  const isPrefilled =
    saveBtn.dataset.mode === "edit" ||
    saveBtn.textContent.trim().toUpperCase().includes("EDIT");

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
(() => {
  console.log("🚀 Invoice readiness logic (final stable version) initialized");

  function enableGenerateBtn() {
    const container = document.getElementById("generateInvoiceBtnContainer");
    const button = document.getElementById("generateInvoiceBtn");
    if (!container || !button) return;

    // force enable
    container.style.display = "flex";
    container.classList.remove("disabled", "d-none");
    button.removeAttribute("disabled");
    button.classList.remove("disabled");
    button.style.opacity = "1";
    button.style.pointerEvents = "auto";
  }

  function disableGenerateBtn() {
    const container = document.getElementById("generateInvoiceBtnContainer");
    const button = document.getElementById("generateInvoiceBtn");
    if (!container || !button) return;

    container.style.display = "none";
    button.setAttribute("disabled", true);
    button.classList.add("disabled");
    button.style.pointerEvents = "none";
    button.style.opacity = "0.5";
  }

  function checkIfInvoiceCanBeGenerated() {
    const cards = document.querySelectorAll(".card.table-custom-card");
    if (!cards.length) return disableGenerateBtn();

    let allReady = true;
    cards.forEach((card) => {
      const mainId = card.querySelector(".start-date")?.dataset.mainId;
      const rows = card.querySelectorAll("tr[data-utility_id]");
      const allGreen = Array.from(rows).every(row =>
        row.querySelector(".total-row-cell")?.classList.contains("cell-green")
      );

      const afterUpload = document.getElementById(`after-upload-${mainId}`);
      const billUploaded = afterUpload && !afterUpload.classList.contains("d-none");

      if (!allGreen || !billUploaded) allReady = false;
    });

    if (allReady) {
      console.log("✅ All cards ready → Generate Invoice ENABLED (force)");
      enableGenerateBtn();
    } else {
      console.log("⛔ Not ready → Generate Invoice DISABLED");
      disableGenerateBtn();
    }
  }

  // hooks
  document.addEventListener("billUploadSuccess", checkIfInvoiceCanBeGenerated);
  document.addEventListener("utilitySaveSuccess", checkIfInvoiceCanBeGenerated);

  const prevRecalc = window.recalcTable;
  window.recalcTable = function (table) {
    if (typeof prevRecalc === "function") prevRecalc(table);
    checkIfInvoiceCanBeGenerated();
  };

  document.addEventListener("DOMContentLoaded", () => setTimeout(checkIfInvoiceCanBeGenerated, 800));
})();
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
  const invoiceMonth = "{{ $invoiceMonth }}";
  const currentMonth = "{{ $currentMonth }}";
  const previousMonth = "{{ $previousMonth }}";
  
  const isEditableMonth = [currentMonth, previousMonth].includes(invoiceMonth);

  if (!isEditableMonth) {
    console.log(`${invoiceMonth} is locked (not editable month)`);

    document.querySelectorAll("td[contenteditable]").forEach(td => {
      td.setAttribute("contenteditable", "false");
      td.classList.add("bg-light");
    });

    document.querySelectorAll(".utility-toggle-btn, #generateInvoiceBtnContainer, [id^='upload-section-']").forEach(el => {
      el.classList.add("d-none");
    });
  }
});
</script>
<script>
/* ✅ Safe, optimized date change handler (no duplicate triggers, debounced) */
if (!window.dateSaveHandlerBound) {
  window.dateSaveHandlerBound = true;

  let dateSaveTimer = null;
  let lastPayload = null;

  document.addEventListener("change", (e) => {
    const input = e.target;
    if (!input.classList.contains("start-date") && !input.classList.contains("end-date")) return;

    const card = input.closest(".card.table-custom-card");
    const mainId = input.dataset.mainId;
    if (!mainId || !card) return;

    const propertyId = "{{ $property->id }}";
    const invoiceMonth = "{{ $invoiceMonth }}";
    const startInput = card.querySelector(`.start-date[data-main-id="${mainId}"]`);
    const endInput = card.querySelector(`.end-date[data-main-id="${mainId}"]`);
    const startDate = startInput?.value || null;
    const endDate = endInput?.value || null;

    // prepare payload
    const payload = {
      property_id: propertyId,
      utility_id: mainId,
      invoice_month: invoiceMonth,
      start_date: startDate,
      end_date: endDate,
    };

    // skip if same as last one (no change)
    if (JSON.stringify(payload) === JSON.stringify(lastPayload)) {
      console.log("⏭️ Skipping duplicate date save request");
      return;
    }
    lastPayload = payload;

    // clear any pending request
    clearTimeout(dateSaveTimer);

    // debounce: only fire after 500 ms
    dateSaveTimer = setTimeout(async () => {
      try {
        const response = await fetch("{{ route('utility-invoices.save-dates') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify(payload)
        });

        const data = await response.json();
        if (response.ok && data.status === "success") {
          toastrs("success", data.message || "Dates saved successfully", "success");
        } else {
          toastrs("error", data.message || "Failed to save dates", "error");
        }
      } catch (err) {
        console.error("Date save failed:", err);
        toastrs("error", "Network error while saving dates", "error");
      }
    }, 500);
  });
}
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    console.log("♻️ Final override — syncing button enable state");

    const btn = document.getElementById("generateInvoiceBtn");
    const container = document.getElementById("generateInvoiceBtnContainer");

    if (!btn || !container) {
      console.warn("Generate Invoice button not found");
      return;
    }

    // If script earlier said it should be enabled, reapply
    if (!btn.disabled && container.style.display === "flex") {
      btn.removeAttribute("disabled");
      btn.classList.remove("disabled");
      container.classList.remove("disabled", "d-none");
      container.style.display = "flex";
      btn.style.opacity = "1";
      console.log("✅ Final override applied — Button re-enabled and visible");
    }

    // Re-run readiness check to ensure latest state applies
    if (typeof window.runInvoiceCheck === "function") {
      window.runInvoiceCheck();
    }
  }, 2000); // wait for all earlier scripts to finish locking
});
</script>
