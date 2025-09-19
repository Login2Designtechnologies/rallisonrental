<?php $__env->startSection('content'); ?>





    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5>All Invoices</h5>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo e(url('other-invoices')); ?>" class="btn btn-secondary  ">
                                <i class="ti ti-circle-plus align-text-bottom"></i> Add New
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>For</th>
                                    <th>Invoice / Due</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage()); ?>

                                        </td>

                                        <td style="max-width: 420px;">
                                            <div class="fw-semibold">
                                                <a href="<?php echo e(route('other-invoices.show', $invoice)); ?>"
                                                    class="text-decoration-none">
                                                    <?php echo e($invoice->subject); ?>

                                                </a>
                                            </div>
                                            <div class="small text-muted">
                                                #<?php echo e($invoice->id); ?>

                                                <?php if($invoice->property ?? null): ?>
                                                    • <?php echo e($invoice->property->name); ?>

                                                <?php endif; ?>
                                            </div>


                                            
                                            <div class="mt-1" style="max-width: 480px; display:flex; flex-wrap:wrap;">
                                                <?php $__empty_2 = true; $__currentLoopData = $invoice->details->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                    <span class="badge bg-secondary text-white border me-1 mb-1">
                                                        <?php echo e($it->item); ?> (x<?php echo e($it->qty); ?>)
                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                                <?php if($invoice->details->count() > 5): ?>
                                                    <span
                                                        class="badge bg-light text-dark border me-1 mb-1">+<?php echo e($invoice->details->count() - 5); ?>

                                                        more</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <?php if($invoice->tenant ?? null): ?>
                                                <div class="fw-semibold"><?php echo e($invoice->tenant->user->name); ?></div>

                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="align-middle">
                                            <div class="small">
                                                <span class="text-muted">Inv:</span>
                                                <?php echo e(optional($invoice->invoice_date)->format('d M Y')); ?>

                                            </div>
                                            <div class="small">
                                                <span class="text-muted">Due:</span>
                                                <?php echo e(optional($invoice->due_date)->format('d M Y')); ?>

                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <strong>₹<?php echo e(number_format($invoice->amount, 2)); ?></strong>
                                        </td>

                                        <td class="align-middle">
                                            <?php
                                                $map = [
                                                    'draft' => 'secondary',
                                                    'sent' => 'info',
                                                    'paid' => 'success',
                                                    'partial' => 'warning',
                                                    'overdue' => 'danger',
                                                    'void' => 'dark',
                                                ];
                                                $badge = $map[$invoice->status] ?? 'secondary';
                                            ?>
                                            <span
                                                class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucfirst($invoice->status)); ?></span>
                                        </td>

                                        <td class="text-end align-middle">
                                            <a href="<?php echo e(route('other-invoices.show', $invoice)); ?>"
                                                class="btn btn-sm btn-outline-secondary">View</a>
                                            <a href="<?php echo e(route('other-invoices.edit', $invoice)); ?>"
                                                class="btn btn-sm btn-outline-primary">Edit</a>

                                            <form action="<?php echo e(route('other-invoices.destroy', $invoice)); ?>" method="post"
                                                class="d-inline delete-form">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-button">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No invoices found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!-- [ Main Content ] end -->


    <?php $__env->stopSection(); ?>

    <?php $__env->startPush('script'); ?>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/dashbaordpage/other.blade.php ENDPATH**/ ?>