<?php $__env->startSection('content'); ?>

<?php
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
?>




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
                                            <img class="img-radius img-fluid wid-80" src="<?php echo e(!empty($tenant->user) && !empty($tenant->user->profile) ? asset(Storage::url('upload/profile/' . $tenant->user->profile)) : asset(Storage::url('upload/profile/avatar.png'))); ?>"
                                                alt="User image" />
                                        </div>
                                        <div class="flex-grow-1 mx-3 position-relative">
                                            <h5 class="mb-1">
                                                <?php echo e($fullName); ?> <br>
                                                <span><?php echo e($u->email); ?></span>
                                            </h5>
                                            

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
                                                <td><?php echo e($u?->emergency_phone_number ?: '-'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Country</b></td>
                                                <td>:</td>
                                                <td><?php echo e($country); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">State</b></td>
                                                <td>:</td>
                                                <td><?php echo e($tenant->state?->name ?? '-'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">City</b></td>
                                                <td>:</td>
                                                <td><?php echo e($tenant->city?->name ?? '-'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Zip Code</b></td>
                                                <td>:</td>
                                                <td><?php echo e($tenant->zip_code ?: '-'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Property</b></td>
                                                <td>:</td>
                                                <td><?php echo e($tenant->property?->title ?? ($tenant->property?->name ?? '-')); ?>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Unit</b></td>
                                                <td>:</td>
                                                <td><?php echo e($tenant->unit?->name ?? ($tenant->unit?->number ?? '-')); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Lease Start Date</b></td>
                                                <td>:</td>
                                                <td><?php echo e($leaseStart); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Lease End Date</b></td>
                                                <td>:</td>
                                                <td><?php echo e($leaseEnd); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Documents</b></td>
                                                <td>:</td>
                                                <td>
                                                    <?php $hasDocs = false; ?>
                                                    <?php if($tenant->application_document): ?>
                                                        <?php $hasDocs = true; ?>
                                                        <div><a href="<?php echo e(Storage::url($tenant->application_document)); ?>"
                                                                target="_blank">Application Document</a></div>
                                                    <?php endif; ?>
                                                    <?php if($tenant->driving_licence): ?>
                                                        <?php $hasDocs = true; ?>
                                                        <div><a href="<?php echo e(Storage::url($tenant->driving_licence)); ?>"
                                                                target="_blank">Driving Licence</a></div>
                                                    <?php endif; ?>
                                                    <?php if($tenant->bank_statement): ?>
                                                        <?php $hasDocs = true; ?>
                                                        <div><a href="<?php echo e(Storage::url($tenant->bank_statement)); ?>"
                                                                target="_blank">Bank Statement</a></div>
                                                    <?php endif; ?>
                                                    <?php if (! ($hasDocs)): ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b class="text-header">Address</b></td>
                                                <td>:</td>
                                                <td><?php echo e($tenant->address ?: '-'); ?></td>
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
                <?php
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
                ?>

                <form id="setupContractForm" method="POST" action="<?php echo e($action); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    
                    <input type="hidden" name="tenant_id" value="<?php echo e($tenantId); ?>">
                    <input type="hidden" name="property_id" value="<?php echo e($contract->property ?? ''); ?>">
                    <input type="hidden" name="owner_id" value="<?php echo e($contract->user_id ?? ''); ?>">

                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <strong>Please fix the errors below:</strong>
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($msg); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                    <?php if(!empty($tenantcontracts->start_date)): ?>
                            <input type="text" style="pointer-events: none;" 
                                   class="form-control"
                                   placeholder="MM-DD-YYYY" autocomplete="off" value="<?php echo e(old('start_date', $tenantcontracts->start_date ?? '')); ?>">
                    <?php else: ?>
                            <input type="text" id="start_date" name="start_date"
                                   class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="MM-DD-YYYY" autocomplete="off" value="<?php echo e(old('start_date', $tenantcontracts->start_date ?? '')); ?>">
                    <?php endif; ?>
                            <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                    <?php if(!empty($tenantcontracts->end_date)): ?>
                            <input type="text" style="pointer-events: none;"
                                   class="form-control"
                                   placeholder="MM-DD-YYYY" autocomplete="off" value="<?php echo e($tenantcontracts->end_date); ?>">
                    <?php else: ?>
                            <input type="text" id="end_date" name="end_date"
                                   class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="MM-DD-YYYY" autocomplete="off" value="<?php echo e($tenantcontracts->end_date); ?>">
                    <?php endif; ?>
                            <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Standard Rent (USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" placeholder="e.g. 1200"
                                       class="form-control <?php $__errorArgs = ['standard_rent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="standard_rent" value="<?php echo e($stdRent); ?>">
                            </div>
                            <?php $__errorArgs = ['standard_rent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Standard Late Fee (USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" placeholder="e.g. 50"
                                       class="form-control <?php $__errorArgs = ['late_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="late_fee" value="<?php echo e($lateFee); ?>">
                            </div>
                            <?php $__errorArgs = ['late_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Security Deposit (USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" placeholder="e.g. 500"
                                       class="form-control <?php $__errorArgs = ['security_deposit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="security_deposit" value="<?php echo e($secDep); ?>">
                            </div>
                            <?php $__errorArgs = ['security_deposit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notice Period</label>
                        <select name="notice_period_months"
                                class="form-control form-select <?php $__errorArgs = ['notice_period_months'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="1" <?php echo e($notice == '1' ? 'selected' : ''); ?>>1 month</option>
                            <option value="2" <?php echo e($notice == '2' ? 'selected' : ''); ?>>2 months</option>
                            <option value="3" <?php echo e($notice == '3' ? 'selected' : ''); ?>>3 months</option>
                        </select>
                        <?php $__errorArgs = ['notice_period_months'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="col-lg-12 mb-3">
                        <h3 class="mb-0 mt-3">Contract Renewal Setup</h3>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contract Renewal Month</label>
                        <select name="contract_renewal_month" id="contract_renewal_month"
                                class="form-control form-select <?php $__errorArgs = ['contract_renewal_month'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="3" <?php echo e($renewMon == '3' ? 'selected' : ''); ?>>3 months</option>
                            <option value="6" <?php echo e($renewMon == '6' ? 'selected' : ''); ?>>6 months</option>
                            <option value="9" <?php echo e($renewMon == '9' ? 'selected' : ''); ?>>9 months</option>
                            <option value="12" <?php echo e($renewMon == '12' ? 'selected' : ''); ?>>12 months</option>
                        </select>
                        <?php $__errorArgs = ['contract_renewal_month'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Tip: End Date will auto-suggest based on Start Date + Renewal Months.</div>
                    </div>

                  <div class="col-md-6">
    <label class="form-label">Contract Renewal Amount Increase (USD)</label>
    <div class="input-group">
        <span class="input-group-text">$</span>
        <input type="number" step="0.01" placeholder="e.g. 100"
               class="form-control <?php $__errorArgs = ['contract_renewal_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               name="contract_renewal_amount" value="<?php echo e($renewAmt); ?>">
    </div>
    <?php $__errorArgs = ['contract_renewal_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>


                    
                    <div class="col-md-12 mt-5">
                        <label class="form-label">Upload Contract</label>
                        <input type="file" name="contract_doc" id="contractFile"
                               class="form-control <?php $__errorArgs = ['contract_doc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,image/*">
                        <?php $__errorArgs = ['contract_doc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <?php if($isEdit && $tenantcontracts->contract_doc): ?>
                            <div class="mt-2">
                                <a href="<?php echo e(asset(Storage::url('upload/contracts/' . $tenantcontracts->contract_doc))); ?>" target="_blank" class="small">
                                    View current contract
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <?php echo e($isEdit ? 'Update Contract' : 'Save Contract'); ?>

                        </button>
                    </div>
                </form>

                
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
<?php if($tenantcontracts && $period): ?>
    <?php $__currentLoopData = $period; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $label = $month->format('F Y');
            $ym = $month->format('Y-m');
            $isPending = $contract->status == 'pending';
        ?>

        <tr data-ym="<?php echo e($ym); ?>">
            <td><?php echo e($label); ?></td>

            
            <td>$<?php echo e(number_format($tenantcontracts->standard_rent, 2)); ?></td>

            
            <td>
                <?php if($index == 0): ?>
                    $<?php echo e(number_format($tenantcontracts->security_deposit, 2)); ?>

                <?php endif; ?>
            </td>

            
            <td></td>

            
            <td>$<?php echo e(number_format($propertyAmenitiesTotal, 2)); ?></td>

            
            <td>
                <select class="form-select form-select-sm status-select">
                    <option value="pending" <?php echo e($isPending ? 'selected' : ''); ?>>Pending</option>
                    <option value="paid" <?php echo e(!$isPending ? 'selected' : ''); ?>>Paid</option>
                </select>
            </td>

            
            <td>
                <button class="btn btn-sm btn-primary" title="View Invoice">
                    <i class="ti ti-eye"></i>
                </button>
                <button class="btn btn-sm btn-secondary" title="Download Invoice">
                    <i class="ti ti-download"></i>
                </button>
                <form action="<?php echo e(route('tenants.resend', $tenant->id)); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-warning" title="Resend Invoice">
                        <i class="ti ti-send"></i>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <tr>
        <td colspan="7">No payment schedule available.</td>
    </tr>
<?php endif; ?>
</tbody>


<tfoot class="table-secondary text-center">
<tr>
    <th>Total</th>
    <th>$<?php echo e(number_format($tenantcontracts->standard_rent * (is_array($period) ? count($period) : 0), 2)); ?></th>
    <th>$<?php echo e(number_format($tenantcontracts->security_deposit, 2)); ?></th>
    <th>$0.00</th>
    <th>$<?php echo e(number_format($propertyAmenitiesTotal * count($period ?? []), 2)); ?></th>
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
                        <?php 
                            $tenantall = DB::table('utility_invoices')->where('tenant_id',$u->id)->where('property_id',$tenant->property)->get();
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $tenantall; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(date('F Y', strtotime($invoice->invoice_month))); ?></td>
                                <td>$<?php echo e(number_format($invoice->amount, 2)); ?></td>
                                <td>
                                    <?php if($invoice->status == 'delivered'): ?>
                                        <span class="badge bg-success">Delivered</span>
                                    <?php elseif($invoice->status == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif($invoice->status == 'draft'): ?>
                                        <span class="badge bg-secondary text-dark">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a target="_blank" href="<?php echo e(route('utility-invoices.show', $invoice->id)); ?>">
                                        <i class="ti ti-eye mx-1" data-bs-toggle="tooltip" title="View Invoice"></i>
                                    </a>
                                    <a target="_blank" href="<?php echo e(route('utility.invoices.pdf', $invoice->id)); ?>">
                                        <i class="ti ti-download mx-1" data-bs-toggle="tooltip" title="Download Invoice"></i>
                                    </a>
                                    <i class="ti ti-refresh mx-1 resend-invoice" data-id="<?php echo e($invoice->id); ?>" data-bs-toggle="tooltip" title="Resend Invoice"></i>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4">No utility invoices found.</td>
                            </tr>
                        <?php endif; ?>
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
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
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
                                    <?php 
                                        $tenantotherinvoicesall = DB::table('other_invoices')->where('tenant_id',$u->id)->where('property_id',$tenant->property)->get();
                                    ?>
                                        <tbody class="text-center">
                                            <?php $__empty_1 = true; $__currentLoopData = $tenantotherinvoicesall; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($i->id); ?></td>
                                                    <td>$<?php echo e($i->amount); ?></td>
                                                    <td>
                                                        <span class="badge bg-success"><?php echo e($i->status); ?></span>
                                                        <!-- <span class="badge bg-warning">Pending</span> -->
                                                    </td>
                                                    <td>
                                                        <a target="_blank"
                                                            href="<?php echo e(route('other-invoices.show', $i->id)); ?>"><i
                                                                class="ti ti-eye mx-1" data-bs-toggle="tooltip"
                                                                data-bs-title="View"></i></a>
                                                        <a target="_blank"
                                                            href="<?php echo e(route('utility.invoices.pdf', $i->id)); ?>"><i
                                                                class="ti ti-download mx-1" data-bs-toggle="tooltip"
                                                                data-bs-title="Download"></i></a>


                                                        <i class="ti ti-refresh mx-1" data-bs-toggle="tooltip"
                                                            data-bs-title="Resend Invoice"></i>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <?php endif; ?>


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

                                    <?php 
                                        $noticesall = DB::table('notices')->where('owner_id',$u->id)->get();
                                    ?>

                                        <?php if(!empty($noticesall)): ?>
                                            <?php $__currentLoopData = $noticesall; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="col-md-4">
                                                    <a href="#" class="btn btn-secondary btn-md w-100"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#sendNoticeMailModal<?php echo e($n->id); ?>">
                                                        <?php echo e($n->title); ?>

                                                    </a>
                                                    <?php echo $__env->make('tenants.notice_mails', ['notice' => $n], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <?php endif; ?>


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

<?php $__env->stopSection(); ?>




<?php $__env->startPush('script'); ?>
    
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/dodgerblue-lapwing-476569.hostingersite.com/public_html/resources/views/tenant/show.blade.php ENDPATH**/ ?>