@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Profile') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Tenant Profile') }}</li>
    
@endsection
<style>
    .form-control.inline-input{display:none}
</style>
@section('content')

<div class="property-dtl">
  <div class="row g-3">

    <!-- Top Edit Buttons -->
    <div class="col-lg-12 text-end">
      <button id="edit-btn" class="btn btn-secondary text-white">Edit</button>
      <button id="save-btn" class="btn btn-primary d-none">Save</button>
      <button id="cancel-btn" class="btn btn-light d-none">Cancel</button>
    </div>

    <!-- Sidebar Tabs -->
    <div class="col-md-3 d-flex">
        <div class="card w-100">
            <div class="nav flex-column nav-pills" id="v-profile-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-profile-header-tab" data-bs-toggle="pill" data-bs-target="#v-profile-header" type="button" role="tab">
                    <i class="bi bi-person-badge"></i> Profile Header
                </button>
                <button class="nav-link" id="v-profile-contact-tab" data-bs-toggle="pill" data-bs-target="#v-profile-contact" type="button" role="tab">
                    <i class="bi bi-telephone"></i> Contact Info
                </button>
                <button class="nav-link" id="v-profile-emergency-tab" data-bs-toggle="pill" data-bs-target="#v-profile-emergency" type="button" role="tab">
                    <i class="bi bi-exclamation-circle"></i> Emergency Contact
                </button>
                <button class="nav-link" id="v-profile-lease-tab" data-bs-toggle="pill" data-bs-target="#v-profile-lease" type="button" role="tab">
                    <i class="bi bi-calendar"></i> Lease Info
                </button>
                <button class="nav-link" id="v-profile-property-tab" data-bs-toggle="pill" data-bs-target="#v-profile-property" type="button" role="tab">
                    <i class="bi bi-house"></i> Property Info
                </button>
                <button class="nav-link" id="v-profile-docs-tab" data-bs-toggle="pill" data-bs-target="#v-profile-docs" type="button" role="tab">
                    <i class="bi bi-file-text"></i> Documents
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="col-md-9 d-flex">
      <div class="card w-100">
        <div class="card-body">
            <div class="tab-content" id="v-profile-tabContent">
                <!-- Profile Header -->
                <div class="tab-pane fade show active" id="v-profile-header" role="tabpanel">
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <img src="{{ $auth_tenant->user->profile_url }}"  alt="{{ $auth_tenant->user->name }}" class="avatar">
                        </div>
                        <div class="col">
                        <h2 class="h3 mb-1 editable" data-field="name">
                            <span class="inline-text">{{ $auth_tenant->user->name }}</span>
                            <input type="text" class="form-control form-control inline-input" value="Sarah Johnson">
                        </h2>

                        <span class="info-label"><i class="bi bi-person-badge me-2 text-muted"></i> Tenant ID</span>
                        <p class="text-muted mb-2 editable" data-field="tenant-id">
                            <span class="inline-text">{{ $auth_tenant->user_id }}</span>
                            <input type="text" class="form-control form-control inline-input" value="{{ $auth_tenant->user_id }}">
                        </p>

                        <span class="status-badge status-active">{{ $auth_tenant->user->is_active == 1 ? "Active" : "Not Active" }}  Lease</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="tab-pane fade" id="v-profile-contact" role="tabpanel">
                    <div class="mb-4 eme-info">
                        <h3 class="mb-3"><i class="bi bi-telephone"></i> Contact Information</h3>
                        <div class="row g-3">
                        <div class="col-md-6 editable" data-field="phone">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-telephone me-2 text-muted"></i> Phone</span>
                            <span class="inline-text">{{ $auth_tenant->user->phone_number ?? 'N/A' }}</span>
                            <input type="text" class="form-control form-control inline-input" value="{{ $auth_tenant->user->phone_number ?? 'N/A' }}">
                            </div>
                        </div>
                        <div class="col-md-6 editable" data-field="email">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-envelope me-2 text-muted"></i> Email</span>
                            <span class="inline-text">{{ $auth_tenant->user->email ?? 'N/A' }}</span>
                            <input type="email" class="form-control form-control inline-input" value="{{ $auth_tenant->user->email ?? 'N/A' }}">
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="tab-pane fade" id="v-profile-emergency" role="tabpanel">
                    <div class="mb-4 eme-info">
                        <h3 class="mb-3"><i class="bi bi-exclamation-circle"></i> Emergency Contact</h3>
                        <div class="row g-3">
                        <div class="col-md-4 editable" data-field="emergency-name">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-person me-2 text-muted"></i> Name</span>
                            <span class="inline-text">Michael Johnson</span>
                            <input type="text" class="form-control form-control inline-input" value="Michael Johnson">
                            </div>
                        </div>
                        <div class="col-md-4 editable" data-field="emergency-phone">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-telephone me-2 text-muted"></i> Phone</span>
                            <span class="inline-text">{{ $auth_tenant->user->emergency_phone_number ?? 'N/A' }}</span>
                            <input type="text" class="form-control form-control inline-input" value="{{ $auth_tenant->user->emergency_phone_number ?? 'N/A' }}">
                            </div>
                        </div>
                        <div class="col-md-4 editable" data-field="emergency-relationship">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-heart me-2 text-muted"></i> Relationship</span>
                            <span class="inline-text">Spouse</span>
                            <input type="text" class="form-control form-control inline-input" value="Spouse">
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Lease Info -->
                <div class="tab-pane fade" id="v-profile-lease" role="tabpanel">
                    <div class="mb-4 eme-info lease-info">
                        <h3 class="mb-3"><i class="bi bi-calendar"></i> Lease Information</h3>
                        <div class="row g-3">
                        <div class="col-md-6 editable" data-field="lease-start">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-calendar-check me-2 text-muted"></i> Lease Start Date</span>
                            <span class="inline-text">{{ $auth_tenant->lease_start_date ?? 'N/A' }}</span>
                            <input type="text" class="form-control form-control inline-input" value="{{ $auth_tenant->lease_start_date ?? 'N/A' }}">
                            </div>
                        </div>
                        <div class="col-md-6 editable" data-field="lease-end">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-calendar-x me-2 text-muted"></i> Lease End Date</span>
                            <span class="inline-text">{{ $auth_tenant->lease_end_date ?? 'N/A' }}</span>
                            <input type="text" class="form-control form-control inline-input" value="{{ $auth_tenant->lease_end_date ?? 'N/A' }}">
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Property Info -->
                <div class="tab-pane fade" id="v-profile-property" role="tabpanel">
                    <div class="mb-4">
                        <h3 class="mb-3"><i class="bi bi-house"></i> Property Information</h3>
                        <div class="row g-3 eme-info">
                        <div class="col-md-6 editable" data-field="property">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-building me-2 text-muted"></i> Property</span>
                            <span class="inline-text">{{ $auth_tenant->property->name ?? 'N/A' }}</span>
                            <input type="text" class="form-control form-control inline-input" value="{{ $auth_tenant->property->name ?? 'N/A' }}">
                            </div>
                        </div>
                        <div class="col-md-6 editable" data-field="address">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-geo-alt me-2 text-muted"></i> Address</span>
                            <span class="inline-text">
                                {{ $auth_tenant->property
                                    ? $auth_tenant->property->address . ', ' .
                                    optional($auth_tenant->property->city)->name . ', ' .
                                    optional($auth_tenant->property->state)->name . ' ' .
                                    $auth_tenant->property->zip_code . ', ' .
                                    $auth_tenant->property->country
                                    : 'N/A'
                                }}
                            </span>
                            <textarea class="form-control form-control inline-input"></textarea>    
                            </div>
                        </div>
                        <div class="col-md-6 editable" data-field="unit">
                            <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-door-open me-2 text-muted"></i> Unit</span>
                            <span class="inline-text">{{ $auth_tenant->unit->name ?? 'N/A' }}</span>
                            <input type="text" class="form-control form-control inline-input" value="{{ $auth_tenant->unit->name ?? 'N/A' }}">
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="tab-pane fade" id="v-profile-docs" role="tabpanel">
                    <div class="mb-4">
                        <h3 class="mb-3"><i class="bi bi-file-text"></i> Documents</h3>
                        <div class="row g-3 eme-info">
                        <div class="col-sm-6 col-lg-3 editable" data-field="doc1">
                            <div class="document-item">
                            <span class="info-label"><i class="bi bi-file-earmark-text me-2 text-muted"></i> Document 1</span>
                            <span class="inline-text">Lease Agreement</span>
                            <input type="text" class="form-control form-control inline-input" value="Lease Agreement">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3 editable" data-field="doc2">
                            <div class="document-item">
                            <span class="info-label"><i class="bi bi-person-badge me-2 text-muted"></i> Document 2</span>
                            <span class="inline-text">ID Verification</span>
                            <input type="text" class="form-control form-control inline-input" value="ID Verification">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3 editable" data-field="doc3">
                            <div class="document-item">
                            <span class="info-label"><i class="bi bi-cash-stack me-2 text-muted"></i> Document 3</span>
                            <span class="inline-text">Income Statement</span>
                            <input type="text" class="form-control form-control inline-input" value="Income Statement">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3 editable" data-field="doc4">
                            <div class="document-item">
                            <span class="info-label"><i class="bi bi-shield-check me-2 text-muted"></i> Document 4</span>
                            <span class="inline-text">Background Check</span>
                            <input type="text" class="form-control form-control inline-input" value="Background Check">
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

            </div><!-- end tab-content -->
        </div>
      </div>
    </div><!-- end col-md-9 -->

  </div><!-- end row -->
</div><!-- end container -->


{{--
<div class="card border bg-custom w-100">
    <div class="card-body">
        <div class="profile-card">
            <div class="">

                <!-- Profile Header -->
                <div class="row align-items-center mb-4">
                <div class="col-auto">
                    <img src="https://whitesmoke-jackal-127066.hostingersite.com/storage/upload/profile/avatar.png"
                        alt="Sarah Johnson" class="avatar">
                </div>
                <div class="col">
                    <h2 class="h3 mb-1 editable" data-field="name">
                    <span class="inline-text">Sarah Johnson</span>
                    <input type="text" class="form-control form-control inline-input" value="Sarah Johnson">
                    </h2>

                    <span class="info-label"><i class="bi bi-person-badge me-2 text-muted"></i> Tenant ID</span>
                    <p class="text-muted mb-2 editable" data-field="tenant-id">
                    <span class="inline-text">TNT-2024-001</span>
                    <input type="text" class="form-control form-control inline-input" value="TNT-2024-001">
                    </p>

                    <span class="status-badge status-active">Active Lease</span>
                </div>
                <div class="col-auto">
                    <button id="edit-btn" class="btn btn-secondary text-white">Edit</button>
                    <button id="save-btn" class="btn btn-primary d-none">Save</button>
                    <button id="cancel-btn" class="btn btn-light d-none">Cancel</button>
                </div>
                </div>

                <!-- Contact Info -->
                <div class="mb-4 eme-info">
                    <h3><i class="bi bi-telephone"></i> Contact Information</h3>
                    <div class="row g-3">
                        <div class="col-md-6 editable" data-field="phone">
                        
                        <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-telephone me-2 text-muted"></i> Phone</span>
                            <span class="inline-text">+1 (555) 123-4567</span>
                            <input type="text" class="form-control form-control inline-input" value="+1 (555) 123-4567">
                        </div>
                        </div>
                        <div class="col-md-6 editable" data-field="email">
                        <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-envelope me-2 text-muted"></i> Email</span>
                            <span class="inline-text">sarah.johnson@email.com</span>
                            <input type="email" class="form-control form-control inline-input" value="sarah.johnson@email.com">
                        </div>
                        </div>
                    </div>
                </div>

                <hr class="divider">

                <!-- Emergency Contact -->
                <div class="mb-4 eme-info">
                <h3><i class="bi bi-exclamation-circle"></i> Emergency Contact</h3>
                <div class="row g-3">
                    <div class="col-md-4 editable" data-field="emergency-name">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-person me-2 text-muted"></i> Name</span>
                        <span class="inline-text">Michael Johnson</span>
                        <input type="text" class="form-control form-control inline-input" value="Michael Johnson">
                    </div>
                    </div>
                    <div class="col-md-4 editable" data-field="emergency-phone">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-telephone me-2 text-muted"></i> Phone</span>
                        <span class="inline-text">+1 (555) 987-6543</span>
                        <input type="text" class="form-control form-control inline-input" value="+1 (555) 987-6543">
                    </div>
                    </div>
                    <div class="col-md-4 editable" data-field="emergency-relationship">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-heart me-2 text-muted"></i> Relationship</span>
                        <span class="inline-text">Spouse</span>
                        <input type="text" class="form-control form-control inline-input" value="Spouse">
                    </div>
                    </div>
                </div>
                </div>

                <hr class="divider">

                <!-- Lease Information -->
                <div class="mb-4 eme-info lease-info">
                <h3><i class="bi bi-calendar"></i> Lease Information</h3>
                <div class="row g-3">
                    <div class="col-md-6 editable" data-field="lease-start">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-calendar-check me-2 text-muted"></i> Lease Start Date</span>
                        <span class="inline-text">January 15, 2024</span>
                        <input type="text" class="form-control form-control inline-input" value="January 15, 2024">
                    </div>
                    </div>
                    <div class="col-md-6 editable" data-field="lease-end">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-calendar-x me-2 text-muted"></i> Lease End Date</span>
                        <span class="inline-text">January 14, 2025</span>
                        <input type="text" class="form-control form-control inline-input" value="January 14, 2025">
                    </div>
                    </div>
                </div>
                </div>

                <hr class="divider">

                <!-- Property Information -->
                <div class="mb-4">
                <h3><i class="bi bi-house"></i> Property Information</h3>
                <div class="row g-3 eme-info">
                    <div class="col-md-6 editable" data-field="property">
                        <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-building me-2 text-muted"></i> Property</span>
                            <span class="inline-text">Sunset Gardens Apartments</span>
                            <input type="text" class="form-control form-control inline-input" value="Sunset Gardens Apartments">
                        </div>
                    </div>
                    <div class="col-md-6 editable" data-field="address">
                        <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-geo-alt me-2 text-muted"></i> Address</span>
                            <span class="inline-text">123 Oak Street, Manhattan, NY 10001, United States</span>
                            <textarea class="form-control form-control inline-input">123 Oak Street, Manhattan, NY 10001, United States</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 editable" data-field="unit">
                        <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-door-open me-2 text-muted"></i> Unit</span>
                            <span class="inline-text">Unit 24B</span>
                            <input type="text" class="form-control form-control inline-input" value="Unit 24B">
                        </div>
                    </div>
                </div>
                </div>

                <hr class="divider">

                <!-- Documents -->
                <div class="mb-4">
                <h3><i class="bi bi-file-text"></i> Documents</h3>
                <div class="row g-3 eme-info">
                    <div class="col-sm-6 col-lg-3 editable" data-field="doc1">
                    <div class="document-item">
                        <span class="info-label"><i class="bi bi-file-earmark-text me-2 text-muted"></i> Document 1</span>
                        <span class="inline-text">Lease Agreement</span>
                        <input type="text" class="form-control form-control inline-input" value="Lease Agreement">
                    </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 editable" data-field="doc2">
                    <div class="document-item">
                        <span class="info-label"><i class="bi bi-person-badge me-2 text-muted"></i> Document 2</span>
                        <span class="inline-text">ID Verification</span>
                        <input type="text" class="form-control form-control inline-input" value="ID Verification">
                    </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 editable" data-field="doc3">
                    <div class="document-item">
                        <span class="info-label"><i class="bi bi-cash-stack me-2 text-muted"></i> Document 3</span>
                        <span class="inline-text">Income Statement</span>
                        <input type="text" class="form-control form-control inline-input" value="Income Statement">
                    </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 editable" data-field="doc4">
                    <div class="document-item">
                        <span class="info-label"><i class="bi bi-shield-check me-2 text-muted"></i> Document 4</span>
                        <span class="inline-text">Background Check</span>
                        <input type="text" class="form-control form-control inline-input" value="Background Check">
                    </div>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>
</div>--}}

<script>
  const editBtn = document.getElementById("edit-btn");
  const saveBtn = document.getElementById("save-btn");
  const cancelBtn = document.getElementById("cancel-btn");

  editBtn.addEventListener("click", () => {
    document.querySelectorAll(".editable").forEach(el => {
      el.querySelector(".inline-text").style.display = "none";
      el.querySelector(".inline-input").style.display = "block";
    });
    editBtn.classList.add("d-none");
    saveBtn.classList.remove("d-none");
    cancelBtn.classList.remove("d-none");
  });

  cancelBtn.addEventListener("click", () => {
    document.querySelectorAll(".editable").forEach(el => {
      const input = el.querySelector(".inline-input");
      const span = el.querySelector(".inline-text");
      input.value = span.textContent;
      input.style.display = "none";
      span.style.display = "inline";
    });
    saveBtn.classList.add("d-none");
    cancelBtn.classList.add("d-none");
    editBtn.classList.remove("d-none");
  });

  saveBtn.addEventListener("click", () => {
    document.querySelectorAll(".editable").forEach(el => {
      const input = el.querySelector(".inline-input");
      const span = el.querySelector(".inline-text");
      span.textContent = input.value;
      input.style.display = "none";
      span.style.display = "inline";
    });
    saveBtn.classList.add("d-none");
    cancelBtn.classList.add("d-none");
    editBtn.classList.remove("d-none");
  });
</script>


@endsection
