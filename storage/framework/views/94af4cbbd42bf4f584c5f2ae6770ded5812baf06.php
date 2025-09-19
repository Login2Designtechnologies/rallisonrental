<?php echo e(Form::open(['url' => 'expense', 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12 col-lg-12">
            <?php echo e(Form::label('title', __('Expense Title'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Expense Title')])); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('expense_id', __('Expense Number'), ['class' => 'form-label'])); ?>

            <div class="input-group">
                <span class="input-group-text ">
                    <?php echo e(expensePrefix()); ?>

                </span>
                <?php echo e(Form::text('expense_id', $billNumber, ['class' => 'form-control', 'placeholder' => __('Enter Expense Number')])); ?>

            </div>
        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('expense_type', __('Expense Type'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('expense_type', $types, null, ['class' => 'form-control hidesearch'])); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('property_id', __('Property'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('property_id', $property, null, ['class' => 'form-control hidesearch', 'id' => 'property_id'])); ?>

        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('unit_id', __('Unit'), ['class' => 'form-label'])); ?>

            <div class="unit_div">
                <select class="form-control hidesearch unit" id="unit_id" name="unit_id">
                    <option value=""><?php echo e(__('Select Unit')); ?></option>
                </select>
            </div>
        </div>
        <div class="form-group  col-md-6 col-lg-6">
            <?php echo e(Form::label('date', __('Date'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::date('date', null, ['class' => 'form-control'])); ?>

        </div>
        <div class="form-group  col-md-6 col-lg-6">
            <?php echo e(Form::label('amount', __('Amount'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::number('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Expense Amount')])); ?>

        </div>
        <div class="form-group  col-md-12 col-lg-12">
            <?php echo e(Form::label('receipt', __('Receipt'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::file('receipt', ['class' => 'form-control'])); ?>

        </div>
        <div class="form-group  col-md-12 col-lg-12">
            <?php echo e(Form::label('notes', __('Notes'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 3])); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Create'), ['class' => 'btn btn-secondary btn-rounded'])); ?>

</div>
<?php echo e(Form::close()); ?>

<script>
    $('#property_id').on('change', function() {
        "use strict";
        var property_id = $(this).val();
        var url = '<?php echo e(route('property.unit', ':id')); ?>';
        url = url.replace(':id', property_id);
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                property_id: property_id,
            },
            contentType: false,
            processData: false,
            type: 'GET',
            success: function(data) {
                $('.unit').empty();
                var unit =
                    `<select class="form-control hidesearch unit" id="unit_id" name="unit_id"></select>`;
                $('.unit_div').html(unit);

                $.each(data, function(key, value) {
                    $('.unit').append('<option value="' + key + '">' + value + '</option>');
                });

                $(".hidesearch").each(function() {
                    var basic_select = new Choices(this, {
                        searchEnabled: false,
                        removeItemButton: true,
                    });
                });

            },

        });
    });
</script>
<?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/expense/create.blade.php ENDPATH**/ ?>