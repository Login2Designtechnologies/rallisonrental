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
              @php $i = 1; @endphp
              @foreach ($tenantDocuments as $key => $tenantDocument)
              @php
                $usersdoc = DB::table('users')->where('id',$tenantDocument->user_id)->first();
              @endphp
                <tr>
                  <td>{{ $i }}</td>
                  @if($tenantDocument->document == '1')
                      <td>Application Document</td>
                  @elseif($tenantDocument->document == '2')
                      <td>Driving Licence</td>
                  @else
                      <td>Bank Statement</td>
                  @endif
                  <td>
                  @if($tenantDocument->document == '1')
                      <a href="{{ asset('storage/upload/tenantdocument/' . $usersdoc->personal_document) }}" 
                        target="_blank">
                        <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                      </a>
                      <a href="{{ asset('storage/upload/tenantdocument/' . $usersdoc->personal_document) }}" download target="_blank">
                        <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </a>
                  @elseif($tenantDocument->document == '2')
                      <a href="{{ asset('storage/upload/tenantdocument/' . $usersdoc->ic_document) }}" 
                        target="_blank">
                        <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                      </a>
                      <a href="{{ asset('storage/upload/tenantdocument/' . $usersdoc->ic_document) }}" download target="_blank">
                        <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </a>
                  @else
                      <a href="{{url('tenant-documents-detail/'.$tenantDocument->id)}}" 
                        target="_blank">
                        <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View Document Details"></i>
                      </a>
                      <a href="{{ asset('storage/upload/tenantdocument/' . $usersdoc->miscellaneous) }}" 
                        target="_blank">
                        <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                      </a>
                      <a href="{{ asset('storage/upload/tenantdocument/' . $usersdoc->miscellaneous) }}" download target="_blank">
                        <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </a>
                  @endif
                  </td>
                </tr> 
                @php $i++; @endphp
                @endforeach
              </tbody>
            </table>
        </div>




      </div>
    </div>
  </div>
</div>




@endsection
