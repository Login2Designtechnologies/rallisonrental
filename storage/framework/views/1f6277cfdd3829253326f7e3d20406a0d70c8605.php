<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Details')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-class'); ?>
    product-detail-page
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('property.index')); ?>"><?php echo e(__('Property')); ?></a>
    </li>
    <li class="breadcrumb-item active">
        <a href="#"><?php echo e(__('Details')); ?></a>
    </li>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
<div class="card bg-custom border p-25">
   <!--  <div class="row">
        <div class="col-sm-12">
            <div class="">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">

                        </div> -->
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create property')): ?>
                            <!-- <div class="col-auto">
                                <a class="btn btn-secondary" href="<?php echo e(route('property.create')); ?>" data-size="md"> <i
                                        class="ti ti-circle-plus align-text-bottom "></i>
                                    <?php echo e(__('Add Unit')); ?></a>

                                <a href="#" class="btn btn-secondary btn-sm customModal" data-title="<?php echo e(__('Add Unit')); ?>"
                                    data-url="<?php echo e(route('unit.create', $property->id)); ?>" data-size="lg"> <i
                                        class="ti-plus mr-5"></i><?php echo e(__('Add Unit')); ?></a> --}}
                            </div> -->
                        <?php endif; ?>
                  <!--   </div>
                </div>
            </div>

        </div>
    </div> -->
    <div class="row property-page mt-3">
        <div class="col-sm-12">
            <div class="card bg-custom border">
                <div class="card-header pb-0">
                    <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab-1" data-bs-toggle="tab" href="#profile-1"
                                role="tab" aria-selected="true">
                                <i class="material-icons-two-tone me-2">meeting_room</i>
                                <?php echo e(__('Property Details')); ?>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab-2" data-bs-toggle="tab" href="#profile-2" role="tab"
                                aria-selected="true">
                                <i class="material-icons-two-tone me-2">ad_units</i>
                                <?php echo e(__('Property Units')); ?>

                            </a>
                        </li>

                         <li class="nav-item">
                            <a class="nav-link" id="profile-tab-3" data-bs-toggle="tab" href="#profile-3" role="tab"
                                aria-selected="true">
                                <i class="ti ti-tools me-2"></i>
                                <?php echo e(__('Amenities')); ?>

                            </a>
                        </li>

                         <li class="nav-item">
                            <a class="nav-link" id="profile-tab-4" data-bs-toggle="tab" href="#profile-4" role="tab"
                                aria-selected="true">
                                <i class="ti ti-bulb me-2"></i>
                                <?php echo e(__('Utilities')); ?>

                            </a>
                        </li>

                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-12 col-xxl-12">
                                            <div class="card box-card w-100">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <!-- <div class="col-md-5">
                                                            <div class="sticky-md-top product-sticky">
                                                                <div id="carouselExampleCaptions"
                                                                    class="carousel slide carousel-fade"
                                                                    data-bs-ride="carousel">
                                                                    <div class="carousel-inner">
                                                                        <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php
                                                                                $img = !empty($image->image)
                                                                                    ? $image->image
                                                                                    : 'default.jpg';
                                                                            ?>
                                                                            <div
                                                                                class="carousel-item <?php echo e($key === 0 ? 'active' : ''); ?>">
                                                                                <img src="<?php echo e(asset(Storage::url('upload/property') . '/' . $img)); ?>"
                                                                                    class="d-block w-100 rounded"
                                                                                    alt="Product image" />
                                                                            </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <ol
                                                                        class="carousel-indicators position-relative product-carousel-indicators my-sm-3 mx-0">
                                                                        <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php
                                                                                $img = !empty($image->image)
                                                                                    ? $image->image
                                                                                    : 'default.jpg';
                                                                            ?>
                                                                            <li data-bs-target="#carouselExampleCaptions"
                                                                                data-bs-slide-to="<?php echo e($key); ?>"
                                                                                class="<?php echo e($key === 0 ? 'active' : ''); ?> w-25 h-auto">
                                                                                <img src="<?php echo e(asset(Storage::url('upload/property') . '/' . $img)); ?>"
                                                                                    class="d-block wid-50 rounded"
                                                                                    alt="Product image" />
                                                                            </li>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </ol>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        <div class="col-md-5">
                                                            <div class="sticky-md-top product-sticky">
                                                                <?php if(!empty($property->propertyImages) && count($property->propertyImages) > 0): ?>
                                                                    <div id="carouselExampleCaptions"
                                                                        class="carousel slide carousel-fade"
                                                                        data-bs-ride="carousel">
                                                                        <div class="carousel-inner">
                                                                            <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php
                                                                                    $img = !empty($image->image) ? $image->image : 'default.jpg';
                                                                                ?>
                                                                                <div class="carousel-item <?php echo e($key === 0 ? 'active' : ''); ?>">
                                                                                    <img src="<?php echo e(asset(Storage::url('upload/property') . '/' . $img)); ?>"
                                                                                        class="d-block w-100 rounded"
                                                                                        alt="Product image" />
                                                                                </div>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </div>
                                                                        <ol class="carousel-indicators position-relative product-carousel-indicators my-sm-3 mx-0">
                                                                            <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php
                                                                                    $img = !empty($image->image) ? $image->image : 'default.jpg';
                                                                                ?>
                                                                                <li data-bs-target="#carouselExampleCaptions"
                                                                                    data-bs-slide-to="<?php echo e($key); ?>"
                                                                                    class="<?php echo e($key === 0 ? 'active' : ''); ?> w-25 h-auto">
                                                                                    <img src="<?php echo e(asset(Storage::url('upload/property') . '/' . $img)); ?>"
                                                                                        class="d-block wid-50 rounded"
                                                                                        alt="Product image" />
                                                                                </li>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </ol>
                                                                    </div>
                                                                <?php else: ?>
                                                                    
                                                                    <div class="text-center">
                                                                        <img src="<?php echo e(asset('assets/images/authentication/logo.png')); ?>" 
                                                                            alt="Default Logo" 
                                                                            class="logo logo-lg" />
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-7">

                                                            <h3 class="">
                                                                <?php echo e(ucfirst($property->name)); ?>


                                                            </h3>
                                                            <span class="badge bg-primary f-14 mt-1"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="<?php echo e(__('Type')); ?>"><?php echo e(\App\Models\Property::$Type[$property->type]); ?></span>
                                                            <h5 class="mt-4 "><?php echo e(__('Property Details')); ?></h5>
                                                            <hr class="my-3" />
                                                            <p class="">
                                                                <?php echo e($property->description); ?>

                                                            </p>

                                                            <h5 class=""><?php echo e(__('Property Address')); ?></h5>
                                                            <hr class="my-3" />
                                                            <div class="mb-1 row">
                                                                <label
                                                                    class="col-form-label col-lg-3 col-sm-12 text-lg-end ">
                                                                    <?php echo e(__('Address')); ?> :

                                                                </label>
                                                                <div
                                                                    class="col-lg-6 col-md-12 col-sm-12 d-flex align-items-center ">
                                                                    <?php echo e($property->address); ?>

                                                                </div>
                                                            </div>
                                                            <div class="mb-1 row">
                                                                <label
                                                                    class="col-form-label col-lg-3 col-sm-12 text-lg-end ">
                                                                    <?php echo e(__('Location')); ?> :

                                                                </label>
                                                                <div
                                                                    class="col-lg-6 col-md-12 col-sm-12 d-flex align-items-center ">
                                                                    <?php echo e($citiesview->name . ', ' . $statesdataview->name . ', ' . $property->country); ?>

                                                                </div>
                                                            </div>
                                                            <div class="mb-1 row">
                                                                <label
                                                                    class="col-form-label col-lg-3 col-sm-12 text-lg-end ">
                                                                    <?php echo e(__('Zip Code')); ?> :

                                                                </label>
                                                                <div
                                                                    class="col-lg-6 col-md-12 col-sm-12 d-flex align-items-center ">
                                                                    <?php echo e($property->zip_code); ?>

                                                                </div>
                                                            </div>

                                                            <hr class="my-3" />

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                            <div class="row">

                            <?php if($units->isNotEmpty()): ?>

                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-xxl-3 col-xl-4 col-md-6">
                                        <div class="card follower-card">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="flex-grow-1 ">
                                                        <h2 class="mb-1 text-truncate"><?php echo e(ucfirst($unit->name)); ?></h2>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <div class="dropdown">
                                                            <a class="dropdown-toggle text-primary opacity-50 arrow-none"
                                                                href="#" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                <i class="ti ti-dots f-16"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">

                                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit unit')): ?>
                                                                    <a class="dropdown-item customModal" href="#"
                                                                        data-url="<?php echo e(route('unit.edit', [$property->id, $unit->id])); ?>"
                                                                        data-title="<?php echo e(__('Edit Unit')); ?>" data-size="lg">
                                                                        <i
                                                                            class="material-icons-two-tone">edit</i><?php echo e(__('Edit Unit')); ?></a>
                                                                <?php endif; ?>

                                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete unit')): ?>
                                                                    <?php echo Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['unit.destroy', $property->id, $unit->id],
                                                                        'id' => 'unit-' . $unit->id,
                                                                    ]); ?>


                                                                    <a class="dropdown-item confirm_dialog" href="#">
                                                                        <i class="material-icons-two-tone">delete</i>
                                                                        <?php echo e(__('Delete Unit')); ?>


                                                                    </a>
                                                                    <?php echo Form::close(); ?>

                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <hr class="my-3" /> -->


                                                

                                                <hr class="my-2" />
                                                <p class="my-3  text-sm">
                                                    <?php echo e($unit->notes); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="col-sm-12 text-center">
                                    <p style="color:white;">No Data Available</p>
                                </div>
                            <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="tab-pane " id="profile-3" role="tabpanel" aria-labelledby="profile-tab-3">
                            <div class="w-100">
                                <div class="row align-items-center g-2">
                                        <div class="col">
                                            <h5>Amenities List</h5>
                                        </div>
                                    </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 custom-bg-table">
                                        <thead class="table-theme">
                                            <tr>
                                                <th>#</th>
                                                <th>Amenity Name</th>
                                                <!-- <th>Price</th> -->
                                                <th>Status</th>
                                                <!-- <th class="text-center">Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                                <td><?php echo e($amenity->name); ?></td>
                                                <!-- <td>$150</td> -->
                                                <td><?php echo e($amenity->status == 1 ? 'Active' : 'Inactive'); ?></td>
                                                
                                                    <!--  <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button> -->
                                                <!-- </td> -->
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="profile-4" role="tabpanel" aria-labelledby="profile-tab-4">
                           <div class="w-100">
                                <div class="row align-items-center g-2">
                                        <div class="col">
                                            <h5>Utilities List</h5>
                                        </div>
                                    </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 custom-bg-table">
                                        <thead class="table-theme">
                                            <tr>
                                                <th>#</th>
                                                <th>Company Name</th>
                                                <th>Sub Category</th>
                                                <th>Sub Category Name</th>
                                                <!-- <th>Price</th> -->
                                                <th>Status</th>
                                                <!-- <th class="text-center">Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php $__currentLoopData = $utilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $utilitval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                                <td><?php echo e($utilitval->name); ?></td>
                                                <td><?php echo e($utilitval->sub_category == 1 ? 'Yes' : 'No'); ?></td>
                                                <td><?php echo e($utilitval->sub_category_name ?? ''); ?></td>
                                                <!-- <td>$75</td> -->
                                                <td><?php echo e($utilitval->status == 1 ? 'Active' : 'Inactive'); ?></td>
                                                
                                                    <!--  <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button> -->
                                                <!-- </td> -->
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
</div>
<?php $__env->stopSection(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const subcategorySelect = document.getElementById('subcategorySelect');
    const subcategoryFields = document.getElementById('subcategoryFields');
    const addMoreBtn = document.getElementById('addMoreBtn');
    const subcategoryList = document.getElementById('subcategoryList');
    const saveBtn = document.getElementById('saveBtn');

    // Show/hide subcategory section
    subcategorySelect.addEventListener('change', () => {
        if (subcategorySelect.value === 'Yes') {
            subcategoryFields.classList.remove('d-none');
        } else {
            subcategoryFields.classList.add('d-none');
        }
    });

    // Add more input fields
    addMoreBtn.addEventListener('click', () => {
        const newField = document.createElement('div');
        newField.classList.add('input-group', 'mb-2');
        newField.innerHTML = `
            <input type="text" name="subcategories[]" class="form-control" placeholder="Enter Sub Category">
            <button class="btn btn-outline-danger remove-btn" type="button"><i class="bi bi-x"></i></button>
        `;
        subcategoryList.appendChild(newField);

        // Remove field on click
        newField.querySelector('.remove-btn').addEventListener('click', () => {
            newField.remove();
        });
    });

    // Save button click handler
    saveBtn.addEventListener('click', () => {
        alert('Form saved successfully!');
    });
</script>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u527856812/domains/dodgerblue-lapwing-476569.hostingersite.com/public_html/resources/views/property/show.blade.php ENDPATH**/ ?>