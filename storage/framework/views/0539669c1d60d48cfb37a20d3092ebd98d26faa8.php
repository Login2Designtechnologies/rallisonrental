<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Edit')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/vendors/dropzone/dropzone.js')); ?>"></script>
    <script>
        var dropzone = new Dropzone('#demo-upload', {
            previewTemplate: document.querySelector('.preview-dropzon').innerHTML,
            parallelUploads: 10,
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 10,
            filesizeBase: 1000,
            autoProcessQueue: false,
            thumbnail: function(file, dataUrl) {
                if (file.previewElement) {
                    file.previewElement.classList.remove("dz-file-preview");
                    var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                    for (var i = 0; i < images.length; i++) {
                        var thumbnailElement = images[i];
                        thumbnailElement.alt = file.name;
                        thumbnailElement.src = dataUrl;
                    }
                    setTimeout(function() {
                        file.previewElement.classList.add("dz-image-preview");
                    }, 1);
                }
            }

        });
        $('#property-update').on('click', function() {
            "use strict";
            $('#property-update').attr('disabled', true);
            var fd = new FormData();
            var file = document.getElementById('thumbnail').files[0];

            var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('property_images[' + key + ']', $('#demo-upload')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });
            if (file == undefined) {
                fd.append('thumbnail', '');
            } else {
                fd.append('thumbnail', file);
            }

            var other_data = $('#property_form').serializeArray();
            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });
            $.ajax({
                url: "<?php echo e(route('property.update', $property->id)); ?>",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    if (data.status == "success") {
                        $('#property-update').attr('disabled', true);
                        toastrs('success', data.msg, 'success');
                        var url = '<?php echo e(route('property.show', ':id')); ?>';
                        url = url.replace(':id', data.id);
                        setTimeout(() => {
                            window.location.href = url;
                        }, "1000");

                    } else {
                        toastrs('Error', data.msg, 'error');
                        $('#property-update').attr('disabled', false);
                    }
                },
                error: function(data) {
                    $('#property-update').attr('disabled', false);
                    if (data.error) {
                        toastrs('Error', data.error, 'error');
                    } else {
                        toastrs('Error', data, 'error');
                    }
                },
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('property.index')); ?>"><?php echo e(__('Property')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Edit')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="card bg-custom border p-25">
    <div class="row">
        <div class="col-md-12">

            <?php echo e(Form::model($property, ['route' => ['property.update', $property->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'property_form'])); ?>

            <div class="row">
                <div class="col-lg-6 d-flex">
                    <div class="card box-card w-100">
                        <div class="card-body">
                            <div class="info-group">
                                <div class="form-group ">
                                    <?php echo e(Form::label('type', __('Type'), ['class' => 'form-label'])); ?> <span class="text-danger">*</span>
                                    <?php echo e(Form::select('type', $types, null, ['class' => 'form-control hidesearch'])); ?>

                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?> <span class="text-danger">*</span>
                                    <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Property Name')])); ?>

                                </div>
                                <div class="form-group ">
                                    <?php echo e(Form::label('description', __('Description'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::textarea('description', null, ['class' => 'form-control', 'rows' => 1, 'placeholder' => __('Enter Property Description')])); ?>

                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('thumbnail', __('Thumbnail Image'), ['class' => 'form-label'])); ?> <span class="text-danger">*</span>
                                    <?php echo e(Form::file('thumbnail', ['class' => 'form-control'])); ?>

                                <?php if(!empty($propertyimages->image)): ?>
                                    <a href="<?php echo e(asset(Storage::url('upload/thumbnail')) . '/' . $propertyimages->image); ?>" target="_blank">
                                        <img src="<?php echo e(asset(Storage::url('upload/thumbnail')) . '/' . $propertyimages->image); ?>"
                                             alt="<?php echo e($property->name); ?>"
                                             class="img-prod"
                                             style="max-width:50px; height:auto;" />
                                    </a>
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex">
                    <div class="card box-card w-100">
                        <div class="card-body">
                            <div class="info-group">
                                <div class="form-group ">
                                    <?php echo e(Form::label('address', __('Address'), ['class' => 'form-label'])); ?> <span class="text-danger">*</span>
                                    <?php echo e(Form::textarea('address', null, ['class' => 'form-control', 'rows' => 1, 'placeholder' => __('Enter Property Address')])); ?>

                                </div>
                               
                                <?php echo e(Form::hidden('country', $property->country ?? 'USA')); ?>

                                <div class="form-group">
                                    <?php echo e(Form::label('state', __('State'), ['class' => 'form-label'])); ?> <span class="text-danger">*</span>
                                    <!-- <?php echo e(Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter Property State')])); ?> -->
                                    <?php echo e(Form::select('state', 
                                        $statesdata->pluck('name','id')->toArray(),  // value=id, text=name
                                        $property->state ?? null, 
                                        ['class' => 'form-control basic-select required-field', 'id'=>'company_state', 'required' => 'required', 'placeholder' => __('Select')]
                                    )); ?>

                                </div>

                                <?php
                                  $cities = DB::table('cities')->where('state_id', $property->state)
                                    ->orderBy('id', 'asc')->get();
                                ?>

                                <div class="form-group">
                                    <?php echo e(Form::label('city', __('City'), ['class' => 'form-label'])); ?> <span class="text-danger">*</span>
                                    <!-- <?php echo e(Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter Property City')])); ?> -->
                                    <select name="city" id="company_city" class="form-control required-field" required>
                                        <option value="" style="background-color: black;">Select</option>
                                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $citieval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($citieval->id); ?>" <?php echo e($property->city == $citieval->id ? 'selected' : ''); ?> selected style="background-color: black;"><?php echo e($citieval->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('zip_code', __('Zip Code'), ['class' => 'form-label'])); ?> <span class="text-danger">*</span>
                                    <?php echo e(Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Property Zip Code')])); ?>

                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 d-flex">
                    <div class="card box-card w-100">
                        <div class="card-header pb-0">
                            <?php echo e(Form::label('demo-upload', __('Property Images'), ['class' => 'form-label'])); ?>

                        </div>
                        <div class="card-body pt-2">
                            <div class="dropzone needsclick" id='demo-upload' action="#">
                                <div class="dz-message needsclick">
                                    <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                    <h3><?php echo e(__('Drop files here or click to upload.')); ?></h3>
                                </div>
                            </div>
                            <div class="preview-dropzon" style="display: none;">
                                <div class="dz-preview dz-file-preview">
                                    <div class="dz-image"><img data-dz-thumbnail="" src="" alt=""></div>
                                    <div class="dz-details">
                                        <div class="dz-size"><span data-dz-size=""></span></div>
                                        <div class="dz-filename"><span data-dz-name=""></span></div>
                                    </div>
                                    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""> </span>
                                    </div>
                                    <div class="dz-success-mark"><i class="fa fa-check" aria-hidden="true"></i></div>
                                </div>
                            </div>
                            <?php if($propertyextraimages->isNotEmpty()): ?>
                              <?php $__currentLoopData = $propertyextraimages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propertyval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(asset(Storage::url('upload/property')) . '/' . $propertyval->image); ?>" target="_blank">
                                    <img src="<?php echo e(asset(Storage::url('upload/property')) . '/' . $propertyval->image); ?>"
                                         alt="<?php echo e($property->name); ?>"
                                         class="img-prod"
                                         style="max-width:50px; height:auto;" />
                                </a>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 d-flex">
                    <div class="card border bg-custom w-100">
                        <div class="card-header">
                            <div class="row align-items-center g-2">
                                <div class="col">
                                    <h5>Amenities List</h5>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addAmenityModal">
                                        <i class="ti ti-circle-plus align-text-bottom"></i> Add New
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mt-4">
                                <div class="table-responsive">
                                   <table class="table table-bordered mb-0 custom-bg-table" id="amenitiesTable">
                                    <thead class="table-theme">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Amenity Name</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr id="row-<?php echo e($amenity->id); ?>">
                                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                                <td class="text-center"><?php echo e($amenity->name); ?></td>
                                                <td class="text-center">
                                                    <?php echo e($amenity->status == 1 ? 'Active' : 'Inactive'); ?>

                                                </td>
                                                <td class="text-center">
                                                   <button type="button" class="btn btn-sm btn-warning editAmenityBtn" 
                                                        data-id="<?php echo e($amenity->id); ?>" 
                                                        data-name="<?php echo e($amenity->name); ?>" 
                                                        data-status="<?php echo e($amenity->status); ?>"
                                                        data-propertyid="<?php echo e($property->id); ?>">
                                                    <i class="ti ti-edit"></i>
                                                </button>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-lg-12 d-flex">
                    <div class="card border bg-custom w-100">
                    <div class="card-header">
                        <div class="row align-items-center g-2">
                            <div class="col">
                                <h5>Utilities List</h5>
                            </div>
                            <div class="col-auto">
                                 <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addUtilitiesModal">
                                    <i class="ti ti-circle-plus align-text-bottom"></i> Add New
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mt-4">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 custom-bg-table" id="UtilitiesTable">
                                <thead class="table-theme">
                                    <tr>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Sub Category</th>
                                        <th>Sub Category Name</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $utilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $utilitval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr id="row2-<?php echo e($utilitval->id); ?>">
                                            <td class="text-center"><?php echo e($index + 1); ?></td>
                                            <td><?php echo e($utilitval->name); ?></td>
                                            <td><?php echo e($utilitval->sub_category == 1 ? 'Yes' : 'No'); ?></td>
                                            <td><?php echo e($utilitval->sub_category_name ?? ''); ?></td>
                                            <td><?php echo e($utilitval->status == 1 ? 'Active' : 'Inactive'); ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-warning editUtilitiesBtn"
                                                    data-id="<?php echo e($utilitval->id); ?>"
                                                    data-name="<?php echo e($utilitval->name); ?>"
                                                    data-status="<?php echo e($utilitval->status); ?>"
                                                    data-sub_category="<?php echo e($utilitval->sub_category); ?>"
                                                    data-sub_category_name="<?php echo e($utilitval->sub_category_name ?? ''); ?>"
                                                    data-propertyid="<?php echo e($property->id); ?>">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                            </td>
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
        </div>
                </div>


                <div class="col-lg-12 mb-4">
                    <div class="group-button text-end">
                        <?php echo e(Form::submit(__('Update'), ['class' => 'btn btn-secondary btn-rounded', 'id' => 'property-update'])); ?>

                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery Script -->
<script>
    $(document).ready(function(){
        // Sub Category Show/Hide
        $('select[name="sub_category"]').on('change', function(){
            if($(this).val() == "1"){
                $('#subCategorySection').slideDown();
            } else {
                $('#subCategorySection').slideUp();
                $('#subCategoryWrapper').html('<input type="text" name="sub_category_name[]" class="form-control mb-2">');
            }
        });

        // Add More Sub Categories
        $(document).on('click','#addMoreSubCategory', function(){
            $('#subCategoryWrapper').append('<input type="text" name="sub_category_name[]" class="form-control mb-2">');
        });
    });
</script>

<script>
    $(document).ready(function() {

        function loadCities(state_id, selectedCity = null) {
            if (state_id) {
                $.ajax({
                    url: '/get_cities/' + state_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#company_city').empty().append('<option value="">Select</option>');

                        $.each(data, function(index, city) {
                            $('#company_city').append('<option value="'+ city.id +'" style="background-color: black;">'+ city.name +'</option>');
                        });

                        // pre-select city if available
                        if (selectedCity) {
                            $('#company_city').val(selectedCity);
                        }
                    }
                });
            } else {
                $('#company_city').empty().append('<option value="">Select</option>');
            }
        }

        // On change of state
        $('#company_state').on('change', function() {
            var state_id = $(this).val();
            loadCities(state_id);
        });

        // On edit form (pre-select values)
        var selectedState = "<?php echo e($unit->state ?? ''); ?>";
        var selectedCity = "<?php echo e($unit->city ?? ''); ?>";

        if (selectedState) {
            $('#company_state').val(selectedState);
            loadCities(selectedState, selectedCity);
        }
    });
</script>


<script>
    $(document).on('click', '#saveUtilities', function (e) {
        e.preventDefault();

        $.ajax({
            url: "<?php echo e(url('propertyutilities-store2')); ?>",
            type: "POST",
            data: $('#UtilitiesForm').serialize(),
            success: function (response) {
                if (response.success) {
                    $('#addUtilitiesModal').modal('hide');
                    $('#UtilitiesForm')[0].reset();

                    alert(response.message);

                    // ✅ Append multiple rows if multiple sub categories
                    response.data.forEach(function(item) {
                        let rowCount = $("#UtilitiesTable tbody tr").length + 1;

                        let statusText = item.status == 1 ? 'Active' : 'Inactive';
                        let subCatText = item.sub_category == 1 ? 'Yes' : 'No';
                        let subCatNames = item.sub_category_name ?? '';

                        $('#UtilitiesTable tbody').append(`
                            <tr id="row2-${item.id}">
                                <td class="text-center">${rowCount}</td>
                                <td>${item.name}</td>
                                <td>${subCatText}</td>
                                <td>${subCatNames}</td>
                                <td>${statusText}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning editUtilitiesModal"
                                        data-id="${item.id}"
                                        data-name="${item.name}"
                                        data-status="${item.status}">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    alert(response.message ?? "Something went wrong!");
                }
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });
</script>


<script>
    $(document).on('click', '#saveAmenity', function (e) {
        e.preventDefault();

        $.ajax({
            url: "<?php echo e(url('propertyamenities-store2')); ?>", 
            type: "POST",
            data: $('#amenityForm').serialize(),
            success: function (response) {
                if (response.success) {
                    // Modal close + form reset
                    $('#addAmenityModal').modal('hide');
                    $('#amenityForm')[0].reset();

                    // ✅ Success message (no reload)
                    alert(response.message);

                    // Row count (next number)
                    let rowCount = $("#amenitiesTable tbody tr").length + 1;

                    // Status setup
                    let statusText = response.data.status == 1 ? 'Active' : 'Inactive';
                    let checked = response.data.status == 1 ? 'checked' : '';

                    // ✅ Append new row to table
                    $('#amenitiesTable tbody').append(`
                        <tr>
                            <td class="text-center">${rowCount}</td>
                            <td class="text-center">${response.data.name}</td>
                            <td class="text-center">${statusText}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning editAmenityBtn" 
                                    data-id="${response.data.id}" 
                                    data-name="${response.data.name}" 
                                    data-status="${response.data.status}">
                                <i class="ti ti-edit"></i>
                            </button>
                            </td>
                        </tr>
                    `);
                } else {
                    alert(response.message ?? "Something went wrong!");
                }
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });
</script>



<script>
    $(document).on('click', '.editAmenityBtn', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let status = $(this).data('status');
        let propertyid = $(this).data('propertyid');

        // Modal fill
        $('#editAmenityId').val(id);
        $('#propertyID').val(propertyid);
        $('#editAmenityName').val(name);
        $('#editAmenityStatus').val(status);

        $('#editAmenityModal').modal('show');
    });

    $(document).on('click', '#updateAmenity', function (e) {
        e.preventDefault();

        $.ajax({
            url: "<?php echo e(url('propertyamenities-update2')); ?>",
            type: "POST",
            data: $('#editAmenityForm').serialize(),
            success: function (response) {
                if (response.success) {
                    $('#editAmenityModal').modal('hide');
                    alert(response.message);

                    // ✅ Update row in table
                    let row = $('#row-' + response.data.id);
                    row.find('td:eq(1)').text(response.data.name); // Amenity Name
                    row.find('td:eq(2)').text(response.data.status == 1 ? 'Active' : 'Inactive'); // Status

                    // ✅ Update button attributes
                    row.find('.editAmenityBtn').data('name', response.data.name);
                    row.find('.editAmenityBtn').data('status', response.data.status);
                } else {
                    alert(response.message ?? "Something went wrong!");
                }
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });
</script>


<script>
    $(document).on('click', '.editUtilitiesBtn', function () {
        let data = $(this).data();

        $('#editUtilitiesId').val(data.id);
        $('#propertyID2').val(data.propertyid);
        $('#editUtilitiesName').val(data.name);
        $('#editUtilitiesStatus').val(data.status);
        $('#editUtilitiesSubCategory').val(data.sub_category);
        $('#editUtilitiesSubCategoryName').val(data.sub_category_name);

        $('#editUtilitiesModal').modal('show');
    });

    $(document).on('click', '#updateUtilities', function (e) {
        e.preventDefault();

        $.ajax({
            url: "<?php echo e(url('propertyUtilities-update2')); ?>",
            type: "POST",
            data: $('#editUtilitiesForm').serialize(),
            success: function (response) {
                if (response.success) {
                    $('#editUtilitiesModal').modal('hide');
                    alert(response.message);

                    let row = $('#row2-' + response.data.id);
                    row.find('td:eq(1)').text(response.data.name);
                    row.find('td:eq(2)').text(response.data.sub_category == 1 ? 'Yes' : 'No');
                    row.find('td:eq(3)').text(response.data.sub_category_name ?? '');
                    row.find('td:eq(4)').text(response.data.status == 1 ? 'Active' : 'Inactive');

                    let btn = row.find('.editUtilitiesBtn');
                    btn.data('name', response.data.name);
                    btn.data('status', response.data.status);
                    btn.data('sub_category', response.data.sub_category);
                    btn.data('sub_category_name', response.data.sub_category_name ?? '');
                } else {
                    alert(response.message ?? "Something went wrong!");
                }
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });
</script>

<?php $__env->stopSection(); ?>


<!-- Add Amenities Modal -->
<div class="modal fade" id="addAmenityModal" tabindex="-1" aria-labelledby="addAmenitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        
            <div class="modal-header">
                <h5 class="modal-title" id="addAmenitiesModalLabel">Add Amenity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form id="amenityForm">
                  <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="amenityName" class="form-label">Amenity Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="amenityName" placeholder="Enter amenity name" required>
                    </div>

                    <!-- <div class="mb-3">
                        <label for="amenityAmount" class="form-label">Amount ($)</label>
                        <input type="number" class="form-control" id="amenityAmount" placeholder="Enter amount">
                    </div> -->
                    <input type="hidden" name="status" value="1">
                    <input type="hidden" name="propertyid" value="<?php echo e($property->id); ?>">
                   <!--  <div class="mb-3">
                        <label for="amenityStatus" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control form-select" name="status" id="amenityStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div> -->
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" id="saveAmenity" form="amenitiesForm" class="btn btn-primary">Save</button>
            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="editAmenityModal" tabindex="-1" aria-labelledby="editAmenityLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAmenityLabel">Edit Amenity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editAmenityForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" id="editAmenityId">
            <input type="hidden" name="propertyid" id="propertyID" value="<?php echo e($property->id); ?>">

            <div class="mb-3">
                <label for="editAmenityName" class="form-label">Amenity Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editAmenityName" name="name" required>
            </div>


            <div class="mb-3">
                <label for="editAmenityStatus" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-control" id="editAmenityStatus" name="status" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateAmenity">Update</button>
      </div>
    </div>
  </div>
</div>




<!-- Add Utilities Modal -->
<div class="modal fade" id="addUtilitiesModal" tabindex="-1">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Utilities</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="UtilitiesForm">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label>Company Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Sub Category <span class="text-danger">*</span></label>
                <select name="sub_category" class="form-select" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="mb-3" id="subCategorySection" style="display:none;">
                <label>Sub Category Name <span class="text-danger">*</span></label>
                <div id="subCategoryWrapper">
                    <input type="text" name="sub_category_name[]" class="form-control mb-2">
                </div>
                <button type="button" id="addMoreSubCategory" class="btn btn-outline-success btn-sm">Add More</button>
            </div>

            <input type="hidden" name="status" value="1">
            <input type="hidden" name="propertyid" value="<?php echo e($property->id); ?>">

           <!--  <div class="mb-3">
                <label>Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div> -->

           <div class="text-end">
             <button type="submit" id="saveUtilities" class="btn btn-success">Save</button>
           </div>
        </form>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="editUtilitiesModal" tabindex="-1" aria-labelledby="editUtilitiesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUtilitiesLabel">Edit Utilities</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editUtilitiesForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" id="editUtilitiesId">
            <input type="hidden" name="propertyid" id="propertyID2" value="<?php echo e($property->id); ?>">

            <div class="mb-3">
                <label for="editUtilitiesName" class="form-label">Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editUtilitiesName" name="name" required>
            </div>

            <div class="mb-3">
                <label for="editUtilitiesSubCategory" class="form-label">Sub Category <span class="text-danger">*</span></label>
                <select name="sub_category" id="editUtilitiesSubCategory" class="form-select" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="editUtilitiesSubCategoryName" class="form-label">Sub Category Name <span class="text-danger">*</span></label>
                <input type="text" id="editUtilitiesSubCategoryName" name="sub_category_name" class="form-control">
            </div>

            <div class="mb-3">
                <label for="editUtilitiesStatus" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-control" id="editUtilitiesStatus" name="status" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateUtilities">Update</button>
      </div>
    </div>
  </div>
</div>




<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/whitesmoke-jackal-127066.hostingersite.com/public_html/resources/views/property/edit.blade.php ENDPATH**/ ?>