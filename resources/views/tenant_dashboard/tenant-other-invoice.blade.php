@extends('layouts.app')
@section('page-title')
    {{ __('other Invoice') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('other Invoice') }}</li>
    
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form action="" id="" class="search-form">
            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="form-group d-flex align-items-center">
                        <div class="search-button">
                            <input type="text" id="tableFilter" class="form-control" placeholder="Search by name..." />
                            <i class="ti ti-search"></i>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Property Name</th>
                        <th>Tenant Name</th>
                        <th>Invoice No.</th>
                        <th>Invoice Date</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example row -->
                    <tr>
                        <td>NYC - Times Square Apartment</td>
                        <td>Michael Johnson</td>
                        <td>INV-1001</td>
                        <td>2025-09-05</td>
                        <td>$1,200</td>
                        <td>
                          <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                          <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </td>
                    </tr>
                    <tr>
                        <td>Los Angeles - Sunset Villa</td>
                        <td>Emily Davis</td>
                        <td>INV-1002</td>
                        <td>2025-09-06</td>
                        <td>$1,500</td>
                        <td>
                          <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" data-bs-title="View"></i>
                          <i class="ti ti-download mx-1" data-bs-toggle="tooltip" data-bs-title="Download"></i>
                      </td>
                    </tr>
                    <tr>
                        <td>Chicago - Lakeview Condo</td>
                        <td>Robert Brown</td>
                        <td>INV-1003</td>
                        <td>2025-09-07</td>
                        <td>$1,800</td>
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


@endsection
