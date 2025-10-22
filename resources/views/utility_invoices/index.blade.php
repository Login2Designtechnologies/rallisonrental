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
                    $utilitiescatg = DB::table('utilities_catg')->where('property_id',$property->id)->where('user_id',auth()->id())->where('status','1')->get();
                  @endphp
                    <div id="companyDetails" property_id="{{ $property->id }}" class="d-none">
                        @forelse($utilitiescatg as $u)
                            <h4 class="mt-4">{{ $u->name }}</h4>
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
    // Add unified input listener
    $(document).on("input", ".price-cell, td[data-renter-id]", function() {
      const $t = $(this);
      let text = $t.text().replace(/[^\d.%]/g, "");
      if ($t.is("td[data-renter-id]")) text = parseFloat(text.replace("%", "")) + "%";
      $t.text(text);
      const $table = $t.closest("table.custom-bg-table");
      recalcTable($table);
    });

    // Initial calculation
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

/* ---------- Single Row Calculation ---------- */
window.recalcRow = function(row) {
  const priceCell = row.querySelector(".price-cell");
  const price = parseFloat((priceCell?.textContent || "").replace(/[^\d.]/g, "")) || 0;

  // Sum all tenant share values
  let totalShare = 0;
  row.querySelectorAll("td[data-renter-id]").forEach(cell => {
    const text = cell.textContent.replace("%", "").trim();
    const val = parseFloat(text) || 0;
    totalShare += val;
  });

  // Update total cell display
  const totalCell = row.querySelector(".total-row-cell");
  if (totalCell) {
    totalCell.textContent = `${totalShare.toFixed(2)}`;
    totalCell.classList.remove("cell-red", "cell-green");

    // ✅ Green only if tenant shares total == price
    const isMatched = price > 0 && totalShare === price;
    totalCell.classList.add(isMatched ? "cell-green" : "cell-red");
  }

  return price;
};

/* ---------- Table Calculation ---------- */
window.recalcTable = function(table) {
  if (!table) return;

  let totalPrice = 0;
  const renterTotals = {};

  table.querySelectorAll("tbody tr[data-utility_id]").forEach((row) => {
    const price = recalcRow(row);
    totalPrice += price;

    // accumulate renter data
    row.querySelectorAll("td[data-renter-id]").forEach((cell) => {
      const rid = cell.getAttribute("data-renter-id");
      const pct = window.toNumber(cell.textContent);
      const amount = (price * pct) / 100;
      renterTotals[rid] = (renterTotals[rid] || 0) + amount;
    });
  });

  // Update column total
  const colTotalEl = table.querySelector(".column-total");
  if (colTotalEl) colTotalEl.textContent = `$${window.formatMoney(totalPrice)}`;

  for (const [rid, amt] of Object.entries(renterTotals)) {
    const cell = table.querySelector(`.renter-total-cell-${rid}`);
    if (cell) cell.textContent = `$${window.formatMoney(amt)}`;
  }

  const totalCol = table.querySelector(".table-total-cell");
  if (totalCol) totalCol.textContent = "—";

  recalcGrandTotals();
};

/* ---------- Grand Totals Calculation ---------- */
window.recalcGrandTotals = function() {
  let grandTotalPrice = 0;
  const grandTotals = {};

  document.querySelectorAll("table.custom-bg-table").forEach(table => {
    const tablePrice = window.toNumber(table.querySelector(".column-total")?.textContent);
    grandTotalPrice += tablePrice;

    table.querySelectorAll("tbody tr[data-utility_id]").forEach(row => {
      const price = window.toNumber(row.querySelector(".price-cell")?.textContent);
      row.querySelectorAll("td[data-renter-id]").forEach(cell => {
        const rid = cell.getAttribute("data-renter-id");
        const pct = window.toNumber(cell.textContent);
        const amount = (price * pct) / 100;
        grandTotals[rid] = (grandTotals[rid] || 0) + amount;
      });
    });
  });

  const gPrice = document.querySelector(".grand_total");
  if (gPrice) gPrice.textContent = `$${window.formatMoney(grandTotalPrice)}`;

  for (const [rid, amt] of Object.entries(grandTotals)) {
    const cell = document.querySelector(`.grand-renter-total-${rid}`);
    if (cell) cell.textContent = `$${window.formatMoney(amt)}`;
  }
};

</script>
