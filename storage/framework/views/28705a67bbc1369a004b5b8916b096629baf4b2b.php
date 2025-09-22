<?php echo e(Form::open(array('url' => 'unit/direct-store','method'=>'post','enctype' => "multipart/form-data"))); ?>

<div class="modal-body">
    <div class="row">
        <div class=" col-md-12 row">

             <!-- Property Select -->
            <div class="form-group  col-md-6">
                <?php echo e(Form::label('property', __('Property'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::select(
                    'property_id',
                    $property,     // array or collection: [id => name]
                    null,
                    [
                        'class' => 'form-control',
                        'id' => 'propertyall',
                        'required' => 'required',
                        'placeholder' => __('Select'),
                        'style' => ''
                    ]
                )); ?>

            </div>

            <div class="form-group  col-md-6">
                <?php echo e(Form::label('name',__('Name'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter unit name')))); ?>

            </div>
            <div class="form-group col-md-12">
                <?php echo e(Form::label('status', __('Status'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::select('status', [
                    '1' => 'Active',
                    '0' => 'Inactive'
                ], null, ['class' => 'form-control', 'placeholder' => __('Select Status')])); ?>

            </div>

            
        <div class="form-group col-md-12">
            <?php echo e(Form::label('notes',__('Description'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::textarea('notes',null,array('class'=>'form-control','rows'=>2,'placeholder'=>__('Enter notes')))); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Create'),array('class'=>'btn btn-secondary btn-rounded'))); ?>

</div>
<?php echo e(Form::close()); ?>

<script>
    $('#rent_type').on('change', function() {
        "use strict";
        var type=this.value;
        $('.rent_type').addClass('d-none')
        $('.'+type).removeClass('d-none')

        var input1= $('.rent_type').find('input');
        input1.prop('disabled', true);
        var input2= $('.'+type).find('input');
        input2.prop('disabled', false);
    });
</script>
<?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/unit/directcreate.blade.php ENDPATH**/ ?>