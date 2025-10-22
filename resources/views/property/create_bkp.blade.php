@extends('layouts.app')
@section('page-title')
    {{ isset($property) && $property->id ? __('Edit Property') : __('Create Property') }}
@endsection

<style>
    ul#myTab {
        pointer-events: none;
    }
	.step-status {
        margin-left: 6px;
    }
    .step-status.done::before {
        content: "✔";  /* green tick */
        color: #28a745;
        font-weight: bold;font-size: 20px;
    }
    .step-status.done{position: absolute;
  right: 10px;}
  .row.position-relative.mb-2.input-row ~ .row.position-relative.mb-2.input-row {
  border-top: 1px solid #ddd;
  padding-top: 15px;
}
.remove-row {
  position: absolute;
  top: 5px;
  cursor: pointer;
  color: #ff0018;
  font-size: 1.3rem;
  right: 0;
  display: flex;
  justify-content: end;
}
</style>

@push('script-page')
    <script src="{{ asset('assets/js/vendors/dropzone/dropzone.js') }}"></script>
    <script>
        $(document).ready(function () {
            "use strict";

            var dropzone = new Dropzone('#demo-upload', {
                previewTemplate: document.querySelector('.preview-dropzon').innerHTML,
                parallelUploads: 10,
                thumbnailHeight: 120,
                thumbnailWidth: 120,
                maxFilesize: 10,
                filesizeBase: 1000,
                autoProcessQueue: false,
                thumbnail: function (file, dataUrl) {
                    if (file.previewElement) {
                        file.previewElement.classList.remove("dz-file-preview");
                        var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                        for (var i = 0; i < images.length; i++) {
                            var thumbnailElement = images[i];
                            thumbnailElement.alt = file.name;
                            thumbnailElement.src = dataUrl;
                        }
                        setTimeout(function () {
                            file.previewElement.classList.add("dz-image-preview");
                        }, 1);
                    }
                }
            });

            $('#property-submit').on('click', function (e) {
                e.preventDefault();
                $('#property-submit').attr('disabled', true);

                const formMode = $('#form_mode').val();
                let fd = new FormData();

                // ✅ Thumbnail (Cropped or Original)
                var croppedImage = $('#croppedImage').val();
                if (croppedImage) {
                    var byteString = atob(croppedImage.split(',')[1]);
                    var mimeString = croppedImage.split(',')[0].split(':')[1].split(';')[0];
                    var ab = new ArrayBuffer(byteString.length);
                    var ia = new Uint8Array(ab);
                    for (var i = 0; i < byteString.length; i++) ia[i] = byteString.charCodeAt(i);
                    var blob = new Blob([ab], { type: mimeString });
                    fd.append('thumbnail', blob, 'thumbnail.jpg');
                } else {
                    var fileInput = document.getElementById('thumbnailInput');
                    if (fileInput && fileInput.files.length > 0) {
                        fd.append('thumbnail', fileInput.files[0]);
                    }
                }

                // ✅ Dropzone Files
                var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
                $.each(files, function (key, file) {
                    fd.append('property_images[' + key + ']', file);
                });

                // ✅ Serialize all form fields (includes costs)
                var other_data = $('#property_form').serializeArray();
                $.each(other_data, function (key, input) {
                    fd.append(input.name, input.value);
                });

                const amenities = [];
                $('#amenitiesTable tbody tr').each(function () {
                    amenities.push({
                        name: $(this).find('td:nth-child(2)').text().trim(),
                        price: $(this).find('td:nth-child(3)').text().replace('$', '').trim(),
                        status: $(this).find('td:nth-child(4)').text().trim() === 'Active' ? 1 : 0
                    });
                });
                fd.append('amenities', JSON.stringify(amenities));

                const utilities = [];
                $('#UtilitiesTable tbody tr').each(function () {
                    utilities.push({
                        name: $(this).find('td:nth-child(2)').text().trim(),
                        sub_category: $(this).find('td:nth-child(3)').text().trim() === 'Yes' ? 1 : 0,
                        sub_category_names: $(this).find('td:nth-child(4)').text().trim(),
                        status: $(this).find('td:nth-child(5)').text().trim() === 'Active' ? 1 : 0
                    });
                });
                fd.append('utilities', JSON.stringify(utilities));

                // ✅ Detect correct URL and method
                let ajaxUrl = '';
                let ajaxType = '';

                if (formMode === 'edit') {
                    ajaxUrl = "{{ isset($property) ? route('property.update', $property->id) : '' }}";
                    ajaxType = 'POST'; // Laravel needs POST with _method=PUT
                    fd.append('_method', 'PUT');
                } else {
                    ajaxUrl = "{{ route('property.store') }}";
                    ajaxType = 'POST';
                }

                // ✅ AJAX call
                $.ajax({
                    url: ajaxUrl,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: fd,
                    contentType: false,
                    processData: false,
                    type: ajaxType,
                    success: function (data) {
                        if (data.status === "success") {
                            toastrs(data.status, data.msg, data.status);

                            // ✅ Redirect to property show
                            const url = '{{ route('property.index', ':id') }}'.replace(':id', data.id);
                            setTimeout(() => window.location.href = url, 1000);
                        } else {
                            toastrs('Error', data.msg, 'error');
                            $('#property-submit').attr('disabled', false);
                        }
                    },
                    error: function (data) {
                        $('#property-submit').attr('disabled', false);
                        toastrs('Error', data.responseText || 'Something went wrong', 'error');
                    }
                });
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
            const formMode = $('#form_mode').val(); // 'create' or 'edit'

            $(window).on('load', function () {
                const $address = $('textarea[name="address"]');
                const addressVal = $address.val();
                if (addressVal && addressVal.trim() !== '') {
                    // Simulate typing once full DOM is ready and all form values are bound
                    $address.trigger('input');
                }
                updateNextButton();
            });

            // Check all required fields inside the active tab
            function checkTabFields($tab) {
                let allFilled = true;
                $tab.find('.required-field').each(function() {
                    const val = $(this).val();
                    // For file input (e.g. thumbnail)
                    if ($(this).attr('type') === 'file') {
                        const hasExistingThumb = $('#existing_thumbnail').length > 0;
                        if (!val && !hasExistingThumb) {
                            allFilled = false;
                            return false;
                        }
                    } else if (!val || val.trim() === '') {
                        allFilled = false;
                        return false;
                    }
                });
                return allFilled;
            }

            // Enable or disable Next button
            function updateNextButton() {
                const $activeTab = $('.tab-content .tab-pane.active');
                const allFilled = checkTabFields($activeTab);
                $('.nextButton').prop('disabled', !allFilled);

                // Remove green tick if tab becomes incomplete again
                let currentTabId = $activeTab.attr('id');
                if (!allFilled) {
                    $('a[href="#' + currentTabId + '"] .step-status')
                        .html('')
                        .removeClass('done');
                }
            }

            // On field change or input
            $(document).on('input change', '.required-field', function() {
                updateNextButton();
            });

            // Next button logic
            $(document).on('click', '.nextButton', function() {
                const $activeTab = $('.tab-content .tab-pane.active');
                if (!checkTabFields($activeTab)) return false;

                // ✅ Mark current tab as complete
                let currentTabId = $activeTab.attr('id');
                $('a[href="#' + currentTabId + '"]').find('.step-status').addClass('done');

                const $nextTab = $activeTab.next('.tab-pane');
                if ($nextTab.length > 0) {
                    const nextTabId = $nextTab.attr('id');
                    $('a[href="#' + nextTabId + '"]').tab('show');

                    if ($nextTab.is(':last-child')) {
                        const submitText = formMode === 'edit' ? 'Update' : 'Finish';
                        $(this).text(submitText).addClass('submit-button');
                    }
                    updateNextButton();
                } else if ($(this).hasClass('submit-button')) {
                    if (!checkTabFields($activeTab)) return false;
                    $('form').submit();
                }
            });

            // Back button
            $(document).on('click', '.prevButton', function() {
                const $activeTab = $('.tab-content .tab-pane.active');
                const $prevTab = $activeTab.prev('.tab-pane');
                if ($prevTab.length > 0) {
                    const prevTabId = $prevTab.attr('id');
                    $('a[href="#' + prevTabId + '"]').tab('show');
                    $('.nextButton').text('Next').removeClass('submit-button');
                }
            });

            // Manual tab switch
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
                const $activeTab = $('.tab-content .tab-pane.active');
                const isLastTab = $activeTab.is(':last-child');
                const submitText = formMode === 'edit' ? 'Update' : 'Finish';
                if (!isLastTab) {
                    $('.nextButton').text('Next').removeClass('submit-button');
                } else {
                    $('.nextButton').text(submitText).addClass('submit-button');
                }
                updateNextButton();
            });

            // Important fix:
            // On EDIT mode, trigger a check for all prefilled required fields once
            if (formMode === 'edit') {
                setTimeout(() => {
                    $('.required-field').each(function() {
                        const val = $(this).val();
                        if (val && val.trim() !== '') {
                            $(this).trigger('input');
                        }
                    });
                    updateNextButton(); // final validation after preload
                }, 800);
            } else {
                // On CREATE mode, keep disabled until filled
                $('.nextButton').prop('disabled', true);
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            // Add new unit
            $(document).on('click', '.add-unit', function() {
                const newUnit = $('.unit_template').clone().removeClass('unit_template d-none').addClass('unit_list new');
                $('.unit_list_results').append(newUnit);
                $('.add-container').hide(); // Hide "Add" button until saved
            });

            // Save unit
            $(document).on('click', '.save-unit', function() {
                const unitBlock = $(this).closest('.unit_list');
                const name = unitBlock.find('.unit-name').val().trim();
                const status = unitBlock.find('.unit-status').val();
                const notes = unitBlock.find('.unit-notes').val().trim();

                if (!name || !status) {
                    toastrs('warning', 'Please enter unit name and select status.', 'Warning');
                    return;
                }

                // (Optional) Perform AJAX save here
                // $.post('/save/unit', {name, status, notes, _token: '{{ csrf_token() }}'}, function(response){ ... });

                // Simulate save success
                unitBlock.find('.unit-name, .unit-status, .unit-notes').prop('readonly', true).prop('disabled', true);
                $(this).removeClass('btn-success save-unit').addClass('btn-danger remove-unit').text('Remove');

                unitBlock.append(`
                    <input type="hidden" name="unitname[]" value="${name}">
                    <input type="hidden" name="status[]" value="${status}">
                    <input type="hidden" name="notes[]" value="${notes}">
                `);

                $('.add-container').show().find('.add-unit').text('Add More Unit');
                toastrs('success', 'Unit saved successfully.', 'Success');
            });

            // Remove unit
            $(document).on('click', '.remove-unit', function() {
                $(this).closest('.unit_list').next('hr').remove();
                $(this).closest('.unit_list').remove();
                toastrs('info', 'Unit removed.', 'Info');
            });
        });
    </script>
    <script>
        $(document).on('click', '.remove-image-btn', function () {
            const imageId = $(this).data('id');
            const button = $(this);

            if (!confirm('Are you sure you want to delete this image?')) return;

            $.ajax({
                url: "{{ route('property.image.delete') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: imageId
                },
                success: function (response) {
                    if (response.success) {
                        // Remove image div
                        button.closest('div.position-relative').remove();
                        toastrs('success', response.message || 'Image deleted successfully.', 'Success');
                    } else {
                        toastrs('error', response.message || 'Unable to delete image.', 'Error');
                    }
                },
                error: function () {
                    toastrs('error', 'Server error while deleting image.', 'Error');
                }
            });
        });
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('property.index') }}">{{ __('Property') }}</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="#">
            {{ isset($property) && $property->id ? __('Edit') : __('Create') }}
        </a>
    </li>
@endsection

@section('content')
    @if(isset($property) && $property->id)
        {{-- EDIT MODE --}}
        {{ Form::model($property, ['route' => ['property.update', $property->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'property_form']) }}
    @else
        {{-- CREATE MODE --}}
        {{ Form::open(['route' => 'property.store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'property_form']) }}
    @endif
    <input type="hidden" id="form_mode" value="{{ isset($property) ? 'edit' : 'create' }}">
    @if(isset($property) && $property->id)
        <input type="hidden" id="property_id" name="propertyid" value="{{ $property->id }}">
    @else
        <input type="hidden" id="property_id" name="propertyid" value="">
    @endif
    
    <div class="row mt-4 g-3">
        <div class="col-md-3 d-flex">
            <div class="bg-white fw-100 mb-lg-5">
                <div class="card-header pb-0">
                    <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab-1" data-bs-toggle="tab" href="#profile-1"
                                role="tab" aria-selected="true">
                                <i class="material-icons-two-tone me-2">info</i>
                                {{ __('Property Details') }}
                                <span class="step-status"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab-2" data-bs-toggle="tab" href="#profile-2" role="tab"
                                aria-selected="true">
                                <i class="material-icons-two-tone me-2">image</i>
                                {{ __('Property Images') }}
                                <span class="step-status"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab-3" data-bs-toggle="tab" href="#profile-3" role="tab"
                                aria-selected="true">
                                <i class="material-icons-two-tone me-2">layers</i>
                                {{ __('Unit') }}
                                <span class="step-status"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab-4" data-bs-toggle="tab" href="#profile-4" role="tab"
                                aria-selected="true">
                                <i class="material-icons-two-tone ti ti-tools me-2"></i>
                                {{ __('Amenities') }}
                                <span class="step-status"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab-5" data-bs-toggle="tab" href="#profile-5" role="tab"
                                aria-selected="true">
                                <i class="material-icons-two-tone ti ti-bulb me-2"></i>
                                {{ __('Utilities') }}
                                <span class="step-status"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab-6" data-bs-toggle="tab" href="#profile-6" role="tab"
                                aria-selected="true">
                                <i class="material-icons-two-tone ti ti-bulb me-2"></i>
                                {{ __('Our Cost') }}
                                <span class="step-status"></span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9 d-flex">
            <div class="card-body w-100">
                <div class="tab-content">
                    <div class="tab-pane show active" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card border bg-custom bg-white mb-3">
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
                                                <!-- Cropper.js -->
                                    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet"/>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

                                                <div class="mb-3">
                                                    {{--<!-- <div class="form-group">
                                                        {{ Form::label('thumbnail', __('Thumbnail Image'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                                        {{ Form::file('thumbnail', ['class' => 'form-control required-field', 'required' => 'required']) }}
                                                    </div> -->--}}
                                                    <div class="form-group">
                                                        {{ Form::label('thumbnail', __('Thumbnail Image'), ['class' => 'form-label']) }} 
                                                        <span class="text-danger">*</span>
                                                        <!-- {{ Form::file('thumbnail', ['class' => 'form-control required-field', 'required' => 'required', 'id' => 'thumbnailInput', 'accept' => 'image/*']) }} -->
                                                        
                                                        
                                                        @if(isset($propertyimages) && $propertyimages->image)
                                                            <div class="mb-2 text-center">
                                                                <img src="{{ asset('storage/upload/thumbnail/'.$propertyimages->image) }}" 
                                                                    alt="Current Thumbnail" 
                                                                    style="max-width: 100%; border-radius: 6px; border:1px solid #ccc;">
                                                                <p class="mt-2"><strong>Current file:</strong> {{ $propertyimages->image }}</p>
                                                            </div>

                                                            <input type="hidden" id="existing_thumbnail" name="existing_thumbnail" value="1">

                                                            <label for="thumbnailInput" class="form-label btn btn-primary btn-sm">Modify File</label>
                                                            <input type="file" id="thumbnailInput" name="thumbnail" 
                                                                class="form-control d-none"
                                                                accept="image/*">

                                                        @else
                                                            {{ Form::file('thumbnail', ['class' => 'form-control required-field', 'required' => 'required', 'id' => 'thumbnailInput', 'accept' => 'image/*']) }}
                                                        @endif
                                                        
                                                        
                                                        <!-- @if(isset($propertyimages) && $propertyimages->image)
                                                            {{-- Existing thumbnail (Edit mode) --}}
                                                            <div class="mb-2 text-center">
                                                                <img src="{{ asset('storage/upload/thumbnail/'.$propertyimages->image) }}" 
                                                                    alt="Current Thumbnail" 
                                                                    style="max-width: 100%; border-radius: 6px; border:1px solid #ccc;">
                                                            </div>

                                                            {{-- Hidden flag for existing image --}}
                                                            <input type="hidden" id="existing_thumbnail" name="existing_thumbnail" value="1">

                                                            {{-- Optional input on Edit --}}
                                                            {{ Form::file('thumbnail', [
                                                                'class' => 'form-control', // NOT required-field on edit
                                                                'id' => 'thumbnailInput',
                                                                'accept' => 'image/*'
                                                            ]) }}
                                                        @else
                                                            {{-- Required input on Create --}}
                                                            {{ Form::file('thumbnail', ['class' => 'form-control required-field', 'required' => 'required', 'id' => 'thumbnailInput', 'accept' => 'image/*']) }}
                                                        @endif -->

                                                    </div>
                                                    
                                                    <!-- Preview & Crop Area -->
                                                    <div id="preview-container" style="display:none; margin-top:10px; text-align:center;">
                                                        <img id="imagePreview" src="" alt="Preview" 
                                                            style="max-width:100%; border:1px solid #ddd; border-radius:6px;">
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-success btn-sm" id="cropButton" style="display:none;">Crop & Save</button>
                                                            <button type="button" class="btn btn-warning btn-sm" id="editButton" style="display:none;">Edit Again</button>
                                                            <button type="button" class="btn btn-danger btn-sm" id="cancelButton" style="display:none;">Cancel</button>
                                                        </div>
                                                    </div>

                                            <!-- Hidden input for cropped image -->
                                            <input type="hidden" name="cropped_image" id="croppedImage">
                                            <script>
                                                let cropper;
                                                const input = document.getElementById('thumbnailInput');
                                                const previewContainer = document.getElementById('preview-container');
                                                const preview = document.getElementById('imagePreview');
                                                const cropBtn = document.getElementById('cropButton');
                                                const editBtn = document.getElementById('editButton');
                                                const cancelBtn = document.getElementById('cancelButton');
                                                const croppedInput = document.getElementById('croppedImage');

                                                input.addEventListener('change', e => {
                                                    const file = e.target.files[0];
                                                    if (file) {
                                                        const reader = new FileReader();
                                                        reader.onload = ev => {
                                                            // ✅ Hide old existing image (if in edit mode)
                                                            const existingThumb = document.querySelector('.text-center img');
                                                            if (existingThumb) {
                                                                existingThumb.style.display = 'none';
                                                            }

                                                            // ✅ Show new preview
                                                            preview.src = ev.target.result;
                                                            previewContainer.style.display = 'block';

                                                            // ✅ Initialize Cropper
                                                            if (cropper) cropper.destroy();
                                                            cropper = new Cropper(preview, {
                                                                aspectRatio: 16 / 9,
                                                                viewMode: 1,
                                                                autoCropArea: 1,
                                                            });

                                                            cropBtn.style.display = 'inline-block';
                                                            cancelBtn.style.display = 'inline-block';
                                                            editBtn.style.display = 'none';
                                                        };
                                                        reader.readAsDataURL(file);
                                                    }
                                                });

                                                // ✅ Crop & Save
                                                cropBtn.addEventListener('click', () => {
                                                    if (cropper) {
                                                        const canvas = cropper.getCroppedCanvas({ width: 800, height: 450 });
                                                        preview.src = canvas.toDataURL();
                                                        croppedInput.value = canvas.toDataURL('image/jpeg');
                                                        cropper.destroy();

                                                        cropBtn.style.display = 'none';
                                                        cancelBtn.style.display = 'none';
                                                        editBtn.style.display = 'inline-block';
                                                    }
                                                });

                                                // ✅ Edit Again
                                                editBtn.addEventListener('click', () => {
                                                    cropper = new Cropper(preview, {
                                                        aspectRatio: 16 / 9,
                                                        viewMode: 1,
                                                        autoCropArea: 1,
                                                    });
                                                    cropBtn.style.display = 'inline-block';
                                                    cancelBtn.style.display = 'inline-block';
                                                    editBtn.style.display = 'none';
                                                });

                                                // ✅ Cancel
                                                cancelBtn.addEventListener('click', () => {
                                                    if (cropper) cropper.destroy();
                                                    preview.src = '';
                                                    previewContainer.style.display = 'none';
                                                    input.value = '';
                                                    croppedInput.value = '';

                                                    // ✅ Re-show the old image (if exists)
                                                    const existingThumb = document.querySelector('.text-center img');
                                                    if (existingThumb) {
                                                        existingThumb.style.display = 'block';
                                                    }

                                                    cropBtn.style.display = 'none';
                                                    editBtn.style.display = 'none';
                                                    cancelBtn.style.display = 'none';
                                                });
                                                </script>
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
                                            
                                            {{ Form::hidden('country', $property->country ?? 'USA') }}
                                                {{-- State --}}
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <div class="form-group">
                                                            {{ Form::label('state', __('State'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                                            {{ Form::select('state', 
                                                                $statesdata->pluck('name','id')->toArray(),
                                                                $property->state_id ?? null, 
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
                                                        <!-- {{ Form::text('zip_code', null, ['class' => 'form-control required-field', 'placeholder' => __('Enter Property Zip Code'), 'required' => 'required']) }} -->
                                                          {{ Form::text('zip_code', null, [
                                                            'class' => 'form-control required-field',
                                                            'placeholder' => __('Enter Property Zip Code'),
                                                            'required' => 'required',
                                                            'maxlength' => 6,
                                                            'pattern' => '[0-9]{6}'
                                                        ]) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        {{ Form::label('is_active', __('Status'), ['class' => 'form-label']) }}
                                                        <span class="text-danger">*</span>
                                                        {{ Form::select(
                                                            'is_active',
                                                            [1 => 'Active', 0 => 'Inactive'],
                                                            $property->is_active ?? 1,
                                                            ['class' => 'form-control required-field', 'required' => 'required']
                                                        ) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                             <a href="#" style="opacity:0">
                               
                                                            </a>
                            <button type="button" class="btn btn-secondary btn-rounded nextButton"
                                data-next-tab="#profile-2">
                                {{ __('Next') }}
                            </button>
                        </div>
                    </div>
                    <div class="tab-pane" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card border bg-custom bg-white mb-3">
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
                                            @if(isset($propertyextraimages) && $propertyextraimages->isNotEmpty())
                                                <div class="existing-images mt-3">
                                                    <h6 class="mb-2 text-muted">{{ __('Existing Images') }}</h6>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($propertyextraimages as $propertyval)
                                                            <div class="position-relative">
                                                                <a href="{{ asset(Storage::url('upload/property')) . '/' . $propertyval->image }}" 
                                                                target="_blank">
                                                                    <img src="{{ asset(Storage::url('upload/property')) . '/' . $propertyval->image }}"
                                                                        alt="{{ $property->name }}"
                                                                        class="img-thumbnail"
                                                                        style="width:80px; height:80px; object-fit:cover;">
                                                                </a>
                                                                <!-- Optional delete button -->
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-image-btn"
                                                                        data-id="{{ $propertyval->id }}"
                                                                        title="Remove Image"
                                                                        style="padding:0 4px;">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
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

                        <div class="d-flex justify-content-between">
                             <button type="button" class="btn btn-primary btn-rounded prevButton">
                                {{ __('Back') }}
                            </button>
                            <button type="button" class="btn btn-secondary btn-rounded nextButton nextButton22"
                                data-next-tab="#profile-2" disabled>
                                {{ __('Next') }}
                            </button>

                        </div>
                    </div>
                    <div class="tab-pane" id="profile-3" role="tabpanel" aria-labelledby="profile-tab-3">
                        <div class="card border bg-custom bg-white mb-3">
                            <div class="card-body w-100">

                                {{-- Hidden Template --}}
                                <div class="row unit_template d-none">
                                    <div class="form-group col-md-6">
                                        {{ Form::label('unitname', __('Name'), ['class' => 'form-label']) }}
                                        {{ Form::text('unitname[]', null, ['class' => 'form-control unit-name', 'placeholder' => __('Enter unit name')]) }}
                                    </div>

                                    <div class="form-group col-md-6">
                                        {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                        {{ Form::select('status[]', ['1' => 'Active', '0' => 'Inactive'], null, ['class' => 'form-control unit-status', 'placeholder' => __('Select Status')]) }}
                                    </div>

                                    <div class="form-group col-md-12">
                                        {{ Form::label('notes', __('Description'), ['class' => 'form-label']) }}
                                        {{ Form::textarea('notes[]', null, ['class' => 'form-control unit-notes', 'rows' => 1, 'placeholder' => __('Enter notes')]) }}
                                    </div>

                                    <div class="col-md-12 text-end mt-2">
                                        <button type="button" class="btn btn-success btn-sm save-unit">{{ __('Save') }}</button>
                                    </div>
                                    <hr class="mt-4 mb-4 border-dark">
                                </div>

                                {{-- ✅ Existing Units --}}
                                <div class="unit_list_results">
                                    @if(isset($units) && $units->isNotEmpty())
                                        @foreach($units as $unit)
                                            <div class="row unit_list saved">
                                                <input type="hidden" name="unit_id[]" value="{{ $unit->id }}">
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('unitname', __('Name'), ['class' => 'form-label']) }}
                                                    {{ Form::text('unitname[]', $unit->name, ['class' => 'form-control', 'readonly' => true]) }}
                                                </div>

                                                <div class="form-group col-md-6">
                                                    {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                                    {{ Form::select('status[]', ['1' => 'Active', '0' => 'Inactive'], $unit->status, ['class' => 'form-control', 'disabled' => true]) }}
                                                </div>

                                                <div class="form-group col-md-12">
                                                    {{ Form::label('notes', __('Description'), ['class' => 'form-label']) }}
                                                    {{ Form::textarea('notes[]', $unit->notes, ['class' => 'form-control', 'rows' => 1, 'readonly' => true]) }}
                                                </div>

                                                <div class="col-md-12 text-end mt-2">
                                                    <button type="button" class="btn btn-danger btn-sm remove-unit">{{ __('Remove') }}</button>
                                                </div>
                                                <hr class="mt-4 mb-4 border-dark">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                {{-- Initial Create Button --}}
                                <div class="col-lg-12 mb-2 text-center add-container">
                                    <button type="button" class="btn btn-secondary btn-md add-unit">{{ __('Create Unit') }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-primary btn-rounded prevButton">
                                {{ __('Back') }}
                            </button>
                            <button type="button" class="btn btn-secondary btn-rounded nextButton" data-next-tab="#profile-4">
                                {{ __('Next') }}
                            </button>
                        </div>
                    </div>


                    <div class="tab-pane" id="profile-4" role="tabpanel" aria-labelledby="profile-tab-4">
                        <div class="card border bg-custom bg-white mb-3">
                            <div class="card-body w-100">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Do we have amenities in the property to billed out?</label>                                        
                                        @php
                                            $hasAmenities = isset($amenities) && $amenities instanceof \Illuminate\Support\Collection && $amenities->count() > 0;
                                        @endphp

                                        <div>
                                            <label class="me-3">
                                                <input type="radio" name="is_billed" value="yes" 
                                                    class="form-check-input is-billed"
                                                    {{ $hasAmenities ? 'checked' : '' }}> Yes
                                            </label>

                                            <label>
                                                <input type="radio" name="is_billed" value="no" 
                                                    class="form-check-input is-billed"
                                                    {{ !$hasAmenities ? 'checked' : '' }}> No
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="isbilleddata" style="display: {{ $hasAmenities ? 'block' : 'none' }};">
                                    <div class="card bg-custom bg-white w-100">
                                        <div class="row align-items-center g-2">
                                            <div class="col-auto mx-auto">
                                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addAmenityModal">
                                                    <i class="ti ti-circle-plus align-text-bottom"></i> Add Amenities 
                                                </button>
                                            </div>
                                        </div>

                                        <div class="new-table mt-3" id="amenitiesWrapper" style="{{ $amenities->count() == 0 ? 'display:none;' : '' }}">
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
                                                                <td class="text-center">$ {{ $amenity->price }}</td>
                                                                <td class="text-center">{{ $amenity->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-warning editAmenityBtn" 
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

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-primary btn-rounded prevButton">
                                {{ __('Back') }}
                            </button>
                            <button type="button" class="btn btn-secondary btn-rounded nextButton" data-next-tab="#profile-5">
                                {{ __('Next') }}
                            </button>
                        </div>
                    </div>
                    <div class="tab-pane" id="profile-5" role="tabpanel" aria-labelledby="profile-tab-5">
                        <div class="card border bg-custom bg-white mb-3">
                            @php
                                // Check if any utilities exist
                                $hasUtilities = isset($utilities) && $utilities instanceof \Illuminate\Support\Collection && $utilities->count() > 0;
                            @endphp
                            <div class="card-body w-100">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Do we have Utilities in the property to billed out ?</label>
                                        <div>
                                            <label class="me-3">
                                                <input type="radio" name="utilities" value="yes" class="form-check-input utilities-radio"{{ $hasUtilities ? 'checked' : '' }}> Yes
                                            </label>
                                            <label>
                                                <input type="radio" name="utilities" value="no" class="form-check-input utilities-radio" {{ !$hasUtilities ? 'checked' : '' }}> No
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="utilitiesdata" role="tabpanel" aria-labelledby="utilitiesdata" style="display: {{ $hasUtilities ? 'block' : 'none' }};">
                                    <div class="w-100">
                                        <div class="">
                                            <div class="row align-items-center g-2">
                                                <div class="col">
                                                    <h5 class="mb-0" id="utilitiesTitle" style="{{ $utilities->count() == 0 ? 'display:none;' : '' }}">Utilities List</h5>
                                                </div>
                                                <div class="col-auto">
                                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addUtilitiesModal">
                                                        <i class="ti ti-circle-plus align-text-bottom"></i> Add New
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="new-table mt-3" id="utilitiesWrapper" style="{{ $utilities->count() == 0 ? 'display:none;' : '' }}">
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
                                                        <tbody id="utilitiesTableBody">
                                                        @php
                                                            // Group utilities by company name
                                                            $groupedUtilities = $utilities
                                                                ->groupBy('name')
                                                                ->map(function ($items) {
                                                                    $first = $items->first();
                                                                    return [
                                                                        'id' => $first->id,
                                                                        'name' => $first->name,
                                                                        'sub_category' => $first->sub_category,
                                                                        'status' => $first->status,
                                                                        'sub_category_names' => $items->pluck('sub_category_name')->filter()->unique()->implode(', ')
                                                                    ];
                                                                })
                                                                ->values();
                                                        @endphp

                                                        @foreach($groupedUtilities as $index => $utility)
                                                        <tr id="row2-{{ $utility['id'] }}">
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td>{{ $utility['name'] }}</td>
                                                            <td>{{ $utility['sub_category'] == 1 ? 'Yes' : 'No' }}</td>
                                                            <td>{{ $utility['sub_category_names'] }}</td>
                                                            <td>{{ $utility['status'] == 1 ? 'Active' : 'Inactive' }}</td>
                                                            <td class="text-center">
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-warning editUtilitiesBtn"
                                                                        data-id="{{ $utility['id'] }}"
                                                                        data-name="{{ $utility['name'] }}"
                                                                        data-status="{{ $utility['status'] }}"
                                                                        data-sub_category="{{ $utility['sub_category'] }}"
                                                                        data-sub_category_name="{{ $utility['sub_category_names'] }}">
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


                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-primary btn-rounded prevButton">
                                {{ __('Back') }}
                            </button>
                            <button type="button" class="btn btn-secondary btn-rounded nextButton"
                                data-next-tab="#profile-5">
                                {{ __('Next') }}
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane" id="profile-6" role="tabpanel"aria-labelledby="profile-tab-6">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card border bg-custom bg-white mb-3">
                                    <div class="card-header">
                                        <h5> {{ __('Our Cost') }}</h5>
                                    </div>
                                    <div class="card-body w-100">
                                        <div id="rowsContainer">
                                            <!-- ===== Row Start ===== -->
                                            <div class="row position-relative mb-2 input-row">
                                                <span class="remove-row d-none">&times;</span>

                                                <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                    <label class="form-label">Mortgage Amount</label> 
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" placeholder="e.g. 1000" class="form-control" name="mortgage_amount" value="{{ old('mortgage_amount', $property->mortgage_amount ?? '') }}">
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>

                                                <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                    <label class="form-label">Insurance Amount</label> 
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" placeholder="e.g. 1000" class="form-control" name="insurance_amount" value="{{ old('insurance_amount', $property->insurance_amount ?? '') }}">
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>

                                                <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                    <label class="form-label">Amenities Amount</label> 
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>                                                        
                                                        <input type="number" step="0.01" placeholder="e.g. 1000" class="form-control" name="amenities_amount" value="{{ old('amenities_amount', $property->amenities_amount ?? '') }}">
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>   
                                            </div>
                                            <!-- ===== Row End ===== -->
                                            </div>

                                            <!-- Add More Button -->
                                            <div class="text-end">
                                            <!-- <button id="addMoreBtn" class="btn btn-sm btn-success">
                                                <i class="bi bi-plus-lg"></i> Add More
                                            </button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="col-lg-12 mb-2">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-primary btn-rounded prevButton">
                                        {{ __('Back') }}
                                    </button>
                                    {{ Form::submit(__('Finish'), ['class' => 'btn btn-secondary btn-rounded nextButton text-white', 'id' => 'property-submit']) }}
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
    const container = document.getElementById('rowsContainer');
    const addMoreBtn = document.getElementById('addMoreBtn');

    addMoreBtn.addEventListener('click', function () {
      // Clone first row
      const clone = container.querySelector('.input-row').cloneNode(true);
      clone.querySelectorAll('input').forEach(input => input.value = ''); // clear inputs
      clone.querySelector('.remove-row').classList.remove('d-none');
      container.appendChild(clone);
    });

    // Remove button event
    container.addEventListener('click', function (e) {
      if (e.target.classList.contains('remove-row')) {
        e.target.closest('.input-row').remove();
      }
    });
  </script>

<script>
    $(document).ready(function() {
        // Function to load cities based on state_id
        function loadCities(state_id, selectedCity = null) {
            if (state_id) {
                $.ajax({
                    url: '/get_cities/' + state_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#company_city').empty().append('<option value="">Select</option>');

                        $.each(data, function(index, city) {
                            $('#company_city').append('<option value="'+ city.id +'">'+ city.name +'</option>');
                        });

                        // ✅ Pre-select city in edit mode
                        if (selectedCity) {
                            $('#company_city').val(selectedCity);
                        }
                    }
                });
            } else {
                $('#company_city').empty().append('<option value="">Select</option>');
            }
        }

        // Trigger city load when state changes
        $('#company_state').on('change', function() {
            var state_id = $(this).val();
            loadCities(state_id);
        });

        // ✅ Detect edit mode (when property exists)
        var selectedState = "{{ $property->state_id ?? '' }}";
        var selectedCity  = "{{ $property->city_id ?? '' }}";

        // ✅ If editing, pre-select state and city
        if (selectedState) {
            $('#company_state').val(selectedState);
            loadCities(selectedState, selectedCity);
        }
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
$(document).ready(function () {

    // ✅ Default: Show utilities section on edit if already has records
    if ($('input[name="utilities"]:checked').val() === 'yes') {
        $('#utilitiesdata').show();
    }

    // ✅ Toggle section on Yes/No
    $(document).on('change', 'input[name="utilities"]', function () {
        if ($(this).val() === 'yes') $('#utilitiesdata').slideDown();
        else $('#utilitiesdata').slideUp();
    });

    // ✅ Show/Hide Subcategory fields
    function refreshSubCategorySection() {
        let val = $('#addUtilitiesModal select[name="sub_category"]').val();
        if (val === '1') $('#subCategorySection').show();
        else $('#subCategorySection').hide();
    }

    $('#addUtilitiesModal').on('shown.bs.modal', refreshSubCategorySection);
    $(document).on('change', '#addUtilitiesModal select[name="sub_category"]', refreshSubCategorySection);

    // ✅ Add More sub-category input
    $(document).on('click', '#addMoreSubCategory', function (e) {
        e.preventDefault();
        $('#subCategoryWrapper').append('<input type="text" name="sub_category_name[]" class="form-control mb-2">');
    });

    // ✅ CLICK HANDLER — Run AJAX only when Save is clicked
    $(document).on('click', '#saveUtilities', function (e) {
        e.preventDefault();

        const formMode = $('#form_mode').val(); // create or edit
        const propertyId = $('#property_id').val();

        const name = $('#addUtilitiesModal input[name="name"]').val().trim();
        const sub_category = $('#addUtilitiesModal select[name="sub_category"]').val();
        const sub_category_name = $('#addUtilitiesModal input[name="sub_category_name[]"]').map(function(){ 
            return $(this).val().trim(); 
        }).get().filter(Boolean);
        const status = $('#addUtilitiesModal select[name="status"]').val();

        if (!name) {
            toastrs('warning', 'Please enter a company name.', 'Warning');
            return;
        }

        const table = $('#UtilitiesTable tbody');
        const existingRow = table.find(`tr:contains(${name})`);
        const subCatText = sub_category == 1 ? 'Yes' : 'No';
        const statusText = status == 1 ? 'Active' : 'Inactive';

        if (formMode === 'edit' && propertyId) {
            $.ajax({
                url: "{{ route('propertyutilities-store2') }}",
                type: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    propertyid: propertyId,
                    name,
                    sub_category,
                    sub_category_name,
                    status
                },
                success: function (response) {
                    toastrs('success', response.message || 'Utility saved successfully.', 'Success');

                    if (existingRow.length) {
                        existingRow.find('td:nth-child(2)').text(name);
                        existingRow.find('td:nth-child(3)').text(subCatText);
                        existingRow.find('td:nth-child(4)').text(sub_category_names);
                        existingRow.find('td:nth-child(5)').text(statusText);
                    } else {
                        const index = table.find('tr').length + 1;
                        table.append(`
                            <tr>
                                <td class="text-center">${index}</td>
                                <td>${name}</td>
                                <td>${subCatText}</td>
                                <td>${sub_category_names}</td>
                                <td>${statusText}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning editUtilitiesBtn"
                                        data-name="${name}"
                                        data-sub_category="${sub_category}"
                                        data-sub_category_name="${sub_category_names}"
                                        data-status="${status}">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    }

                    $('#addUtilitiesModal').modal('hide');
                    $('#UtilitiesForm')[0].reset();
                    $('#utilitiesWrapper').show();
                },
                error: function (xhr) {
                    toastrs('error', xhr.responseJSON?.message || 'Failed to save utility.', 'Error');
                }
            });
        } else{
            if (existingRow.length) {
                existingRow.find('td:nth-child(2)').text(name);
                existingRow.find('td:nth-child(3)').text(subCatText);
                existingRow.find('td:nth-child(4)').text(sub_category_names);
                existingRow.find('td:nth-child(5)').text(statusText);
            } else {
                const index = table.find('tr').length + 1;
                table.append(`
                    <tr>
                        <td class="text-center">${index}</td>
                        <td>${name}</td>
                        <td>${subCatText}</td>
                        <td>${sub_category_names}</td>
                        <td>${statusText}</td>
                        <td class="text-center">
                            <button type="button" 
                                    class="btn btn-sm btn-warning editUtilitiesBtn"
                                    data-name="${name}"
                                    data-sub_category="${sub_category}"
                                    data-sub_category_name="${sub_category_names}"
                                    data-status="${status}">
                                <i class="ti ti-edit"></i>
                            </button>
                        </td>
                    </tr>
                `);


                $('#addUtilitiesModal').modal('hide');
                $('#UtilitiesForm')[0].reset();
                $('#utilitiesWrapper').show();
            }
        }
        // $('input[name="utilities"][value="yes"]').prop('checked', true);
        // $('#utilitiesdata').slideDown();

        // --- AJAX POST call ---
        // $.ajax({
        //     url: "{{ url('property-utilities-store') }}",
        //     type: "POST",
        //     data: payload,
        //     success: function (response) {
        //         if (response.success) {
        //             $('#addUtilitiesModal').modal('hide');
        //             $('#UtilitiesForm')[0].reset();
        //             $('#utilitiesTitle').show();
        //             $('#utilitiesWrapper').show();
        //             alert(response.message);

        //             // ✅ Group incoming JSON by company name
        //             let grouped = {};
        //             response.data.forEach(function (item) {
        //                 if (!grouped[item.name]) {
        //                     grouped[item.name] = {
        //                         id: item.id,
        //                         name: item.name,
        //                         sub_category: item.sub_category,
        //                         status: item.status,
        //                         sub_names: []
        //                     };
        //                 }
        //                 if (item.sub_category_name) {
        //                     grouped[item.name].sub_names.push(item.sub_category_name);
        //                 }
        //             });

        //             // ✅ Update DOM table
        //             Object.values(grouped).forEach(function (companyData) {
        //                 let existingRow = $("#UtilitiesTable tbody tr").filter(function () {
        //                     return $(this).find("td:nth-child(2)").text().trim() === companyData.name;
        //                 });

        //                 let subCatText = companyData.sub_category == 1 ? 'Yes' : 'No';
        //                 let statusText = companyData.status == 1 ? 'Active' : 'Inactive';
        //                 let subCatNames = companyData.sub_names.join(', ');

        //                 if (existingRow.length > 0) {
        //                     let existingSubNames = existingRow.find("td:nth-child(4)").text().split(/\s*,\s*/);
        //                     companyData.sub_names.forEach(function (sn) {
        //                         if (sn && !existingSubNames.includes(sn)) existingSubNames.push(sn);
        //                     });
        //                     existingRow.find("td:nth-child(4)").text(existingSubNames.join(', '));
        //                 } else {
        //                     let rowCount = $("#UtilitiesTable tbody tr").length + 1;
        //                     $('#UtilitiesTable tbody').append(`
        //                         <tr id="row2-${companyData.id}">
        //                             <td class="text-center">${rowCount}</td>
        //                             <td>${companyData.name}</td>
        //                             <td>${subCatText}</td>
        //                             <td>${subCatNames}</td>
        //                             <td>${statusText}</td>
        //                             <td class="text-center">
        //                                 <button type="button" class="btn btn-sm btn-warning editUtilitiesBtn"
        //                                     data-id="${companyData.id}"
        //                                     data-name="${companyData.name}"
        //                                     data-status="${companyData.status}"
        //                                     data-sub_category="${companyData.sub_category}"
        //                                     data-sub_category_name="${subCatNames}">
        //                                     <i class="ti ti-edit"></i>
        //                                 </button>
        //                             </td>
        //                         </tr>
        //                     `);
        //                 }
        //             });

        //             $("#UtilitiesTable tbody tr").each(function (index) {
        //                 $(this).find("td:first").text(index + 1);
        //             });

        //             $('input[name="utilities"][value="yes"]').prop('checked', true);
        //             $('#utilitiesdata').slideDown();

        //         } else {
        //             alert(response.message ?? "Something went wrong!");
        //         }
        //     },
        //     error: function (xhr) {
        //         console.error(xhr);
        //         alert("Error: " + xhr.responseText);
        //     }
        // });
    });

    // ✅ Prefill modal for edit
    // $(document).on('click', '.editUtilitiesBtn', function () {
    //     $('#utilities_id').val($(this).data('id'));
    //     $('#utilities_name').val($(this).data('name'));
    //     $('#utilities_status').val($(this).data('status'));
    //     $('#utilities_sub_category').val($(this).data('sub_category'));
    //     $('#utilities_sub_category_name').val($(this).data('sub_category_name'));
    //     $('#addUtilitiesModal').modal('show');
    // });
});
</script>

<script>
    $(document).ready(function () {

        // ✅ Function: Toggle amenities section smoothly
        function toggleAmenitiesSection(show) {
            if (show) {
                $('#isbilleddata').slideDown();
            } else {
                $('#isbilleddata').slideUp();
            }
        }

        // ✅ Check default (on page load)
        const isCheckedYes = $('input[name="is_billed"]:checked').val() === 'yes';
        toggleAmenitiesSection(isCheckedYes);

        // ✅ When radio value changes
        $(document).on('change', 'input[name="is_billed"]', function () {
            toggleAmenitiesSection($(this).val() === 'yes');
        });

        // ✅ Add/Edit Amenity Form submission
        $(document).on('submit', '#amenityForm', function (e) {
            e.preventDefault();

            const formMode = $('#form_mode').val();
            const name = $('#amenityName').val().trim();
            const price = $('#amenityAmount').val().trim();
            const status = $('#amenityStatus').val();
            const statusText = status == 1 ? 'Active' : 'Inactive';
            const propertyId = $('#property_id').val();

            if (!name || !price) {
                toastrs('warning', 'Please fill all required fields.', 'Warning');
                return;
            }

            const table = $('#amenitiesTable tbody');
            const existingRow = table.find(`tr:contains(${name})`);

            
            if (formMode === 'edit' && propertyId) {
                $.ajax({
                    url: "{{ route('propertyamenities-store2') }}", 
                    type: "POST",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        propertyid: propertyId,
                        name,
                        price,
                        status,
                    },
                    success: function (response) {
                        toastrs(response.status, response.message, response.status);

                        if (existingRow.length) {
                            existingRow.find('td:nth-child(2)').text(name);
                            existingRow.find('td:nth-child(3)').text(`$ ${price}`);
                            existingRow.find('td:nth-child(4)').text(statusText);
                        } else {
                            const index = table.find('tr').length + 1;
                            table.append(`
                                <tr>
                                    <td class="text-center">${index}</td>
                                    <td class="text-center">${name}</td>
                                    <td class="text-center">$ ${price}</td>
                                    <td class="text-center">${statusText}</td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-sm btn-warning editAmenityBtn"
                                                data-name="${name}" 
                                                data-price="${price}" 
                                                data-status="${status}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            `);
                        }

                        $('#addAmenityModal').modal('hide');
                        $('#amenityForm')[0].reset();
                        $('#amenitiesWrapper').show();
                    },
                    error: function (xhr) {
                        toastrs('error', xhr.responseJSON?.message || 'Failed to save amenity.', 'Error');
                    }
                });
            }else{
                if (existingRow.length) {
                    // ✅ Update existing row
                    existingRow.find('td:nth-child(2)').text(name);
                    existingRow.find('td:nth-child(3)').text(`$ ${price}`);
                    existingRow.find('td:nth-child(4)').text(statusText);
                } else {
                    // ✅ Append new row
                    const index = table.find('tr').length + 1;
                    table.append(`
                        <tr>
                            <td class="text-center">${index}</td>
                            <td class="text-center">${name}</td>
                            <td class="text-center">$ ${price}</td>
                            <td class="text-center">${statusText}</td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-sm btn-warning editAmenityBtn"
                                        data-name="${name}" 
                                        data-price="${price}" 
                                        data-status="${status}">
                                    <i class="ti ti-edit"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                }

                // ✅ Reset form + close modal
                $('#addAmenityModal').modal('hide');
                $('#amenityForm')[0].reset();
                $('#amenitiesWrapper').show();
            }
        });        
    });
</script>

<script>
    $(document).on('click', '.editAmenityBtn', function () {
        
        const name = $(this).data('name');
        const price = $(this).data('price');
        const status = $(this).data('status');

        $('#editAmenityName').val(name);
        $('#amenitydataAmount').val(price);
        $('#editAmenityStatus').val(status);
        $('#editAmenityModal').modal('show');

        // store ref to the row for inline update
        $('#editAmenityModal').data('row', $(this).closest('tr'));
    });

    $(document).on('click', '#updateAmenity', function (e) {
        e.preventDefault();

        const name = $('#editAmenityName').val().trim();
        const price = $('#amenitydataAmount').val().trim();
        const status = $('#editAmenityStatus').val();
        const statusText = status == 1 ? 'Active' : 'Inactive';

        if (!name || !price) {
            toastrs('warning', 'Please fill all required fields.', 'Warning');
            return;
        }

        const row = $('#editAmenityModal').data('row');
        const propertyId = $('#property_id').val();
        const formMode = $('#form_mode').val();
        const amenityId = row.find('.editAmenityBtn').data('id');
        
        if (formMode === 'edit' && propertyId) {
            $.ajax({
                url: "{{ route('propertyamenities-store2') }}",
                type: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    id: amenityId,
                    propertyid: propertyId,
                    name,
                    price,
                    status,
                },
                success: function (response) {
                    toastrs(response.status, response.message, response.status);

                    // Update the row in the table
                    row.find('td:nth-child(2)').text(name);
                    row.find('td:nth-child(3)').text(`$ ${price}`);
                    row.find('td:nth-child(4)').text(statusText);

                    const editBtn = row.find('.editAmenityBtn');

                    $('#editAmenityModal').modal('hide');
                },
                error: function (xhr) {                    
                    toastrs('error', 'Failed to update amenity.', 'Error');
                }
            });
        }else {
            row.find('td:nth-child(2)').text(name);
            row.find('td:nth-child(3)').text(`$ ${price}`);
            row.find('td:nth-child(4)').text(statusText);

            $('#editAmenityModal').modal('hide');
        }

        // row.find('td:nth-child(2)').text(name);
        // row.find('td:nth-child(3)').text(`$ ${price}`);
        // row.find('td:nth-child(4)').text(statusText);

        // const editBtn = row.find('.editAmenityBtn');
        // editBtn.data('name', name);
        // editBtn.data('price', price);
        // editBtn.data('status', status);

        // $('#editAmenityModal').modal('hide');
        // toastr.success('Amenity updated successfully.');
    });

    // $(document).on('click', '.editAmenityBtn', function () {
    //     let id = $(this).data('id');
    //     let name = $(this).data('name');
    //     let price = $(this).data('price');
    //     let status = $(this).data('status');

    //     // Modal fill
    //     $('#editAmenityId').val(id);
    //     $('#editAmenityName').val(name);
    //     $('#amenitydataAmount').val(price);
    //     $('#editAmenityStatus').val(status);

    //     $('#editAmenityModal').modal('show');
    // });

    // $(document).on('click', '#updateAmenity', function (e) {
    //     e.preventDefault();

    //     $.ajax({
    //         url: "{{ url('property-amenities-update') }}",
    //         type: "POST",
    //         data: $('#editAmenityForm').serialize(),
    //         success: function (response) {
    //             if (response.success) {
    //                 $('#editAmenityModal').modal('hide');
    //                 alert(response.message);

    //                 // ✅ Update row in table
    //                 let row = $('#row-' + response.data.id);
    //                 row.find('td:eq(1)').text(response.data.name); // Amenity Name
    //                 row.find('td:eq(2)').text(response.data.price); // Amenity price
    //                 row.find('td:eq(3)').text(response.data.status == 1 ? 'Active' : 'Inactive'); // Status

    //                 // ✅ Update button attributes
    //                 row.find('.editAmenityBtn').data('name', response.data.name);
    //                 row.find('.editAmenityBtn').data('price', response.data.price);
    //                 row.find('.editAmenityBtn').data('status', response.data.status);
    //             } else {
    //                 alert(response.message ?? "Something went wrong!");
    //             }
    //         },
    //         error: function (xhr) {
    //             alert("Error: " + xhr.responseText);
    //         }
    //     });
    // });
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
$(document).ready(function () {

    // Open Modal and Prefill Data
    $(document).on('click', '.editUtilitiesBtn', function () {
        let data = $(this).data();

        $('#editUtilitiesName').val(data.name);
        $('#editUtilitiesSubCategory').val(data.sub_category);
        $('#editUtilitiesStatus').val(data.status);
        $('#editUtilitiesId').val(data.id);
        $('#editUtilitiesPropertyId').val($('#property_id').val());

        if (data.sub_category == 1) {
            $('#editSubCategorySection').show();
            $('#editSubCategoryWrapper').html('');

            const subs = data.sub_category_name ? data.sub_category_name.split(',').map(s => s.trim()) : [];
            subs.forEach(sub => {
                $('#editSubCategoryWrapper').append(`<input type="text" name="sub_category_name[]" class="form-control mb-2" value="${sub}">`);
            });
        } else {
            $('#editSubCategorySection').hide();
            $('#editSubCategoryWrapper').html('');
        }

        $('#editUtilitiesModal').modal('show');
        $('#editUtilitiesModal').data('row', $(this).closest('tr'));
    });

    // 🟢 Add More Subcategory Input
    $(document).on('click', '#addMoreEditSubCategory', function () {
        $('#editSubCategoryWrapper').append(`
            <div class="d-flex mb-2 align-items-center subcat-row">
                <input type="text" name="sub_category_name[]" class="form-control me-2" placeholder="Enter Sub Category" required>
                <button type="button" class="btn btn-danger btn-sm removeEditSubCategory">&times;</button>
            </div>
        `);
    });

    // 🟢 Remove a subcategory input
    $(document).on('click', '.removeEditSubCategory', function () {
        $(this).closest('.subcat-row').remove();
    });

    // 🟢 Show/Hide subcategory section when main select changes
    $(document).on('change', '#editUtilitiesSubCategory', function () {
        if ($(this).val() == 1) {
            $('#editSubCategorySection').slideDown();
            if ($('#editSubCategoryWrapper').children().length === 0) {
                $('#editSubCategoryWrapper').append(`
                    <div class="d-flex mb-2 align-items-center subcat-row">
                        <input type="text" name="sub_category_name[]" class="form-control me-2" placeholder="Enter Sub Category" required>
                        <button type="button" class="btn btn-danger btn-sm removeEditSubCategory">&times;</button>
                    </div>
                `);
            }
        } else {
            $('#editSubCategorySection').slideUp();
            $('#editSubCategoryWrapper').empty();
        }
    });

    // 🟢 Update AJAX
    $(document).on('click', '#updateUtilities', function (e) {
        e.preventDefault();

        const formData = $('#editUtilitiesForm').serializeArray();
        let payload = {};

        formData.forEach(({ name, value }) => {
            if (name === 'sub_category_name[]') {
                if (!payload['sub_category_name']) payload['sub_category_name'] = [];
                payload['sub_category_name'].push(value.trim());
            } else {
                payload[name] = value;
            }
        });

        // ✅ Convert array to comma-separated string
        if (payload.sub_category_name) {
            payload.sub_category_name = payload.sub_category_name.join(', ');
        }

        $.ajax({
            url: "{{ route('propertyUtilities-update2') }}",
            type: "POST",
            data: payload,
            success: function (response) {
                if (response.success) {
                    const item = response.data;

                    // ✅ Close modal
                    $('#editUtilitiesModal').modal('hide');

                    // ✅ Success toast
                    toastrs(response.status, response.message, response.status);

                    const companyName = item.name.trim().toLowerCase();
                    let updated = false;

                    // ✅ Try to find matching row by company name
                    $("#UtilitiesTable tbody tr").each(function () {
                        const rowCompanyName = $(this).find("td:nth-child(2)").text().trim().toLowerCase();

                        if (rowCompanyName === companyName) {
                            // ✅ Update existing row values inline
                            $(this).find("td:nth-child(2)").text(item.name);
                            $(this).find("td:nth-child(3)").text(item.sub_category == 1 ? 'Yes' : 'No');
                            $(this).find("td:nth-child(4)").text(item.sub_category_names || '');
                            $(this).find("td:nth-child(5)").text(item.status == 1 ? 'Active' : 'Inactive');

                            // ✅ Update edit button data attributes
                            const editBtn = $(this).find(".editUtilitiesBtn");
                            editBtn.data('id', item.id);
                            editBtn.data('name', item.name);
                            editBtn.data('status', item.status);
                            editBtn.data('sub_category', item.sub_category);
                            editBtn.data('sub_category_name', item.sub_category_names || '');

                            // ✅ Highlight row for feedback
                            $(this).css('background-color', '#d4edda');
                            setTimeout(() => $(this).css('background-color', ''), 1000);

                            updated = true;
                            return false; // stop loop
                        }
                    });

                    // ✅ If company not found (renamed or new), append a new row
                    if (!updated) {
                        const subCatText = item.sub_category == 1 ? 'Yes' : 'No';
                        const statusText = item.status == 1 ? 'Active' : 'Inactive';
                        const subCats = item.sub_category_names || '';
                        const rowCount = $("#UtilitiesTable tbody tr").length + 1;

                        const newRow = `
                            <tr id="row2-${item.id}">
                                <td class="text-center">${rowCount}</td>
                                <td>${item.name}</td>
                                <td>${subCatText}</td>
                                <td>${subCats}</td>
                                <td>${statusText}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning editUtilitiesBtn"
                                        data-id="${item.id}"
                                        data-name="${item.name}"
                                        data-status="${item.status}"
                                        data-sub_category="${item.sub_category}"
                                        data-sub_category_name="${subCats}">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        $("#UtilitiesTable tbody").append(newRow);
                    }

                } else {
                    toastrs('error', response.message ?? "Something went wrong!", 'Error');
                }
            },
            error: function (xhr) {
                toastrs('error', "Error: " + xhr.responseText, 'Error');
            }
        });
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
                        <input type="number" class="form-control" id="amenityAmount" name="price" placeholder="Enter amount" required>
                    </div>

                    <div class="mb-3">
                        <label for="amenityStatus" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control form-select" name="status" id="amenityStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- ✅ Proper submit button -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
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
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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
             <button type="button" id="saveUtilities" class="btn btn-success">Save</button>
           </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Edit Utilities Modal -->
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
          <input type="hidden" name="propertyid" id="editUtilitiesPropertyId" value="{{ $property->id ?? '' }}">

          <div class="mb-3">
            <label class="form-label">Company Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="editUtilitiesName" name="name" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Sub Category <span class="text-danger">*</span></label>
            <select name="sub_category" id="editUtilitiesSubCategory" class="form-select" required>
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>

          <!-- ✅ Subcategory section -->
          <div class="mb-3" id="editSubCategorySection" style="display:none;">
            <label class="form-label">Sub Category Name <span class="text-danger">*</span></label>
            <div id="editSubCategoryWrapper"></div>

            <!-- Add new sub-category input -->
            <button type="button" id="addMoreEditSubCategory" class="btn btn-outline-success btn-sm mt-2">
              + Add More
            </button>
          </div>

          <div class="mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-control" id="editUtilitiesStatus" name="status" required>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateUtilities">Update</button>
      </div>

    </div>
  </div>
</div>
