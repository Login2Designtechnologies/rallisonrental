@extends('layouts.app')
@section('page-title')
    {{ __('Payment Section') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Payment Section') }}</li>
    
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <div class="row">

            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-3">
                    <h2 class="h4 fw-semibold mb-0">Overview</h2>
                </div>

                <div class="row g-3">
                    <!-- Next Payment Due -->
                    <div class="col-md-6 col-lg-3 d-flex">
                        <div class="card border-primary border-opacity-25 position-relative overflow-hidden fw-100 mb-0">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center py-2">
                                <h5 class="card-title fw-medium mb-0">Next Payment Due</h5>
                                <i class="bi bi-calendar text-primary fs-3"></i>
                            </div>
                            <div class="card-body position-relative pt-0 pb-3">
                                <div class="fs-3 fw-bold">
                                 @if($dueDate)
                                  {{ $dueDate->format('M d, Y') }}
                                 @else
                                 @endif
                                </div>
                                <p class="text-muted small mb-2">{{ $daysRemaining > 0 ? "$daysRemaining days remaining" : "Due today" }}</p>
                                <!-- <span class="badge bg-warning text-dark">Due Soon</span> -->
                                <a href="#" class="btn btn-primary btn-sm fs-6">Make Payment</a>
                            </div>
                        </div>
                    </div>

                    <!-- Next Payment -->
                    <div class="col-md-6 col-lg-3 d-flex">
                        <div class="card border-success border-opacity-25 position-relative overflow-hidden fw-100 mb-0">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-success bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center py-2">
                                <h5 class="card-title fw-medium mb-0">Next Payment</h5>
                                <i class="bi bi-currency-dollar text-success fs-3"></i>
                            </div>
                            <div class="card-body position-relative pt-0 pb-3">
                                <div class="fs-4 fw-bold">${{ number_format($rentAmount, 2) }}</div>
                                <p class="text-muted small mb-2">Monthly rent</p>
                                <a href="#" class="btn btn-primary btn-sm fs-6">Make Payment</a>
                            </div>
                        </div>
                    </div>

                    <!-- Outstanding -->
                    <div class="col-md-6 col-lg-3 d-flex">
                        <div class="card border-danger border-opacity-25 position-relative overflow-hidden fw-100 mb-0">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-danger bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center py-2">
                                <h5 class="card-title fw-medium mb-0">Outstanding</h5>
                                <i class="bi bi-exclamation-triangle text-danger fs-3"></i>
                            </div>
                            <div class="card-body position-relative pt-0 pb-3">
                                <div class="fs-3 fw-bold text-danger">$75.00</div>
                                <p class="text-muted small mb-2">Late fees included</p>
                                <span class="badge bg-danger">Overdue</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="col-md-6 col-lg-3 d-flex">
                        <div class="card border-secondary border-opacity-25 position-relative overflow-hidden fw-100 mb-0">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-secondary bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center py-2">
                                <h5 class="card-title fw-medium mb-0">Payment Method</h5>
                                <i class="bi bi-credit-card text-muted fs-3"></i>
                            </div>
                            <div class="card-body position-relative pt-0 pb-3">
                                <div class="fs-4 fw-bold">•••• 4532</div>
                                <p class="text-muted small mb-2">Expires 12/26</p>
                                <button type="button" class="btn btn-outline-secondary btn-sm fs-6">Update</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 d-flex">
                        <div class="card border-secondary border-opacity-25 position-relative overflow-hidden fw-100 mb-0"> 
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-secondary bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center py-2">
                                <h5 class="card-title fw-medium mb-0">Register Summary </h5>
                                <i class="bi bi-clipboard-data text-muted fs-3"></i>
                            </div>
                            <div class="card-body position-relative pt-0 pb-3">
                                <a href="#" class="btn btn-primary btn-sm fs-6 me-1 mb-1">View Invoices </a>
                                <a href="{{ url('view-payment') }}" class="btn btn-outline-secondary btn-sm fs-6 mb-1">View Payments  </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 d-flex">
                        <div class="card border-secondary border-opacity-25 position-relative overflow-hidden fw-100 mb-0">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-secondary bg-opacity-10"></div>
                            <div class="card-header bg-transparent border-0 position-relative d-flex justify-content-between align-items-center py-2">
                                <h5 class="card-title fw-medium mb-0">View Invoices  </h5>
                                <i class="bi bi-receipt text-muted fs-3"></i>
                            </div>
                            <div class="card-body position-relative pt-0 pb-3">
                                <a href="#" class="btn btn-outline-secondary btn-sm fs-6 mb-1 me-1">Utilities Billings  </a>
                                <a href="#" class="btn btn-outline-secondary btn-sm fs-6 mb-1 me-1">Other billings   </a>
                                <a href="#" class="btn btn-outline-secondary btn-sm fs-6 mb-1">Rent    </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
           
            <div class="col-lg-12">
                <div class="d-flex align-items-center mb-3">
                    <h2 class="h4 fw-semibold mb-0">
                        
                    </h2>
                </div>
                <div class="card h-100">
                    <div class="bg-transparent border-0 d-flex justify-content-between align-items-start">
                        <div data-component-content="%7B%7D">
                            <h5 class="card-title mb-2">
                                Payment History
                            </h5>
                            <p class="text-muted small mb-0">
                                View all your rent payments and transactions
                            </p>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-download me-2"></i>
                            Export
                        </button>
                    </div>
                    <div class="mt-3">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>Month</th>
                                        <th>Rent</th>
                                        <th>Security</th>
                                        <th>Last Month Rent</th>
                                        <th>Amenities</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @if(!empty($payments))
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td class="fw-medium ">{{ $payment['month'] }}</td>
                                            <td class="fw-semibold ">${{ number_format($payment['rent'], 2) }}</td>
                                            <td>${{ number_format($payment['security'], 2) }}</td>
                                            <td>${{ number_format($payment['last_month_rent'], 2) }}</td>
                                            <td>${{ number_format($payment['amenities'], 2) }}</td>
                                            <td>
                                                <span class="badge {{ $payment['status'] === 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                    {{ $payment['status'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-outline-secondary btn-sm">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-secondary btn-sm">
                                                        <i class="bi bi-download"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">No payment records found</td>
                                        </tr>
                                    @endforelse  
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">No payment records found</td>
                                    </tr>
                                @endif                                 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>




@endsection
