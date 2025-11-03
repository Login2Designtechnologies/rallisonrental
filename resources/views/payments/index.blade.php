@extends('layouts.app')
@section('page-title')
    {{ __('Payments & Account Summary') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Payments & Account Summary') }}</li>
@endsection
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@push('script-page')
<script>
        var options = {
            chart: {
                type: 'area',
                height: 250,
                toolbar: {
                    show: false
                },
                foreColor: '#000000'
            },
            colors: ['#30a73cff', '#98f6d2ff'],
            dataLabels: {
                enabled: false
            },
            legend: {
                show: true,
                position: 'top',
                labels: {
                    colors: '#000000'
                }
            },
            markers: {
                size: 1,
                colors: ['#30a73cff'],
                strokeColors: ['#98f6d2ff'],
                strokeWidth: 1,
                shape: 'circle',
                hover: {
                    size: 4
                }
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    type: 'vertical',
                    inverseColors: false,
                    opacityFrom: 0.5,
                    opacityTo: 0,
                    colorStops: []
                }
            },
            grid: {
                show: false
            },
            series: [
                {
                    name: "Total Income",
                    data: [1000, 1500, 2000, 1800, 2200, 2500]
                },
                {
                    name: "Total Expense",
                    data: [700, 1200, 1600, 1300, 1700, 2100]
                }
            ],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                tooltip: {
                    enabled: false
                },
                labels: {
                    style: {
                        colors: '#000000'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#000000'
                    }
                }
            },
            tooltip: {
                theme: 'dark'
            }
        };

        var chart = new ApexCharts(document.querySelector('#incomeExpense'), options);
        chart.render();
    </script>
@endpush

<style>
    .card.bg-custom.radius-40.bg-box{border: 1px solid #34a891;}
</style>
@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form action="{{url('payments-search')}}" method="get" id="propertySearchForm">
           <div class="row g-3">
            <!-- Select Property -->
            <div class="col-md-6">
                <label for="property" class="form-label fw-bold">Select Property</label>
                <select id="property" name="property" class="form-control form-select" required>
                    <option value="">-- Select --</option>
                @foreach($propertiesdata as $propertiesval)
                    {{--<!-- <option value="property1">{{$propertiesval->name}}</option> -->--}}
                    <option value="{{$propertiesval->id}}" {{ request()->get('property') == $propertiesval->id ? 'selected' : '' }}>{{$propertiesval->name}}</option>
                @endforeach
                </select>
            </div>

            <!-- <div class="col-md-6">
            </div> -->
        @if(!empty(request()->get('property')))
            <div class="col-md-6">
                <label for="tenant_view" class="form-label fw-bold">Select Tenants</label>
                <select id="tenantview" name="tenant" class="form-control form-select" required>
                    <option value="">-- Select --</option>
                @foreach($tenantssearchall as $tenantsvalall)
                   @php
                      $tenantuser = DB::table('users')->where('id',$tenantsvalall->user_id)->where('parent_id', Auth::user()->id)->first();
                   @endphp
                    <option value="{{$tenantuser->id}}" {{ request()->get('tenant') == $tenantuser->id ? 'selected' : '' }}>{{$tenantuser->first_name ?? ''}} {{$tenantuser->last_name ?? ''}}</option>
                @endforeach
                </select>
            </div>
        @endif
            <!-- Select Module -->
            {{--<!-- <div class="col-md-6">
                <label for="company" class="form-label fw-bold">Select Module</label> -->
                <!-- <select id="company" class="form-control form-select" name="module" required>--> 
                <!-- <select class="form-control" name="module" required>
                    <option value="">-- Select --</option>
                    <option value="property_view" {{ request()->get('module') == 'property_view' ? 'selected' : '' }}>View Property</option>
                    <option value="tenant_view" {{ request()->get('module') == 'tenant_view' ? 'selected' : '' }}>View Tenant</option>
                </select>
            </div>
            <div class="col-md-12" style="text-align-last: end;">
                <button class="btn btn-secondary" type="submit">Search</button>
            </div> -->--}}
           </div>
        </form>
    </div>
</div>
@if(!empty(request()->get('tenant')) && !empty(request()->get('property')))

@elseif(!empty(request()->get('property')))
    <!-- Views -->
    <div class="card card-view d-block">
        <div class="card-body">

        <!-- Property View -->
            <div class="property_view d-block view-select">
                <div>
                    <h4 class="ttl">Property Name: {{$propertiesearch->name ?? ''}}</h4>
                    <div class="row g-3 mb-2">
                        <div class="col-lg-3 col-md-6">
                            <div class="card bg-custom radius-40 bg-box">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="avtar bg-light-secondary">
                                                <i class="ti ti-wallet f-24"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1">Current Amount Due	</p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h4 class="mb-0">$<span class="count">0</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card bg-custom radius-40 bg-box">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="avtar bg-light-warning">
                                                <i class="ti ti-alert-circle f-24"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1">Past Due Amount	</p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h4 class="mb-0">$<span class="count">0</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card bg-custom radius-40 bg-box">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="avtar bg-light-primary">
                                                <i class="ti ti-file-invoice f-24"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1">Utilities Due </p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h4 class="mb-0">$<span class="count">0</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card bg-custom radius-40 bg-box">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="avtar bg-light-danger">
                                                <i class="ti ti-file-alert f-24"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1">Utilities Past Due </p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h4 class="mb-0">$<span class="count">0</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./ -->

                <!-- <h4 class="ttl">Property Name: Property 1</h4> -->
                 <h4 class="ttl">Analysis Report</h4>
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-custom radius-40 bg-1 bg-img">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="avtar bg-light-secondary">
                                            <i class="ti ti-ticket f-24"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1">How Many Days Late : 9</p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h4 class="mb-0">Pay Status : <span class="badge bg-warning">Pending</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-custom radius-40 bg-2 bg-img">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="avtar bg-light-warning">
                                            <i class="ti ti-3d-cube-sphere f-24"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1">Total Unit</p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h4 class="mb-0">5</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-custom radius-40 bg-3 bg-img">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="avtar bg-light-primary">
                                            <i class="ti ti-file-invoice f-24"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1">Amount collected YTD</p>
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
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="avtar bg-light-danger">
                                            <i class="ti ti-exposure f-24"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1">Total Expenses YTD</p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h4 class="mb-0">$<span class="count">0</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="card bg-custom custom-theme">
                        <div class="card-body w-100 px-0">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h5 class="mb-1">{{ __('Analysis Report') }}</h5>
                                    <p class="text-muted mb-2">{{ __('Income and Expense Overview') }}</p>
                                </div>

                            </div>
                            <div id="incomeExpense"></div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
@endif

    <!-- Tenant View -->
@if(!empty(request()->get('tenant')) && !empty(request()->get('property')))
    
    @foreach($tenantssearch as $tenantsval)
       @php
          $tenantuser = DB::table('users')->where('id',$tenantsval->user_id)->where('parent_id', Auth::user()->id)->first();
          $otherinvoices = DB::table('other_invoices')->where('tenant_id',$tenantsval->id)->where('property_id',request()->get('property'))->where('owner_id', Auth::user()->id)->get();
       @endphp
       <div class="card card-view d-block mt-4">
         <div class="card-body">
        <div class="tenant_view d-block view-select">
            <h4 class="ttl">View Tenant {{$tenantuser->first_name ?? ''}} {{$tenantuser->last_name ?? ''}}</h4>
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="card bg-custom radius-40 bg-1 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="avtar bg-light-secondary">
                                        <i class="ti ti-ticket f-24"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1">Amount</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">$9</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card bg-custom radius-40 bg-2 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="avtar bg-light-warning">
                                        <i class="ti ti-3d-cube-sphere f-24"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1">Current Amount Due</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0">$5</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card bg-custom radius-40 bg-3 bg-img">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="avtar bg-light-primary">
                                        <i class="ti ti-file-invoice f-24"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1">Past Amount Due</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0"><span class="count"> $0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <form action="" id="" class="search-form">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="form-group d-flex align-items-center">
                                <div class="search-button">
                                    <input type="text" id="tableFilter" class="form-control" placeholder="Search by name...">
                                    <i class="ti ti-search"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex align-items-center justify-content-end">
                            <div class="form-group d-flex align-items-center">
                                <label for="filterDate" class="me-2 text-nowrap">Filter:</label>
                                <input type="date" id="filterDate" class="form-control form-control-sm">
                            </div>

                            <button class="btn btn-sm btn-secondary text-nowrap ms-2">Export PDF</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="custom-bg-table">
                        <thead class="table-theme text-center">
                            <tr>
                                <th>Invoice No.</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Charge</th>
                                <th>Payment</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                        @foreach($otherinvoices as $otherinvoicesval)
                            <tr>
                                <td>{{ $otherinvoicesval->invoice_no ?? '' }}</td>
                                {{--<!-- <td>{{ $otherinvoicesval->invoice_date ?? '' }}</td> -->--}}
                                <td>{{ $otherinvoicesval->invoice_date ? date('m-d-Y', strtotime($otherinvoicesval->invoice_date)) : '' }}</td>
                                <td>{{ $otherinvoicesval->subject ?? '' }}</td>
                                <td>$5,000</td>
                                <td>${{ $otherinvoicesval->amount ?? '' }}</td>
                                <td>$2,500</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        </div>
       </div>
    @endforeach
@endif
    

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const baseUrl = "{{ url('payments') }}";
        const searchUrl = "{{ url('payments-search') }}";
        const propertySelect = document.getElementById('property');
        const tenantSelect = document.getElementById('tenantview');

        // Property select redirect
        if (propertySelect) {
            propertySelect.addEventListener('change', function () {
                const propertyId = this.value;
                if (propertyId !== '') {
                    window.location.href = `${searchUrl}?property=${propertyId}`;
                } else {
                    window.location.href = baseUrl;
                }
            });
        }

        // Tenant select redirect (when property already selected)
        if (tenantSelect) {
            tenantSelect.addEventListener('change', function () {
                const tenantId = this.value;
                const propertyId = document.getElementById('property')?.value || '';
                if (tenantId !== '' && propertyId !== '') {
                    window.location.href = `${searchUrl}?property=${propertyId}&tenant=${tenantId}`;
                } else if (propertyId !== '') {
                    window.location.href = `${searchUrl}?property=${propertyId}`;
                } else {
                    window.location.href = baseUrl;
                }
            });
        }
    });
</script>

<!-- Script -->
<script>
    document.getElementById("company").addEventListener("change", function () {
        let moduleValue = this.value;
        let propertyValue = document.getElementById("property").value;

        // Check if property is selected
        if (!propertyValue) {
            alert("⚠️ Please select a property first!");
            this.value = ""; // reset module selection
            return;
        }

        // Hide all views
        document.querySelectorAll(".view-select").forEach(el => el.classList.add("d-none"));
        document.querySelector(".card-view").classList.add("d-none");

        // Show selected view + parent card
        if (moduleValue) {
            document.querySelector(".card-view").classList.remove("d-none");
            document.querySelector("." + moduleValue).classList.remove("d-none");
        }
    });
</script>


@endsection
