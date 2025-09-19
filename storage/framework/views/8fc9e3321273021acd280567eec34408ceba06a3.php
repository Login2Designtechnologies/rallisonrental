<?php echo e(Form::model($FAQ, array('route' => array('FAQ.update', $FAQ->id), 'method' => 'PUT'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('question',__('Question'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('question',null,array('class'=>'form-control','placeholder'=>__('Enter Question')))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('description',__('Description'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::textarea('description',null,array('class'=>'form-control','rows'=>5))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('enabled', __('Enabled FAQ'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::hidden('enabled', 0, ['class' => 'form-check-input'])); ?>

            <div class="form-check form-switch">
                <?php echo e(Form::checkbox('enabled', 1, true, ['class' => 'form-check-input', 'role' => 'switch', 'id' => 'flexSwitchCheckChecked'])); ?>

                <?php echo e(Form::label('', '', ['class' => 'form-check-label'])); ?>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Update'),array('class'=>'btn btn-secondary btn-rounded'))); ?>

</div>
<?php echo e(Form::close()); ?>


<?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/FAQ/edit.blade.php ENDPATH**/ ?>