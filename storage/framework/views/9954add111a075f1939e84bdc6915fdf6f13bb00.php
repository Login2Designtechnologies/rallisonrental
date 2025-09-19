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
<!-- <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary mb-3">
    ← Back
</a> -->
<div class="card bg-custom border p-25">
    <div class="row">
        <form action="<?php echo e(url('addAmenities-store/'.$id)); ?>" method="post">
           <?php echo csrf_field(); ?>
            <div class="col-lg-6">
                <div class="mb-3">
                   <label for="amenityName" class="form-label">Amenity Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="amenityName" placeholder="Enter amenity name" required>
                </div>
            </div>

            <!-- <div class="col-lg-6">
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required>
                </div>
            </div> -->

            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="amenityStatus" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-control form-select" name="status" id="amenityStatus">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            
           <div class="btn-block mt-2">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">← Back</button>
                    <button type="submit" class="btn btn-secondary">Save</button>
                </div>
            </div>
        </form>
        </div>
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
        if (subcategorySelect.value === 'Yes') {
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
            <input type="text" name="subcategories[]" class="form-control" placeholder="Enter Sub Category">
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

    saveBtn.addEventListener('click', () => {
        alert('Form saved successfully!');
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/property/addAmenities.blade.php ENDPATH**/ ?>