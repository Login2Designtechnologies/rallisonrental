@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Notices') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Tenant Notices') }}</li>
    
@endsection

@section('content')
<div class="card border w-100">
  <div class="card-body default-card">
    <div class="row justify-content-center">
      <div class="col-12">

        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
          <div class="table-head-ttl">
            <h3 class="mb-1">Tenant Notices Details</h3>
          </div>
          <!-- Buttons -->
         
        </div>


        <div class="card-body">
          <div class="card theme-card">
              <div class="card border bg-light w-100">
                  <div class="card-header">
                      <h5 class="mb-0">📄 Notices Details</h5>
                  </div>
                  <div class="card-body">

                      <!-- Document -->
                      <div class="mb-3">
                          <label class="form-label fw-bold">Notices Name</label>
                            <p class="doc-name">{{$managennotice->name ?? ''}}</p>
                      </div>

                      <!-- Date -->
                      <div class="mb-3">
                          <label class="form-label fw-bold">Date</label>
                          <p class="doc-subject">
                          {{ \Carbon\Carbon::parse($generatenoticedetail->created_at)->format('m-d-Y') }}
                          </p>
                      </div>

                      <!-- Subject -->
                      <div class="mb-3">
                          <label class="form-label fw-bold">Subject</label>
                          <p class="doc-subject">{{$managennotice->subject ?? ''}}
                          </p>
                      </div>

                      <!-- Message  -->
                      <div class="mb-3">
                          <label class="form-label fw-bold">Message </label>
                          <p class="doc-comment">
                            <?php echo $managennotice->body ?? ''; ?>
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
