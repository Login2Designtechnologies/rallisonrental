<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-class'); ?>
    product-detail-page
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item active">
        <a href="#"><?php echo e(__('Utilities Invoice')); ?></a>
    </li>
<?php $__env->stopSection(); ?>

<style>
    .datepicker-cell.day { color: #000; }
    .form-control.custom-datepicker {
        padding: 0;
        background: inherit !important;
        border: 1px solid transparent !important;
    }
    .cell-green { background-color: #02b902 !important; }
    .cell-red { background-color: #ff0017 !important; }
    .month-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        color: white;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .month-arrow {
        cursor: pointer;
        padding: 5px 10px;
        background: #eef2f6;
        border-radius: 5px;
        user-select: none;
    }
    .month-arrow:hover {opacity: 0.8;}

    /* Force modal to be on top */
    .modal {
        z-index: 99999 !important;
    }

    #confirmYes:hover {
  background: #15be73;
  border: 1px solid #15be73;
}
</style>
<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <!-- Property Select -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="company" class="form-label fw-bold">Select Property</label>
                <select id="company" class="form-control form-select">
                    <option value="">-- Select Property --</option>
                    <option value="Demo">Demo</option>
                </select>
            </div>
        </div>

        <!-- Month Navigation (single, global - hidden by default) -->
        <div id="monthNav" class="d-flex justify-content-center align-items-center mb-3" style="display:none;">
            <span id="prevMonth" class="month-arrow fs-4 me-3" style="cursor:pointer; display:none;" onclick="changeMonth(-1)">←</span>
            <span id="monthLabel" class="fw-bold text-dark" style="display:none;"></span>
            <span id="nextMonth" class="month-arrow fs-4 ms-3" style="cursor:pointer; display:none;" onclick="changeMonth(1)">→</span>
        </div>

        <!-- Company Tables -->
        <div id="companyDetails"></div>

        <!-- Grand Total -->
        <div id="grandTotalTable" class="mt-4"></div>

        <!-- Generate Invoice Button -->
        <div id="generateInvoiceBtnContainer" class="mt-3 text-center" style="display:none;">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generateInvoiceModal">
                Generate Invoice
            </button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css-page'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker-bs5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-page'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
<script>
// Data: Each company has data per month (YYYY-MM)
const monthlyCompanyData = {
    "Eagle Mountain": {
        "2025-08": [
            { name: "Water Usage", price: 1500, renters: [10, 35, 30] },
            { name: "Water Base", price: 300, renters: [20, 50, 30] }
        ],
        "2025-09": [
            { name: "Water Usage", price: 1400, renters: [10, 30, 30] },
            { name: "Water Base", price: 350, renters: [25, 45, 30] }
        ]
    },
    "Rocky Mountain Power": {
        "2025-08": [
            { name: "Total KWH", price: 200, renters: [20, 50, 30] }
        ],
        "2025-09": [
            { name: "Total KWH", price: 250, renters: [30, 40, 30] }
        ]
    }
};

// Global month state
let globalMonthDate = new Date();

// Selected companies
let selectedCompanies = [];

// Utils
function formatMonthLabel(date) {
    return date.toLocaleString('default', { month: 'long', year: 'numeric' });
}
function shiftMonth(date, dir) {
    let newDate = new Date(date);
    newDate.setMonth(newDate.getMonth() + dir);
    return newDate;
}
function monthKey(date) {
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
}
function initDatepickers() {
    document.querySelectorAll('.custom-datepicker').forEach(input => {
        new Datepicker(input, { format: 'mm-dd-yy', autohide: true });
    });
}

// Render all tables for selected companies
function renderAllTables() {
    const detailsDiv = document.getElementById('companyDetails');
    const grandTotalDiv = document.getElementById('grandTotalTable');
    const btnContainer = document.getElementById('generateInvoiceBtnContainer');
    const monthNav = document.getElementById('monthNav');
    const monthLabel = document.getElementById('monthLabel');
    const prevMonth = document.getElementById('prevMonth');
    const nextMonth = document.getElementById('nextMonth');

    detailsDiv.innerHTML = '';
    grandTotalDiv.innerHTML = '';
    btnContainer.style.display = 'none';

    // Hide month nav by default
    monthNav.style.display = 'none';
    monthLabel.style.display = 'none';
    prevMonth.style.display = 'none';
    nextMonth.style.display = 'none';

    if (selectedCompanies.length > 0) {
        // Show month nav only when companies selected
        monthNav.style.display = 'flex';
        monthLabel.style.display = 'inline';
        prevMonth.style.display = 'inline';
        nextMonth.style.display = 'inline';
        monthLabel.innerText = formatMonthLabel(globalMonthDate);
    }

    let totalSum = 0, totalRenterSums = [0, 0, 0];
    let mKey = monthKey(globalMonthDate);

    selectedCompanies.forEach(company => {
        let companyTotal = 0;
        let companyRenterTotals = [0, 0, 0];

        let html = `<h4 class="mt-4">${company}</h4>`;
        html += `<div class="table-responsive"><table class="table table-bordered custom-bg-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Price ($)</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Renter 1 (%)</th>
                            <th>Renter 2 (%)</th>
                            <th>Renter 3 (%)</th>
                            <th>Total (%)</th>
                        </tr>
                    </thead>
                    <tbody>`;

        let monthData = monthlyCompanyData[company][mKey] || [];
        monthData.forEach(row => {
            companyTotal += row.price || 0;
            totalSum += row.price || 0;

            let rowTotalPercent = row.renters.reduce((a, b) => a + b, 0);
            row.renters.forEach((r, i) => {
                companyRenterTotals[i] += r || 0;
                totalRenterSums[i] += r || 0;
            });

            let percentClass = rowTotalPercent === 100 ? 'cell-green' : 'cell-red';

            let startDate = new Date(globalMonthDate.getFullYear(), globalMonthDate.getMonth(), 1);
            let endDate = new Date(globalMonthDate.getFullYear(), globalMonthDate.getMonth() + 1, 0);

            html += `<tr>
                        <td contenteditable="true">${row.name}</td>
                        <td contenteditable="true" class="price-cell">${row.price ?? ''}</td>
                        <td><input class="form-control custom-datepicker" value="${startDate.toLocaleDateString()}" type="text"></td>
                        <td><input class="form-control custom-datepicker" value="${endDate.toLocaleDateString()}" type="text"></td>
                        <td contenteditable="true" class="renter-cell">${row.renters[0] ?? ''}</td>
                        <td contenteditable="true" class="renter-cell">${row.renters[1] ?? ''}</td>
                        <td contenteditable="true" class="renter-cell">${row.renters[2] ?? ''}</td>
                        <td class="${percentClass}">${rowTotalPercent}</td>
                    </tr>`;
        });

        html += `<tr class="fw-bold bg-light">
                    <td>Total</td>
                    <td>${companyTotal}</td>
                    <td></td><td></td>
                    <td>${companyRenterTotals[0]}</td>
                    <td>${companyRenterTotals[1]}</td>
                    <td>${companyRenterTotals[2]}</td>
                    <td></td>
                 </tr>`;
        html += `</tbody></table></div>`;
        html += `<div class="text-end"><button class="btn btn-primary mb-4">Save</button></div>`;

        detailsDiv.innerHTML += html;
    });

    if (selectedCompanies.length > 0) {
        grandTotalDiv.innerHTML = `
            <table class="table table-bordered table-striped mt-3 custom-bg-table">
                <thead>
                    <tr>
                        <th>Grand Total Price</th>
                        <th>Renter 1 Total %</th>
                        <th>Renter 2 Total %</th>
                        <th>Renter 3 Total %</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${totalSum}</td>
                        <td>${totalRenterSums[0]}</td>
                        <td>${totalRenterSums[1]}</td>
                        <td>${totalRenterSums[2]}</td>
                    </tr>
                </tbody>
            </table>
        `;
        btnContainer.style.display = 'block';
    }

    initDatepickers();
}

function changeMonth(dir) {
    globalMonthDate = shiftMonth(globalMonthDate, dir);
    renderAllTables();
}

// On company select
document.getElementById('company').addEventListener('change', function() {
    if (this.value === 'Demo') {
        selectedCompanies = Object.keys(monthlyCompanyData);
    } else if (monthlyCompanyData[this.value]) {
        selectedCompanies = [this.value];
    } else {
        selectedCompanies = [];
    }
    renderAllTables();
});

// Yes button click - lock details
document.getElementById('confirmYes').addEventListener('click', function() {
    alert('Details are now locked.');
    $('#generateInvoiceModal').modal('hide');
});
</script>
<?php $__env->stopPush(); ?>

<!-- Modal -->
<div class="modal fade" id="generateInvoiceModal" tabindex="-1" aria-labelledby="generateInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="generateInvoiceLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        Once saved, these details cannot be edited. Are you sure you want to proceed?
      </div>
      <div class="modal-footer">
        <button type="button" id="confirmYes" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/property/selectProperty.blade.php ENDPATH**/ ?>