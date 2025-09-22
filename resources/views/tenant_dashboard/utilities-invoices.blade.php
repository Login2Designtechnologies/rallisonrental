@extends('layouts.app')
@section('page-title')
    {{ __('Utilities Invoices') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Utilities Invoices') }}</li>
    
@endsection

@section('content')
<div class="card border w-100">
  <div class="card-body default-card">
    <div class="row justify-content-center">
      <div class="col-12">

        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
          <div>
            <h3 class="mb-1">Utilities Invoices</h3>
            <p class="text-muted mb-0">Access your latest Utilities Invoices</p>
          </div>
        </div>


        <div class="table-responsive">
            <table class="table table-bordered mb-0" id="custom-bg-table">
              <thead class="table-theme text-center">
                <tr>
                  <th>Month</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                  <tr>
                      <td>April 2025</td>
                      <td>₹10,000</td>
                      <td>
                          <span class="badge bg-success">Delivered</span>
                          <!-- <span class="badge bg-warning">Pending</span> -->
                      </td>
                      <td>
                          <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                          <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </td>
                  </tr>
                  <tr>    
                      <td>May 2025</td>
                      <td>₹8,000</td>
                      <td>
                          <span class="badge bg-warning text-dark">Pending</span>
                      </td>
                      <td>
                          <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                          <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </td>
                  </tr>
              </tbody>
            </table>
        </div>




      </div>
    </div>
  </div>
</div>




@endsection
