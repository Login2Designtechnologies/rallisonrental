@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Profile') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Tenant Profile') }}</li>
    
@endsection

@section('content')
<div class="card border bg-custom w-100 tenant-profile-sec">
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

                    <span class="status-badge status-active">{{ $auth_tenant->user->is_active == 1 ? "Active" : "Not Active" }} Lease</span>
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
                    alert(errors.join("\n"));
                    return; // stop submission if errors exist
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
                alert("Profile updated successfully!");
                saveBtn.classList.add("d-none");
                cancelBtn.classList.add("d-none");
                editBtn.classList.remove("d-none");
            } else if (res.errors) {
                // Show server-side validation errors
                const messages = Object.values(res.errors).flat();
                alert(messages.join("\n"));
            } else {
                alert("Something went wrong!");
            }
        })
        .catch(err => {
            console.error(err);
            alert("An unexpected error occurred. Check console.");
        });
    });

</script>
@endsection
