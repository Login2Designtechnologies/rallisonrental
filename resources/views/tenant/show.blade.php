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
    function formatTenantDate($date)
    {
        if (!$date) return '-';
        try {
            // If date is stored as m-d-Y
            return \Carbon\Carbon::createFromFormat('m-d-Y', $date)->format('M j, Y');
        } catch (\Exception $e) {
            try {
                // Fallback to parse any other format
                return \Carbon\Carbon::parse($date)->format('M j, Y');
            } catch (\Exception $e2) {
                return '-';
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
                                            <img class="img-radius img-fluid wid-80" src="{{ !empty($tenant->user) && !empty($tenant->user->profile) ? asset(Storage::url('upload/profile/' . $tenant->user->profile)) : asset(Storage::url('upload/profile/avatar.png')) }}"
                                                alt="User image" />
                                        </div>
                                        <div class="flex-grow-1 mx-3 position-relative">
                                            <h5 class="mb-1">
                                                {{ $fullName }} <br>
                                                <span>{{ $u->email }}</span>
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
                                            <i class="ti ti-key me-2 f-20"></i>
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
                                            <i class="ti ti-bulb me-2 f-20"></i>
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
                                            <i class="ti ti-settings me-2 f-20"></i>
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
                                            <i class="ti ti-settings me-2 f-20"></i>
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
                                            <i class="ti ti-mail me-2 f-20"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-0">Report</h5>
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
                    <input type="hidden" name="property_id" value="{{$contract->property ?? '' }}">
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
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                    @if(!empty($tenantcontracts->start_date))
                            <input type="text" style="pointer-events: none;" 
                                   class="form-control"
                                   placeholder="MM-DD-YYYY" autocomplete="off" value="{{ old('start_date', $tenantcontracts->start_date ?? '') }}">
                    @else
                            <input type="text" id="start_date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   placeholder="MM-DD-YYYY" autocomplete="off" value="{{ old('start_date', $tenantcontracts->start_date ?? '') }}">
                    @endif
                            @error('start_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                    @if(!empty($tenantcontracts->end_date))
                            <input type="text" style="pointer-events: none;"
                                   class="form-control"
                                   placeholder="MM-DD-YYYY" autocomplete="off" value="{{$tenantcontracts->end_date ?? ''}}">
                    @else
                            <input type="text" id="end_date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   placeholder="MM-DD-YYYY" autocomplete="off" value="{{$tenantcontracts->end_date ?? ''}}">
                    @endif
                            @error('end_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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

                        <div class="col-md-6">
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
                        </div>

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
                    </div>

                    {{-- Notice Period --}}
                    <div class="col-md-12 mb-3">
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

                    {{-- Contract Renewal --}}
                    <div class="col-lg-12 mb-3">
                        <h3 class="mb-0 mt-3">Contract Renewal Setup</h3>
                    </div>

                    <div class="col-md-6">
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
                    </div>

                  <div class="col-md-6">
    <label class="form-label">Contract Renewal Amount Increase (USD)</label>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input type="number" step="0.01" placeholder="e.g. 100"
               class="form-control @error('contract_renewal_amount') is-invalid @enderror"
               name="contract_renewal_amount" value="{{ $renewAmt }}">
    </div>
    @error('contract_renewal_amount')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>


                    {{-- File Upload --}}
                    <div class="col-md-12 mt-5">
                        <label class="form-label">Upload Contract</label>
                        <input type="file" name="contract_doc" id="contractFile"
                               class="form-control @error('contract_doc') is-invalid @enderror" accept=".pdf,image/*">
                        @error('contract_doc')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        @if ($isEdit && optional($tenantcontracts)->contract_doc)
                            <div class="mt-2">
                                <a href="{{ asset(Storage::url('upload/contracts/' . $tenantcontracts->contract_doc)) }}" target="_blank" class="small">
                                    View current contract
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <div class="text-end">
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
                        <thead class="table-theme">
                            <tr>
                                <th>Month</th>
                                <th>Rent</th>
                                <th>Security</th>
                                <th>Last Month Rent</th>
                                <th>Amenities</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
              <tbody>
@if($tenantcontracts && $period)
    @foreach ($period as $index => $month)
        @php
            $label = $month->format('F Y');
            $ym = $month->format('Y-m');
            $isPending = $contract->status == 'pending';
        @endphp

        <tr data-ym="{{ $ym }}">
            <td>{{ $label }}</td>

            {{-- Rent --}}
            <td>${{ number_format($tenantcontracts->standard_rent, 2) }}</td>

            {{-- Security Deposit --}}
            <td>
                @if($index == 0)
                    ${{ number_format($tenantcontracts->security_deposit, 2) }}
                @endif
            </td>

            {{-- Last Month Rent --}}
            <td></td>

            {{-- Amenities --}}
            <td>${{ number_format($propertyAmenitiesTotal, 2) }}</td>

            {{-- Status --}}
            <td>
                <select class="form-select form-select-sm status-select">
                    <option value="pending" {{ $isPending ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ !$isPending ? 'selected' : '' }}>Paid</option>
                </select>
            </td>

            {{-- Actions --}}
            <td>
                <button class="btn btn-sm btn-primary" title="View Invoice">
                    <i class="ti ti-eye"></i>
                </button>
                <button class="btn btn-sm btn-secondary" title="Download Invoice">
                    <i class="ti ti-download"></i>
                </button>
                <form action="{{ route('tenants.resend', $tenant->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning" title="Resend Invoice">
                        <i class="ti ti-send"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="7">No payment schedule available.</td>
    </tr>
@endif
</tbody>

{{-- Table Footer with grand totals --}}
<tfoot class="table-secondary text-center">
<tr>
    <th>Total</th>
    <th>${{ number_format((optional($tenantcontracts)->standard_rent ?? 0) * (is_array($period) ? count($period) : 0), 2) }}</th>
    <th>
        ${{ number_format($tenantcontracts?->security_deposit ?? 0, 2) }}
    </th>
    <th>$0.00</th>
    <th>${{ number_format($propertyAmenitiesTotal * count($period ?? []), 2) }}</th>
    <th></th>
    <th></th>
</tr>
</tfoot>

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
                    </thead>
                    <tbody>
                        @php 
                            $tenantall = DB::table('utility_invoices')->where('tenant_id',$u->id)->where('property_id',$tenant->property)->get();
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
                                        $tenantotherinvoicesall = DB::table('other_invoices')->where('tenant_id',$u->id)->where('property_id',$tenant->property)->get();
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

                </div>
            </div>

        </div>
    </div>



    <!-- [ Main Content ] end -->

@stop




@push('script')
    {{-- ===== Dynamic behaviour (vanilla JS) ===== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startEl = document.getElementById('start_date');
            const endEl = document.getElementById('end_date');
            const renewEl = document.getElementById('contract_renewal_month');
            const fileEl = document.getElementById('contractFile');
            const previewEl = document.getElementById('preview');

            // Auto-suggest end date = start date + renewal months
            function suggestEndDate() {
                if (!startEl.value || !renewEl?.value) return;
                const months = parseInt(renewEl.value, 10);
                const d = new Date(startEl.value);
                if (isNaN(d.getTime())) return;

                // add months
                const end = new Date(d);
                end.setMonth(end.getMonth() + months);
                // format yyyy-mm-dd for <input type="date">
                const yyyy = end.getFullYear();
                const mm = String(end.getMonth() + 1).padStart(2, '0');
                const dd = String(end.getDate()).padStart(2, '0');
                endEl.value = `${yyyy}-${mm}-${dd}`;
                endEl.min = startEl.value; // enforce end >= start
            }

            startEl?.addEventListener('change', suggestEndDate);
            renewEl?.addEventListener('change', suggestEndDate);

            // File preview (image thumb or filename for pdf/others)
            fileEl?.addEventListener('change', function() {
                previewEl.innerHTML = '';
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) return;

                const type = file.type.toLowerCase();
                if (type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Contract preview';
                        img.style.maxHeight = '120px';
                        img.className = 'img-thumbnail';
                        previewEl.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    const p = document.createElement('div');
                    p.className = 'small text-muted';
                    p.textContent = `Selected: ${file.name}`;
                    previewEl.appendChild(p);
                }
            });

            // ensure end >= start even if user edits manually
            endEl?.addEventListener('change', function() {
                if (startEl.value && endEl.value && endEl.value < startEl.value) {
                    alert('End Date cannot be before Start Date.');
                    endEl.value = startEl.value;
                }
            });
        });
    </script>

    <script>
        $(function() {
            var $group = $('#myTab');
            if (!$group.length || !window.bootstrap || !bootstrap.Tab) return;

            var key = 'tabs:' + location.pathname + ':' + $group.attr('id');

            // Restore saved tab
            var saved = localStorage.getItem(key);
            if (saved) {
                var $trigger = $group.find('[data-bs-toggle="tab"][href="' + saved +
                    '"], [data-bs-toggle="tab"][data-bs-target="' + saved + '"]');
                if ($trigger.length) {
                    new bootstrap.Tab($trigger[0]).show();
                }
            }

            // Save on change
            $group.find('[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr('data-bs-target') || $(e.target).attr('href');
                if (target && target.charAt(0) === '#') {
                    localStorage.setItem(key, target);
                }
            });
        });
    </script>
@endpush
