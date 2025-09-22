@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Notices') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Tenant Notices') }}</li>
    
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
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
                  
                </div>
            </form>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-theme">
                    <tr>
                    <th>Property Name</th>
                    <th>Tenant Name</th>
                    <th>Payment Due Date</th>
                    <th>Actual Payment Date</th>
                    <th>Fees Amount ($)</th>
                    <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                 <tbody>
                    <tr>
                        <td>Property 1</td>
                        <td>John Doe</td>
                        <td>08-01-25</td>
                        <td>08-05-25</td>
                        <td>$300</td>
                        <td>
                          <a href="https://whitesmoke-jackal-127066.hostingersite.com/view-invoice"><i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i></a>
                          <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </td>
                    </tr>
                    <tr>
                        <td>Property 2</td>
                        <td>Mary Smith</td>
                        <td>08-03-25</td>
                        <td>08-03-25</td>
                        <td>$200</td>
                        <td>
                          <a href="https://whitesmoke-jackal-127066.hostingersite.com/view-invoice"><i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i></a>
                          <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </td>
                    </tr>
                    <tr>
                        <td>Property 3</td>
                        <td>Robert Brown</td>
                        <td>07-30-25</td>
                        <td>08-02-25</td>
                        <td>$500</td>
                        <td>
                          <a href="https://whitesmoke-jackal-127066.hostingersite.com/view-invoice"><i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i></a>
                          <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
</div>
<!-- <div class="card border w-100">
  <div class="card-body default-card">
    <div class="row justify-content-center">
      <div class="col-12">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
          <div>
            <h3 class="mb-1">Tenant Notices</h3>
            <p class="text-muted mb-0">All important updates & reminders from management</p>
          </div>
         
        </div>


        <div class="row g-4">
          <div class="col-12 col-md-6">
            <div class="card notice-card p-3 mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-1">Rent Payment Reminder</h5>
                <span class="badge bg-warning">Pending</span>
              </div>
              <p class="text-muted mb-2">
                Your rent for <strong>September 2025</strong> is due on <strong>5th Sept</strong>.
              </p>
              <div class="d-flex justify-content-between">
                <small class="text-muted"><i class="bi bi-calendar"></i> Sent: Aug 25, 2025</small>
                <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="card notice-card p-3 mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-1">Maintenance Work</h5>
                <span class="badge bg-info">Info</span>
              </div>
              <p class="text-muted mb-2">
                Scheduled maintenance of water supply on <strong>10th Sept, 9 AM - 12 PM</strong>.
              </p>
              <div class="d-flex justify-content-between">
                <small class="text-muted"><i class="bi bi-calendar"></i> Sent: Sept 1, 2025</small>
                <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="card notice-card p-3 mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-1">Lease Renewal</h5>
                <span class="badge bg-danger">Action Required</span>
              </div>
              <p class="text-muted mb-2">
                Your lease is expiring on <strong>Jan 14, 2025</strong>. Please confirm renewal decision.
              </p>
              <div class="d-flex justify-content-between">
                <small class="text-muted"><i class="bi bi-calendar"></i> Sent: Aug 20, 2025</small>
                <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Respond</a>
              </div>
            </div>
          </div>
        </div>




      </div>
    </div>
  </div>
</div> -->




@endsection
