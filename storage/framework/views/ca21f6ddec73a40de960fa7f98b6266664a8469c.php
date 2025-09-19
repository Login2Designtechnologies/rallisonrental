<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Create')); ?>

<?php $__env->stopSection(); ?>
<style>
  .card-header-title span{float:right;color: #30af77;font-weight: 600;}
  #tiersRepeater .border{border:1px solid #acffdc !important;}
  .card-header .toggle-btn{background-color: #e8eff7}
</style>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-sm-12">
    <div class="">
      

      <div class="pt-0">
        <?php
          // Make sure $late_fee is an array
          $saved = $late_fee ?? [];
          if (is_string($saved)) {
              $decoded = json_decode($saved, true);
              $saved = is_array($decoded) ? $decoded : [];
          }
          $initialTiers = old('tiers', data_get($saved, 'tiers', []));
        ?>

        <form action="<?php echo e(route('setup_late_fee')); ?>" method="post" id="lateFeeForm">
          <?php echo csrf_field(); ?>

          
          <!-- <div class="card mb-3">
            <div class="card-header">
              <div class="row align-items-center g-2">
                <div class="col-12">
                  <h5 class="card-header-title">Global Setting for contract Cancellation <span><i class="ti ti-plus"></i></span></h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Contract Cancel Days</label>
                  <input
                    type="number" min="0" class="form-control" name="contract_cancel_days" placeholder="e.g. 30"
                    value="<?php echo e(old('contract_cancel_days', data_get($saved, 'contract_cancel_days'))); ?>"
                  >
                  <small class="text-muted">Number of days after due date when contract is canceled.</small>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Fixed Late Fee</label>
                  <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input
                      type="number" min="0" step="0.01" class="form-control" name="fixed_late_fee" placeholder="e.g. 250.00"
                      value="<?php echo e(old('fixed_late_fee', data_get($saved, 'fixed_late_fee'))); ?>"
                    >
                  </div>
                  <small class="text-muted">A one-time late fee (optional).</small>
                </div>
              </div>
            </div>
          </div> -->

          <div class="card mb-3">
              <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="card-header-title mb-0">
                      Global Setting for Contract Cancellation
                  </h5>
                  <button class="btn btn-sm btn-light toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#contractCollapse">
                      <i class="ti ti-plus"></i>
                  </button>
              </div>

              <!-- Default collapse open -->
              <div id="contractCollapse" class="collapse show">
                  <div class="card-body">
                      <div class="row g-3">
                          <div class="col-md-6">
                              <label class="form-label">Contract Cancel Days</label>
                              <input
                                  type="number" min="0" class="form-control" name="contract_cancel_days" placeholder="e.g. 30"
                                  value="<?php echo e(old('contract_cancel_days', data_get($saved, 'contract_cancel_days'))); ?>"
                              >
                              <small class="text-muted">Number of days after due date when contract is canceled.</small>
                          </div>

                          <div class="col-md-6">
                              <label class="form-label">Fixed Late Fee</label>
                              <div class="input-group">
                                  <span class="input-group-text">₹</span>
                                  <input
                                      type="number" min="0" step="0.01" class="form-control" name="fixed_late_fee" placeholder="e.g. 250.00"
                                      value="<?php echo e(old('fixed_late_fee', data_get($saved, 'fixed_late_fee'))); ?>"
                                  >
                              </div>
                              <small class="text-muted">A one-time late fee (optional).</small>
                          </div>
                      </div>
                  </div>
              </div>
          </div>



          
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="card-header-title">Late Fee Setup</h5>
              <button type="button" class="btn btn-sm btn-primary" id="addTierBtn">
                <i class="ti ti-plus"></i> Add Tier
              </button>
            </div>

            <div class="card-body pb-0">
              <div id="tiersRepeater" class="row g-3"></div>
            </div>

            <div class="card-footer text-end">
              <button type="submit" class="btn btn-success">Save Settings</button>
            </div>
          </div>
        </form>

        
        <template id="tierRowTpl">
          <div class="tier-row border rounded p-3 position-relative">
            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label">Tier Name</label>
                <input type="text" class="form-control" data-name="name" placeholder="e.g. Tier 1" required>
              </div>

              <div class="col-md-3">
                <label class="form-label">No. of Days</label>
                <input type="number" class="form-control" data-name="days_after" min="0" placeholder="e.g. 5" required>
                <small class="text-muted d-block">After due date when the tier starts.</small>
              </div>

              <div class="col-md-3">
                <label class="form-label">Start Time</label>
                <input type="time" class="form-control" data-name="start_time" required>
                <small class="text-muted d-block">Time of day when the tier applies.</small>
              </div>

              <div class="col-md-3">
                <label class="form-label">Fee Type</label>
                <select class="form-select" data-name="fee_type" required>
                  <option value="" disabled>Select</option>
                  <option value="per_day">Per Day</option>
                  <option value="fixed">Fixed</option>
                </select>
              </div>

              <div class="col-md-3">
                <label class="form-label">Fee Amount</label>
                <div class="input-group">
                  <span class="input-group-text">₹</span>
                  <input type="number" class="form-control" data-name="amount" min="0" step="0.01" placeholder="e.g. 100.00" required>
                </div>
              </div>

              <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" data-name="status" required>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>

              <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger w-100 btn-remove-tier">
                  <i class="ti ti-trash"></i> Remove
                </button>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const btn = document.querySelector(".toggle-btn");
    const icon = btn.querySelector("i");
    const collapseEl = document.querySelector("#contractCollapse");

    // पहली बार load पर collapse open रहेगा और icon PLUS ही दिखेगा

    collapseEl.addEventListener("hide.bs.collapse", function () {
        icon.classList.remove("ti-plus");
        icon.classList.add("ti-minus");
    });

    collapseEl.addEventListener("show.bs.collapse", function () {
        // दोबारा open होने पर फिर से plus दिखेगा
        icon.classList.remove("ti-minus");
        icon.classList.add("ti-plus");
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const repeater = document.getElementById('tiersRepeater');
  const tpl      = document.getElementById('tierRowTpl');
  const addBtn   = document.getElementById('addTierBtn');

  // Saved/old tiers from backend (already computed server-side)
  const initialTiers = <?php echo json_encode($initialTiers, 15, 512) ?>;

  const normalizeTier = (t = {}) => ({
    name:       t.name ?? '',
    days_after: t.days_after ?? t.tier_day ?? null,   // support old key
    start_time: t.start_time ?? t.tier_time ?? '',    // support old key
    fee_type:   t.fee_type ?? t.type ?? '',
    amount:     t.amount ?? t.fee_amount ?? null,     // support old key
    status:     String(t.status ?? 1),
  });

  function addTierRow(defaults = {}) {
    const d   = normalizeTier(defaults);
    const idx = repeater.querySelectorAll('.tier-row').length;
    const frag = tpl.content.cloneNode(true);

    frag.querySelectorAll('[data-name]').forEach(el => {
      const key = el.getAttribute('data-name');
      el.name = `tiers[${idx}][${key}]`;
      if (d[key] != null && d[key] !== '') {
        el.value = d[key];
      }
      if (key === 'status' && (d[key] == null || d[key] === '')) {
        el.value = '1';
      }
    });

    repeater.appendChild(frag);
  }

  function ensureAtLeastOneRow() {
    if (!repeater.querySelector('.tier-row')) addTierRow();
  }

  // Seed rows from saved/old data
  if (Array.isArray(initialTiers) && initialTiers.length) {
    initialTiers.forEach(t => addTierRow(t || {}));
  } else {
    addTierRow();
  }

  addBtn.addEventListener('click', () => addTierRow());

  repeater.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-remove-tier');
    if (!btn) return;
    btn.closest('.tier-row').remove();
    ensureAtLeastOneRow();
  });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/dashbaordpage/late-fees.blade.php ENDPATH**/ ?>