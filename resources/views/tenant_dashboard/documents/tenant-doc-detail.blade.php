@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Documents') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Tenant Documents') }}</li>
    
@endsection

@section('content')
<div class="card border w-100">
  <div class="card-body default-card">
    <div class="row justify-content-center">
      <div class="col-12">

        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
          <div class="table-head-ttl">
            <h3 class="mb-1">Tenant Documents Details</h3>
            <p class="text-muted mb-0">Access and manage your property-related documents</p>
          </div>
          <!-- Buttons -->
         
        </div>


        <div class="card-body">
          <div class="card theme-card">
              <div class="card border bg-light w-100">
                  <div class="card-header">
                      <h5 class="mb-0">📄 Document Details</h5>
                  </div>
                  <div class="card-body">

                      <!-- Document -->
                      <div class="mb-3">
                          <label class="form-label fw-bold">Document Name</label>
                          @if($tenantdocdetail->document == '1')
                              <p class="doc-name">Application Document</p>
                          @elseif($tenantdocdetail->document == '2')
                              <p class="doc-name">Driving Licence</p>
                          @else
                              <p class="doc-name">Bank Statement</p>
                          @endif
                      </div>

                      <!-- Subject -->
                      <div class="mb-3">
                          <label class="form-label fw-bold">Subject</label>
                          <p class="doc-subject">{{$tenantdocdetail->subject ?? ''}}
                          </p>
                      </div>

                      <!-- Comment -->
                      <div class="mb-3">
                          <label class="form-label fw-bold">Comment</label>
                          <p class="doc-comment">
                              {{$tenantdocdetail->description ?? ''}}
                          </p>
                      </div>

                  </div>
              </div>

          </div>
      </div>
  </div>




      </div>
    </div>
  </div>
</div>




@endsection
