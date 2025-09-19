
<?php echo e(Form::open(array('url'=>'pages','method'=>'post'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('title',__('Title'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Title')))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('enabled', __('Enabled Page'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::hidden('enabled', 0, ['class' => 'form-check-input'])); ?>

            <div class="form-check form-switch">
                <?php echo e(Form::checkbox('enabled', 1, true, ['class' => 'form-check-input', 'role' => 'switch', 'id' => 'flexSwitchCheckChecked'])); ?>

                <?php echo e(Form::label('', '', ['class' => 'form-check-label'])); ?>

            </div>
        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('content',__('Content'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::textarea('content', null, ['class' => 'form-control', 'id' => 'classic-editor'])); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Create'),array('class'=>'btn btn-secondary btn-rounded'))); ?>

</div>
<?php echo e(Form::close()); ?>



<?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/Pages/create.blade.php ENDPATH**/ ?>