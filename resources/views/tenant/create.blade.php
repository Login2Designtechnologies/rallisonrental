@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Create') }}
@endsection
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

        $('#tenant-submit').on('click', function() {
            "use strict";
            $('#tenant-submit').attr('disabled', true);
            var fd = new FormData();
            var file = document.getElementById('profile').files[0];


            var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('tenant_images[' + key + ']', $('#demo-upload')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });
            fd.append('profile', file);
            var other_data = $('#tenant_form').serializeArray();
            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });
            $.ajax({
                url: "{{ route('tenant.store') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    if (data.status == "success") {
                        $('#tenant-submit').attr('disabled', true);
                        toastrs(data.status, data.msg, data.status);
                        var url = '{{ route('tenant.index') }}';
                        setTimeout(() => {
                            window.location.href = url;
                        }, "1000");

                    } else {
                        toastrs('Error', data.msg, 'error');
                        $('#tenant-submit').attr('disabled', false);
                    }
                },
                error: function(data) {
                    $('#tenant-submit').attr('disabled', false);
                    if (data.error) {
                        toastrs('Error', data.error, 'error');
                    } else {
                        toastrs('Error', data, 'error');
                    }
                },
            });
        });

        // $('#property').on('change', function() {
        //     "use strict";
        //     var property_id = $(this).val();
        //     var url = '{{ route('property.unit', ':id') }}';
        //     url = url.replace(':id', property_id);

        //     $.ajax({
        //         url: url,
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         data: {
        //             property_id: property_id,
        //         },
        //         contentType: false,
        //         processData: false,
        //         type: 'GET',
        //         success: function(data) {
        //             $('.unit').empty();
        //             var unit =
        //                 `<select class="form-control hidesearch unit" id="unit" name="unit"></select>`;
        //             $('.unit_div').html(unit);

        //             $.each(data, function(key, value) {
        //                 $('.unit').append('<option value="' + key + '">' + value + '</option>');
        //             });
        //             $(".hidesearch").each(function() {
        //                 var basic_select = new Choices(this, {
        //                     searchEnabled: false,
        //                     removeItemButton: true,
        //                 });
        //             });
        //         },

        //     });
        // });
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Tenant') }}</li>
    <li class="breadcrumb-item active">
        <a href="#">{{ __('Create') }}</a>
    </li>
@endsection


@section('content')
<div class="card bg-custom border p-25">
    <div class="row">

        {{ Form::open(['url' => 'tenant', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'tenant_form']) }}
        <div class="row">
            <div class="col-lg-6 d-flex">
                <div class="card box-card w-100">
                    <div class="card-header">
                        <h5>{{ __('Personal Details') }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('first_name', __('First Name'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::text('first_name', null, ['class' => 'form-control',
                                        'required' => 'required', 'placeholder' => __('Enter First Name')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('last_name', __('Last Name'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::text('last_name', null, ['class' => 'form-control',
                                        'required' => 'required', 'placeholder' => __('Enter Last Name')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::text('email', null, ['class' => 'form-control',
                                        'required' => 'required', 'placeholder' => __('Enter Email')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('password', __('Password'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::password('password', ['class' => 'form-control',
                                        'required' => 'required', 'placeholder' => __('Enter Password')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('phone_number', __('Phone Number'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::text('phone_number', null, ['class' => 'form-control phone_number','required' => 'required', 'placeholder' => __('xxx-xxx-xxxx')]) }}
                            </div>
                            <!-- <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('family_member', __('Total Family Member'), ['class' => 'form-label']) }}
                                {{ Form::number('family_member', null, ['class' => 'form-control', 'placeholder' => __('Enter Total Family Member')]) }}
                            </div> -->

                            <div class=" col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="">Emergency Contact Number</label> <span class="text-danger">*</span>
                                    <input type="text" name="emergency_phone_number" class="form-control phone_number" placeholder="xxx-xxx-xxxx" required>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('profile', __('Profile (Optional)'), ['class' => 'form-label']) }}
                                {{ Form::file('profile', ['id' => 'profile', 'class' => 'form-control file-input']) }}
                                <div class="preview mt-2 small text-muted" style="color: white !important;"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-6 d-flex">
                <div class="card box-card w-100">
                    <div class="card-header">
                        <h5>{{ __('Address Details') }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <!-- <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                                {{ Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country')]) }}
                            </div> -->

                            <div class="form-group ">
                                {{ Form::label('address', __('Address'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::textarea('address', null, ['class' => 'form-control','required' => 'required', 'rows' => 1, 'placeholder' => __('Enter Address')]) }}
                            </div>

                            {{ Form::hidden('country', $unit->country ?? 'USA') }}

                            <!-- <div class="form-group col-lg-4 col-md-4">
                                {{ Form::label('state', __('State'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter State')]) }}
                            </div>
                            <div class="form-group col-lg-4 col-md-4">
                                {{ Form::label('city', __('City'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter City')]) }}
                            </div> -->

                           <div class="form-group col-lg-4 col-md-4">
                                {{ Form::label('state', __('State'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::select(
                                    'state', 
                                    $statesdata->pluck('name','id')->toArray(),  // value=id, text=name
                                    $unit->state ?? null, 
                                    [
                                        'class' => 'form-control', 
                                        'id' => 'company_state', 
                                        'required' => 'required', 
                                        'placeholder' => __('Select'),
                                    ]
                                ) }}
                            </div>

                        {{-- City --}}

                        <div class="form-group col-lg-4 col-md-4">
                            <label for="company_city" class="form-label">City <span class="text-danger">*</span></label>
                            <select name="city" id="company_city" class="form-control" required>
                                <option value="" style="background-color: black;">Select</option>
                            </select>
                        </div>

                            <div class="form-group col-lg-4 col-md-4">
                                {{ Form::label('zip_code', __('Zip Code'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::text('zip_code', null, ['class' => 'form-control','required' => 'required', 'placeholder' => __('Enter Zip Code')]) }}
                            </div>
                            
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex">
                <div class="card box-card w-100">
                    <div class="card-header">
                        <h5>{{ __('Property Details') }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <!-- <div class="form-group col-lg-6 col-md-6"> -->
                               <!--  {{ Form::label('property', __('Property'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                                {{ Form::select('property', $property, null, ['class' => 'form-control hidesearch', 'id' => 'property']) }} -->
                              <!--   {{ Form::label('property', __('Property'), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::select(
                                        'property',
                                        $property,     // आपका array/collection
                                        null,          // default selected
                                        [
                                            'class' => 'form-control hidesearch',
                                            'id' => 'property',
                                            'required' => 'required',
                                            'placeholder' => __('Select')  // 👈 यह add करें
                                        ]
                                    ) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('unit', __('Unit'), ['class' => 'form-label']) }}
                                <div class="unit_div">
                                    <select class="form-control hidesearch unit" id="unit" name="unit">
                                        <option value="">{{ __('Select Unit') }}</option>
                                    </select>
                                </div>
                            </div> -->

                            <!-- Property Select -->
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('property', __('Property'), ['class' => 'form-label']) }}
                                <span class="text-danger">*</span>
                                {{ Form::select(
                                    'property',
                                    $property,     // array or collection: [id => name]
                                    null,
                                    [
                                        'class' => 'form-control',
                                        'id' => 'propertyall',
                                        'required' => 'required',
                                        'placeholder' => __('Select'),
                                    ]
                                ) }}
                            </div>

                            <!-- Unit Select -->
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('unit', __('Unit'), ['class' => 'form-label']) }}
                                <div class="unit_div">
                                    <select class="form-control" id="unitall" name="unit">
                                        <option value="" style="">{{ __('Select Unit') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('lease_start_date', __('Start Date'), ['class' => 'form-label']) }}
                                {{ Form::date('lease_start_date', null, ['class' => 'form-control', 'placeholder' => __('Enter lease start date')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('lease_end_date', __('End Date'), ['class' => 'form-label']) }}
                                {{ Form::date('lease_end_date', null, ['class' => 'form-control', 'placeholder' => __('Enter lease end date')]) }}
                            </div> -->
                            {{--<div class="col-md-6 mb-3">
                                <label for="lease_start_date" class="form-label">Lease Start Date  <span class="text-danger">*</span></label>
                                <input type="text" id="lease_start_date" class="form-control" placeholder="MM-DD-YY" name="lease_start_date" autocomplete="off" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lease_end_date" class="form-label">Lease End Date  <span class="text-danger">*</span></label>
                                <input type="text" id="lease_end_date" class="form-control" placeholder="MM-DD-YY" name="lease_end_date" autocomplete="off" required>
                            </div>--}}
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex">
                <div class="card box-card w-100">
                    <div class="card-header">
                        <h5>{{ __('Documents Upload') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- <div class="form-group ">
                            <label for="formFile" class="form-label">Contract Document </label>
                            <input class="form-control" name="contract_document" type="file" id="formFile">
                        </div>   -->
                       <div class="form-group mb-3">
                            <label for="personal_document" class="form-label">Application Document</label>
                            <input class="form-control file-input" name="personal_document" type="file" id="personal_document">
                            <div class="preview mt-2 small text-muted" style="color: white !important;"></div>
                        </div> 

                        <div class="form-group mb-3">
                            <label for="ic_document" class="form-label">Driving Licence</label>
                            <input class="form-control file-input" name="ic_document" type="file" id="ic_document">
                            <div class="preview mt-2 small text-muted" style="color: white !important;"></div>
                        </div> 

                        <div class="form-group mb-3">
                            <label for="miscellaneous" class="form-label">Bank Statement</label>
                            <input class="form-control file-input" name="miscellaneous" type="file" id="miscellaneous">
                            <div class="preview mt-2 small text-muted" style="color: white !important;"></div>
                        </div> 
                          
                    </div>
                    <!-- <div class="card-body">
                        <div class="dropzone needsclick" id='demo-upload' action="#">
                            <div class="dz-message needsclick">
                                <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                <h3>{{ __('Drop files here or click to upload.') }}</h3>
                            </div>
                        </div>
                        <div class="preview-dropzon" style="display: none;">
                            <div class="dz-preview dz-file-preview">
                                <div class="dz-image"><img data-dz-thumbnail="" src="" alt="">
                                </div>
                                <div class="dz-details">
                                    <div class="dz-size"><span data-dz-size=""></span></div>
                                    <div class="dz-filename"><span data-dz-name=""></span></div>
                                </div>
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="">
                                    </span></div>
                                <div class="dz-success-mark"><i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-12 mb-2">
                <div class="group-button text-end">
                    {{ Form::submit(__('Create'), ['class' => 'btn btn-secondary btn-rounded', 'id' => 'tenant-submit']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

<script>
    document.querySelectorAll('.file-input').forEach(input => {
      input.addEventListener('change', function () {
        const preview = this.nextElementSibling; 
        preview.innerHTML = ''; // reset old preview

        if (this.files && this.files[0]) {
          const file = this.files[0];
          const fileName = `<strong>${file.name}</strong> (${(file.size / 1024).toFixed(1)} KB)`;

          // Image preview (jpg/png/webp/gif)
          if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = "img-thumbnail mt-1";
            img.style.maxHeight = "100px";
            preview.innerHTML = fileName + "<br>";
            preview.appendChild(img);
          } else {
            // For pdf/zip/mp4 etc → just show icon + name
            preview.innerHTML = `📄 ${fileName}`;
          }
        }
      });
    });
  </script>

@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {
    const phoneInputs = document.getElementsByClassName('phone_number');

    Array.from(phoneInputs).forEach(function(input) {
        input.addEventListener('input', function (e) {
            let x = e.target.value.replace(/\D/g, '').substring(0, 10); // only digits
            let formattedNumber = '';

            if (x.length > 6) {
                formattedNumber = x.replace(/(\d{3})(\d{3})(\d{1,4})/, '$1-$2-$3');
            } else if (x.length > 3) {
                formattedNumber = x.replace(/(\d{3})(\d{1,3})/, '$1-$2');
            } else {
                formattedNumber = x;
            }

            e.target.value = formattedNumber;
        });
    });
});
</script>


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
                            $('#company_city').append('<option value="'+ city.id +'" style="background-color: ;">'+ city.name +'</option>');
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
        // var selectedState = "{{ $unit->state ?? '' }}";
        // var selectedCity = "{{ $unit->city ?? '' }}";

        if (selectedState) {
            $('#company_state').val(selectedState);
            loadCities(selectedState, selectedCity);
        }
    });
</script>


<script>
    $(document).ready(function () {
        $('#propertyall').on('change', function () {
            var property_id = $(this).val();

            // Reset unit dropdown
            $('#unitall').empty().append('<option value="">{{ __("Select Unit") }}</option>');

            if (property_id) {
                var url = "{{ route('property.unit', ':id') }}";  
                url = url.replace(':id', property_id); // replace placeholder with actual id

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (data) {
                        $.each(data, function (id, name) {
                           $('#unitall').append('<option value="' + id + '" style="">' + name + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>


 