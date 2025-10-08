@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Profile') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Tenant Profile') }}</li>
    
@endsection

@section('content')
<style>
    .navtabsulli ul li{width: 100%;border-top: 1px solid #ede5e5;}
.navtabsulli ul li button{width:100%}
.navtabsulli ul li button.active,
.navtabsulli ul li button:hover {
 background: linear-gradient(to bottom, #000, #1a1a47, #0f172a) !important;
  color: #fff !important;
}

        .property-dtl .property-header {
            background: rgb(30, 187, 88);
            position: relative;
            overflow: hidden;
        }

        .property-dtl .property-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

       .property-dtl .document-link:hover {
            text-decoration: underline;
        }

       .property-dtl .icon-wrapper {
            width: 50px;
            height: 50px;
            background: rgba(34, 197, 94, 0.2);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;display: none;
        }

        .property-dtl .icon-wrapper i {
            color: #22c55e;
            font-size: 1.5rem;
        }
.document-item.fw-100 {
  background: #f9f9f9;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #eef2f6;
}
    .info-card.p-3{padding: 10px 10px !important;}
    .form-control.form-control.inline-input{display:none}
</style>
<div class="property-dtl ">
    <div class="row g-3">
    <!-- Sidebar Tabs -->
    <div class="col-md-3 d-flex">
        <div class="fw-100 bg-white navtabsulli">
            <ul class="nav nav-tabs flex-column mb-4" id="propertyTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-selected="true">
                        <i class="bi bi-person"></i> Personal Info
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-telephone"></i> Contact Information
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-file-earmark-text"></i> Documents Information
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-activity"></i> Emergency Contact
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-credit-card"></i> Payment Methods
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="col-md-9 d-flex flex-column tenant-profile-sec position-relative">
        <div class="tab-content fw-100 bg-white p-4 h-100" id="propertyTabsContent">
             <div class="col-auto custom-edit-button text-end mb-3">
                        <button id="edit-btn" class="btn btn-secondary text-white">Edit</button>
                        <button id="save-btn" class="btn btn-primary d-none" data-base-route="{{ route('tenant-profile.update', $auth_tenant->id) }}" data-tenant-id="{{ $auth_tenant->user_id }}">Save</button>
                        <button id="cancel-btn" class="btn btn-light d-none">Cancel</button>
                    </div>
            <!-- Personal Info -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                 <div class="row align-items-center mb-4">
                    <div class="col-2 ">
                        <img src="{{ $auth_tenant->user->profile_url }}" alt="{{ $auth_tenant->user->name }}" class="avatar img-fluid">

                        <input type="file" name="profile_image" id="profile-image-input" 
                            accept="image/*" class="form-control form-control inline-input" style="display:none">
                        
                        <!-- hidden by default -->
                        <span id="profile-edit-btn" 
                            class="position-absolute bg-white p-1 rounded-circle d-none" 
                            style="cursor:pointer;">
                            <i class="bi bi-pencil"></i>
                        </span>
                    </div>
                    <div class="col">
                        <h2 class="h4 mb-2 editable" data-field="name">
                            <span class="inline-text">{{ $auth_tenant->user->name }}</span>
                            <input type="text" name="full_name" class="form-control form-control inline-input" value="{{ $auth_tenant->user->name }}">
                        </h2>
                        <h6 class="h5 mb-2 editable" data-field="name">
                            <span class="inline-text"><i class="bi bi-map me-2 text-muted"></i>{{ $auth_tenant->address }}</span>
                            <input type="text" name="address" class="form-control form-control inline-input" value="{{ $auth_tenant->address }}">
                        </h6>
                        <h6 class="h5 mb-2 editable" data-field="name">
                            <span class="inline-text"><i class="bi bi-lock me-2 text-muted"></i></span>
                            <input type="text" name="address" class="form-control form-control inline-input" value="" placeholder="Enter your password">
                        </h6>

                        <!-- <span class="status-badge status-active">{{ $auth_tenant->user->is_active == 1 ? "Active" : "Not Active" }} Lease</span> -->
                    </div>
                   
                </div>

            </div>

            <!-- Contact Information -->
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="mb-4 eme-info">
                    <h3><i class="bi bi-telephone"></i> Contact Information</h3>
                    <div class="row g-3">
                        <div class="col-md-6 editable" data-field="phone">
                        
                        <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-telephone me-2 text-muted"></i> Phone</span>
                            <span class="inline-text">{{ $auth_tenant->user->phone_number ?? 'N/A' }}</span>
                            <input type="text" name="phone_number" class="form-control form-control inline-input" value="{{ $auth_tenant->user->phone_number}}">
                        </div>
                        </div>
                        <div class="col-md-6 editable" data-field="email">
                        <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-envelope me-2 text-muted"></i> Email</span>
                            <span class="inline-text">{{ $auth_tenant->user->email ?? 'N/A' }}</span>
                            <input type="email" name="email" class="form-control form-control inline-input" value="{{ $auth_tenant->user->email ?? 'N/A' }}">
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Information -->
            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                <div class="mb-4 eme-info">
                <h3><i class="bi bi-exclamation-circle"></i> Emergency Contact</h3>
                <div class="row g-3">
                    <div class="col-md-4 editable" data-field="emergency-name">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-person me-2 text-muted"></i> Name</span>
                        <span class="inline-text">{{ $auth_tenant->user->emergency_contact_name ?? 'N/A' }}</span>
                        <input type="text" name="emergency_contact_name" class="form-control form-control inline-input" value="{{ $auth_tenant->user->emergency_contact_name }}">
                    </div>
                    </div>
                    <div class="col-md-4 editable" data-field="emergency-phone">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-telephone me-2 text-muted"></i> Phone</span>
                        <span class="inline-text">{{ $auth_tenant->user->emergency_phone_number ?? 'N/A' }}</span>
                        <input type="text" name="emergency_phone_number" class="form-control form-control inline-input" value="{{ $auth_tenant->user->emergency_phone_number}}">
                    </div>
                    </div>
                    <div class="col-md-4 editable" data-field="emergency-relationship">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-heart me-2 text-muted"></i> Relationship</span>
                        <span class="inline-text">{{ $auth_tenant->user->emergency_contact_relationship ?? 'N/A' }}</span>
                        <input type="text" name="emergency_contact_relationship" class="form-control form-control inline-input" value="{{ $auth_tenant->user->emergency_contact_relationship }}">
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="tab-pane fade" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
                
                <!--  -->
                <div class="mb-4 eme-info">
                <h3><i class="bi bi-file-text"></i> Documents</h3>
                <div class="row g-3 eme-info">
                    @php
                        $documents = [
                            'Personal Document' => $auth_tenant->user->personal_document,
                            'IC Document' => $auth_tenant->user->ic_document,
                            'Miscellaneous' => $auth_tenant->user->miscellaneous,
                        ];
                    @endphp
                    @foreach($documents as $label => $file)
                        <div class="col-sm-6 col-lg-6 editable" data-field="{{ Str::slug($label, '-') }}">
                            <div class="document-item fw-100">
                                <span class="info-label">
                                    <i class="bi bi-file-earmark-text me-2 text-muted"></i> {{ $label }}
                                </span>
                                <span class="inline-text d-block">
                                    @if($file)
                                        <a href="{{ asset('storage/upload/tenantdocument/' . $file) }}" target="_blank">{{ $file }}</a>
                                    @else
                                        <span class="inline-text text-muted">{{ 'N/A' }}</span>
                                    @endif
                                </span>
                                
                                <input type="file" name="{{ Str::slug($label, '_') }}" class="form-control inline-input" style="display:none">
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                
                <div class="card-form eme-info">
                    <h3><i class="bi bi-file-text"></i> Payment card</h3>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <div class="fw-100">
                                <!-- Card Preview -->
                                <div class="card-list">
                                    <div class="credit-card" id="credit-card">
                                    <div class="card-inner">
                                        <!-- FRONT -->
                                        <div class="card-front">
                                        <div class="card-number" id="card-number-display">#### #### #### ####</div>
                                        <div class="card-holder" id="card-holder-display">FULL NAME</div>
                                        <div class="card-expiry">Valid Thru <span id="card-expiry-display">MM/YY</span></div>
                                        </div>
                                        <!-- BACK -->
                                        <div class="card-back">
                                        <div class="magnetic-strip"></div>
                                        <div class="cvv-box">CVV: <span id="cvv-display">***</span></div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <!-- Payment Form -->
                            <div class="card-form__inner">
                                <div class="card-input">
                                <label>Card Number</label>
                                <input type="text" id="card-number" maxlength="19" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="card-input">
                                <label>Card Holder</label>
                                <input type="text" id="card-holder" placeholder="John Doe">
                                </div>
                                <div class="card-form__row">
                                <div class="card-form__col">
                                    <label>Expiration Month</label>
                                    <select id="card-month" class="form-control">
                                    <option value="">MM</option>
                                    <option>01</option><option>02</option><option>03</option><option>04</option>
                                    <option>05</option><option>06</option><option>07</option><option>08</option>
                                    <option>09</option><option>10</option><option>11</option><option>12</option>
                                    </select>
                                </div>
                                <div class="card-form__col">
                                    <label>Expiration Year</label>
                                    <select id="card-year" class="form-control"> 
                                    <option value="">YY</option>
                                    </select>
                                </div>
                                <div class="card-form__col">
                                    <label>CVV</label>
                                    <input class="form-control" type="password" id="card-cvv" maxlength="4" placeholder="123">
                                </div>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-primary w-100"><i class="bi bi-lock-fill me-2"></i> Pay Securely</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

</div>

<!-- ./ -->
{{--
<div class="card border bg-custom w-100 tenant-profile-sec d-none">
    <div class="card-body">
        <div class="profile-card">
            <div class="">

                <!-- Profile Header -->
                <div class="row align-items-center mb-4">
                    <div class="col-auto ">
                        <img src="{{ $auth_tenant->user->profile_url }}" alt="{{ $auth_tenant->user->name }}" class="avatar">

                        <input type="file" name="profile_image" id="profile-image-input" 
                            accept="image/*" class="form-control form-control inline-input" style="display:none">
                        
                        <!-- hidden by default -->
                        <span id="profile-edit-btn" 
                            class="position-absolute bg-white p-1 rounded-circle d-none" 
                            style="cursor:pointer;">
                            <i class="bi bi-pencil"></i>
                        </span>
                    </div>
                    <div class="col">
                        <h2 class="h3 mb-1 editable" data-field="name">
                            <span class="inline-text">{{ $auth_tenant->user->name }}</span>
                            <input type="text" name="full_name" class="form-control form-control inline-input" value="{{ $auth_tenant->user->name }}">
                        </h2>
                        <h6 class="h5 mb-1 editable" data-field="name">
                            <span class="inline-text"><i class="bi bi-map me-2 text-muted"></i>{{ $auth_tenant->address }}</span>
                            <input type="text" name="address" class="form-control form-control inline-input" value="{{ $auth_tenant->address }}">
                        </h6>

                        <!-- <span class="status-badge status-active">{{ $auth_tenant->user->is_active == 1 ? "Active" : "Not Active" }} Lease</span> -->
                    </div>
                    <div class="col-auto custom-edit-button">
                        <button id="edit-btn" class="btn btn-secondary text-white">Edit</button>
                        <button id="save-btn" class="btn btn-primary d-none" data-base-route="{{ route('tenant-profile.update', $auth_tenant->id) }}" data-tenant-id="{{ $auth_tenant->user_id }}">Save</button>
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
                            <span class="inline-text">{{ $auth_tenant->user->phone_number ?? 'N/A' }}</span>
                            <input type="text" name="phone_number" class="form-control form-control inline-input" value="{{ $auth_tenant->user->phone_number}}">
                        </div>
                        </div>
                        <div class="col-md-6 editable" data-field="email">
                        <div class="info-card p-3">
                            <span class="info-label"><i class="bi bi-envelope me-2 text-muted"></i> Email</span>
                            <span class="inline-text">{{ $auth_tenant->user->email ?? 'N/A' }}</span>
                            <input type="email" name="email" class="form-control form-control inline-input" value="{{ $auth_tenant->user->email ?? 'N/A' }}">
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
                        <span class="inline-text">{{ $auth_tenant->user->emergency_contact_name ?? 'N/A' }}</span>
                        <input type="text" name="emergency_contact_name" class="form-control form-control inline-input" value="{{ $auth_tenant->user->emergency_contact_name }}">
                    </div>
                    </div>
                    <div class="col-md-4 editable" data-field="emergency-phone">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-telephone me-2 text-muted"></i> Phone</span>
                        <span class="inline-text">{{ $auth_tenant->user->emergency_phone_number ?? 'N/A' }}</span>
                        <input type="text" name="emergency_phone_number" class="form-control form-control inline-input" value="{{ $auth_tenant->user->emergency_phone_number}}">
                    </div>
                    </div>
                    <div class="col-md-4 editable" data-field="emergency-relationship">
                    <div class="info-card p-3">
                        <span class="info-label"><i class="bi bi-heart me-2 text-muted"></i> Relationship</span>
                        <span class="inline-text">{{ $auth_tenant->user->emergency_contact_relationship ?? 'N/A' }}</span>
                        <input type="text" name="emergency_contact_relationship" class="form-control form-control inline-input" value="{{ $auth_tenant->user->emergency_contact_relationship }}">
                    </div>
                    </div>
                </div>
                </div>

                <hr class="divider">

                <!-- Documents -->
                <div class="mb-4">
                <h3><i class="bi bi-file-text"></i> Documents</h3>
                <div class="row g-3 eme-info">
                    @php
                        $documents = [
                            'Personal Document' => $auth_tenant->user->personal_document,
                            'IC Document' => $auth_tenant->user->ic_document,
                            'Miscellaneous' => $auth_tenant->user->miscellaneous,
                        ];
                    @endphp
                    @foreach($documents as $label => $file)
                        <div class="col-sm-6 col-lg-4 editable" data-field="{{ Str::slug($label, '-') }}">
                            <div class="document-item fw-100">
                                <span class="info-label">
                                    <i class="bi bi-file-earmark-text me-2 text-muted"></i> {{ $label }}
                                </span>
                                <span class="inline-text d-block">
                                    @if($file)
                                        <a href="{{ asset('storage/upload/tenantdocument/' . $file) }}" target="_blank">{{ $file }}</a>
                                    @else
                                        <span class="inline-text text-muted">{{ 'N/A' }}</span>
                                    @endif
                                </span>
                                
                                <input type="file" name="{{ Str::slug($label, '_') }}" class="form-control inline-input" style="display:none">
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>

            </div>
        </div>
    </div>
</div>
--}}

<script>
    const editBtn = document.getElementById("edit-btn");
    const saveBtn = document.getElementById("save-btn");
    const cancelBtn = document.getElementById("cancel-btn");

    const profileEditBtn = document.getElementById("profile-edit-btn");
    const profileInput = document.getElementById("profile-image-input");
    const profilePreview = document.getElementById("profile-preview");

    editBtn.addEventListener("click", () => {
        document.querySelectorAll(".editable").forEach(el => {
            el.querySelector(".inline-text").style.display = "none";
            el.querySelector(".inline-input").style.display = "block";
        });
        editBtn.classList.add("d-none");
        saveBtn.classList.remove("d-none");
        cancelBtn.classList.remove("d-none");
        profileEditBtn.classList.remove("d-none");
    });

    profileEditBtn.addEventListener("click", () => {
        profileInput.click(); // open file selector
    });

    profileInput.addEventListener("change", () => {
        const file = profileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
            profilePreview.src = e.target.result; // live preview
            };
            reader.readAsDataURL(file);
        }
    });

    cancelBtn.addEventListener("click", () => {
        document.querySelectorAll(".editable").forEach(el => {
            const input = el.querySelector(".inline-input");
            const span = el.querySelector(".inline-text");

            if (!input) return;

            if (input.type === "file") {
                // Reset file input, don’t set textContent
                input.value = ""; 
            } else {
                input.value = span ? span.textContent.trim() : "";
            }

            input.style.display = "none";
            if (span) span.style.display = "inline";
        });

        saveBtn.classList.add("d-none");
        cancelBtn.classList.add("d-none");
        editBtn.classList.remove("d-none");
        profileEditBtn.classList.add("d-none");
    });

    saveBtn.addEventListener("click", () => {
        const tenantId = saveBtn.dataset.tenantId;
        const url = `${saveBtn.dataset.baseRoute}`;
        let errors = [];
        fetch(url, {
            method: "POST", 
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: (() => {
                const formData = new FormData();
                // Collect inline inputs
                document.querySelectorAll(".editable").forEach(el => {
                    const input = el.querySelector(".inline-input");
                    if (input && input.name) {
                        if (input.type === "file" && input.files && input.files.length > 0) {
                            const file = input.files[0];
                            if (file.size > 4 * 1024 * 1024) {
                                errors.push(`${input.name} must be less than 4MB`);
                            }
                            // Validate file type
                            const allowedTypes = ["image/jpeg","image/png","image/jpg","application/pdf","application/msword",
                                                "application/vnd.openxmlformats-officedocument.wordprocessingml.document"];
                            if (!allowedTypes.includes(file.type)) {
                                errors.push(`${input.name} has an invalid file type`);
                            }
                            formData.append(input.name, file);
                        } else  if (input.type !== "file") {
                            // normal text/number/etc inputs
                            const value = input.value.trim();
                            if (input.name === "email" && value) {
                                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                if (!emailPattern.test(value)) {
                                    errors.push("Invalid email format");
                                }
                            }
                            if (input.name === "full_name" && value.length > 100) {
                                errors.push("Full name cannot exceed 100 characters");
                            }
                            // Add more text validations as needed
                            formData.append(input.name, value);
                        }
                    }
                });

                // Add profile image if selected
                const profileFile = profileInput.files[0];
                if (profileFile) {
                    const file = profileInput.files[0];
                    if (file.size > 2 * 1024 * 1024) errors.push("Profile image must be less than 2MB");
                    const allowedProfileTypes = ["image/jpeg","image/png","image/jpg","image/gif"];
                    if (!allowedProfileTypes.includes(file.type)) errors.push("Profile image has invalid type");
                    formData.append("profile_image", file);
                }
                if (errors.length > 0) {
                    toastrs("error", errors.join("<br>"), "error");
                    return null; // stop submission if errors exist
                }
                return formData;
            })()
        })
        .then(async res => {
            const data = await res.json().catch(async () => {
                const text = await res.text();
                throw new Error("Server returned invalid JSON:\n" + text);
            });
            return data;
        })
        .then(res => {
            if (res.success) {
                toastrs("success", "Profile updated successfully!", "success");

                // Reset all editable fields back to view mode
                document.querySelectorAll(".editable").forEach(el => {
                    const input = el.querySelector(".inline-input");
                    const span = el.querySelector(".inline-text");
                    if (input.type === "file") {
                    // if a new file was uploaded, update link with filename
                        if (input.files.length > 0) {
                            const fileName = input.files[0].name;
                            // Here use your backend storage path if response gives you file
                            span.innerHTML = `<a href="/storage/upload/tenantdocument/${fileName}" target="_blank">${fileName}</a>`;
                        }
                    } else {
                        // for normal text fields
                        span.textContent = input.value;
                    }

                    input.style.display = "none";
                    span.style.display = "inline";
                });

                // Hide Save/Cancel, show Edit again
                saveBtn.classList.add("d-none");
                cancelBtn.classList.add("d-none");
                editBtn.classList.remove("d-none");
                profileEditBtn.classList.add("d-none");

                
            } else if (res.errors) {
                // Show server-side validation errors
                const messages = Object.values(res.errors).flat();
                toastrs("error", messages.join("<br>"), "error");
            } else {
                toastrs("error", "Something went wrong!", "error");
            }
        })
        .catch(err => {
            console.error(err);
            toastrs("error", "An unexpected error occurred. Check console.", "error");
        });
    });
</script>



 <script>
    const cardNumberInput = document.getElementById("card-number");
    const cardHolderInput = document.getElementById("card-holder");
    const cardMonthSelect = document.getElementById("card-month");
    const cardYearSelect = document.getElementById("card-year");
    const cardCvvInput = document.getElementById("card-cvv");

    const numberDisplay = document.getElementById("card-number-display");
    const holderDisplay = document.getElementById("card-holder-display");
    const expiryDisplay = document.getElementById("card-expiry-display");
    const cvvDisplay = document.getElementById("cvv-display");
    const card = document.getElementById("credit-card");

    // Fill years dynamically
    const currentYear = new Date().getFullYear();
    for (let i = 0; i < 12; i++) {
      const option = document.createElement("option");
      option.value = currentYear + i;
      option.innerText = currentYear + i;
      cardYearSelect.appendChild(option);
    }

    // Format card number
    cardNumberInput.addEventListener("input", () => {
      let value = cardNumberInput.value.replace(/\D/g, "").substring(0,16);
      let formattedValue = value.replace(/(.{4})/g, "$1 ").trim();
      cardNumberInput.value = formattedValue;
      numberDisplay.innerText = formattedValue || "#### #### #### ####";
    });

    // Card Holder
    cardHolderInput.addEventListener("input", () => {
      holderDisplay.innerText = cardHolderInput.value.toUpperCase() || "FULL NAME";
    });

    // Expiry
    function updateExpiry() {
      let mm = cardMonthSelect.value;
      let yy = cardYearSelect.value ? cardYearSelect.value.toString().slice(-2) : "YY";
      expiryDisplay.innerText = (mm || "MM") + "/" + yy;
    }
    cardMonthSelect.addEventListener("change", updateExpiry);
    cardYearSelect.addEventListener("change", updateExpiry);

    // CVV flip
    cardCvvInput.addEventListener("focus", () => card.classList.add("flipped"));
    cardCvvInput.addEventListener("blur", () => card.classList.remove("flipped"));

    cardCvvInput.addEventListener("input", () => {
      cvvDisplay.innerText = cardCvvInput.value.replace(/./g, "*") || "***";
    });
  </script>
@endsection
