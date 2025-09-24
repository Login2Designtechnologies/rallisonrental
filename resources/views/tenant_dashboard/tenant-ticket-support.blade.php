@extends('layouts.app')
@section('page-title')
    {{ __('Ticket List') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Ticket List') }}</li>
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <div>
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
                        

                        <a href="{{url('add-tenant-ticket')}}" class="btn btn-secondary text-white"><i class="ti ti-circle-plus align-text-bottom"></i> Create New Ticket</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle" data-sorting="true">
            <thead class="table-light">
                <tr>
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
                        <td>{{ $ticket->subject }}</td>
                        <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('m-d-Y') }}</td>
                        <td>{{ $ticket->category }}</td>
                        <td>{{ $ticket->status }}</td>
                       <td>
                            <div class="d-flex align-items-center gap-2 action-button">
                            
                            <a href="{{url('tenant-view-ticket')}}">
                                        <i class="ti ti-pencil me-2 editRow fs-4" data-bs-toggle="tooltip" title="Edit"></i>
                                    </a>
                            @if(!empty($ticket->photo))
                                <a href="{{ asset('storage/upload/tickets/' . $ticket->photo) }}" target="_blank">
                                    <i class="ti ti-eye me-2 editRow fs-4" data-bs-toggle="tooltip" title="View"></i>
                                </a>
                            @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
