<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Units')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Units')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

<style>
    .dt-buttons.btn-group.flex-wrap {
        display: none;
    }
    @media (min-width: 991px) {
  div.dt-container div.dt-search {
    position: absolute;
    top: 20px;
    left: 100px;
  }
}
</style>

<div class="card bg-custom border p-25">
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header p-3">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5><?php echo e(__('Unit List')); ?></h5>
                        </div>
                        <?php if(Gate::check('create unit')): ?>
                            <div class="col-auto">
                                <a href="#" class="btn btn-secondary customModal" data-size="lg"
                                    data-url="<?php echo e(route('unit.direct-create')); ?>" data-title="<?php echo e(__('Create unit')); ?>"> <i
                                        class="ti ti-circle-plus align-text-bottom"></i> <?php echo e(__('Create Unit')); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class=" pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Property Name')); ?></th>
                                    <th><?php echo e(__('Unit Name')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    
                                    <?php if(Gate::check('edit unit') || Gate::check('delete unit')): ?>
                                        <th class="text-right"><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <?php
                                     $propertiesdata = DB::table('properties')->where('id',$unit->property_id)->first();
                                   ?>
                                    <tr>
                                        <td><?php echo e($propertiesdata->name ?? ''); ?> </td>
                                        <td><?php echo e($unit->name); ?> </td>
                                      <?php if($unit->status == '1'): ?>
                                        <td>Active</td>
                                      <?php else: ?>
                                        <td>Inactive</td>
                                      <?php endif; ?>
                                        
                                        <?php if(Gate::check('edit unit') || Gate::check('delete unit')): ?>
                                            <td class="text-right">
                                                <div class="cart-action">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['unit.destroy', [$unit->property_id, $unit->id]]]); ?>


                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit unit')): ?>
                                                        <a class="text-secondary customModal"
                                                            data-url="<?php echo e(route('unit.edit', [$unit->property_id, $unit->id])); ?>"
                                                            href="#" data-size="lg" data-title="<?php echo e(__('Edit Unit')); ?>"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                            <i data-feather="edit"></i></a>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete unit')): ?>
                                                        <!-- <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                            data-bs-original-title="<?php echo e(__('Detete')); ?>" href="#"> <i
                                                                data-feather="trash-2"></i></a> -->
                                                    <?php endif; ?>
                                                    <?php echo Form::close(); ?>

                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/unit/index.blade.php ENDPATH**/ ?>