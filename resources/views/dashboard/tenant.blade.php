@extends('layouts.app')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}">{{__('Dashboard')}}</a>
        </li>

    </ul>
@endsection
@push('script-page')

@endpush
@php
$settings=settings();
@endphp
@section('content')
<style>
    .page-header{display:none}
</style>
 
    <div class="row g-3 pt-0 mb-3">
        <div class="col-lg-3 col-md-6 d-flex">
            <div class="card bg-custom radius-40 bg-1 bg-img fw-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="avtar bg-light-secondary">
                                <i class="ti ti-calendar f-24"></i>
                            </div>
                        </div>
                        <div class="">
                            <p class="mb-1">Next Rent Payment</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 fs-14">Date : <span class="count">{{ $result['nextPaymentDate'] ? $result['nextPaymentDate']->format('d-m-Y') : 'N/A' }}</span></h4>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 fs-14">Amount : <span class="count">${{ number_format($result['rentAmount'], 2) }}</span></h4>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 d-flex">
            <div class="card bg-custom radius-40 bg-2 bg-img fw-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="avtar bg-light-warning">
                                <i class="ti ti-flame f-24"></i>
                            </div>
                        </div>
                        <div class="">
                            <p class="mb-1">Utilities Due</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 fs-14">$<span class="count">{{ number_format($result['utilitiesDue'], 2) }}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-md-6 d-flex">
            <div class="card bg-custom radius-40 bg-3 bg-img fw-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="avtar bg-light-primary">
                                <i class="ti ti-file-invoice f-24"></i>
                            </div>
                        </div>
                        <div class="">
                            <p class="mb-1">Past Due</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 fs-14">$<span class="count">{{ number_format($result['pastDueAmount'], 2) }}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 d-flex">
            <div class="card bg-custom radius-40 bg-4 bg-img fw-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="avtar bg-light-danger">
                                <i class="ti ti-receipt f-24"></i>
                            </div>
                        </div>
                        <div class="">
                            <p class="mb-1">Other Expenses</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 fs-14">$<span class="count">0</span></h4>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-3">
        <div class="col-md-7 d-flex">
            <div class="card fw-100 mb-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-4">Rent, Utilities & Other Amount</h5>
                        <div class="d-flex justify-content-end">
                            <select id="monthSelect" class="form-select mb-3" style="max-width: 200px;">
                                <option value="0">January</option>
                                <option value="1">February</option>
                                <option value="2">March</option>
                                <option value="3">April</option>
                                <option value="4">May</option>
                                <option value="5">June</option>
                            </select>
                        </div>
                    </div>
                    <canvas id="propertyChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5 d-flex">
            <div class="card fw-100 mb-0">
                <div class="card-body">
                    <h5 class="card-title mb-4">Tenant requests</h5>

                    <!-- Request 1 -->
                    <div class="d-flex align-items-center request-item mb-3">
                        <div class="icon-circle-box me-3">
                            <i class="ti ti-building f-24"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div>277 West 11th Street #3C</div>
                            <small class="text-muted">Broken Window</small>
                        </div>
                        <span class="badge bg-warning text-dark ms-3">IN PROCESS</span>
                    </div>

                    <!-- Request 2 -->
                    <div class="d-flex align-items-center request-item mb-3">
                        <div class="icon-circle-box me-3">
                            <i class="ti ti-lock f-24"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div>277 West 11th Street #3C</div>
                            <small class="text-muted">Lock broken</small>
                        </div>
                        <span class="badge bg-warning text-dark ms-3">IN PROCESS</span>
                    </div>

                    <!-- Date Label -->
                    <div class="date-label mt-4 fw-bold text-muted">Wed May 24 2025</div>

                    <!-- Request 3 -->
                    <div class="d-flex align-items-center request-item mt-3">
                        <div class="icon-circle-box me-3">
                            <i class="ti ti-droplet f-24"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div>323 Johnson Drive</div>
                            <small class="text-muted">Dishwasher leak</small>
                        </div>
                        <span class="badge bg-danger ms-3">OVERDUE</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 d-flex">
            <div class="card w-100 mb-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Property overview</h5>
                    <div class="row align-items-center">
                        <!-- Chart -->
                        <div class="col-md-7">
                        <canvas id="spendingChart"></canvas>
                        </div>
                        <!-- Dynamic Data -->
                        <div class="col-md-5">
                            <div class="">
                                <h4 class="mb-3 ls-1">Show for spending</h4>
                                <ul id="spendingList" class="list-unstyled mb-0"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7 d-flex">
            <div class="card w-100 mb-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        
                        <!-- Occupancy Chart -->
                        <div class="col-md-5">
                        <h5 class="card-title mb-3 ls-1">Occupancy rate</h5>
                        <canvas id="occupancyChart" width="150" height="150"></canvas>
                        </div>

                        <!-- Contract Details -->
                        <div class="col-md-7">
                            <div class="contract-box">
                                <h4 class="text-left">Contract Details</h4>
                                <ul class="list-unstyled mb-0">
                                <li><strong>Dates of agreement:</strong>  Jan 01 2025 –  June 31 2025</li>
                                <li><strong>Payment Due Date:</strong> 5th of every month</li>
                                <li><strong>Late if not paid by:</strong> 10th of every month</li>
                                <li><strong>Months Left:</strong> 15 months</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


       

    </div>

    <div class="row g-3 my-1">
         <div class="col-md-4 d-flex">
            <div class="card w-100 tenant-card">
                <div class="card-body">
                    <a href="#" class="d-flex align-items-center link-txt">
                        <i class="ti ti-arrow-right me-2 f-20"></i> New Documents to view
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card w-100 tenant-card">
                <div class="card-body">
                    <a href="#" class="d-flex align-items-center link-txt">
                        <i class="ti ti-arrow-right me-2 f-20"></i> Create a work Order
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card w-100 tenant-card">
                <div class="card-body">
                    <a href="#" class="d-flex align-items-center link-txt">
                        <i class="ti ti-arrow-right me-2 f-20"></i> Notice to Cancel Agreement
                    </a>
                </div>
            </div>
        </div>
    </div>

   

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
 // ---------------- BAR CHART DATA ----------------
const months = ['January', 'February', 'March', 'April', 'May', 'June'];

const dataSets = {
  rent: { due: [400,500,300,600,700,600], paid: [400,500,250,550,650,600] },
  utilities: { due: [300,400,250,500,800,700], paid: [300,400,200,450,700,700] },
  others: { due: [300,300,250,400,500,400], paid: [200,300,250,400,450,400] }
};

const colors = {
  rent: 'rgba(255, 99, 132, 0.7)',
  utilities: 'rgba(54, 162, 235, 0.7)',
  others: 'rgba(255, 205, 86, 0.7)'
};

// ✅ Function to get chart data for multiple months (min 5)
function getMultiMonthChartData(startIndex = 0, count = 5) {
  const endIndex = Math.min(startIndex + count, months.length);
  const labels = months.slice(startIndex, endIndex);

  return {
    labels: labels,
    datasets: [
      {
        label: 'Rent',
        data: labels.map((_, i) => {
          const idx = startIndex + i;
          return dataSets.rent.due[idx] + dataSets.rent.paid[idx];
        }),
        backgroundColor: colors.rent,
        borderRadius: 5
      },
      {
        label: 'Utilities',
        data: labels.map((_, i) => {
          const idx = startIndex + i;
          return dataSets.utilities.due[idx] + dataSets.utilities.paid[idx];
        }),
        backgroundColor: colors.utilities,
        borderRadius: 5
      },
      {
        label: 'Others',
        data: labels.map((_, i) => {
          const idx = startIndex + i;
          return dataSets.others.due[idx] + dataSets.others.paid[idx];
        }),
        backgroundColor: colors.others,
        borderRadius: 5
      }
    ]
  };
}

// ---------------- INIT CHART ----------------
const ctx1 = document.getElementById('propertyChart').getContext('2d');
const propertyChart = new Chart(ctx1, {
  type: 'bar',
  data: getMultiMonthChartData(0, 5), // ✅ default 5 months show karega
  options: {
    responsive: true,
    plugins: {
      legend: { display: true },
      title: { display: true, text: 'Rent, Utilities, and Others Amounts (5 Months View)' },
      tooltip: {
        callbacks: {
          label: function(context) {
            return `${context.dataset.label}: $${context.parsed.y}`;
          }
        }
      }
    },
    scales: {
      x: { title: { display: true, text: 'Months' } },
      y: { beginAtZero: true, title: { display: true, text: 'Amount ($)' } }
    }
  }
});

// ---------------- MONTH SELECT ----------------
document.getElementById('monthSelect').addEventListener('change', (e) => {
  const startIndex = parseInt(e.target.value);
  propertyChart.data = getMultiMonthChartData(startIndex, 5); // ✅ min 5 months dikhaye
  propertyChart.update();
});


  // ---------------- DOUGHNUT CHART DATA ----------------
  const chartData = {
    labels: ['Rent', 'Utilities', 'Maintenance'],
    datasets: [{
      data: [45, 25, 30],
      backgroundColor: ['#4bc0c0', '#ffce56', '#36a2eb'],
      borderWidth: 1
    }]
  };

  const ctx2 = document.getElementById('spendingChart').getContext('2d');
  new Chart(ctx2, {
    type: 'doughnut',
    data: chartData,
    options: {
      plugins: { legend: { display: false } },
      cutout: '70%'
    }
  });

  // Show Data in "Show for spending" box
  const spendingList = document.getElementById('spendingList');
  chartData.labels.forEach((label, i) => {
    const value = chartData.datasets[0].data[i];
    const color = chartData.datasets[0].backgroundColor[i];
    const li = document.createElement('li');
    li.innerHTML = `
      <span style="display:inline-block;width:15px;height:15px;background:${color};margin-right:8px;border-radius:3px;"></span>
      <strong>${label}:</strong> ${value}%
    `;
    spendingList.appendChild(li);
  });
</script>


<script>
  const occupancyPercent = 72; // Occupancy %
  const remaining = 100 - occupancyPercent;

  // Custom plugin for center text
  const centerTextPlugin = {
    id: 'centerText',
    afterDatasetsDraw(chart, args, options) {
      const { ctx, chartArea: { width, height } } = chart;
      ctx.save();
      ctx.font = 'bold 20px Arial';
      ctx.fillStyle = '#4bc0c0';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText(`${occupancyPercent}%`, width / 2, height / 2 - 10);

      ctx.font = '14px Arial';
      ctx.fillStyle = '#555';
      ctx.fillText('occupied', width / 2, height / 2 + 15);
      ctx.restore();
    }
  };

  const ctx = document.getElementById('occupancyChart').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Occupied', 'Vacant'],
      datasets: [{
        data: [occupancyPercent, remaining],
        backgroundColor: ['#4bc0c0', '#e0e0e0'],
        borderWidth: 0
      }]
    },
    options: {
      plugins: {
        legend: { display: false },
        tooltip: { enabled: false }
      },
      cutout: '75%'
    },
    plugins: [centerTextPlugin]
  });
</script>

@endsection
