<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Add Manage Notice')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('ticket-support')); ?>"><?php echo e(__('Ticket List')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Add')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form action="">
            <div class="row g-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">Subject <span class="text-danger">*</span></label>
                <input class="form-control" placeholder="Enter name" required="required" name="name" type="text" value="" id="first_name">
            </div>
            
            <div class="col-lg-6">
                <label for="" class="form-label"> Photo </label>
                <input type="file" class="form-control">
            </div>
            <div class="col-lg-6">
                <label for="" class="form-label"> Provide a detailed description </label>
                <textarea name="" id="" class="form-control" rows="1"></textarea>
            </div>
            <div class="col-lg-6">
                <label for="" class="form-label"> Select Category </label>
                <select name="" id="" class="form-control">
                  <option value="">-- Select --</option>
                  <option value="">Invoice</option>
                  <option value="">Account</option>
                  <option value="">Technical</option>
                </select>
            </div>
            <div class="col-lg-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-rounded">Submit</button>
            </div>
        </div>
        </form>
    </div>
</div>


<!-- CKEditor 5 Classic Build -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/dashbaordpage/add-ticket.blade.php ENDPATH**/ ?>