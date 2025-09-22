@extends('layouts.app')
@section('page-title')
    {{ __('Property Create') }}
@endsection

<style>
    ul#myTab {
        pointer-events: none;
    }
</style>

@push('script-page')
    <script src="{{ asset('assets/js/vendors/dropzone/dropzone.js') }}"></script>
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
        $('#property-submit').on('click', function() {
            "use strict";
            $('#property-submit').attr('disabled', true);
            var fd = new FormData();
            var file = document.getElementById('thumbnail').files[0];

            var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('property_images[' + key + ']', $('#demo-upload')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });
            fd.append('thumbnail', file);
            var other_data = $('#property_form').serializeArray();
            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });
            $.ajax({
                url: "{{ route('property.store') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    if (data.status == "success") {
                        $('#property-submit').attr('disabled', true);
                        toastrs(data.status, data.msg, data.status);
                        var url = '{{ route('property.show', ':id') }}';
                        url = url.replace(':id', data.id);
                        setTimeout(() => {
                            window.location.href = url;
                        }, "1000");

                    } else {
                        toastrs('Error', data.msg, 'error');
                        $('#property-submit').attr('disabled', false);
                    }
                },
                error: function(data) {
                    $('#property-submit').attr('disabled', false);
                    if (data.error) {
                        toastrs('Error', data.error, 'error');
                    } else {
                        toastrs('Error', data, 'error');
                    }
                },
            });
        });



    </script>

    <script>
        $('#rent_type').on('change', function() {
            "use strict";
            var type = this.value;
            $('.rent_type').addClass('d-none')
            $('.' + type).removeClass('d-none')

            var input1 = $('.rent_type').find('input');
            input1.prop('disabled', true);
            var input2 = $('.' + type).find('input');
            input2.prop('disabled', false);
        });
    </script>

    <script>
      

        $(document).ready(function() {

        function checkTabFields($tab) {
            let allFilled = true;
            $tab.find('.required-field').each(function() {
                if (!$(this).val() || $(this).val().trim() === '') {
                    allFilled = false;
                    return false; // break loop
                }
            });
            return allFilled;
        }

        function updateNextButton() {
            let $activeTab = $('.tab-content .tab-pane.active');
            let allFilled = checkTabFields($activeTab);
            $('.nextButton').prop('disabled', !allFilled);
        }

        // Run on input/select change
        $(document).on('input change', '.required-field', function () {
            updateNextButton();
        });

        // Initial check
        updateNextButton();

        // Next button click
        $('.nextButton').on('click', function() {
            let $activeTab = $('.tab-content .tab-pane.active');

            // Validate current tab
            if (!checkTabFields($activeTab)) {
                // alert('Please fill all required fields in this tab!');
                return false;
            }

            let $nextTab = $activeTab.next('.tab-pane');

            if ($nextTab.length > 0) {
                let nextTabId = $nextTab.attr('id');
                $('a[href="#' + nextTabId + '"]').tab('show');

                // If next tab is last, change button text to Submit
                if ($nextTab.is(':last-child')) {
                    $(this).text('Submit').addClass('submit-button');
                }

                // Update button enable/disable
                updateNextButton();
            } else if ($(this).hasClass('submit-button')) {
                // Validate last tab before submitting
                if (!checkTabFields($activeTab)) {
                    // alert('Please fill all required fields in this tab!');
                    return false;
                }
                $('form').submit();
            }
        });

        // Update button text on manual tab switch
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
            let $activeTab = $('.tab-content .tab-pane.active');
            let isLastTab = $activeTab.is(':last-child');
            if (!isLastTab) {
                $('.nextButton').text('Next').removeClass('submit-button');
            }
            updateNextButton();
        });
    });

    </script>



<script>

    $(document).ready(function() {
        let firstClick = true;

        $(document).on('click', '.add-unit', function() {
            if(firstClick) {
                // पहला क्लिक: hidden वाला form दिखाओ
                $('.unit_list:first').removeClass('d-none');
                $('hr').removeClass('d-none');
                firstClick = false;
            } else {
                // बाद में क्लिक: clone करो
                let originalRow = $('.unit_list:first');
                let clonedRow = originalRow.clone();

                clonedRow.find('input, select, textarea').val('');
                $('.unit_list_results').append(clonedRow).append('<hr class="mt-2 mb-4 border-dark">');
            }
        });

        // Remove करने का logic अगर चाहिए तो
        $(document).on('click', '.remove-unit', function() {
            $(this).closest('.unit_list').next('hr').remove();
            $(this).closest('.unit_list').remove();
        });
    });

    // $(document).ready(function() {
    //     let firstClick = true;

    //     $(document).on('click', '.add-unit', function() {
    //         if(firstClick) {
    //             // पहला क्लिक: hidden वाला form दिखाओ
    //             $('.unit_list:first').removeClass('d-none');
    //             $('hr').removeClass('d-none');
    //             firstClick = false;
    //         } else {
    //             // बाद में क्लिक: clone करो
    //             let originalRow = $('.unit_list:first');
    //             let clonedRow = originalRow.clone();

    //             clonedRow.find('input, select, textarea').val('');
    //             $('.unit_list_results').append(clonedRow).append('<hr class="mt-2 mb-4 border-dark">');
    //         }
    //     });

    //     // Remove करने का logic अगर चाहिए तो
    //     $(document).on('click', '.remove-service', function() {
    //         $(this).closest('.unit_list').next('hr').remove();
    //         $(this).closest('.unit_list').remove();
    //     });
    // });
</script>


@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">
        <a href="{{ route('property.index') }}">{{ __('Property') }}</a>
    </li>
    <li class="breadcrumb-item active"><a href="#">{{ __('Create') }}</a>
    </li>
@endsection

@section('content')
    {{ Form::open(['url' => 'property', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'property_form']) }}
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card-header pb-0">
                <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab-1" data-bs-toggle="tab" href="#profile-1"
                            role="tab" aria-selected="true">
                            <i class="material-icons-two-tone me-2">info</i>
                            {{ __('Property Details') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab-2" data-bs-toggle="tab" href="#profile-2" role="tab"
                            aria-selected="true">
                            <i class="material-icons-two-tone me-2">image</i>
                            {{ __('Property Images') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab-3" data-bs-toggle="tab" href="#profile-3" role="tab"
                            aria-selected="true">
                            <i class="material-icons-two-tone me-2">layers</i>
                            {{ __('Unit') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab-4" data-bs-toggle="tab" href="#profile-4" role="tab"
                            aria-selected="true">
                            <i class="material-icons-two-tone ti ti-tools me-2"></i>
                            {{ __('Amenities') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab-5" data-bs-toggle="tab" href="#profile-5" role="tab"
                            aria-selected="true">
                            <i class="material-icons-two-tone ti ti-bulb me-2"></i>
                            {{ __('Utilities') }}
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card-body w-100">
                <div class="tab-content">
                    <div class="tab-pane  show active" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card border bg-custom bg-white">
                                    <div class="card-header">
                                        <h5> {{ __('Add Property Details') }}</h5>
                                    </div>
                                    <div class="card-body w-100">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <div class="form-group ">
                                                        {{ Form::label('type', __('Type'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                                        {{ Form::select('type', ['' => 'Select'] + $types, $property->type ?? null, ['class' => 'form-control basic-select required-field', 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                            </div>
                                    
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                                        {{ Form::text('name', null, ['class' => 'form-control required-field', 'placeholder' => __('Enter Property Name'), 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        {{ Form::label('thumbnail', __('Thumbnail Image'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                                        {{ Form::file('thumbnail', ['class' => 'form-control required-field', 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <div class="form-group ">
                                                        {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                                                        {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 1, 'placeholder' => __('Enter Property Description')]) }}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <div class="form-group ">
                                                        {{ Form::label('address', __('Address'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                                        {{ Form::textarea('address', null, ['class' => 'form-control required-field', 'rows' => 1, 'placeholder' => __('Enter Property Address'), 'required' => 'required']) }}
                                                    </div>

                                                </div>
                                            </div>
                                            
                                            {{ Form::hidden('country', $unit->country ?? 'USA') }}

                                    

                                                {{-- State --}}
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <div class="form-group">
                                                            {{ Form::label('state', __('State'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                                            {{ Form::select('state', 
                                                                $statesdata->pluck('name','id')->toArray(),  // value=id, text=name
                                                                $unit->state ?? null, 
                                                                ['class' => 'form-control basic-select required-field', 'id'=>'company_state', 'required' => 'required', 'placeholder' => __('Select')]
                                                            ) }}
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- City --}}

                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                    <div class="form-group">
                                                    <label for="company_city" class="form-label">City <span class="text-danger">*</span></label>
                                                    <select name="city" id="company_city" class="form-control required-field" required>
                                                        <option value="" style="background-color: black;">Select</option>
                                                    </select>
                                                </div>
                                                </div>
                                                </div>
                                            

                                        
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        {{ Form::label('zip_code', __('Zip Code'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                                        {{ Form::text('zip_code', null, ['class' => 'form-control required-field', 'placeholder' => __('Enter Property Zip Code'), 'required' => 'required']) }}
                                                    </div>

                                                </div>
                                            </div>
                                            

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary btn-rounded nextButton"
                                data-next-tab="#profile-2">
                                {{ __('Next') }}
                            </button>
                        </div>
                    </div>
                    <div class="tab-pane" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card border bg-custom bg-white">
                                    <div class="card-header">
                                        {{ Form::label('demo-upload', __('Add Property Images'), ['class' => 'form-label']) }}
                                    </div>
                                    <div class="card-body w-100">
                                        <div class="row">
                                            <div class="dropzone needsclick" id='demo-upload' action="#">
                                                <div class="dz-message needsclick">
                                                    <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                                    <h3 class="mb-0">{{ __('Drop files here or click to upload.') }}</h3>
                                                </div>
                                            </div>
                                            <div class="preview-dropzon" style="display: none;">
                                                <div class="dz-preview dz-file-preview">
                                                    <div class="dz-image"><img data-dz-thumbnail="" src=""
                                                            alt=""></div>
                                                    <div class="dz-details">
                                                        <div class="dz-size"><span data-dz-size=""></span></div>
                                                        <div class="dz-filename"><span data-dz-name=""></span></div>
                                                    </div>
                                                    <div class="dz-progress"><span class="dz-upload"
                                                            data-dz-uploadprogress=""> </span></div>
                                                    <div class="dz-success-mark"><i class="fa fa-check"
                                                            aria-hidden="true"></i></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary btn-rounded nextButton nextButton22"
                                data-next-tab="#profile-2" disabled>
                                {{ __('Next') }}
                            </button>

                        </div>
                    </div>
                    <div class="tab-pane" id="profile-3" role="tabpanel"
                        aria-labelledby="profile-tab-3">
                        <div class="card border bg-custom bg-white">
                            <div class="card-body w-100">
                                <div class="row unit_list d-none">
                                        <div class="form-group col-md-6">
                                            {{ Form::label('unitname', __('Name'), ['class' => 'form-label']) }}
                                            {{ Form::text('unitname[]', null, ['class' => 'form-control', 'placeholder' => __('Enter unit name')]) }}
                                        </div>

                                        <div class="form-group col-md-6">
                                            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                            {{ Form::select('status[]', [
                                                '1' => 'Active',
                                                '0' => 'Inactive'
                                            ], null, ['class' => 'form-control', 'placeholder' => __('Select Status')]) }}
                                        </div>

                                        
                                        <div class="form-group col-md-12">
                                            {{ Form::label('notes', __('Description'), ['class' => 'form-label']) }}
                                            {{ Form::textarea('notes[]', null, ['class' => 'form-control', 'rows' => 1, 'placeholder' => __('Enter notes')]) }}
                                        </div>
                                     <!-- Remove button -->
                                    <div class="col-md-12 text-end mt-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-unit">{{ __('Remove') }}</button>
                                    </div>
                                </div>

                                <hr class="mt-2 mb-4 border border-secondary">
                                <!-- <div class="unit_list_results"></div> -->

                                <div class="col-lg-12 mb-2  text-end">
                                    <button type="button" class="btn btn-secondary btn-md add-unit ">
                                        {{ __('Add Unit') }}
                                    </button>
                                </div>

                            </div>
                        </div>


            
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary btn-rounded nextButton"
                                data-next-tab="#profile-4">
                                {{ __('Next') }}
                            </button>
                        </div>

                    </div>

                    <div class="tab-pane" id="profile-4" role="tabpanel"
                        aria-labelledby="profile-tab-4">
                        <div class="card border bg-custom bg-white">
                            <div class="card-body w-100">
                                <div class="row">
                                        <div class="form-group col-md-12">
                                        <label class="form-label">Do we have amenities in the property to billed out ?
                                        </label>
                                        <div>
                                            <label class="me-3">
                                                {{ Form::radio('is_billed', 'yes', false, ['class' => 'form-check-input is-billed']) }} Yes
                                            </label>
                                            <label class="">
                                                {{ Form::radio('is_billed', 'no', true, ['class' => 'form-check-input is-billed']) }} No
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="isbilleddata" role="tabpanel" aria-labelledby="isbilleddata" style="display: none;">
                                    <div class="card border bg-custom bg-white w-100">
                                        <div class="">
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
                                        <div class="new-table mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0" id="amenitiesTable">
                                                    <thead class="table-theme">
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">Amenity Name</th>
                                                            <th class="text-center">Cost</th>
                                                            <th class="text-center">Status</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($amenities as $index => $amenity)
                                                            <tr id="row-{{ $amenity->id }}">
                                                                <td class="text-center">{{ $index + 1 }}</td>
                                                                <td class="text-center">{{ $amenity->name }}</td>
                                                                <td class="text-center">$</td>
                                                                <td class="text-center">
                                                                    {{ $amenity->status == 1 ? 'Active' : 'Inactive' }}
                                                                </td>
                                                                <td class="text-center">
                                            <button class="btn btn-sm btn-warning editAmenityBtn" 
                                                data-id="{{ $amenity->id }}" 
                                                data-name="{{ $amenity->name }}"
                                                data-price="{{ $amenity->price }}" 
                                                data-status="{{ $amenity->status }}">
                                            <i class="ti ti-edit"></i>
                                        </button>

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>



                            <div class="text-end">
                            <button type="button" class="btn btn-secondary btn-rounded nextButton"
                                data-next-tab="#profile-5">
                                {{ __('Next') }}
                            </button>
                        </div>

                    </div>
                    



                    <div class="tab-pane" id="profile-5" role="tabpanel"
                        aria-labelledby="profile-tab-5">
                        <div class="card border bg-custom bg-white">
                            <div class="card-body w-100">
                                <div class="row">
                                        <div class="form-group col-md-12">
                                        <label class="form-label">Do we have Utilities in the property to billed out ?
                                        </label>
                                        <div>
                                            <label class="me-3">
                                                {{ Form::radio('utilities', 'yes','false', false, ['class' => 'form-check-input']) }} Yes
                                            </label>
                                            <label class="">
                                                {{ Form::radio('utilities', 'no','true', false, ['class' => 'form-check-input']) }} No
                                            </label>
                                        </div>
                                    </div>
                                </div>

                        <div class="tab-pane" id="utilitiesdata" role="tabpanel" aria-labelledby="utilitiesdata" style="display: none;">
                            <div class="w-100">
                                <div class="">
                                    <div class="row align-items-center g-2">
                                        <div class="col">
                                            <h5 class="mb-0">Utilities List</h5>
                                        </div>
                                        <div class="col-auto">
                                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addUtilitiesModal">
                                                <i class="ti ti-circle-plus align-text-bottom"></i> Add New
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="new-table mt-3">
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
                                                    @foreach($utilities as $index => $utilitval)
                                                        <tr id="row2-{{ $utilitval->id }}">
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td>{{ $utilitval->name }}</td>
                                                            <td>{{ $utilitval->sub_category == 1 ? 'Yes' : 'No' }}</td>
                                                            <td>{{ $utilitval->sub_category_name ?? '' }}</td>
                                                            <td>{{ $utilitval->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                            <td class="text-center">
                                                                <button class="btn btn-sm  btn-warning editUtilitiesBtn" type="button" 
                                                                    data-id="{{ $utilitval->id }}"
                                                                    data-name="{{ $utilitval->name }}"
                                                                    data-status="{{ $utilitval->status }}"
                                                                    data-sub_category="{{ $utilitval->sub_category }}"
                                                                    data-sub_category_name="{{ $utilitval->sub_category_name ?? '' }}">
                                                                    <i class="ti ti-edit"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    </div>
                                </div>

                            </div>

                            </div>
                        </div>


                        <div class="col-lg-12 mb-2">
                            <div class="group-button text-end">
                                {{ Form::submit(__('Create'), ['class' => 'btn btn-secondary btn-rounded nextButton', 'id' => 'property-submit']) }}
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
            
    </div>
    {{ Form::close() }}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                            $('#company_city').append('<option value="'+ city.id +'"">'+ city.name +'</option>');
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
        var selectedState = "{{ $unit->state ?? '' }}";
        var selectedCity = "{{ $unit->city ?? '' }}";

        if (selectedState) {
            $('#company_state').val(selectedState);
            loadCities(selectedState, selectedCity);
        }
    });
</script>


<script>
    $(document).ready(function () {
        // ✅ Default check (अगर Yes पहले से selected है तो div दिखेगा)
        if ($('input[name="is_billed"]:checked').val() === 'yes') {
            $('#isbilleddata').show();
        }

        // ✅ Radio change event
        $(document).on('change', 'input[name="is_billed"]', function () {
            if ($(this).val() === 'yes') {
                $('#isbilleddata').show();
            } else {
                $('#isbilleddata').hide();
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        // ✅ Default check (अगर Yes पहले से selected है तो div दिखेगा)
        if ($('input[name="utilities"]:checked').val() === 'yes') {
            $('#utilitiesdata').show();
        }

        // ✅ Radio change event
        $(document).on('change', 'input[name="utilities"]', function () {
            if ($(this).val() === 'yes') {
                $('#utilitiesdata').show();
            } else {
                $('#utilitiesdata').hide();
            }
        });
    });
</script>

<script>
    $(document).on('click', '#saveUtilities', function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ url('property-utilities-store') }}",
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
            url: "{{ url('property-amenities-store') }}", 
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
                            <td class="text-center">${response.data.price}</td>
                            <td class="text-center">${statusText}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning editAmenityBtn" 
                                    data-id="${response.data.id}" 
                                    data-name="${response.data.name}" 
                                    data-price="${response.data.price}" 
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
        let price = $(this).data('price');
        let status = $(this).data('status');

        // Modal fill
        $('#editAmenityId').val(id);
        $('#editAmenityName').val(name);
        $('#amenitydataAmount').val(price);
        $('#editAmenityStatus').val(status);

        $('#editAmenityModal').modal('show');
    });

    $(document).on('click', '#updateAmenity', function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ url('property-amenities-update') }}",
            type: "POST",
            data: $('#editAmenityForm').serialize(),
            success: function (response) {
                if (response.success) {
                    $('#editAmenityModal').modal('hide');
                    alert(response.message);

                    // ✅ Update row in table
                    let row = $('#row-' + response.data.id);
                    row.find('td:eq(1)').text(response.data.name); // Amenity Name
                    row.find('td:eq(2)').text(response.data.price); // Amenity price
                    row.find('td:eq(3)').text(response.data.status == 1 ? 'Active' : 'Inactive'); // Status

                    // ✅ Update button attributes
                    row.find('.editAmenityBtn').data('name', response.data.name);
                    row.find('.editAmenityBtn').data('price', response.data.price);
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
    $(document).on('click', '.editUtilitiesBtn', function () {
        let data = $(this).data();

        $('#editUtilitiesId').val(data.id);
        $('#editUtilitiesName').val(data.name);
        $('#editUtilitiesStatus').val(data.status);
        $('#editUtilitiesSubCategory').val(data.sub_category);
        $('#editUtilitiesSubCategoryName').val(data.sub_category_name);

        $('#editUtilitiesModal').modal('show');
    });

    $(document).on('click', '#updateUtilities', function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ url('property-Utilities-update') }}",
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




@endsection


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
                  @csrf
                    <div class="mb-3">
                        <label for="amenityName" class="form-label">Amenity Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="amenityName" placeholder="Enter amenity name" required>
                    </div>

                    <div class="mb-3">
                        <label for="amenityAmount" class="form-label">Cost ($) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amenityAmount" placeholder="Enter amount" name="price" required>
                    </div>

                    <div class="mb-3">
                        <label for="amenityStatus" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control form-select" name="status" id="amenityStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
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
            @csrf
            <input type="hidden" name="id" id="editAmenityId">

            <div class="mb-3">
                <label for="editAmenityName" class="form-label">Amenity Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editAmenityName" name="name" required>
            </div>

            <div class="mb-3">
                <label for="amenityAmount" class="form-label">Cost ($) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="amenitydataAmount" placeholder="Enter amount" name="price" required>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Utilities</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="UtilitiesForm">
            @csrf
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

            <div class="mb-3">
                <label>Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

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
            @csrf
            <input type="hidden" name="id" id="editUtilitiesId">

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


