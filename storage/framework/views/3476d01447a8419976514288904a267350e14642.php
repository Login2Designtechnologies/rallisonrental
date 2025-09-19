<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-class'); ?>
    product-detail-page
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('property.index')); ?>"><?php echo e(__('Property')); ?></a>
    </li>
    <li class="breadcrumb-item active">
        <a href="#"><?php echo e(__('Details')); ?></a>
    </li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

<div class="card bg-custom border p-25">
    <form action="<?php echo e(url('addUtilities-store/'.$id)); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="companyName" class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" id="companyName" name="name" class="form-control" placeholder="Enter Company Name" required>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="subcategorySelect" class="form-label">Sub Category <span class="text-danger">*</span></label>
                    <select id="subcategorySelect" class="form-control form-select" name="sub_category" required>
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-12">
                <div id="subcategoryFields" class="mb-3 d-none">
                    <label class="form-label">Enter Sub Categories <span class="text-danger">*</span></label>
                    <div id="subcategoryList"></div>
                    <div class="d-flex justify-content-end">
                        <button type="button" id="addMoreBtn" class="btn btn-outline-secondary btn-sm mb-3"> Add More
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
               <div class="mb-3">
                    <label for="subcategorySelect" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>


            <div class="col-lg-12 d-flex justify-content-center">
                <button type="submit" class="btn btn-secondary ">
                    Save
                </button>
            </div>
        </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const subcategorySelect = document.getElementById('subcategorySelect');
    const subcategoryFields = document.getElementById('subcategoryFields');
    const addMoreBtn = document.getElementById('addMoreBtn');
    const subcategoryList = document.getElementById('subcategoryList');
    const saveBtn = document.getElementById('saveBtn');

    subcategorySelect.addEventListener('change', () => {
        if (subcategorySelect.value === '1') {
            subcategoryFields.classList.remove('d-none');
            if (subcategoryList.children.length === 0) {
                addNewField();
            }
        } else {
            subcategoryFields.classList.add('d-none');
            subcategoryList.innerHTML = '';
        }
    });

    function addNewField() {
        const newField = document.createElement('div');
        newField.classList.add('input-group', 'mb-2');
        newField.innerHTML = `
            <input type="text" name="sub_category_name[]" class="form-control" placeholder="Enter Sub Category">
            <button class="btn btn-outline-danger remove-btn" type="button"><i class="fa fa-times"></i></button>
        `;
        subcategoryList.appendChild(newField);
        newField.querySelector('.remove-btn').addEventListener('click', () => {
            newField.remove();
        });
    }

    addMoreBtn.addEventListener('click', () => {
        addNewField();
    });

    // saveBtn.addEventListener('click', () => {
    //     alert('Form saved successfully!');
    // });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/property/addUtilities.blade.php ENDPATH**/ ?>