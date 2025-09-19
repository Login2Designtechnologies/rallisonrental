<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-class'); ?>
    product-detail-page
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('property.index')); ?>"><?php echo e(__('Property')); ?></a>
    </li>
    <li class="breadcrumb-item active">
        <a href="#"><?php echo e(__('Details')); ?></a>
    </li>
<?php $__env->stopSection(); ?>

<style>
    
</style>
<?php $__env->startSection('content'); ?>
<!-- <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary mb-3">
    ← Back
</a> -->
<div class="card bg-custom border p-25">
    <div class="month-nav">
        <span id="prevMonth" class="month-btn">&laquo;</span>
        <h4 id="monthTitle" class="m-0">August 2023</h4>
        <span id="nextMonth" class="month-btn">&raquo;</span>
    </div>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>Company Name</th>
                <th>Sub Category</th>
                <th>Price</th>
                <th>Renter 1</th>
                <th>Renter 2</th>
                <th>Renter 3</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th id="totalPrice"></th>
                <th colspan="3">
                    <button class="btn btn-success btn-sm" onclick="saveData()">Save</button>
                </th>
            </tr>
        </tfoot>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<script>
    const months = ["July 2023", "August 2023", "September 2023"];

    const monthData = {
        "August 2023": [
            { company: "Eagle Mountain", sub: "Water Usage", price: 100, renters: [33.33, 33.30, 33.40] },
            { company: "Eagle Mountain", sub: "Water Base", price: 101, renters: [33.50, 33.30, 33.20] },
            { company: "Eagle Mountain", sub: "Sewer Usage", price: 102, renters: [34.00, 33.90, 33.80] },
            { company: "Eagle Mountain", sub: "Sewer Base", price: 103, renters: [33.70, 33.60, 33.50] },
            { company: "Eagle Mountain", sub: "Garbage", price: 104, renters: [33.80, 33.70, 33.60] },
            { company: "Eagle Mountain", sub: "Storm Drain", price: 105, renters: [33.90, 33.80, 33.70] }
        ],
        "September 2023": [
            { company: "Eagle Mountain", sub: "Water Usage", price: 110, renters: [36.33, 36.30, 36.40] },
            { company: "Eagle Mountain", sub: "Water Base", price: 111, renters: [36.50, 36.30, 36.20] },
            { company: "Eagle Mountain", sub: "Sewer Usage", price: 112, renters: [37.00, 36.90, 36.80] },
            { company: "Eagle Mountain", sub: "Sewer Base", price: 113, renters: [36.70, 36.60, 36.50] },
            { company: "Eagle Mountain", sub: "Garbage", price: 114, renters: [36.80, 36.70, 36.60] },
            { company: "Eagle Mountain", sub: "Storm Drain", price: 115, renters: [36.90, 36.80, 36.70] }
        ]
    };

    let currentMonthIndex = months.indexOf("August 2023");

    function loadTable(month) {
        document.getElementById("monthTitle").textContent = month;
        const data = monthData[month] || [];
        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";

        let total = 0;
        data.forEach((row, idx) => {
            total += parseFloat(row.price);
            tbody.innerHTML += `
                <tr>
                    <td><input type="text" value="${row.company}" data-row="${idx}" data-field="company"></td>
                    <td><input type="text" value="${row.sub}" data-row="${idx}" data-field="sub"></td>
                    <td class="currency-cell"><span>$</span><input type="number" step="0.01" value="${row.price}" data-row="${idx}" data-field="price"></td>
                    <td class="currency-cell"><span>$</span><input type="number" step="0.01" value="${row.renters[0]}" data-row="${idx}" data-field="renter1"></td>
                    <td class="currency-cell"><span>$</span><input type="number" step="0.01" value="${row.renters[1]}" data-row="${idx}" data-field="renter2"></td>
                    <td class="currency-cell"><span>$</span><input type="number" step="0.01" value="${row.renters[2]}" data-row="${idx}" data-field="renter3"></td>
                </tr>
            `;
        });

        document.getElementById("totalPrice").textContent = "$" + total.toFixed(2);
    }

    document.getElementById("prevMonth").addEventListener("click", () => {
        if (currentMonthIndex > 0) {
            currentMonthIndex--;
            loadTable(months[currentMonthIndex]);
        }
    });

    document.getElementById("nextMonth").addEventListener("click", () => {
        if (currentMonthIndex < months.length - 1) {
            currentMonthIndex++;
            loadTable(months[currentMonthIndex]);
        }
    });

    function saveData() {
        const inputs = document.querySelectorAll("#tableBody input");
        const updatedData = {};
        updatedData[months[currentMonthIndex]] = monthData[months[currentMonthIndex]];

        inputs.forEach(input => {
            let rowIndex = input.getAttribute("data-row");
            let field = input.getAttribute("data-field");
            let value = input.value;

            if (field.startsWith("renter")) {
                let renterIndex = parseInt(field.replace("renter", "")) - 1;
                updatedData[months[currentMonthIndex]][rowIndex].renters[renterIndex] = parseFloat(value);
            } else if (field === "price") {
                updatedData[months[currentMonthIndex]][rowIndex].price = parseFloat(value);
            } else {
                updatedData[months[currentMonthIndex]][rowIndex][field] = value;
            }
        });

        console.log("Updated Data:", updatedData[months[currentMonthIndex]]);
        alert("Data saved! (Check console for output)");
    }

    loadTable(months[currentMonthIndex]);
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/property/list.blade.php ENDPATH**/ ?>