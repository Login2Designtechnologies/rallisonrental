@extends('layouts.app')
@section('page-title')
    {{ __('Manage Template') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Manage Template') }}</li>
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
                        

                        <a href="{{url('add-template')}}" class="btn btn-secondary text-white"><i class="ti ti-circle-plus align-text-bottom"></i>  Create Manage Notice</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="emailTable">
                <thead class="table-dark">
                    <tr>
                        <th>Template Name</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dummy Data -->
                    <tr>
                        <td>Welcome Notice</td>
                        <td>Hello John, welcome to our platform!</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                            </div>
                        </td>
                        <td>
                            <a href="{{url('edit-template')}}">
                                <i class="ti ti-pencil me-2 text-warning editRow fs-4" data-bs-toggle="tooltip" title="Edit"></i>
                            </a>
                            <i class="ti ti-trash text-danger deleteRow fs-4" data-bs-toggle="tooltip" title="Delete"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>Reminder</td>
                        <td>Dear Jane, please complete your profile.</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                            </div>
                        </td>
                        <td>
                            <a href="{{url('edit-template')}}">
                                <i class="ti ti-pencil me-2 text-warning editRow fs-4" data-bs-toggle="tooltip" title="Edit"></i>
                            </a>
                            <i class="ti ti-trash text-danger deleteRow fs-4" data-bs-toggle="tooltip" title="Delete"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>Payment Confirmation</td>
                        <td>Your payment has been successfully received.</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                            </div>
                        </td>
                        <td>
                            <a href="{{url('edit-template')}}">
                                <i class="ti ti-pencil me-2 text-warning editRow fs-4" data-bs-toggle="tooltip" title="Edit"></i>
                            </a>
                            <i class="ti ti-trash text-danger deleteRow fs-4" data-bs-toggle="tooltip" title="Delete"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

@endsection
