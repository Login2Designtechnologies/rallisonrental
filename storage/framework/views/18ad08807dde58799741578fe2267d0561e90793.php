<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Add Manage Notice')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('manage_notice')); ?>"><?php echo e(__('Manage notice List')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Add')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form>
            <!-- Template Name -->
            <div class="mb-3">
            <label for="NoticeName" class="form-label">Notice Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="NoticeeName" placeholder="Enter Notice name" >
            </div>

            <!-- Subject -->
            <div class="mb-3">
            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="subject" placeholder="Enter subject">
            </div>

            <!-- Send Email Status -->
            <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="sendStatus" checked>
            <label class="form-check-label" for="sendStatus">
                Send Email Status
            </label>
            </div>

            <!-- Message (CKEditor) -->
            <div class="mb-3">
            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
            <textarea id="editor">
                Message
            </textarea>
            </div>

        

             <!-- Preview Email + Send Button -->
            <div class="mb-3">
                <label for="preview" class="form-label">Preview </label>
                <div class="d-flex">
                    <input type="email" id="emailInput" class="form-control me-2" placeholder="Enter recipient email">
                    <button type="button" class="btn btn-success" id="sendBtn">Send</button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-4">
                Submit
            </button>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/dashbaordpage/add-notice.blade.php ENDPATH**/ ?>