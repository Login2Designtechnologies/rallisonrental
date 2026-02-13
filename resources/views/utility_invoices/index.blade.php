@extends('layouts.app')

@section('content')


<style>
  /* optional visual nudge */
  .invalid-row { outline: 2px solid #dc3545; outline-offset: -2px; }
  @keyframes shake { 10%,90%{transform:translateX(-1px)} 20%,80%{transform:translateX(2px)}
    30%,50%,70%{transform:translateX(-4px)} 40%,60%{transform:translateX(4px)} }
  .shake { animation: shake .5s; }
  #generateInvoiceBtnContainer .disabled { opacity:.5; pointer-events:none; }

        .cell-red {
            background-color: red !important;
        }

        .cell-green {
            background-color: green !important;
        }

        .disabled {
            opacity: .35;
            pointer-events: none;
        }
    </style>

<!-- [ Main Content ] start -->
<div class="card border bg-custom w-100">
    <div class="card-body">
        <!-- Property Select -->
        <div class="row justify-content-between">
            <div class="col-md-4 mb-3">
                <label for="properties" class="form-label fw-bold">Select Property</label>
                <select id="properties" class="form-control form-select">
                    <option value="">-- Select Property --</option>

                    @forelse($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @empty
                        <option value="" disabled>No properties available with active tenants and utilities.</option>
                    @endforelse
                </select>
            </div>

            <div class="col-md-4 mb-3 text-end">
                <a href="{{ route('utility-invoices.all') }}" class="btn btn-primary">All Invoices</a>
            </div>
        </div>
        
        <div class="text-center month-navigation mb-3 d-none">
          <button id="prevMonth" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i></button>
          <span id="monthLabel" class="fw-bold mx-3"></span>
          <button id="nextMonth" class="btn btn-light btn-sm"><i class="bi bi-arrow-right"></i></button>
        </div>
        <div class="table_data">
            <!-- Company Tables -->
            @if($properties->isNotEmpty())
                @foreach($properties as $property)
                  @php
                    $utilitiescatg = DB::table('utilities_main as m')
                      ->join('utilities_sub as s', 'm.id', '=', 's.utility_main_id')
                      ->where('m.property_id', $property->id)
                      ->where('m.user_id', auth()->id())
                      ->where('m.status', '1')
                      ->where('s.status', '1')
                      ->select(
                          'm.id as main_id',
                          'm.name as main_name',
                          's.id as sub_id',
                          's.sub_category_name'
                      )
                      ->get()
                      ->groupBy('main_name');
                  @endphp            
                    <div id="companyDetails" property_id="{{ $property->id }}" class="d-none">
                        @forelse($utilitiescatg as $mainName => $subcategories)
                          <h4 class="mt-4">{{ $mainName }}</h4>
                        @empty
                            <p class="text-muted">No active utilities found for this property.</p>
                        @endforelse
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@if(isset($invoice) && $invoice->status === 'delivered')
  <script>
    $(function(){
      $('td[contenteditable], input').attr('readonly', true).attr('contenteditable', false);
      $('#generateInvoiceBtnContainer button').prop('disabled', true);
    });
  </script>
@endif

<div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Invoice Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="invoicePreviewBody">
        <!-- Previews injected here -->
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="confirmGenerateBtn" class="btn btn-success">Confirm & Generate</button>
      </div>
    </div>
  </div>
</div>
<!-- [ Main Content ] end -->
@stop

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
  const toNumber = (v) => parseFloat(String(v).replace(/[^\d.]/g, "")) || 0;
  const formatMoney = (n) => Number(n).toFixed(2);

  // Recalculate a single row
  function recalcRow($row) {
    const price = toNumber($row.find(".price-cell").text());
    let totalPct = 0;
    $row.find("td[data-renter-id]").each(function() {
      totalPct += toNumber($(this).text().replace("%", ""));
    });
    $row.find(".total-row-cell").text(totalPct.toFixed(0) + "%");
    return price;
  }

  // Recalculate totals for one table
  function recalcTable($table) {
    $table = $($table);
    let totalPrice = 0;
    const renterTotals = {};

    $table.find("tbody tr[data-utility_id]").each(function() {
      const $row = $(this);
      const price = recalcRow($row);
      totalPrice += price;

      $row.find("td[data-renter-id]").each(function() {
        const rid = $(this).data("renter-id");
        const pct = toNumber($(this).text());
        const amount = (price * pct) / 100;
        renterTotals[rid] = (renterTotals[rid] || 0) + amount;
      });
    });

    $table.find(".column-total").text(`$${formatMoney(totalPrice)}`);
    for (const [rid, amt] of Object.entries(renterTotals)) {
      $table.find(`.renter-total-cell-${rid}`).text(`$${formatMoney(amt)}`);
    }

    recalcGrandTotals();
  }

  // Calculate grand totals across all tables
  function recalcGrandTotals() {
    let grandTotalPrice = 0;
    const grandTotals = {};

    $("table.custom-bg-table").each(function() {
      const $table = $(this);
      grandTotalPrice += toNumber($table.find(".column-total").text());

      $table.find("tbody tr[data-utility_id]").each(function() {
        const $row = $(this);
        const price = toNumber($row.find(".price-cell").text());
        $row.find("td[data-renter-id]").each(function() {
          const rid = $(this).data("renter-id");
          const pct = toNumber($(this).text());
          const amount = (price * pct) / 100;
          grandTotals[rid] = (grandTotals[rid] || 0) + amount;
        });
      });
    });

    $(".grand_total").text(`$${formatMoney(grandTotalPrice)}`);
    for (const [rid, amt] of Object.entries(grandTotals)) {
      $(`.grand-renter-total-${rid}`).text(`$${formatMoney(amt)}`);
    }
  }

  // Rebind handlers after AJAX load
  function bindDynamicHandlers() {
    // Remove old listeners
    $(document).off("input", ".price-cell, td[data-renter-id]");
    
    // ❌ Disable old % logic
    if (false) {
      $(document).on("input", ".price-cell, td[data-renter-id]", function() {
        const $t = $(this);
        let text = $t.text().replace(/[^\d.%]/g, "");
        if ($t.is("td[data-renter-id]")) text = parseFloat(text.replace("%", "")) + "%";
        $t.text(text);
        const $table = $t.closest("table.custom-bg-table");
        recalcTable($table);
      });
    }

    // Initial calculation (keep this)
    $("table.custom-bg-table").each(function() {
      recalcTable(this);
    });
    recalcGrandTotals();
  }

  // Handle property dropdown
  $("#properties").on("change", function() {
    const propertyId = $(this).val();
    const invoiceMonth = getInvoiceYYYYMM();
    if (!propertyId) return $(".table_data").empty();

    $(".month-navigation").removeClass("d-none");

    $(".table_data").html("<p class='text-muted'>Loading...</p>");
    $.ajax({
      url: "{{ route('utility-invoices.create') }}",
      type: "GET",
      data: { property_id: propertyId, invoice_month: invoiceMonth },
      success: function(html) {
        $(".table_data").html(html);

        // 🧩 Fix: Remove old inline % scripts from AJAX-loaded HTML
        $('script:contains("recalcRow")').remove();
        document.dispatchEvent(new Event("ajaxPageLoaded"));

        bindDynamicHandlers();
        setTimeout(() => {
          document.querySelectorAll("table.custom-bg-table").forEach((table) => {
            window.recalcTable(table);
          });
          window.recalcGrandTotals();
        }, 300);

        if (typeof initMonthNav === "function") initMonthNav();
      },
      error: function() {
        $(".table_data").html("<p class='text-danger'>Error loading data.</p>");
      }
    });
  });

  bindDynamicHandlers();
});
</script>

<script>
window.getInvoiceYYYYMM = function () {
  const label = document.getElementById("monthLabel")?.textContent?.trim() || "";
  const monthMap = {
    January: "01", February: "02", March: "03", April: "04", May: "05", June: "06",
    July: "07", August: "08", September: "09", October: "10", November: "11", December: "12"
  };
  const match = label.match(/([A-Za-z]+)\s*[-]?\s*(\d{4})/);
  if (match && monthMap[match[1]]) {
    return `${match[2]}-${monthMap[match[1]]}`;
  }
  // fallback to current month if nothing found
  return new Date().toISOString().slice(0, 7);
};
</script>

<script>
window.initMonthNav = function () {
  const monthLabel = document.getElementById("monthLabel");
  const prevBtn = document.getElementById("prevMonth");
  const nextBtn = document.getElementById("nextMonth");
  const invoiceMonthInput = document.getElementById("invoiceMonth");

  // stop if not on this page or elements not loaded yet
  if (!monthLabel || !prevBtn || !nextBtn) return;

  // list of month names
  const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];

  // Helper to update visible label and hidden input
  function updateMonthLabel(date) {
    const label = `${monthNames[date.getMonth()]} - ${date.getFullYear()}`;
    monthLabel.textContent = label;
    if (invoiceMonthInput)
      invoiceMonthInput.value = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, "0")}`;
  }

  // Initialize month value
  const initVal = invoiceMonthInput?.value || new Date().toISOString().slice(0, 7);
  let [year, month] = initVal.split("-");
  let current = new Date(parseInt(year), parseInt(month) - 1, 1);
  updateMonthLabel(current);

  prevBtn.onclick = () => {
    current.setMonth(current.getMonth() - 1);
    updateMonthLabel(current);
    $("#properties").trigger("change"); // 🔁 refresh table for previous month
  };
  nextBtn.onclick = () => {
    current.setMonth(current.getMonth() + 1);
    updateMonthLabel(current);
    $("#properties").trigger("change"); // 🔁 refresh table for next month
  };
};

// ✅ Run once when page first loads
document.addEventListener("DOMContentLoaded", () => {
  initMonthNav();
});
</script>

<script>
  // ----------------- GLOBAL TOTAL LOGIC -----------------
window.toNumber = (v) => parseFloat(String(v).replace(/[^\d.]/g, "")) || 0;
window.formatMoney = (n) => Number(n).toFixed(2);

function parseCurrencyText(text) {
  if (!text && text !== 0) return 0;
  // remove $ and commas and other non-number chars except dot and minus
  const n = String(text).replace(/[^0-9.-]/g, '');
  return parseFloat(n) || 0;
}
function formatCurrency(n) {
  return '$' + Number(n || 0).toFixed(2);
}

/* ---------- Single Row Calculation ---------- */
window.recalcRow = function(rowEl) {
  const row = rowEl instanceof Element ? rowEl : document.querySelector(rowEl);
  if (!row) return 0;

  const priceCell = row.querySelector('.price-cell');
  const totalCell  = row.querySelector('.total-row-cell');

  const price = parseCurrencyText(priceCell?.textContent);

  // sum tenant absolute amounts
  let tenantSum = 0;
  row.querySelectorAll('td[data-renter-id]').forEach(td => {
    tenantSum += parseCurrencyText(td.textContent);
  });

  // update total column to show absolute amount difference or sum
  if (totalCell) {
    // show tenant sum formatted
    totalCell.textContent = formatCurrency(tenantSum);

    // remove existing classes
    totalCell.classList.remove('cell-red','cell-green');

    // small tolerance for floating point arithmetic
    const EPS = 0.01;
    if (Math.abs(tenantSum - price) <= EPS && price > 0) {
      totalCell.classList.add('cell-green');
    } else {
      totalCell.classList.add('cell-red');
    }
  }

  return price;
};

/* ---------- Table Calculation ---------- */
window.recalcTable = function(tableEl) {
  const table = tableEl instanceof Element ? tableEl : document.querySelector(tableEl);
  if (!table) return;

  let columnTotal = 0;
  const renterTotals = {}; // absolute amounts per renter id

  table.querySelectorAll('tbody tr[data-utility_id]').forEach(row => {
    const price = window.recalcRow(row);
    columnTotal += price;

    row.querySelectorAll('td[data-renter-id]').forEach(td => {
      const rid = td.getAttribute('data-renter-id');
      const amt = parseCurrencyText(td.textContent);
      renterTotals[rid] = (renterTotals[rid] || 0) + amt;
    });
  });

  // update column total cell
  const colTotalEl = table.querySelector('.column-total');
  if (colTotalEl) colTotalEl.textContent = formatCurrency(columnTotal);

  // update per-renter totals inside the table footer/total row
  for (const [rid, amt] of Object.entries(renterTotals)) {
    const cell = table.querySelector(`.renter-total-cell-${rid}`);
    if (cell) cell.textContent = formatCurrency(amt);
  }

  // ensure grand totals recalculated
  if (typeof window.recalcGrandTotals === 'function') window.recalcGrandTotals();

  // after recalculation, check invoice readiness
  if (typeof window.checkIfInvoiceCanBeGenerated === 'function') {
    window.checkIfInvoiceCanBeGenerated();
  }
};
/* ---------- Grand Totals Calculation ---------- */
window.recalcGrandTotals = function() {
  let grandTotalPrice = 0;
  const grandTotals = {};

  document.querySelectorAll('table.custom-bg-table').forEach(table => {
    const colTotal = parseCurrencyText(table.querySelector('.column-total')?.textContent);
    grandTotalPrice += colTotal;

    table.querySelectorAll('tbody tr[data-utility_id]').forEach(row => {
      row.querySelectorAll('td[data-renter-id]').forEach(td => {
        const rid = td.getAttribute('data-renter-id');
        const amt = parseCurrencyText(td.textContent);
        grandTotals[rid] = (grandTotals[rid] || 0) + amt;
      });
    });
  });

  const gPrice = document.querySelector('.grand_total');
  if (gPrice) gPrice.textContent = formatCurrency(grandTotalPrice);

  for (const [rid, amt] of Object.entries(grandTotals)) {
    const cell = document.querySelector(`.grand-renter-total-${rid}`);
    if (cell) cell.textContent = formatCurrency(amt);
  }
};

function bindInlineEditing() {
  // price-cell and renter cells should be editable. We sanitize and format on input/blur.
  if (false) {
    // price-cell and renter cells should be editable. We sanitize and format on input/blur.
    document.addEventListener('input', function(ev) {
      const t = ev.target;
      if (!t.matches('.price-cell, td[data-renter-id]')) return;

      let raw = t.textContent;
      raw = raw.replace(/[^\d.-]/g, '');
      const parts = raw.split('.');
      if (parts.length > 2) raw = parts.shift() + '.' + parts.join('');
      t.textContent = raw;
    });

    document.addEventListener('blur', function(ev) {
      const t = ev.target;
      if (!t.matches('.price-cell, td[data-renter-id]')) return;

      const val = parseCurrencyText(t.textContent);
      t.textContent = formatCurrency(val);

      const table = t.closest('table.custom-bg-table');
      if (table) window.recalcTable(table);
    }, true);
  }
}

/* Initialize calculations on page load (and after AJAX loads) */
document.addEventListener('DOMContentLoaded', function() {
  bindInlineEditing();

  // format existing price and tenant cells that are not yet formatted
  document.querySelectorAll('.price-cell').forEach(td => {
    const v = parseCurrencyText(td.textContent);
    td.textContent = formatCurrency(v);
  });
  document.querySelectorAll('td[data-renter-id]').forEach(td => {
    const v = parseCurrencyText(td.textContent);
    td.textContent = formatCurrency(v);
  });

  // run table recalc for all present tables
  document.querySelectorAll('table.custom-bg-table').forEach(table => {
    window.recalcTable(table);
  });
});
</script>

<script>
/* ===============================
   🔥 UNIVERSAL INLINE EDITING FIX
   (for all AJAX-loaded create pages)
   =============================== */
/* ✅ FINAL INLINE EDITING FIX — No cursor jump, smooth typing, correct totals */
(() => {
  console.log("💡 Stable inline editing logic active — cursor fix applied");

  const parseCurrency = (v) => parseFloat(String(v).replace(/[^\d.-]/g, "")) || 0;
  const formatCurrency = (n) => "$" + (parseFloat(n) || 0).toFixed(2);

  // User typing — keep cursor steady (no auto-$)
  document.addEventListener("input", (e) => {
    const t = e.target;
    if (!t.matches(".price-cell, td[data-renter-id]")) return;

    let val = t.textContent.replace(/[^\d.]/g, "");
    const parts = val.split(".");
    if (parts.length > 2) val = parts[0] + "." + parts.slice(1).join("");

    if (t.textContent !== val) {
      const sel = window.getSelection();
      const range = sel.rangeCount ? sel.getRangeAt(0) : null;
      const cursorOffset = range ? range.startOffset : 0;

      t.textContent = val;

      if (t.firstChild) {
        const newRange = document.createRange();
        newRange.setStart(t.firstChild, Math.min(cursorOffset, t.textContent.length));
        newRange.collapse(true);
        sel.removeAllRanges();
        sel.addRange(newRange);
      }
    }

    // update totals live
    const table = t.closest("table.custom-bg-table");
    if (table && typeof window.recalcTable === "function") {
      window.recalcTable(table);
    }
  });

  // On blur — format with $ and recheck totals
  document.addEventListener("blur", (e) => {
    const t = e.target;
    if (!t.matches(".price-cell, td[data-renter-id]")) return;
    const num = parseCurrency(t.textContent);
    t.textContent = formatCurrency(num);

    const table = t.closest("table.custom-bg-table");
    if (table && typeof window.recalcTable === "function") {
      window.recalcTable(table);
    }
  }, true);

  // Reinitialize after AJAX content load (like property change)
  document.addEventListener("ajaxPageLoaded", () => {
    document.querySelectorAll("table.custom-bg-table").forEach(window.recalcTable);
  });

  // Initial recalculation on load
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("table.custom-bg-table").forEach(window.recalcTable);
  });
})();
</script>