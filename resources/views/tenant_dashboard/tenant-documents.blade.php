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
          <div>
            <h3 class="mb-1">Tenant Documents</h3>
            <p class="text-muted mb-0">Access and manage your property-related documents</p>
          </div>
          <!-- Buttons -->
         
        </div>


        <div class="table-responsive">            
            <table class="table table-bordered mb-0" id="custom-bg-table">
              <thead class="table-theme text-center">
                <tr>
                  <th>S.No.</th>
                  <th>Document Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach ($tenantDocuments as $key => $tenantDocument)
                <tr>
                  <td>{{ $key }}</td>
                  <td>{{ $tenantDocument->document }}</td>
                  <td>
                      <a href="{{ asset('storage/upload/tenantdocument/' . $tenantDocument->document) }}" 
                        target="_blank">
                        <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                      </a>
                      <a href="{{ route('tenant.document.download', $tenantDocument->id) }}">
                        <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </a>
                  </td>
                </tr> 
                @endforeach
              </tbody>
            </table>
        </div>




      </div>
    </div>
  </div>
</div>




@endsection
