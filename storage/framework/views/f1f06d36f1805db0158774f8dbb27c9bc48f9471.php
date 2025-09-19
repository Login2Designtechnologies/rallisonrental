<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('View Ticket')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('ticket-support')); ?>"><?php echo e(__('Ticket List')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('View')); ?></li>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card border bg-custom w-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex justify-content-between" style="width: 100%;">
                <div>
                    <h4 class="mb-0">acx <small class="text-muted">#17522330448</small></h4>
                    <div class="small text-muted mt-1">Mr. Customer &nbsp; &nbsp; | &nbsp; 08-26-2025 01:40 AM</div>
                </div>
                <div>
                    <h6 class="mb-0">Ticket Category : <small class="text-muted">Invoice</small></h6>
                </div>
            </div>
        </div>

        <!-- Editor Form -->
        <form id="replyForm">
            

            <div class="mb-3">
                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                <textarea id="editor">
                    Message
                </textarea>
            </div>

            <!-- Attachment + Submit row -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="company_name" class="form-label">Attachment</label>
                    <input type="file" id="company_name" name="company_name" class="form-control" required="" />
                </div>

                <div class="col-lg-12 text-end mt-2">
                    <!-- Status dropdown + Submit -->
                    <div class="btn-group">
                        <button type="button" id="submitStatusBtn" class="btn btn-dark action-btn mx-1">
                            Submit as <span id="statusLabel1"><b>Pending</b></span>
                        </button>

                        <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item status-option" href="#" data-status="Pending">Submit as Pending</a></li>
                            <li><a class="dropdown-item status-option" href="#" data-status="Resolved">Submit as Resolved</a></li>
                            <li><a class="dropdown-item status-option" href="#" data-status="Closed">Submit as Closed</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>

        <!-- Example previous reply -->
        <div class="reply-card d-flex gap-3">
            <img src="https://placehold.co/50x50" class="rounded-circle" alt="avatar" />
            <div>
                <div class="d-flex align-items-center mb-1">
                    <strong class="me-2">Admin</strong>
                    <small class="text-muted">08-26-2025 01:10 AM</small>
                </div>
                <div>abc</div>
            </div>
        </div>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/dashbaordpage/edit-ticket.blade.php ENDPATH**/ ?>