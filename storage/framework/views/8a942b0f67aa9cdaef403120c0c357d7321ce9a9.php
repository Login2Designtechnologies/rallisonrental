<?php echo e(Form::open(array('url' => 'maintainer','enctype' => "multipart/form-data"))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('first_name',__('First Name'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('first_name',null,array('class'=>'form-control','placeholder'=>__('Enter First Name')))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('last_name',__('Last Name'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('last_name',null,array('class'=>'form-control','placeholder'=>__('Enter Last Name')))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('email',__('Email'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Email')))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('password',__('Password'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Password')))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('phone_number',__('Phone Number'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('phone_number',null,array('class'=>'form-control','placeholder'=>__('Enter Phone Number')))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('property_id',__('Property'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::select('property_id[]',$property,null,array('class'=>'form-control hidesearch','id'=>'property','multiple'))); ?>

        </div>

        <div class="form-group">
            <?php echo e(Form::label('type_id',__('Type'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::select('type_id',$types,null,array('class'=>'form-control hidesearch'))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('profile',__('Profile'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::file('profile',array('class'=>'form-control'))); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Create'),array('class'=>'btn btn-secondary btn-rounded'))); ?>

</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/maintainer/create.blade.php ENDPATH**/ ?>