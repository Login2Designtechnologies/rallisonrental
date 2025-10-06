@extends('layouts.app')
@section('page-title')
    {{ __('Property Details') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Property Details') }}</li>
    
@endsection
<style>
.navtabsulli ul li{width: 100%;border-top: 1px solid #ede5e5;}
.navtabsulli ul li button{width:100%}
.navtabsulli ul li button.active,
.navtabsulli ul li button:hover {
 background: linear-gradient(to bottom, #000, #1a1a47, #0f172a) !important;
  color: #fff !important;
}

        .property-dtl .property-header {
            background: -webkit-gradient(linear,left top,right top,from(#ffbf96),to(#fe7096));
            background: linear-gradient(90deg,#ffbf96,#fe7096);
            position: relative;
            overflow: hidden;
        }

        .property-dtl .property-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

       .property-dtl .document-link:hover {
            text-decoration: underline;
        }

       .property-dtl .icon-wrapper {
            width: 50px;
            height: 50px;
            background: rgba(34, 197, 94, 0.2);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;display: none;
        }

        .property-dtl .icon-wrapper i {
            color: #22c55e;
            font-size: 1.5rem;
        }
     #propertyDash .btn.btn-dash {
  border: 1px solid #fff;
  color: #fff;
}
#propertyDash .btn.btn-dash:hover{background:#0b0b1e;border:1px solid #0b0b1e;}
      
</style>
@section('content')
<!--  -->
<div class="w-100">
  <div class="property-dtl">
    <div class="">
       <!-- Page Header -->
      <!-- <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
          <h3 class="mb-1">Property Details</h3>
          <p class="text-muted mb-0">Comprehensive property and lease management</p>
        </div>
      </div> -->

  <div class="row g-3">
    <div class="col-md-3 d-flex">
      <div class="fw-100 bg-white navtabsulli">
        <!-- Nav Tabs -->
      <ul class="nav nav-tabs" id="propertyTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="propertyDash-tab" data-bs-toggle="tab" data-bs-target="#propertyDash" type="button" role="tab">
            <i class="bi bi-building"></i> Dashboard
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="property-tab" data-bs-toggle="tab" data-bs-target="#property" type="button" role="tab">
            <i class="bi bi-building"></i> Property Info
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="lease-tab" data-bs-toggle="tab" data-bs-target="#lease" type="button" role="tab">
            <i class="bi bi-file-earmark-text"></i> Lease Agreement
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab">
            <i class="bi bi-cash-stack"></i> Financial Info
          </button>
        </li>
        <!-- <li class="nav-item" role="presentation">
          <button class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab">
            <i class="bi bi-activity"></i> Lease Status
          </button>
        </li> -->
      </ul>
      </div>
    </div>

    <div class="col-md-9 d-flex flex-column">

     
     

      <!-- Tab Content -->
      <div class="tab-content fw-100 bg-white p-4" id="propertyTabsContent">

        <div class="tab-pane fade show active" id="propertyDash" role="tabpanel">
          <h4 class="mb-3"><i class="bi bi-building"></i> Property Dashboard</h4>
          <div class="row g-3 mb-4">
                      <div class="col-md-4 d-flex">
                          <div class="card bg-success text-white shadow-sm w-100 new-bg-1">
                              <div class="card-body p-3 text-center">
                                  <h4 class="card-title">Lease Terms</h4>
                                  <p class="card-text">View your lease details</p>
                                  <a href="#" class="btn btn-dash btn-sm"><small>View</small></a>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4 d-flex">
                          <div class="card bg-success text-white shadow-sm w-100 new-bg-3">
                              <div class="card-body p-3 text-center">
                                  <h4 class="card-title">Payments Due</h4>
                                  <p class="card-text">Check your pending payments</p>
                                  <a href="#" class="btn btn-dash btn-sm"><small>Pay Now</small></a>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4 d-flex">
                          <div class="card bg-success text-white shadow-sm w-100 new-bg-2">
                              <div class="card-body p-3 text-center">
                                  <h4 class="card-title">Lease Agreement</h4>
                                  <p class="card-text">Download your agreement</p>
                                  <a href="#" class="btn btn-dash btn-sm"><small>Download</small></a>
                              </div>
                          </div>
                      </div>
                 
                  </div>
        </div>


        <!-- Property Info -->
        <div class="tab-pane fade" id="property" role="tabpanel">
          <h4 class="mb-3"><i class="bi bi-building"></i> Property &amp; Unit Information</h4>
          <div class="row g-3 mb-4">
            <div class="col-md-6 d-flex">
              <div class="card w-100">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-building"></i></div>
                  <h6 class="text-muted">Property Name</h6>
                  <p class="mb-0 fw-bold">{{ $property->properties->name ?? 'N/A' }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 d-flex">
              <div class="card w-100">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-door-open"></i></div>
                  <h6 class="text-muted">Landlord Contact Info</h6>
                  <p class="mb-0 fw-bold"></p>
                </div>
              </div>
            </div>
            <!-- <div class="col-md-4 d-flex">
              <div class="card w-100">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-door-open"></i></div>
                  <h6 class="text-muted">Unit Number</h6>
                  <p class="mb-0 fw-bold">{{ $property->units->name ?? 'N/A' }}</p>
                </div>
              </div>
            </div> -->
            <div class="col-md-12">
              <div class="text-white p-4 rounded property-header">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <h5 class="text-white">Full Address</h5>
                    <h2 class="h4 mb-2 text-white">{{ $property->properties->name ?? '' }} - {{ $property->units->name ?? '' }}</h2>
                    <p class="mb-0">
                      <i class="bi bi-geo-alt me-2"></i>
                      @php $prop = $property->properties; @endphp
                      @if($prop && $prop->address)
                        {{ $prop->address }},
                        {{ optional($prop->city)->name }},
                        {{ $prop->country }}
                        {{ $prop->zip_code }}
                      @endif
                    </p>
                  </div>
                  <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-dark">
                      <i class="bi bi-check-circle"></i> Active Lease
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 d-flex">
              <div class="card w-100">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-door-open"></i></div>
                  <h6 class="text-muted">Email  </h6>
                  <p class="mb-0 fw-bold"></p>
                </div>
              </div>
            </div>
            <div class="col-md-6 d-flex">
              <div class="card w-100">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-door-open"></i></div>
                  <h6 class="text-muted">Number </h6>
                  <p class="mb-0 fw-bold"></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Lease Agreement -->
        <div class="tab-pane fade" id="lease" role="tabpanel">
          <h4 class="mb-3"><i class="bi bi-file-earmark-text"></i> Lease Agreement</h4>
          <div class="row g-3 mb-4">
            <div class="col-md-6 d-flex">
              <div class="card w-100">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-calendar-range"></i></div>
                  <h6 class="text-muted">Lease Period</h6>
                  <p class="mb-0 fw-bold">
                    {{ \Carbon\Carbon::parse($property->lease_start_date)->format('F d, Y') }} - 
                    {{ \Carbon\Carbon::parse($property->lease_end_date)->format('F d, Y') }}
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6 d-flex">
              <div class="card w-100">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-calendar-range"></i></div>
                  <h6 class="text-muted">Lease Document</h6>
                  <a href="#" class="d-block mb-2 text-decoration-none">
                    <i class="bi bi-file-earmark-pdf"></i> Download Lease Agreement
                  </a>
                  <a href="#" class="d-block text-decoration-none">
                    <i class="bi bi-eye"></i> View Online
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Financial Info -->
        <div class="tab-pane fade" id="financial" role="tabpanel">
          <h4 class="mb-3"><i class="bi bi-cash-stack"></i> Financial Information</h4>
          <!-- <div class="row g-3 mb-3">
            <div class="col-md-6 d-flex">
              <div class="card bg-success text-white shadow-sm w-100 new-bg-1">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div>
                    <h5 class="ttl">{{ optional($property->units)->rent_type ? ucfirst(optional($property->units)->rent_type) : 'N/A' }} Rent</h5>
                    <p class="h4 mb-0">${{ $property->units->rent ?? '0' }}</p>
                  </div>
                  <i class="bi bi-house-fill fs-1"></i>
                </div>
              </div>
            </div>
            <div class="col-md-6 d-flex">
              <div class="card bg-info text-white shadow-sm w-100 new-bg-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div>
                    <h5 class="ttl">Security Deposit</h5>
                    <p class="h4 mb-0">${{ $property->units->deposit_amount ?? '0' }}</p>
                  </div>
                  <i class="bi bi-shield-check fs-1"></i>
                </div>
              </div>
            </div>
          </div> -->

          <div class="row g-3 mb-4">
            <div class="col-md-4 d-flex">
              <div class="card bg-success text-white shadow-sm w-100 new-bg-1">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-check-circle"></i></div>
                  <h5 class="text-white">Deposit Paid</h5>
                  <p class="mb-0 fw-bold">$5,000</p>
                </div>
              </div>
            </div>
            <div class="col-md-4 d-flex">
              <div class="card bg-success text-white shadow-sm w-100 new-bg-2">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-exclamation-circle"></i></div>
                  <h5 class="text-white">Balance Due</h5>
                  <p class="mb-0 fw-bold">$0</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card bg-success text-white shadow-sm w-100 new-bg-3">
                <div class="card-body">
                  <div class="icon-wrapper"><i class="bi bi-calendar-check"></i></div>
                  <h5 class="text-white">Next Payment Due</h5>
                  <p class="mb-0 fw-bold">
                    {{ $property->units?->payment_due_date 
                      ? \Carbon\Carbon::parse($property->units->payment_due_date)->format('F d, Y') 
                      : 'N/A' }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Lease Status -->
        <div class="tab-pane fade" id="status" role="tabpanel">
          <h4 class="mb-3"><i class="bi bi-activity"></i> Lease Status &amp; Timeline</h4>
          <div class="card shadow-sm w-100">
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <h6 class="text-muted">Current Status</h6>
                  @if($property->status == '0')
                    <span class="badge bg-success">
                      <i class="bi bi-check-circle"></i> Active
                    </span>
                  @else
                    <span class="badge bg-danger">
                      <i class="bi bi-x-circle"></i> Inactive
                    </span>
                  @endif
                </div>
                <div class="col-6">
                  <h6 class="text-muted">Days Until Expiry</h6>
                  <p class="fw-bold mb-0">45 days</p>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-6">
                  <h6 class="text-muted">Lease Start</h6>
                  <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($property->lease_start_date)->format('F d, Y') }}</p>
                </div>
                <div class="col-6">
                  <h6 class="text-muted">Lease End</h6>
                  <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($property->lease_end_date)->format('F d, Y') }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- End Tab Content -->

    </div>
  </div>
</div>

  </div>
</div>
<!-- ./ -->
@endsection
