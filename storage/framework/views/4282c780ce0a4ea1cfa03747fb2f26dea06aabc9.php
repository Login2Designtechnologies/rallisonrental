<?php echo e(Form::model($contact, array('route' => array('contact.update', $contact->id), 'method' => 'PUT'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('name',__('Name'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter contact name')))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('email',__('Email'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter contact email')))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('contact_number',__('Contact Number'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('contact_number',null,array('class'=>'form-control','placeholder'=>__('Enter contact number')))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('subject',__('Subject'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('subject',null,array('class'=>'form-control','placeholder'=>__('Enter contact subject')))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('message',__('Message'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::textarea('message',null,array('class'=>'form-control','rows'=>5))); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Update'),array('class'=>'btn btn-secondary btn-rounded'))); ?>

</div>
<?php echo e(Form::close()); ?>


<?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/contact/edit.blade.php ENDPATH**/ ?>