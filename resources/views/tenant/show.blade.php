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
$leaseEnd = formatTenantDate($tenant->lease_end_date); 

// Country (via state -> country)
$country = $tenant->state?->country?->name ?? 'Usa';
@endphp


<style>
    p.doc-name a {
        color: blue !important;
    }
</style>
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
                    <ul class="nav flex-column nav-tabs account-tabs box-card custom-theme" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile_content"
                                role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        @if(empty($tenant->user) && !empty($tenant->user->profile) &&
                                        Storage::exists('upload/profile/' . $tenant->user->profile))
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
                            <a class="nav-link" id="invoice-tab" data-bs-toggle="tab" href="#invoice_content" role="tab"
                                aria-selected="false">
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
                            <a class="nav-link" id="notice-tab" data-bs-toggle="tab" href="#notice_content" role="tab"
                                aria-selected="false">
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
                        <!-- <li class="nav-item" role="presentation">
                            <a class="nav-link" id="report-tab" data-bs-toggle="tab" href="#report_content" role="tab"
                                aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-report me-2 f-20"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h5 class="mb-0">Report</h5>
                                         <small class="text-muted">Report</small> 
                                    </div>
                                </div>
                            </a>
                        </li> -->

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
                            <a class="nav-link" id="tenant-tab" data-bs-toggle="tab" href="#tenant_content" role="tab"
                                aria-selected="false">
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
                                            <td>{{ $statesdata?->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><b class="text-header">City</b></td>
                                            <td>:</td>
                                            <td>{{ $citiesdata?->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><b class="text-header">Zip Code</b></td>
                                            <td>:</td>
                                            <td>{{ $tenant->zip_code ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><b class="text-header">Property</b></td>
                                            <td>:</td>
                                            <td>{{ $propertyname?->name ?? '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b class="text-header">Unit</b></td>
                                            <td>:</td>
                                            <td>{{ $propertyunit?->name ?? '-' }}
                                            </td>
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
                                                @if ($tenant->user->personal_document)
                                                @php $hasDocs = true; @endphp
                                                <div><a href="{{ asset('storage/upload/tenantdocument/' . $tenant->user->personal_document) }}"
                                                        target="_blank">Application Document</a></div>
                                                @endif
                                                @if ($tenant->user->ic_document)
                                                @php $hasDocs = true; @endphp
                                                <div><a href="{{ asset('storage/upload/tenantdocument/' . $tenant->user->ic_document) }}"
                                                        target="_blank">Driving Licence</a></div>
                                                @endif
                                                @if ($tenant->user->miscellaneous)
                                                @php $hasDocs = true; @endphp
                                                <div><a href="{{ asset('storage/upload/tenantdocument/' . $tenant->user->miscellaneous) }}"
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
                                $renewMon = old('contract_renewal_month', $tenantcontracts->contract_renewal_month ??
                                '12');
                                $renewAmt = old('contract_renewal_amount', $tenantcontracts->contract_renewal_amount ??
                                '');
                                $tenantId = $tenant->id;
                                $propertyId = old('property_id', $tenantcontracts->property_id ?? $tenant->property_id);
                                $ownerId = old('owner_id', $tenantcontracts->owner_id ?? ($tenant->owner_id ??
                                (auth()->user()->id ?? '')));
                                @endphp

                                <form id="setupContractForm" method="POST" action="{{ $action }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    {{-- Hidden inputs --}}
                                    <input type="hidden" name="tenant_id" value="{{ $tenantId }}">
                                    <input type="hidden" name="property_id"
                                        value="{{ $tenantcontracts->property_id ?? $tenant->property_id ?? '' }}">

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
                                            @php
                                                $sd = old('start_date');
                                                $ed = old('end_date');

                                                // if not old() then take model value and format to Y-m-d safely
                                                if (empty($sd) && !empty($tenantcontracts->start_date)) {
                                                    try { $sd = \Carbon\Carbon::parse($tenantcontracts->start_date)->format('Y-m-d'); } catch (\Exception $e) { $sd = ''; }
                                                }
                                                if (empty($ed) && !empty($tenantcontracts->end_date)) {
                                                    try { $ed = \Carbon\Carbon::parse($tenantcontracts->end_date)->format('Y-m-d'); } catch (\Exception $e) { $ed = ''; }
                                                }
                                            @endphp
                                            <!-- <input type="date" id="start_date" name="start_date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                value="{{ old('start_date', $tenantcontracts->start_date ?? '') }}"
                                                {{ !empty($tenantcontracts->start_date) ? 'readonly' : '' }}> -->
                                                 <input type="date" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                                    value="{{ $sd }}" {{ !empty($sd) ? 'readonly' : '' }}>
                                            @error('start_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">End Date</label>
                                            <!-- <input type="date" id="end_date" name="end_date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                value="{{ old('end_date', $tenantcontracts->end_date ?? '') }}"
                                                {{ !empty($tenantcontracts->end_date) ? 'readonly' : '' }}> -->
                                            <input type="date" id="end_date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                                value="{{ $ed }}" {{ !empty($ed) ? 'readonly' : '' }}>
                                            @error('end_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Lease Term (Months)</label>
                                            <input type="number" id="lease_term" name="lease_term" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                    {{-- Flatpickr CSS/JS --}}
                                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                                        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

                                        <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const startEl = document.getElementById('start_date');
                                            const endEl = document.getElementById('end_date');
                                            const leaseTermEl = document.getElementById('lease_term');
                                            const renewEl = document.getElementById('contract_renewal_month');

                                            // --- helper: robust parse from common input formats into JS Date or null
                                            function parseToDate(val) {
                                                if (!val) return null;
                                                val = String(val).trim();

                                                // YYYY-MM-DD
                                                if (/^\d{4}-\d{2}-\d{2}$/.test(val)) {
                                                    const d = new Date(val + 'T00:00:00');
                                                    return isNaN(d) ? null : d;
                                                }

                                                // DD-MM-YYYY or DD/MM/YYYY
                                                if (/^\d{2}[-\/]\d{2}[-\/]\d{4}$/.test(val)) {
                                                    let parts = val.split(/[-\/]/);
                                                    // parts: [DD,MM,YYYY]
                                                    const iso = `${parts[2]}-${parts[1]}-${parts[0]}T00:00:00`;
                                                    const d = new Date(iso);
                                                    return isNaN(d) ? null : d;
                                                }

                                                // MM-DD-YYYY or MM/DD/YYYY
                                                if (/^\d{2}[-\/]\d{2}[-\/]\d{4}$/.test(val)) {
                                                    let parts = val.split(/[-\/]/);
                                                    // could be ambiguous; try MM-DD-YYYY -> ISO
                                                    const iso = `${parts[2]}-${parts[0]}-${parts[1]}T00:00:00`;
                                                    const d = new Date(iso);
                                                    return isNaN(d) ? null : d;
                                                }

                                                // fallback: Date parse
                                                const d = new Date(val);
                                                return isNaN(d) ? null : d;
                                            }

                                            // month difference helper (end >= start)
                                            function monthDiff(start, end) {
                                                if (!start || !end) return 0;
                                                let months = (end.getFullYear() - start.getFullYear()) * 12 + (end.getMonth() - start.getMonth());
                                                if (end.getDate() < start.getDate()) months--;
                                                return months >= 0 ? months : 0;
                                            }

                                            // get canonical JS Date for start (from picker if available, else from input value)
                                            function getStartDate(startPicker) {
                                                if (startPicker && startPicker.selectedDates && startPicker.selectedDates.length) {
                                                    return startPicker.selectedDates[0];
                                                }
                                                return parseToDate(startEl.value);
                                            }

                                            // get canonical JS Date for end
                                            function getEndDate(endPicker) {
                                                if (endPicker && endPicker.selectedDates && endPicker.selectedDates.length) {
                                                    return endPicker.selectedDates[0];
                                                }
                                                return parseToDate(endEl.value);
                                            }

                                            // Initialize startPicker but don't let it overwrite input when readonly.
                                            // We still initialize so the altInput (visible) is rendered, but we disable onChange if readonly.
                                            const startIsReadonly = startEl.hasAttribute('readonly') || startEl.disabled;

                                            const startPicker = flatpickr(startEl, {
                                                dateFormat: "Y-m-d",
                                                altInput: true,
                                                altFormat: "d-m-Y",
                                                defaultDate: parseToDate(startEl.value) || null,
                                                allowInput: false, // avoid manual edits causing weird parsing
                                                onChange: function(selectedDates) {
                                                    if (startIsReadonly) {
                                                        // if readonly, do NOT change underlying input value — restore canonical Y-m-d
                                                        if (selectedDates.length) {
                                                            const d = selectedDates[0];
                                                            // ensure the input value is in Y-m-d (canonical)
                                                            const yyyy = d.getFullYear().toString().padStart(4, '0');
                                                            const mm = (d.getMonth() + 1).toString().padStart(2, '0');
                                                            const dd = d.getDate().toString().padStart(2, '0');
                                                            startEl.value = `${yyyy}-${mm}-${dd}`;
                                                        }
                                                        return;
                                                    }
                                                    // When editable start changes, adjust end if renewal present
                                                    if (!selectedDates.length) return;
                                                    const months = parseInt(renewEl?.value || 12);
                                                    const newEnd = new Date(selectedDates[0]);
                                                    newEnd.setMonth(newEnd.getMonth() + months);
                                                    endPicker.setDate(newEnd, true, "Y-m-d");
                                                    updateLeaseTerm();
                                                }
                                            });

                                            // Initialize endPicker (always editable; user can change)
                                            const endPicker = flatpickr(endEl, {
                                                dateFormat: "Y-m-d",
                                                altInput: true,
                                                altFormat: "d-m-Y",
                                                defaultDate: parseToDate(endEl.value) || null,
                                                allowInput: false,
                                                onChange: function() {
                                                    // only recalc lease term — never touch start date
                                                    updateLeaseTerm();
                                                }
                                            });

                                            // update lease term display
                                            function updateLeaseTerm() {
                                                const s = getStartDate(startPicker);
                                                const e = getEndDate(endPicker);
                                                if (!s || !e || e < s) {
                                                    leaseTermEl.value = '';
                                                    return;
                                                }
                                                leaseTermEl.value = monthDiff(s, e);
                                            }

                                            // renewal change handler (uses start date as source)
                                            renewEl?.addEventListener('change', function() {
                                                const s = getStartDate(startPicker);
                                                if (!s) return;
                                                const months = parseInt(this.value || 12);
                                                const newEnd = new Date(s);
                                                newEnd.setMonth(newEnd.getMonth() + months);
                                                endPicker.setDate(newEnd, true, "Y-m-d");
                                                updateLeaseTerm();
                                            });

                                            // ensure the underlying input values are canonical Y-m-d before form submit
                                            // (this prevents altInput mismatch causing wrong payload)
                                            const form = startEl.closest('form');
                                            if (form) {
                                                form.addEventListener('submit', function() {
                                                    // ensure start input is canonical
                                                    const s = getStartDate(startPicker) || parseToDate(startEl.value);
                                                    if (s) {
                                                        const yyyy = s.getFullYear().toString().padStart(4, '0');
                                                        const mm = (s.getMonth() + 1).toString().padStart(2, '0');
                                                        const dd = s.getDate().toString().padStart(2, '0');
                                                        startEl.value = `${yyyy}-${mm}-${dd}`;
                                                    }

                                                    // ensure end input is canonical
                                                    const e = getEndDate(endPicker) || parseToDate(endEl.value);
                                                    if (e) {
                                                        const yyyy = e.getFullYear().toString().padStart(4, '0');
                                                        const mm = (e.getMonth() + 1).toString().padStart(2, '0');
                                                        const dd = e.getDate().toString().padStart(2, '0');
                                                        endEl.value = `${yyyy}-${mm}-${dd}`;
                                                    }
                                                });
                                            }

                                            // initial lease term calc
                                            setTimeout(updateLeaseTerm, 200);
                                        });
                                        </script>


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
                                                <option value="1" {{ $notice == '1' ? 'selected' : '' }}>1 month
                                                </option>
                                                <option value="2" {{ $notice == '2' ? 'selected' : '' }}>2 months
                                                </option>
                                                <option value="3" {{ $notice == '3' ? 'selected' : '' }}>3 months
                                                </option>
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
                                                    class="form-control @error('contract_doc') is-invalid @enderror"
                                                    accept=".pdf,image/*">
                                                <button type="button" id="previewBtn" class="btn btn-outline-primary"
                                                    style="display:none;" target="_blank">
                                                    👁
                                                </button>
                                            </div>
                                            @error('contract_doc')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror

                                            @if (!empty($tenantcontracts->contract_doc))
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/upload/contracts/' . $tenantcontracts->contract_doc) }}"
                                                    target="_blank" class="btn btn-outline-primary btn-sm">
                                                    👁 Preview Contract
                                                </a>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Invoice Due Date</label>
                                            <select name="invoice_due_date" class="form-control form-select @error('invoice_due_date') is-invalid @enderror">
                                                <option value="">Select Day</option>
                                                @for ($i = 1; $i <= 31; $i++)
                                                    <option value="{{ $i }}" {{ old('invoice_due_date', $tenantcontracts->invoice_due_date ?? '') == $i ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('invoice_due_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
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
                                                    <label class="form-label">Contract Renewal Amount Increase
                                                        (USD)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" placeholder="e.g. 100"
                                                            class="form-control" name="contract_renewal_amount[]">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Start Months</label>
                                                    <input type="text" class="form-control" name="start_months[]"
                                                        placeholder="e.g. January">
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">End Months</label>
                                                    <input type="text" class="form-control" name="end_months[]"
                                                        placeholder="e.g. June">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" id="add-more" class="btn btn-sm btn-primary">+ Add
                                                More</button>
                                        </div>




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
                                                        <input type="text" class="form-control" name="grace_days[]"
                                                            placeholder="e.g. 5">
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
                                                                <input type="number" placeholder="e.g. 1200"
                                                                    class="form-control" name="amount[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end mt-3">
                                                <button type="button" id="late-add-more"
                                                    class="btn btn-sm btn-primary">+ Add More</button>
                                            </div>
                                        </div>



                                        {{-- Submit Button --}}
                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                {{ $isEdit ? 'Update Contract' : 'Save Contract' }}
                                            </button>
                                        </div>
                                    </div>
                                </form>

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
                                        newRow.querySelectorAll('input, select').forEach(el => el
                                            .value = '');

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
                                                    @if(($month['type'] ?? '') === 'base' && $loop->first && $security >
                                                    0)
                                                    ${{ number_format($security, 2) }}
                                                    @endif
                                                </td>

                                                {{-- Amenities --}}
                                                <td>${{ number_format($propertyAmenitiesTotal ?? 0, 2) }}</td>

                                                <td></td>
                                                <td></td>

                                                {{-- Status --}}
                                                @php
                                                $isPending = (optional($tenantcontracts)->status ?? 'pending') ===
                                                'pending';
                                                @endphp
                                                <td>
													<select class="form-select form-select-sm status-select" data-ym="{{ $ym }}">
														<option value="pending" {{ $month['status'] === 'pending' ? 'selected' : '' }}>Pending</option>
														<option value="paid" {{ $month['status'] === 'paid' ? 'selected' : '' }}>Paid</option>
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
                                                <th>${{ number_format($tenantcontracts->security_deposit ?? 0, 2) }}
                                                </th>
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
                                    </thead <tbody>
                                    @php
                                    $tenantall =
                                    DB::table('utility_invoices')->where('tenant_id',$u->id)->where('property_id',$tenant->property_id)->get();
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
                                            <a target="_blank"
                                                href="{{ route('utility-invoices.show', $invoice->id) }}">
                                                <i class="ti ti-eye mx-1" data-bs-toggle="tooltip"
                                                    title="View Invoice"></i>
                                            </a>
                                            <a target="_blank" href="{{ route('utility.invoices.pdf', $invoice->id) }}">
                                                <i class="ti ti-download mx-1" data-bs-toggle="tooltip"
                                                    title="Download Invoice"></i>
                                            </a>
                                            <i class="ti ti-refresh mx-1 resend-invoice" data-id="{{ $invoice->id }}"
                                                data-bs-toggle="tooltip" title="Resend Invoice"></i>
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
                                    $tenantotherinvoicesall =
                                    DB::table('other_invoices')->where('tenant_id',$u->id)->where('property_id',$tenant->property_id)->get();
                                    @endphp
                                    <tbody class="text-center">
                                        @forelse($tenantotherinvoicesall as $i)
                                        <tr>
                                            <td>{{ $i->invoice_no }}</td>
                                            <td>${{ $i->amount }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $i->status }}</span>
                                                <!-- <span class="badge bg-warning">Pending</span> -->
                                            </td>
                                            <td>
                                                {{--<a target="_blank" href=""><i
                                                        class="ti ti-eye mx-1" data-bs-toggle="tooltip"
                                                        data-bs-title="View"></i></a>
                                                <a target="_blank" href="{{ route('otherInvoice.download', $i->id) }}"><i
                                                        class="ti ti-download mx-1" data-bs-toggle="tooltip"
                                                        data-bs-title="Download"></i></a>--}}
                                                <a target="_blank" href="{{ route('other_invoices.email_preview', $i->id) }}"><i 
                                                        class="ti ti-refresh mx-1" data-bs-toggle="tooltip"
                                                        data-bs-title="Resend Invoice"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                           <tr>
                                                <td colspan="6" class="text-center">No invoices found.</td>
                                            </tr>
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
        @php
            $noticesall = DB::table('managen-notice')->where('created_by',\Auth::user()->id)->where('status','1')->get();
            $templates = DB::table('manage-template')->where('created_by', \Auth::user()->id)->orderBy('created_at', 'desc')->get();
        @endphp
        <div class="card-body allwhite">
            <div class=" ">
                <div class="">
                <div class="row g-3">
                  @if(!empty($noticesall))
                    @foreach($noticesall as $index => $notice)
                       <div class="col-md-4">
                            <a href="{{url('owner-generate-notice/'.$notice->id.'/'.$tenant->id)}}" class="btn btn-secondary w-100" data-size="lg" data-url="{{url('owner-generate-notice/'.$notice->id.'/'.$tenant->id)}}" data-title="Generate notice">{{ $notice->name }}</a>
                        </div>
                    @endforeach
                  @else
                    <div class="col-md-12 mt-3">
                        <div class="text-center text-muted">No notices found.</div>
                    </div>
                  @endif
                    </div>
                    {{--<!-- <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>#</th>
                                <th>Notice Name</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Template</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($noticesall as $index => $notice)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $notice->name }}</td>
                                <td>{{ ucfirst($notice->status ?? 'N/A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($notice->created_at)->format('Y-m-d') }}</td>
                                <td>
                                    <select class="form-select templateSelect" data-notice="{{ $notice->id }}">
                                        <option value="">-- Select Template --</option>
                                        @foreach($templates as $template)
                                            <option value="{{ $template->id }}"
                                                data-subject="{{ $template->subject }}"
                                                data-body="{{ $template->body }}">
                                                {{ $template->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn btn-primary btn-sm previewBtn"
                                        data-notice="{{ $notice->id }}"
                                        style="display:none;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#previewModal">
                                        Preview
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No notices found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table> -->--}}
                </div>
            </div>

        </div>

        {{-- Preview Modal --}}
        <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h6 id="previewSubject" class="fw-bold"></h6>
                        <hr>
                        <div id="previewBody" style="white-space: pre-wrap;"></div>
                    </div>
                    <div class="modal-footer">
                        <form id="sendMailForm" method="POST" action="">
                            @csrf
                            <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                            <input type="hidden" name="template_id" id="template_id">
                            <input type="hidden" name="notice_id" id="notice_id">
                            <button type="submit" class="btn btn-success">Send Mail</button>
                        </form>
                    </div>
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
                                    @foreach($senddocdata as $senddocval)

                                      @php
                                        $usersdoc = DB::table('users')->where('id',$senddocval->user_id)->first();
                                      @endphp
                                            <tr data-id="{{ $senddocval->id }}"
                                            data-docname2="@if($senddocval->document == '1') Application Document
                                                            @elseif($senddocval->document == '2') Driving Licence
                                                            @else Bank Statement @endif"
                                            data-link="@if($senddocval->document == '1') {{ asset('storage/upload/tenantdocument/' . $usersdoc->personal_document) }}
                                                       @elseif($senddocval->document == '2') {{ asset('storage/upload/tenantdocument/' . $usersdoc->ic_document) }}
                                                       @else {{ asset('storage/upload/tenantdocument/' . $usersdoc->miscellaneous) }} @endif"
                                            data-subject2="{{ $senddocval->subject ?? 'N/A' }}"
                                            data-comment2="{{ $senddocval->description ?? 'No comment' }}">
                                            @if($senddocval->document == '1')
                                                <td>Application Document</td>
                                            @elseif($senddocval->document == '2')
                                                <td>Driving Licence</td>
                                            @else
                                                <td>Bank Statement</td>
                                            @endif
                                                <td><span class="badge bg-success">Delivered</span></td>
                                                <td>
                                                    <a href="#" class="view-btn show-doc"><i class="ti ti-eye mx-1"></i></a>
                                                    <a href="#" class=""><i class="ti ti-send mx-1"></i></a>
                                                </td>
                                            </tr>
                                    @endforeach
                                            <!-- <tr>
                                                <td>Document 2</td>
                                                <td><span class="badge bg-warning text-dark">Pending</span>
                                                </td>
                                                <td>
                                                    <a href="#" class="view-btn"><i class="ti ti-eye mx-1"></i></a>
                                                </td>
                                            </tr> -->
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
                                <form action="{{url('owner-send-doc/'.$tenant->user->id.'/'.$tenant->id)}}" method="post" id="sendDocForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="toEmail" class="form-label">Select Document</label>
                                        <select name="document" id="" required class="form-control">
                                            <option value="">-- Select --</option>
                                            <option value="1">Application Document</option>
                                            <option value="2">Driving Licence</option>
                                            <option value="3">Bank Statement</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required 
                                            placeholder="Document subject..." required="">
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Comment</label>
                                        <textarea class="form-control" id="description" name="description" required rows="4"
                                            placeholder="Enter details..."></textarea>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-secondary">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- View Document -->
                    <div class="card box-card w-100 view-document view-document2  document-card d-none">
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
                                            <p class="doc-name">Document 1</p>
                                        </div>

                                        <!-- Subject -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Subject</label>
                                            <p class="doc-subject">Sample Subject for Document
                                            </p>
                                        </div>

                                        <!-- Comment -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Comment</label>
                                            <p class="doc-comment">
                                                This is the comment text entered by the user.
                                                It shows the details about the document.
                                            </p>
                                        </div>

                                        <!-- <div class="text-end">
                                            <a href="#" class="btn btn-secondary back-btn">Back</a>
                                        </div> -->

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <!-- <div class="tab-pane fade" id="report_content" role="tabpanel" aria-labelledby="report-tab">
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

                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject"
                                            placeholder="Document subject..." required="">
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Comment</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            placeholder="Enter details..."></textarea>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-secondary">Send</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div> -->

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
                                            <td>{{ $statesdata?->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><b class="text-header">City</b></td>
                                            <td>:</td>
                                            <td>{{ $citiesdata?->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><b class="text-header">Zip Code</b></td>
                                            <td>:</td>
                                            <td>{{ $tenant->zip_code ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><b class="text-header">Property</b></td>
                                            <td>:</td>
                                            <td>{{ $propertyname?->name ?? '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b class="text-header">Unit</b></td>
                                            <td>:</td>
                                            <td>{{ $propertyunit?->name ?? '-' }}</td>
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
                                                @if ($tenant->user->personal_document)
                                                @php $hasDocs = true; @endphp
                                                <div><a href="{{ asset('storage/upload/tenantdocument/' . $tenant->user->personal_document) }}"
                                                        target="_blank">Application Document</a></div>
                                                @endif
                                                @if ($tenant->user->ic_document)
                                                @php $hasDocs = true; @endphp
                                                <div><a href="{{ asset('storage/upload/tenantdocument/' . $tenant->user->ic_document) }}"
                                                        target="_blank">Driving Licence</a></div>
                                                @endif
                                                @if ($tenant->user->miscellaneous)
                                                @php $hasDocs = true; @endphp
                                                <div><a href="{{ asset('storage/upload/tenantdocument/' . $tenant->user->miscellaneous) }}"
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.templateSelect');
    const previewBtns = document.querySelectorAll('.previewBtn');

    selects.forEach(select => {
        select.addEventListener('change', function() {
            const noticeId = this.dataset.notice;
            const btn = document.querySelector(`.previewBtn[data-notice="${noticeId}"]`);
            if (this.value) {
                btn.style.display = 'inline-block';
                btn.dataset.subject = this.selectedOptions[0].dataset.subject;
                btn.dataset.body = this.selectedOptions[0].dataset.body;
                btn.dataset.template = this.value;
            } else {
                btn.style.display = 'none';
            }
        });
    });

    previewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('previewSubject').innerText = this.dataset.subject;
            document.getElementById('previewBody').innerText = this.dataset.body;
            document.getElementById('template_id').value = this.dataset.template;
            document.getElementById('notice_id').value = this.dataset.notice;
        });
    });
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Send New button click
    $('.add-new-btn').on('click', function() {
        $('.list-document').addClass('d-none'); // hide list
        $('.send-document').removeClass('d-none'); // show form
    });

    // Back button click inside Send Document
    $('.send-document .back-btn').on('click', function() {
        $('.send-document').addClass('d-none'); // hide form
        $('.list-document').removeClass('d-none'); // show list
    });

    // Back button inside View Document
    $('.view-document .back-btn').on('click', function() {
        $('.view-document').addClass('d-none'); // hide view
        $('.list-document').removeClass('d-none'); // show list
    });

    // Optional: View button click to show View Document
    $('.view-btn').on('click', function() {
        var row = $(this).closest('tr');
        var docName = row.find('td:first').text();
        var status = row.find('td:nth-child(2)').text();

        // Fill details in view-document
        $('.view-document p.form-control-plaintext').eq(0).text(docName); // Selected Document
        $('.view-document p.form-control-plaintext').eq(1).text('Subject for ' + docName); // Subject
        $('.view-document p.form-control-plaintext').eq(2).text('Comment for ' + docName); // Comment

        $('.list-document').addClass('d-none'); // hide list
        $('.view-document').removeClass('d-none'); // show view
    });
});
</script>


<script>
$(document).ready(function() {

    // View button click
    $('.show-doc').on('click', function() {
        var row = $(this).closest('tr');

        var docName = row.data('docname2');   // Text only
        var fileUrl = row.data('link');       // Actual file link
        var subject = row.data('subject2');
        var comment = row.data('comment2');

        // Populate view-document div
        $('.view-document2 .doc-name')
            .html('<a href="'+fileUrl+'" target="_blank">'+docName+'</a>'); // clickable text
        $('.view-document2 .doc-subject').text(subject);
        $('.view-document2 .doc-comment').text(comment);

        $('.list-document').addClass('d-none');
        $('.view-document2').removeClass('d-none');
    });

    // Back button click
    $('.view-document2 .back-btn').on('click', function() {
        $('.view-document2').addClass('d-none');
        $('.list-document').removeClass('d-none');
    });

});
</script>
<script>
$(document).on('change', '.status-select', function() {
    var status = $(this).val();
    var ym = $(this).data('ym');
    var tenantId = "{{ $tenant->id }}";
    var propertyId = "{{ $tenantcontracts->property_id ?? 0 }}";

    $.ajax({
        url: "{{ route('tenant.payment.update') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            tenant_id: tenantId,
            property_id: propertyId,
            month: ym,
            status: status
        },
        success: function(response) {
            if (response.success) {
                toastrs('success', 'Status updated successfully!', 'success');
            } else {
                toastrs('error', 'Error updating status.', 'error');
            }
        },
        error: function() {
            toastrs('error', 'Something went wrong.', 'error');
        }
    });
});
</script>
@endpush
