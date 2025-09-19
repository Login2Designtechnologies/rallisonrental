@extends('layouts.main')

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
                    @php
                        $filteredProperties = $properties->filter(function($property) {
                            return $property->utilities->count() > 0 && $property->tenants->count() > 0;
                        });
                    @endphp

                    @forelse($filteredProperties as $property)
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

        <div class="table_data">
            <!-- Company Tables -->
            @if($filteredProperties->isNotEmpty())
                @foreach($filteredProperties as $property)
                    <div id="companyDetails" property_id="{{ $property->id }}" class="d-none">
                        @forelse($property->utilities->where('status', 1)->sortBy('company_name') as $u)
                            <h4 class="mt-4">{{ $u->company_name }}</h4>
                        @empty
                            <p class="text-muted">No active utilities found for this property.</p>
                        @endforelse
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>


    <!-- [ Main Content ] end -->



@stop

@push('script')
    <script>
        $(document).ready(function() {
            $('#properties').on('change', function() {
                let propertyId = $(this).val();
                if (propertyId) {
                    $.ajax({
                        url: "{{ route('utility-invoices.create') }}",
                        type: "GET",
                        data: {
                            property_id: propertyId
                        },
                        beforeSend: function() {
                            $('.table_data').html('<p class="text-muted">Loading...</p>');
                        },
                        success: function(response) {
                            $('.table_data').html(response);
                            initDatepicker();
                            initMonthNav();
                        },
                        error: function(xhr) {
                            $('.table_data').html(
                                '<p class="text-danger">Something went wrong. Please try again.</p>'
                                );
                        }
                    });
                } else {
                    $('.table_data').empty();
                }
            });
        });
    </script>

    <script>
        $(function() {
            // --- Helpers ---
            const toNumber = (v) => {
                if (v == null) return 0;
                const s = String(v).replace(/[^\d.]/g, '');
                if (!s) return 0;
                const fixed = s.replace(/\.(?=.*\.)/g, ''); // keep only first dot
                return parseFloat(fixed) || 0;
            };
            const formatMoney = (n) => (isFinite(n) ? Number(n).toFixed(2) : '0.00');
            const almostEqual = (a, b, eps = 0.0001) => Math.abs(a - b) < eps;

            // Read renter IDs that exist for this table (from the totals footer cells)
            function getRenterIdsForTable($table) {
                const ids = [];
                $table.find('tbody tr.fw-bold [data-renter-is]').each(function() {
                    const id = $(this).data('renter-is');
                    if (id != null) ids.push(String(id));
                });
                return ids;
            }

            // Enforce numeric in contenteditable cells
            function enforceNumeric($cell) {
                const txt = $cell.text();
                const cleaned = txt.replace(/[^\d.]/g, '').replace(/\.(?=.*\.)/g, '');
                if (txt !== cleaned) $cell.text(cleaned);
            }

            // Recalculate a single data row: sum % and fill data-renter-price
            function recalcRow($tr, renterIds) {
                // price for the row
                const price = toNumber($tr.find('.price-cell').text());

                // sum row % and set data-renter-price on each renter cell
                let rowPct = 0;

                renterIds.forEach(id => {
                    const $cell = $tr.find(`.renter-cell-${id}[data-renter-id="${id}"]`);
                    if ($cell.length) {
                        const pct = toNumber($cell.text());
                        rowPct += pct;

                        const amount = price * (pct / 100);
                        // write computed amount into attribute (and title for quick hover check)
                        $cell.attr('data-renter-price', formatMoney(amount));
                        $cell.attr('title', `Amount: $${formatMoney(amount)}`);
                    }
                });

                // Update row total % + color
                const $rowTotal = $tr.find('.total-row-cell');
                $rowTotal.text(rowPct % 1 === 0 ? rowPct.toFixed(0) : rowPct.toFixed(2));
                $rowTotal.removeClass('cell-red cell-green')
                    .addClass(almostEqual(rowPct, 100) ? 'cell-green' : 'cell-red');
            }

            // Recalculate totals for ONE table (company block)
            function recalcTableTotals($table, renterIds) {
                const $rows = $table.find('tbody tr').not('.fw-bold'); // data rows
                const renterTotals = {}; // renterId -> sum amount ($)
                let tablePriceTotal = 0;

                $rows.each(function() {
                    const $tr = $(this);
                    const price = toNumber($tr.find('.price-cell').text());
                    tablePriceTotal += price;

                    renterIds.forEach(id => {
                        const $cell = $tr.find(`.renter-cell-${id}[data-renter-id="${id}"]`);
                        if (!$cell.length) return;
                        const pct = toNumber($cell.text());
                        const amount = price * (pct / 100);
                        renterTotals[id] = (renterTotals[id] || 0) + amount;
                    });
                });

                // Price total for this table
                $table.find('.column-total').text(formatMoney(tablePriceTotal));

                // Per-renter totals (amounts) in this table’s footer row
                renterIds.forEach(id => {
                    $table.find(`.renter-total-cell-${id}[data-renter-is="${id}"]`)
                        .text(formatMoney(renterTotals[id] || 0));
                });
            }

            // Recalculate everything for ONE table (rows + totals)
            function recalcTable($table) {
                const renterIds = getRenterIdsForTable($table);
                $table.find('tbody tr').not('.fw-bold').each(function() {
                    recalcRow($(this), renterIds);
                });
                recalcTableTotals($table, renterIds);
            }

            // GRAND TOTALS across all company tables
            function recalcGrandTotals() {
                let grandPrice = 0;

                // Sum all company price totals
                $('table.custom-bg-table').each(function() {
                    const t = toNumber($(this).find('.column-total').text());
                    grandPrice += t;
                });

                // Write grand total price
                $('.grand_total').text(formatMoney(grandPrice));

                // Collect all renter IDs from the grand total header cells
                const renterIds = [];
                $('#grandTotalTable thead th').each(function() {
                    // headers like "Name Total %" don't carry IDs; use the body cells’ classes instead
                    const cls = this.className || '';
                    // not reliable; we’ll instead scan body cells:
                });

                // Better: find renter Ids from grand total body cells
                const foundIds = new Set();
                $('#grandTotalTable tbody td[class^="grand-renter-total-"]').each(function() {
                    const m = this.className.match(/grand-renter-total-(\d+)/);
                    if (m) foundIds.add(m[1]);
                });

                // Sum each renter’s total across all company tables’ footer cells
                foundIds.forEach(id => {
                    let sum = 0;
                    $(`.renter-total-cell-${id}[data-renter-is="${id}"]`).each(function() {
                        sum += toNumber($(this).text());
                    });
                    $(`.grand-renter-total-${id}`).text(formatMoney(sum));
                });
            }

            // Recalc all tables + grand totals
            function recalcAll() {
                $('table.custom-bg-table').each(function() {
                    recalcTable($(this));
                });
                recalcGrandTotals();
            }

            // --- Events ---
            // Price cell editing
            $(document).on('input blur', '.price-cell', function() {
                const $cell = $(this);
                enforceNumeric($cell);
                const $table = $cell.closest('table.custom-bg-table');
                recalcTable($table);
                recalcGrandTotals();
            });

            // Renter % editing
            $(document).on('input blur', 'td[data-renter-id]', function() {
                const $cell = $(this);
                enforceNumeric($cell);
                const $table = $cell.closest('table.custom-bg-table');
                recalcTable($table);
                recalcGrandTotals();
            });

            // Initial compute (for content already on page)
            recalcAll();

            // If you inject via AJAX, call recalcAll() after you set innerHTML:
            // $.get('/endpoint', function(html){
            //   $('#companyDetails').html(html);
            //   recalcAll();
            // });
        });
    </script>

    <script>
(function () {
  // --- helpers ---
  const monthNames = [
    "January","February","March","April","May","June",
    "July","August","September","October","November","December"
  ];
  const monthMap = {
    January:'01', February:'02', March:'03', April:'04', May:'05', June:'06',
    July:'07', August:'08', September:'09', October:'10', November:'11', December:'12'
  };
  const normalizeMonth = (d) => new Date(d.getFullYear(), d.getMonth(), 1);
  const MAX_MONTH = normalizeMonth(new Date());
  let monthState = normalizeMonth(new Date());

  // Safely render label/arrows; return true if elements found
  function renderMonthNav() {
    const labelEl = document.getElementById('monthLabel');
    const nextEl  = document.getElementById('nextMonth');
    if (!labelEl || !nextEl) return false;

    labelEl.textContent = `${monthNames[monthState.getMonth()]} ${monthState.getFullYear()}`;

    if (normalizeMonth(monthState) >= MAX_MONTH) {
      nextEl.classList.add('disabled');
      nextEl.setAttribute('aria-disabled','true');
    } else {
      nextEl.classList.remove('disabled');
      nextEl.removeAttribute('aria-disabled');
    }
    return true;
  }

  function initMonthNav() {
    monthState = normalizeMonth(new Date());
    renderMonthNav();
  }

  // Expose public API (so you can call it after AJAX)
  window.initMonthNav = initMonthNav;

  // Parse "September 2025" -> "2025-09" (null-safe)
  window.getInvoiceYYYYMM = function getInvoiceYYYYMM() {
    const labelEl = document.getElementById('monthLabel');
    const text = (labelEl && labelEl.textContent || '').trim();
    if (!text) return null;
    const [mName, year] = text.split(/\s+/);
    return `${year}-${monthMap[mName]}`;
  };

  // Arrow clicks (delegated so it still works after AJAX)
  document.addEventListener('click', (e) => {
    if (e.target.id === 'prevMonth') {
      monthState.setMonth(monthState.getMonth() - 1);
      renderMonthNav();
    }
    if (e.target.id === 'nextMonth') {
      const t = new Date(monthState);
      t.setMonth(t.getMonth() + 1);
      if (normalizeMonth(t) > MAX_MONTH) return;  // block future
      monthState = t;
      renderMonthNav();
    }
  });

  // Init on first page load (if HTML is present)
  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('monthLabel')) {
      initMonthNav();
    }
  });
})();
</script>


    {{-- SAVE --}}
    <script>
(function () {
  // ---------------- Helpers ----------------
  const toNumber = (v) => {
    if (v == null) return 0;
    const s = String(v).replace(/[^\d.]/g, '').replace(/\.(?=.*\.)/g, '');
    return parseFloat(s) || 0;
  };

  const monthMap = {
    January:'01', February:'02', March:'03', April:'04', May:'05', June:'06',
    July:'07', August:'08', September:'09', October:'10', November:'11', December:'12'
  };

  const parseMonthLabelToYYYYMM = (label) => {
    // "September 2025" -> "2025-09"
    const [monthName, year] = label.trim().split(/\s+/);
    return `${year}-${monthMap[monthName]}`;
  };

  // renter IDs present in a row
  function renterIdsInRow($tr) {
    const ids = new Set();
    $tr.find('td[data-renter-id]').each(function () {
      const id = $(this).data('renter-id');
      if (id != null) ids.add(String(id));
    });
    return Array.from(ids);
  }

  // require each row total cell to be green (100%)
  function allRowsBalanced() {
    let ok = true;
    $('#companyDetails table.custom-bg-table tbody tr').not('.fw-bold').each(function () {
      const $total = $(this).find('.total-row-cell');
      if (!$total.hasClass('cell-green')) { ok = false; return false; }
    });
    return ok;
  }

  function firstUnbalancedRow() {
    let $bad = $();
    $('#companyDetails table.custom-bg-table tbody tr').not('.fw-bold').each(function () {
      const $total = $(this).find('.total-row-cell');
      if (!$total.hasClass('cell-green')) { $bad = $(this); return false; }
    });
    return $bad;
  }

  // ---------------- Build payload ----------------
  function buildPayload() {
    const propertyId = Number($('#companyDetails').attr('property_id'));
    const dueDate    = $('#generateInvoiceBtnContainer input[name="due_date"]').val() || null;

    // Month/Year from the nav label
    const label         = $('#monthLabel').text();
    const invoiceMonth  = parseMonthLabelToYYYYMM(label); // "YYYY-MM"

    // rentersMap[tenant_id] = { total: number, details: [...] }
    const rentersMap = {};

    $('table.custom-bg-table').each(function () {
      const $table = $(this);

      // data rows (skip footer .fw-bold)
      $table.find('tbody tr').not('.fw-bold').each(function () {
        const $tr = $(this);

        const utilityId = Number($tr.attr('data-utility_id')) || null;
        const category  = $tr.children('td').eq(0).text().trim();
        const price     = toNumber($tr.find('.price-cell').text());

        // start/end dates
        const startDate = $tr.find('input.form-control').eq(0).val() || null;
        const endDate   = $tr.find('input.form-control').eq(1).val() || null;

        // each renter % in this row
        renterIdsInRow($tr).forEach((id) => {
          const $cell  = $tr.find(`td[data-renter-id="${id}"]`);
          const pct    = toNumber($cell.text());
          const amount = +(price * (pct / 100)).toFixed(2);

          if (!rentersMap[id]) rentersMap[id] = { total: 0, details: [] };
          rentersMap[id].total += amount;

          rentersMap[id].details.push({
            property_utility_id: utilityId,
            category: category,
            amount: amount,
            start_date: startDate,
            end_date: endDate
          });
        });
      });
    });

    const invoices = Object.entries(rentersMap).map(([tenantId, data]) => ({
      tenant_id: Number(tenantId),
      amount: +Number(data.total).toFixed(2),
      details: data.details
    }));

    return {
      property_id: propertyId,
      invoice_month: invoiceMonth,
      due_date: dueDate,
      invoices: invoices
    };
  }

  // ---------------- Submit handler ----------------
  $(document).on('click', '#generateInvoiceBtnContainer button.btn-success', function () {
    // Gate: all rows must be balanced (100% → green)
    if (!allRowsBalanced()) {
      const $bad = firstUnbalancedRow();
      if ($bad.length) {
        $('html, body').animate({ scrollTop: $bad.offset().top - 120 }, 350);
        $bad.addClass('invalid-row');
        setTimeout(() => $bad.removeClass('invalid-row'), 1500);
      }
      Swal.fire({
      icon: 'warning',
      title: 'Incomplete Split',
      text: 'Each row must total 100% (last column should be green) before generating invoices.',
      confirmButtonText: 'Okay'
    });
      return;
    }

    const payload = buildPayload();

    // Nothing to submit if no renters/details found
    if (!payload.invoices.length) {
      alert('No invoice data found. Please enter amounts/percentages first.');
      return;
    }

    // Show JSON BEFORE request
    console.log('Utility Invoice Payload →', JSON.stringify(payload, null, 2));

axios.post("{{ route('utility-invoices.generate') }}", payload, {
  headers: {
    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content,
    'Accept': 'application/json'
  }
})
.then(({data}) => {
  console.log('Server response:', data);
  Swal.fire({
    icon: 'success',
    title: 'Invoices Processed',
    html: `Created <b>${data.created?.length || 0}</b> invoice(s)<br>
           Updated <b>${data.updated?.length || 0}</b> invoice(s) successfully.`
  });
})
.catch(err => {
  console.error('Error creating invoices:', err?.response?.data || err);
  Swal.fire({
    icon: 'error',
    title: 'Failed',
    text: 'Failed to create/update invoices. Check console.'
  });
});

  });
})();
</script>

@endpush
