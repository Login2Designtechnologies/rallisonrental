@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Notices') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Tenant Notices') }}</li>
    
@endsection


@section('content')

<style>
    .dt-buttons.btn-group.flex-wrap {
        display: none;
    }
    @media (min-width: 991px) {
  div.dt-container div.dt-search {
    position: absolute;
    top: 12px;
    left: 0px;
  }
}
.card .card-header {
    border-bottom: 1px solid #dfd9d9;
    margin-top: 33px;
}

</style>

<div class="card bg-custom border p-25">
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header py-2 px-0">
                    <div class="row align-items-center g-2">
                        <div class="col">
                        </div>
                            <div class="col-auto">
                            </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable" style="margin-top: 0px;">
                            <thead>
                                <tr>
                                  <th>Notices Name</th>
                                  <th>Subject </th>
                                  <th>Date</th>
                                  <th style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($generatenotice as $generateval)
                              @php 
                                 $managennotice = DB::table('managen-notice')->where('id',$generateval->notice_id)->first();
                              @endphp
                              <tr>
                                 <td>{{$managennotice->name ?? ''}}</td>
                                  <td>{{$managennotice->subject ?? ''}}</td>
                                  <td>{{ \Carbon\Carbon::parse($generateval->created_at)->format('m-d-Y') }}</td>
                                  <td>
                                    <a href="{{url('tenant-notices-detail/'.$generateval->id)}}"><i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i></a>
                                    <!-- <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i> -->
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
</div>
@endsection
