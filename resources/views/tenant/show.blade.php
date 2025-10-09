@extends('layouts.app')

@section('content')

@php
    // User info
    $u = $tenant->user;
    $fullName = $u->name ?? '-';

    // Avatar / Profile picture
    $avatarRaw = $u?->profile_pic;
    $avatar = $avatarRaw
        ? (preg_match('/^https?:\/\//i', $avatarRaw)
            ? $avatarRaw
            : Storage::url('upload/profile/' . $avatarRaw))
        : asset('images/avatar.png');

    // Helper function for safe date formatting
    if (!function_exists('formatTenantDate')) {
        function formatTenantDate($date)
        {
            if (!$date) return '-';
            try {
                return \Carbon\Carbon::createFromFormat('m-d-Y', $date)->format('M j, Y');
            } catch (\Exception $e) {
                try {
                    return \Carbon\Carbon::parse($date)->format('M j, Y');
                } catch (\Exception $e2) {
                    return '-';
                }
            }
        }
    }

    // Lease dates
    $leaseStart = formatTenantDate($tenant->lease_start_date);
    $leaseEnd   = formatTenantDate($tenant->lease_end_date);

    // Country (via state -> country)
    $country = $tenant->state?->country?->name ?? '-';
@endphp




    <!-- [ Main Content ] start -->
    <div class="custom-card-box">

<div class="row">
    <div class="col-lg-4 col-xxl-3 d-flex">
        <div class="card box-card w-100">

            <div class="pb-0">
                <!-- <div class="list-group list-group-flush">

                                            <ul class="nav flex-column nav-tabs account-tabs box-card custom-theme" id="myTab"
                                                role="tablist">

                                                Profile Tab -->
                <ul class="nav flex-column nav-tabs account-tabs box-card custom-theme" id="myTab"
                    role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile_content"
                            role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    @if(empty($tenant->user) && !empty($tenant->user->profile) && Storage::exists('upload/profile/' . $tenant->user->profile))
                                        <img class="img-fluid wid-70 me-2 tenant-img"
                                            src="{{ asset(Storage::url('upload/profile/' . $tenant->user->profile)) }}"
                                            alt="{{ $tenant->user->name }}">
                                    @else
                                        <div class="tenant-avatar d-flex align-items-center justify-content-center wid-70 me-2"
                                            style="width:50px; height:50px; border-radius:50%; background:#ddd; font-size:28px; font-weight:bold;">
                                            {{ strtoupper(substr($tenant->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 mx-2 position-relative">
                                    <h5 class="mb-1">
                                        {{ $fullName }} <br>
                                        <!-- <span>{{ $u->email }}</span> -->
                                    </h5>
                                    {{-- <a href="" class="text-white position-absolute top-0.5 right-0.5" >Edit</a> --}}

                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Contract Tab -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contract-tab" data-bs-toggle="tab" href="#contract_content"
                            role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-user-check me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">Contract Setup</h5>
                                    <!-- <small class="text-muted">Contract Setup</small> -->
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Invoice Tab -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="invoice-tab" data-bs-toggle="tab" href="#invoice_content"
                            role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-calendar me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">Payment Schedule</h5>
                                    <!-- <small class="text-muted">Invoice Setup</small> -->
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Invoice Tab -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="utilities-tab" data-bs-toggle="tab" href="#utilities" role="tab"
                            aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-bulb me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">Utilities</h5>
                                    <!-- <small class="text-muted">Invoice Setup</small> -->
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Invoice Tab -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="other_invoice-tab" data-bs-toggle="tab" href="#other_invoice"
                            role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-file-invoice me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">Other Invoice</h5>
                                    <!-- <small class="text-muted">Invoice Setup</small> -->
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Notice Tab -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="notice-tab" data-bs-toggle="tab" href="#notice_content"
                            role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-notes me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">Generate Notice</h5>
                                    <!-- <small class="text-muted">Exit Notice</small> -->
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Document Tab -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="document-tab" data-bs-toggle="tab" href="#document_content"
                            role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-file-upload me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">Send Document</h5>
                                    <!-- <small class="text-muted">Send Document</small> -->
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Report Tab -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="report-tab" data-bs-toggle="tab" href="#report_content"
                            role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-report me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">Report</h5>
                                    <!-- <small class="text-muted">Report</small> -->
                                </div>
                            </div>
                        </a>
                    </li>
                    
                    <!-- Emergency Contact Tab -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="emergency-tab" data-bs-toggle="tab" href="#emergency_content"
                            role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-phone me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">Emergency Contact</h5>
                                    <!-- <small class="text-muted">Report</small> -->
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tenant-tab" data-bs-toggle="tab" href="#tenant_content"
                            role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-user me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">Tenant Information</h5>
                                    <!-- <small class="text-muted">Report</small> -->
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="username-tab" data-bs-toggle="tab" href="#username_content"
                            role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-lock me-2 f-20"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="mb-0">User Name & Password</h5>
                                    <!-- <small class="text-muted">Report</small> -->
                                </div>
                            </div>
                        </a>
                    </li>

                </ul>

            </div>

        </div>
    </div>
    <div class="col-lg-8 col-xxl-9">
        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade show active" id="profile_content" role="tabpanel"
                aria-labelledby="profile-tab">
                <div class="card box-card w-100">
                    <div class="card-header">
                        <h5>Additional Information</h5>
                    </div>
                    <div class="card-body allwhite px-3">


                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><b class="text-header">Emergency Contact No.</b></td>
                                        <td>:</td>
                                        <td>{{ $u?->emergency_phone_number ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">Country</b></td>
                                        <td>:</td>
                                        <td>{{ $country }}</td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">State</b></td>
                                        <td>:</td>
                                        <td>{{ $tenant->state?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">City</b></td>
                                        <td>:</td>
                                        <td>{{ $tenant->city?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">Zip Code</b></td>
                                        <td>:</td>
                                        <td>{{ $tenant->zip_code ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">Property</b></td>
                                        <td>:</td>
                                        <td>{{ $tenant->property?->title ?? ($tenant->property?->name ?? '-') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">Unit</b></td>
                                        <td>:</td>
                                        <td>{{ $tenant->unit?->name ?? ($tenant->unit?->number ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">Lease Start Date</b></td>
                                        <td>:</td>
                                        <td>{{ $leaseStart }}</td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">Lease End Date</b></td>
                                        <td>:</td>
                                        <td>{{ $leaseEnd }}</td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">Documents</b></td>
                                        <td>:</td>
                                        <td>
                                            @php $hasDocs = false; @endphp
                                            @if ($tenant->application_document)
                                                @php $hasDocs = true; @endphp
                                                <div><a href="{{ Storage::url($tenant->application_document) }}"
                                                        target="_blank">Application Document</a></div>
                                            @endif
                                            @if ($tenant->driving_licence)
                                                @php $hasDocs = true; @endphp
                                                <div><a href="{{ Storage::url($tenant->driving_licence) }}"
                                                        target="_blank">Driving Licence</a></div>
                                            @endif
                                            @if ($tenant->bank_statement)
                                                @php $hasDocs = true; @endphp
                                                <div><a href="{{ Storage::url($tenant->bank_statement) }}"
                                                        target="_blank">Bank Statement</a></div>
                                            @endif
                                            @unless ($hasDocs)
                                                -
                                            @endunless
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b class="text-header">Address</b></td>
                                        <td>:</td>
                                        <td>{{ $tenant->address ?: '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

                <div class="tab-pane fade" id="contract_content" role="tabpanel" aria-labelledby="contract-tab">
                    <div class="card box-card w-100">
                        <div class="card-header">
                            <h5>Contract Setup</h5>
                        </div>
                        <div class="allwhite pt-0">
                            <div class="card-body theme-card">
                                @php
                                    $isEdit = isset($contract) && $contract?->exists;
                                    $action = route('tenant-contractsupdate', $contract ?? null);

                                    $stdRent = old('standard_rent', $tenantcontracts->standard_rent ?? '');
                                    $lateFee = old('late_fee', $tenantcontracts->late_fee ?? '');
                                    $secDep = old('security_deposit', $tenantcontracts->security_deposit ?? '');
                                    $notice = old('notice_period_months', $tenantcontracts->notice_period_months ?? '3');
                                    $renewMon = old('contract_renewal_month', $tenantcontracts->contract_renewal_month ?? '12');
                                    $renewAmt = old('contract_renewal_amount', $tenantcontracts->contract_renewal_amount ?? '');
                                    $tenantId = $tenant->id;
                                    $propertyId = old('property_id', $tenantcontracts->property_id ?? $tenant->property_id);
                                    $ownerId = old('owner_id', $tenantcontracts->owner_id ?? ($tenant->owner_id ?? (auth()->user()->id ?? '')));
                                @endphp

                                <form id="setupContractForm" method="POST" action="{{ $action }}" enctype="multipart/form-data">
                                    @csrf

                                    {{-- Hidden inputs --}}
                                    <input type="hidden" name="tenant_id" value="{{ $tenantId }}">
                                    <input type="hidden" name="property_id" value="{{ $tenantcontracts->property_id ?? $tenant->property_id ?? '' }}">
                                    <small class="text-muted">Property ID: {{ $tenantcontracts->property_id ?? $tenant->property_id ?? 'N/A' }}</small>
                                    <input type="hidden" name="owner_id" value="{{ $contract->user_id ?? '' }}">

                                    {{-- Top error summary --}}
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <strong>Please fix the errors below:</strong>
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $msg)
                                                    <li>{{ $msg }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    {{-- Dates --}}
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Start Date</label>
                                            <input
                                                type="date"
                                                id="start_date"
                                                name="start_date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                value="{{ old('start_date', $tenantcontracts->start_date ?? '') }}"
                                                {{ !empty($tenantcontracts->start_date) ? 'readonly' : '' }}>
                                            @error('start_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">End Date</label>
                                            <input
                                                type="date"
                                                id="end_date"
                                                name="end_date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                value="{{ old('end_date', $tenantcontracts->end_date ?? '') }}"
                                                {{ !empty($tenantcontracts->end_date) ? 'readonly' : '' }}>
                                            @error('end_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Lease Term (Months)</label>
                                            <input type="number" id="lease_term" name="lease_term" class="form-control" readonly>
                                        </div>
                                    </div>

                                    {{-- Standard Rent / Fees / Security Deposit --}}
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Standard Rent (USD)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" placeholder="e.g. 1200"
                                                    class="form-control @error('standard_rent') is-invalid @enderror"
                                                    name="standard_rent" value="{{ $stdRent }}">
                                            </div>
                                            @error('standard_rent')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- <div class="col-md-6">
                                            <label class="form-label">Standard Late Fee (USD)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" placeholder="e.g. 50"
                                                    class="form-control @error('late_fee') is-invalid @enderror"
                                                    name="late_fee" value="{{ $lateFee }}">
                                            </div>
                                            @error('late_fee')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div> -->

                                        <div class="col-md-6">
                                            <label class="form-label">Security Deposit (USD)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" placeholder="e.g. 500"
                                                    class="form-control @error('security_deposit') is-invalid @enderror"
                                                    name="security_deposit" value="{{ $secDep }}">
                                            </div>
                                            @error('security_deposit')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Notice Period --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Notice Period</label>
                                            <select name="notice_period_months"
                                                    class="form-control form-select @error('notice_period_months') is-invalid @enderror">
                                                <option value="1" {{ $notice == '1' ? 'selected' : '' }}>1 month</option>
                                                <option value="2" {{ $notice == '2' ? 'selected' : '' }}>2 months</option>
                                                <option value="3" {{ $notice == '3' ? 'selected' : '' }}>3 months</option>
                                            </select>
                                            @error('notice_period_months')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                   {{-- File Upload --}}
                    <div class="col-md-6">
                        <label class="form-label">Upload Contract</label>
                        <div class="input-group">
                            <input type="file" name="contract_doc" id="contractFile"
                                class="form-control @error('contract_doc') is-invalid @enderror" accept=".pdf,image/*">
                            <button type="button" id="previewBtn" class="btn btn-outline-primary" style="display:none;" target="_blank">
                                👁
                            </button>
                        </div>
                        @error('contract_doc')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        @if (!empty($tenantcontracts->contract_doc))
                            <div class="mt-2">
                                <a href="{{ asset('storage/upload/contracts/' . $tenantcontracts->contract_doc) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    👁 Preview Contract
                                </a>
                            </div>
                        @endif
                    </div>



                                    {{-- Contract Renewal --}}
                                    <div class="col-lg-12 mb-2">
                                        <h3 class="mb-0 mt-3">Contract Renewal Setup</h3>
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <label class="form-label">Contract Renewal Month</label>
                                        <select name="contract_renewal_month" id="contract_renewal_month"
                                                class="form-control form-select @error('contract_renewal_month') is-invalid @enderror">
                                            <option value="3" {{ $renewMon == '3' ? 'selected' : '' }}>3 months</option>
                                            <option value="6" {{ $renewMon == '6' ? 'selected' : '' }}>6 months</option>
                                            <option value="9" {{ $renewMon == '9' ? 'selected' : '' }}>9 months</option>
                                            <option value="12" {{ $renewMon == '12' ? 'selected' : '' }}>12 months</option>
                                        </select>
                                        @error('contract_renewal_month')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Tip: End Date will auto-suggest based on Start Date + Renewal Months.</div>
                                    </div> -->

                                <!-- <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Contract Renewal Amount Increase (USD)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>                                                    
                                        </div>
                                        @error('contract_renewal_amount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Start Months</label>
                                        <input type="text" class="form-control">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">End Months</label>  
                                           <input type="text" class="form-control">                                         
                                    </div>
                                </div> -->

                                <div id="renewal-container">
                                    <!-- Default Row -->
                                    <div class="row g-3 renewal-row align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label">Contract Renewal Amount Increase (USD)</label>
                                            <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" placeholder="e.g. 100"
                                                    class="form-control" name="contract_renewal_amount[]">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Start Months</label>
                                            <input type="text" class="form-control" name="start_months[]" placeholder="e.g. January">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">End Months</label>
                                            <input type="text" class="form-control" name="end_months[]" placeholder="e.g. June">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <button type="button" id="add-more" class="btn btn-sm btn-primary">+ Add More</button>
                                </div>


            
                                <script>
                                    document.getElementById('add-more').addEventListener('click', function() {
                                    const container = document.getElementById('renewal-container');
                                    const firstRow = container.querySelector('.renewal-row');
                                    const newRow = firstRow.cloneNode(true);

                                    // Reset input values
                                    newRow.querySelectorAll('input').forEach(input => input.value = '');

                                    // If remove button already exists (from previous clone), remove it first to prevent duplication
                                    const oldRemove = newRow.querySelector('.remove-row');
                                    if (oldRemove) oldRemove.remove();

                                    // Create remove button
                                    const removeBtn = document.createElement('button');
                                    removeBtn.type = 'button';
                                    removeBtn.className = 'remove-row ms-2';
                                    removeBtn.innerHTML = '×';
                                    removeBtn.title = 'Remove this row';
                                    removeBtn.onclick = function() {
                                    newRow.remove();
                                    };

                                    // Add remove button next to End Month input
                                    const lastCol = newRow.lastElementChild;
                                    const endInput = lastCol.querySelector('input');
                                    const wrapper = document.createElement('div');
                                    wrapper.className = 'd-flex align-items-center gap-2';
                                    wrapper.appendChild(endInput);
                                    wrapper.appendChild(removeBtn);

                                    // Replace content in last column
                                    lastCol.innerHTML = '';
                                    lastCol.appendChild(wrapper);

                                    // Append new row to container
                                    container.appendChild(newRow);
                                });
                                </script>
                                <div class="late-payment">
                                    <div id="late-rows">
                                    <!-- Default Row -->
                                    <div class="row g-3 late-row">
                                        <div class="col-lg-12 mb-0">
                                        <h3 class="mb-0 mt-3">Late Payment Setup</h3>
                                        </div>

                                        <div class="col-md-3">
                                        <label class="form-label">Select Tier</label>
                                        <select class="form-select" name="tier[]">
                                            <option value="">Select</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                        </select>
                                        </div>

                                        <div class="col-md-3">
                                        <label class="form-label">How many days grace</label>
                                        <input type="text" class="form-control" name="grace_days[]" placeholder="e.g. 5">
                                        </div>

                                        <div class="col-md-3">
                                        <label class="form-label">Time</label>
                                        <input type="time" class="form-control" name="time[]">
                                        </div>

                                        <div class="col-md-3">
                                            <div>
                                                <label class="form-label">Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" placeholder="e.g. 1200" class="form-control" name="amount[]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-3">
                                    <button type="button" id="late-add-more" class="btn btn-sm btn-primary">+ Add More</button>
                                    </div>
                                </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const addMoreBtn = document.getElementById('late-add-more');
  const lateRowsContainer = document.getElementById('late-rows');

  addMoreBtn.addEventListener('click', function() {
    // Clone the first row
    const firstRow = document.querySelector('.late-row');
    const newRow = firstRow.cloneNode(true);

    // Remove heading from cloned rows
    const heading = newRow.querySelector('h3');
    if (heading) heading.remove();

    // Clear inputs and selects
    newRow.querySelectorAll('input, select').forEach(el => el.value = '');

    // Remove old remove button (if any)
    const oldRemove = newRow.querySelector('.remove-row');
    if (oldRemove) oldRemove.remove();

    // Add remove button
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'remove-row';
    removeBtn.innerHTML = '&times;';
    removeBtn.title = 'Remove this row';
    removeBtn.addEventListener('click', () => newRow.remove());

    // Add remove button beside last input group
    const lastCol = newRow.querySelector('.col-md-3:last-child');
    lastCol.classList.add('d-flex', 'align-items-start', 'gap-2');
    lastCol.appendChild(removeBtn);

    // Append new row
    lateRowsContainer.appendChild(newRow);
  });
});
</script>
                                        

                                        {{-- Submit Button --}}
                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                {{ $isEdit ? 'Update Contract' : 'Save Contract' }}
                                            </button>
                                        </div>
                                    </form>

                                    {{-- Flatpickr CSS/JS --}}
                                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                                    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const startEl = document.getElementById('start_date');
                                            const endEl = document.getElementById('end_date');
                                            const renewEl = document.getElementById('contract_renewal_month');

                                            // Start Date Picker
                                            flatpickr(startEl, {
                                                dateFormat: "Y-m-d",
                                                altInput: true,
                                                altFormat: "m-d-Y",
                                                defaultDate: startEl.value || null,
                                                allowInput: true,
                                                onChange: function(selectedDates) {
                                                    if (!selectedDates.length) return;
                                                    const months = parseInt(renewEl?.value || 12);
                                                    const endDate = new Date(selectedDates[0]);
                                                    endDate.setMonth(endDate.getMonth() + months);

                                                    endEl._flatpickr.setDate(endDate, true);
                                                }
                                            });

                                            // End Date Picker
                                            flatpickr(endEl, {
                                                dateFormat: "Y-m-d",
                                                altInput: true,
                                                altFormat: "m-d-Y",
                                                defaultDate: endEl.value || null,
                                                allowInput: true
                                            });

                                            // Renewal Month Change
                                            renewEl?.addEventListener('change', function () {
                                                if (!startEl._flatpickr.selectedDates[0]) return;
                                                const startDate = new Date(startEl._flatpickr.selectedDates[0]);
                                                const months = parseInt(this.value || 12);
                                                startDate.setMonth(startDate.getMonth() + months);
                                                endEl._flatpickr.setDate(startDate, true);
                                            });
                                        });
                                    </script>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="invoice_content" role="tabpanel" aria-labelledby="invoice-tab">
                            <div class="card box-card w-100">
                                <div class="card-header">
                                    <h5>Payment Schedule</h5>
                                </div>
                                <div class="card-body">
                                    <div class="card theme-card">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0 text-center" id="payment-schedule-table">
                                                    @if($tenantcontracts && $periods)
                                                        <thead class="table-theme">
                                                            <tr>
                                                                <th>Month</th>
                                                                <th>Rent</th>
                                                                <th>Security</th>
                                                                <!-- <th>Last Month Rent</th> -->
                                                                <th>Amenities</th>
                                                                <th>Utilities</th>
                                                                <th>Late Payments</th>
                                                                <th>Status</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($periods as $index => $month)
                                                                @php
                                                                    $label = $month['month_label'];
                                                                    $ym = $month['ym'];
                                                                    $rent = $month['rent'];
                                                                    $security = $month['security'];
                                                                @endphp

                                                                <tr data-ym="{{ $ym }}">
                                                                    <td>
                                                                        {{ $label }}                                                                        
                                                                    </td>

                                                                    {{-- Rent --}}
                                                                    <td>${{ number_format($rent, 2) }}</td>

                                                                    {{-- Security Deposit --}}
                                                                    <td>
                                                                        @if(($month['type'] ?? '') === 'base' && $loop->first && $security > 0)
                                                                            ${{ number_format($security, 2) }}
                                                                        @endif
                                                                    </td>

                                                                    {{-- Amenities --}}
                                                                    <td>${{ number_format($propertyAmenitiesTotal ?? 0, 2) }}</td>

                                                                    <td></td>
                                                                    <td></td>

                                                                    {{-- Status --}}
                                                                    @php
                                                                        $isPending = (optional($tenantcontracts)->status ?? 'pending') === 'pending';
                                                                    @endphp
                                                                    <td>
                                                                        <select class="form-select form-select-sm status-select" data-ym="{{ $ym }}">
                                                                            <option value="pending" {{ $isPending ? 'selected' : '' }}>Pending</option>
                                                                            <option value="paid" {{ !$isPending ? 'selected' : '' }}>Paid</option>
                                                                        </select>
                                                                    </td>

                                                                    {{-- Actions --}}
                                                                    <td> ... </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>


                                                        {{-- Table Footer with grand totals --}}
                                                        @php
                                                            $totalRent = collect($periods)->sum('rent');
                                                            $totalAmenities = $propertyAmenitiesTotal * count($periods);
                                                        @endphp
                                                        <tfoot class="table-secondary text-center">
                                                        <tr>
                                                            <th>Total</th>
                                                            <th>${{ number_format($totalRent, 2) }}</th>
                                                            <th>${{ number_format($tenantcontracts->security_deposit ?? 0, 2) }}</th>
                                                            <th>${{ number_format($totalAmenities, 2) }}</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        </tfoot>

                                                    @else
                                                        <tr>
                                                            <td colspan="7">No payment schedule available.</td>
                                                        </tr>
                                                    @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- JS to toggle contenteditable based on status --}}
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const table = document.getElementById('payment-schedule-table');

                            table.querySelectorAll('tr[data-ym]').forEach(row => {
                                const select = row.querySelector('.status-select');
                                const editableCells = row.querySelectorAll('.editable');

                                // Set initial editable state
                                editableCells.forEach(cell => {
                                    cell.contentEditable = select.value === 'pending';
                                });

                                // Toggle editable when status changes
                                select.addEventListener('change', function() {
                                    const isPending = this.value === 'pending';
                                    editableCells.forEach(cell => {
                                        cell.contentEditable = isPending;
                                    });
                                });
                            });
                        });
                        </script>

                        <div class="tab-pane fade" id="utilities" role="tabpanel" aria-labelledby="utilities-tab">
                            <div class="card box-card w-100">
                                <div class="card-header">
                                    <h5>Utilities</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0 text-center" id="utilities-table">
                                            <thead class="table-theme">
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead
                                            <tbody>
                                                @php 
                                                    $tenantall = DB::table('utility_invoices')->where('tenant_id',$u->id)->where('property_id',$tenant->property_id)->get();
                                                @endphp

                                                @forelse ($tenantall as $invoice)
                                                    <tr>
                                                        <td>{{ date('F Y', strtotime($invoice->invoice_month)) }}</td>
                                                        <td>${{ number_format($invoice->amount, 2) }}</td>
                                                        <td>
                                                            @if($invoice->status == 'delivered')
                                                                <span class="badge bg-success">Delivered</span>
                                                            @elseif($invoice->status == 'pending')
                                                                <span class="badge bg-warning text-dark">Pending</span>
                                                            @elseif($invoice->status == 'draft')
                                                                <span class="badge bg-secondary text-dark">Draft</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a target="_blank" href="{{ route('utility-invoices.show', $invoice->id) }}">
                                                                <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" title="View Invoice"></i>
                                                            </a>
                                                            <a target="_blank" href="{{ route('utility.invoices.pdf', $invoice->id) }}">
                                                                <i class="ti ti-download mx-1" data-bs-toggle="tooltip" title="Download Invoice"></i>
                                                            </a>
                                                            <i class="ti ti-refresh mx-1 resend-invoice" data-id="{{ $invoice->id }}" data-bs-toggle="tooltip" title="Resend Invoice"></i>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4">No utility invoices found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Resend Invoice AJAX
                            document.querySelectorAll('.resend-invoice').forEach(button => {
                                button.addEventListener('click', function() {
                                    const invoiceId = this.dataset.id;
                                    fetch(`/utility-invoices/${invoiceId}/resend`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        alert(data.message || 'Invoice resent successfully!');
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        alert('Error resending invoice.');
                                    });
                                });
                            });
                        });
                        </script>


                    <div class="tab-pane fade" id="other_invoice" role="tabpanel" aria-labelledby="other_invoice-tab">
                        <div class="card box-card w-100">
                            <div class="card-header">
                                <h5>Other Invoices</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="custom-bg-table">
                                        <thead class="table-theme text-center">
                                            <tr>
                                                <th>Invoice No.</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    @php 
                                        $tenantotherinvoicesall = DB::table('other_invoices')->where('tenant_id',$u->id)->where('property_id',$tenant->property_id)->get();
                                    @endphp
                                        <tbody class="text-center">
                                            @forelse($tenantotherinvoicesall as $i)
                                                <tr>
                                                    <td>{{ $i->id }}</td>
                                                    <td>${{ $i->amount }}</td>
                                                    <td>
                                                        <span class="badge bg-success">{{ $i->status }}</span>
                                                        <!-- <span class="badge bg-warning">Pending</span> -->
                                                    </td>
                                                    <td>
                                                        <a target="_blank"
                                                            href="{{ route('other-invoices.show', $i->id) }}"><i
                                                                class="ti ti-eye mx-1" data-bs-toggle="tooltip"
                                                                data-bs-title="View"></i></a>
                                                        <a target="_blank"
                                                            href="{{ route('utility.invoices.pdf', $i->id) }}"><i
                                                                class="ti ti-download mx-1" data-bs-toggle="tooltip"
                                                                data-bs-title="Download"></i></a>


                                                        <i class="ti ti-refresh mx-1" data-bs-toggle="tooltip"
                                                            data-bs-title="Resend Invoice"></i>
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse


                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="notice_content" role="tabpanel" aria-labelledby="notice-tab">
                        <div class="card box-card w-100">
                            <div class="card-header">
                                <h5>Generate Notice</h5>
                            </div>
                            <div class="card-body allwhite">
                                <div class="card theme-card">
                                    <!-- <div class="d-flex align-items-center justify-content-center text-center mb-4">
                                                        <button class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#exitNoticeModal">
                                                            Generate Notice
                                                        </button>
                                                        </div> -->
                                    <ul class="row g-3 list-unstyled justify-content-center">

                                    @php 
                                        $noticesall = DB::table('notices')->where('owner_id',$u->id)->get();
                                    @endphp

                                        @if (!empty($noticesall))
                                            @foreach($noticesall as $n)
                                                <li class="col-md-4">
                                                    <a href="#" class="btn btn-secondary btn-md w-100"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#sendNoticeMailModal{{ $n->id }}">
                                                        {{ $n->title }}
                                                    </a>
                                                    @include('tenants.notice_mails', ['notice' => $n])
                                                </li>
                                            @endforeach

                                        @endif


                                    </ul>

                                    <!-- Exit Notice Card Container -->
                                    <div id="exitNoticeCards" class="row g-3 px-3">
                                        <!-- Cards will be added here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="document_content" role="tabpanel" aria-labelledby="document-tab">
                        <!-- List Document -->
                        <div class="card box-card w-100 list-document document-card">
                            <div class="card-header">
                                <h5 class="d-flex justify-content-between align-items-center">
                                    Document List
                                    <span class="btn btn-secondary add-new-btn">Send New</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0" id="custom-bg-table">
                                            <thead class="table-theme text-center">
                                                <tr>
                                                    <th>Document Name</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <tr>
                                                    <td>Document 1</td>
                                                    <td><span class="badge bg-success">Delivered</span></td>
                                                    <td>
                                                        <a href="#" class="view-btn"><i
                                                                class="ti ti-eye mx-1"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Document 2</td>
                                                    <td><span class="badge bg-warning text-dark">Pending</span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="view-btn"><i
                                                                class="ti ti-eye mx-1"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Send Document -->
                        <div class="card box-card w-100 send-document document-card d-none">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Send Document</h5>
                                <span class="btn btn-outline-secondary back-btn">Back</span>
                            </div>
                            <div class="card-body allwhite mb-0">
                                <div class="card theme-card">
                                    <form id="sendDocForm" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="toEmail" class="form-label">Select Document</label>
                                            <select name="" id="" class="form-control">
                                                <option value="">-- Select --</option>
                                                <option value="">Document 1</option>
                                                <option value="">Document 2</option>
                                                <option value="">Document 3</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="subject" class="form-label">Subject</label>
                                            <input type="text" class="form-control" id="subject" name="subject"
                                                placeholder="Document subject..." required="">
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Comment</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter details..."></textarea>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-secondary">Send</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- View Document -->
                        <div class="card box-card w-100 view-document document-card d-none">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>View Document</h5>
                                <span class="btn btn-outline-secondary back-btn">Back</span>
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
                                                <label class="form-label fw-bold">Selected Document</label>
                                                <p class="form-control-plaintext">Document 1</p>
                                            </div>

                                            <!-- Subject -->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Subject</label>
                                                <p class="form-control-plaintext">Sample Subject for Document
                                                </p>
                                            </div>

                                            <!-- Comment -->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Comment</label>
                                                <p class="form-control-plaintext">
                                                    This is the comment text entered by the user.
                                                    It shows the details about the document.
                                                </p>
                                            </div>

                                            <div class="text-end">
                                                <a href="#" class="btn btn-secondary back-btn">Back</a>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="report_content" role="tabpanel" aria-labelledby="report-tab">
                        <div class="card box-card w-100">
                            <div class="card-header">
                                <h5>Report</h5>
                            </div>
                            <div class="card-body allwhite mb-0">
                                <div class="card theme-card">
                                    <form id="sendDocForm" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="toEmail" class="form-label">Select Document</label>
                                            <select name="" id="" class="form-control">
                                                <option value="">-- Select --</option>
                                                <option value="">Document 1</option>
                                                <option value="">Document 2</option>
                                                <option value="">Document 3</option>
                                            </select>
                                        </div>

                                        <!-- Subject -->
                                        <div class="mb-3">
                                            <label for="subject" class="form-label">Subject</label>
                                            <input type="text" class="form-control" id="subject" name="subject"
                                                placeholder="Document subject..." required="">
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Comment</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter details..."></textarea>
                                        </div>

                                        <!-- Send Button -->
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-secondary">Send</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- a -->
                    <div class="tab-pane fade" id="emergency_content" role="tabpanel" aria-labelledby="emergency-tab">
                        <div class="card box-card w-100">
                            <div class="card-header">
                                <h5>Emergency Information</h5>
                            </div>
                            <div class="card-body allwhite px-3">


                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td><b class="text-header">Emergency Contact No.</b></td>
                                                <td>:</td>
                                                <td>{{ $u?->emergency_phone_number ?: '-' }}</td>
                                            </tr>
                                         
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- b -->
                    <div class="tab-pane fade" id="tenant_content" role="tabpanel" aria-labelledby="tenant-tab">
                        <div class="card box-card w-100">
                            <div class="card-header">
                                <h5>Tenant Information</h5>
                            </div>
                            <div class="card-body allwhite px-3">


                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td><b class="text-header">Emergency Contact No.</b></td>
                                                <td>:</td>
                                                <td>{{ $u?->emergency_phone_number ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Country</b></td>
                                                <td>:</td>
                                                <td>{{ $country }}</td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">State</b></td>
                                                <td>:</td>
                                                <td>{{ $tenant->state?->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">City</b></td>
                                                <td>:</td>
                                                <td>{{ $tenant->city?->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Zip Code</b></td>
                                                <td>:</td>
                                                <td>{{ $tenant->zip_code ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Property</b></td>
                                                <td>:</td>
                                                <td>{{ $tenant->property?->title ?? ($tenant->property?->name ?? '-') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Unit</b></td>
                                                <td>:</td>
                                                <td>{{ $tenant->unit?->name ?? ($tenant->unit?->number ?? '-') }}</td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Lease Start Date</b></td>
                                                <td>:</td>
                                                <td>{{ $leaseStart }}</td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Lease End Date</b></td>
                                                <td>:</td>
                                                <td>{{ $leaseEnd }}</td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Documents</b></td>
                                                <td>:</td>
                                                <td>
                                                    @php $hasDocs = false; @endphp
                                                    @if ($tenant->application_document)
                                                        @php $hasDocs = true; @endphp
                                                        <div><a href="{{ Storage::url($tenant->application_document) }}"
                                                                target="_blank">Application Document</a></div>
                                                    @endif
                                                    @if ($tenant->driving_licence)
                                                        @php $hasDocs = true; @endphp
                                                        <div><a href="{{ Storage::url($tenant->driving_licence) }}"
                                                                target="_blank">Driving Licence</a></div>
                                                    @endif
                                                    @if ($tenant->bank_statement)
                                                        @php $hasDocs = true; @endphp
                                                        <div><a href="{{ Storage::url($tenant->bank_statement) }}"
                                                                target="_blank">Bank Statement</a></div>
                                                    @endif
                                                    @unless ($hasDocs)
                                                        -
                                                    @endunless
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Address</b></td>
                                                <td>:</td>
                                                <td>{{ $tenant->address ?: '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- c -->
                    <div class="tab-pane fade" id="username_content" role="tabpanel" aria-labelledby="username-tab">
                        <div class="card box-card w-100">
                            <div class="card-header">
                                <h5>User Name & Password Information</h5>
                            </div>
                            <div class="card-body allwhite px-3">


                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td><b class="text-header">User Name </b></td>
                                                <td>:</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Password </b></td>
                                                <td>:</td>
                                                <td></td>
                                            </tr>
                                         
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>



    <!-- [ Main Content ] end -->

@stop

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Lease term script loaded');

    // ===== ELEMENTS =====
    const startEl = document.getElementById('start_date');
    const endEl = document.getElementById('end_date');
    const renewEl = document.getElementById('contract_renewal_month');
    const leaseTermEl = document.getElementById('lease_term');

    // ===== 1️⃣ Calculate lease term =====
    function calculateLeaseTerm() {

        if (!startEl?.value || !endEl?.value) {
            leaseTermEl.value = '';
            return;
        }

        const startDate = new Date(startEl.value);
        const endDate = new Date(endEl.value);

        if (isNaN(startDate) || isNaN(endDate) || endDate <= startDate) {
            leaseTermEl.value = '';
            console.log('Invalid or missing dates');
            return;
        }

        const diffDays = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24));
        const months = Math.floor(diffDays / 30);
        console.log('Lease Term:', months, 'months');
        leaseTermEl.value = months;
    }

    // ===== 2️⃣ Auto-suggest End Date =====
    function suggestEndDate() {
        if (!startEl?.value || !renewEl?.value) return;
        const months = parseInt(renewEl.value, 10);
        if (isNaN(months)) return;

        const start = new Date(startEl.value);
        const end = new Date(start);
        end.setMonth(end.getMonth() + months);

        endEl.value = end.toISOString().split('T')[0];
        endEl.min = startEl.value;

        calculateLeaseTerm();
    }

    // ===== 3️⃣ Event bindings =====
    startEl?.addEventListener('change', () => {
        suggestEndDate();
        calculateLeaseTerm();
    });
    endEl?.addEventListener('change', calculateLeaseTerm);
    renewEl?.addEventListener('change', suggestEndDate);

    // ===== 4️⃣ Recalculate if data already filled (edit mode) =====
    if (startEl?.value && endEl?.value) {
        calculateLeaseTerm();
    }
});
</script>

<script>
    const fileInput = document.getElementById('contractFile');
    const previewBtn = document.getElementById('previewBtn');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) {
            previewBtn.style.display = 'none';
            return;
        }

        const fileURL = URL.createObjectURL(file);
        previewBtn.style.display = 'inline-block';
        previewBtn.onclick = () => window.open(fileURL, '_blank');
    });
</script>
@endpush
