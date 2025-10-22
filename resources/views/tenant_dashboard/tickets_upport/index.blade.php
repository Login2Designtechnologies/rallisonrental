@extends('layouts.app')
@section('page-title')
    {{ __('Ticket List') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Ticket List') }}</li>
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
                                <a href="{{url('add-tenant-ticket')}}" class="btn btn-secondary text-white"><i class="ti ti-circle-plus align-text-bottom"></i> Create New Ticket</a>
                            </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th>Ticket Id</th>
                                    <th>Subject</th>
                                    <th data-breakpoints="lg" data-type="number">Sending Date</th>
                                    <th data-breakpoints="lg" data-type="number">Category</th>
                                    <th data-breakpoints="lg" data-type="number">Status</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($tickets as $ticket)                
                                <tr>
                                    <td>{{ $ticket->random_id }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('m-d-Y') }}</td>
                                    <td>{{ $ticket->category }}</td>
                                    <td>
                                        @if($ticket->status == '1')
                                            <span class="badge bg-warning text-dark">Open</span>
                                        @elseif($ticket->status == '2')
                                            <span class="badge bg-success">Resolved</span>
                                        @else
                                            <span class="badge bg-danger">Closed</span>
                                        @endif
                                    </td>
                                   <td>
                                        <div class="d-flex align-items-center gap-2 action-button">
                                        
                                        <a href="{{url('tenant-view-ticket/'.$ticket->id)}}">
                                                    <!-- <i class="ti ti-pencil me-2 editRow fs-4" data-bs-toggle="tooltip" title="Edit"></i> -->
                                            <i class="ti ti-eye me-2 editRow fs-4" data-bs-toggle="tooltip" title="View"></i>
                                        </a>
                                        </div>
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
