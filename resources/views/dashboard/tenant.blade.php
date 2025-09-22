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
    <!-- <div class="row">
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue bg-custom radius-40">
                <div class="card-header">
                    <h4>{{__('Property')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2 class="text-white">
                        <span class="">{{!empty($tenant->properties)?$tenant->properties->name:'-'}}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue bg-custom radius-40">
                <div class="card-header">
                    <h4>{{__('Unit')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2 class="text-white">
                        <span class="">{{!empty($tenant->units)?$tenant->units->name:'-'}}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue bg-custom radius-40">
                <div class="card-header">
                    <h4>{{__('Rent')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2 class="text-white">
                        <span class="">{{$settings['CURRENCY_SYMBOL']}}{{!empty($result['unit'])?$result['unit']->rent:'-'}}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue bg-custom radius-40">
                <div class="card-header">
                    <h4>{{__('Total Invoice')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2 class="text-white">
                        <span class="count">{{$result['totalInvoice']}}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue bg-custom radius-40">
                <div class="card-header">
                    <h4>{{__('Total Contact')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2 class="text-white">
                        <span class="count">{{$result['totalContact']}}</span>
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
            <div class="card sale-revenue bg-custom radius-40">
                <div class="card-header">
                    <h4>{{__('Total Notes')}}</h4>
                </div>
                <div class="card-body progressCounter">
                    <h2 class="text-white"><span class="count">{{$result['totalNote']}}</span> </h2>
                </div>
            </div>
        </div>
    </div> -->

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-custom radius-40 bg-1 bg-img">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar bg-light-secondary">
                                <i class="ti ti-3d-cube-sphere f-24"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1">Property Name</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Property 1</h4>

                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-custom radius-40 bg-2 bg-img">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar bg-light-warning">
                                <i class="ti ti-building f-24"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1">Unit</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">9</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-md-6">
            <div class="card bg-custom radius-40 bg-3 bg-img">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar bg-light-primary">
                                <i class="ti ti-file-invoice f-24"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1">Total Amount Due</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">$<span class="count">0</span></h4>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-custom radius-40 bg-4 bg-img">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avtar bg-light-danger">
                                <i class="ti ti-exposure f-24"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1">Total Amount Paid</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">$<span class="count">0</span></h4>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card mt-4">
        <div class="card-body">
            <canvas id="propertyChart"></canvas>
        </div>
    </div>

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('propertyChart').getContext('2d');

  // Months as labels
  const months = ['January', 'February', 'March', 'April', 'May', 'June'];

  // Dummy data for a single property (example amounts)
  const amountDue = [1000, 1200, 800, 1500, 2000, 1700];
  const amountPaid = [900, 1200, 700, 1400, 1800, 1700];

  // Chart.js Config
  const propertyChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Total Amount Due ($)',
          data: amountDue,
          backgroundColor: 'rgba(255, 99, 132, 0.7)', // Red
          borderRadius: 5
        },
        {
          label: 'Total Amount Paid ($)',
          data: amountPaid,
          backgroundColor: 'rgba(75, 192, 192, 0.7)', // Green
          borderRadius: 5
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Total Amount Due vs Paid (Single Property)'
        },
        legend: {
          position: 'top'
        },
        tooltip: {
          mode: 'index',
          intersect: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Amount ($)'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Months'
          }
        }
      }
    }
  });
</script>

@endsection
