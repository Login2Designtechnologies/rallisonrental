@extends('layouts.app')
@section('page-title')
    {{ __('Property Details') }}
@endsection
@section('page-class')
    product-detail-page
@endsection
@push('script-page')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">
        <a href="{{ route('property.index') }}">{{ __('Property') }}</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="#">{{ __('Details') }}</a>
    </li>
@endsection


@section('content')
<!-- <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
    ← Back
</a> -->
<div class="card bg-custom border p-25">
    <div class="row">
        <form action="{{url('editAmenities-update/'.$amenitydata->id.'/'.$propertyid)}}" method="post">
           @csrf
            <div class="col-lg-6">
                <div class="mb-3">
                   <label for="amenityName" class="form-label">Amenity Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="amenityName" placeholder="Enter amenity name" value="{{$amenitydata->name}}" required>
                </div>
            </div>

            <!-- <div class="col-lg-6">
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required>
                </div>
            </div> -->

            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="amenityStatus" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-control form-select" name="status" id="amenityStatus">
                        <option value="1" {{ $amenitydata->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $amenitydata->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            
           <div class="btn-block mt-2">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">← Back</button>
                    <button type="submit" class="btn btn-secondary">Save</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const subcategorySelect = document.getElementById('subcategorySelect');
    const subcategoryFields = document.getElementById('subcategoryFields');
    const addMoreBtn = document.getElementById('addMoreBtn');
    const subcategoryList = document.getElementById('subcategoryList');
    const saveBtn = document.getElementById('saveBtn');

    subcategorySelect.addEventListener('change', () => {
        if (subcategorySelect.value === 'Yes') {
            subcategoryFields.classList.remove('d-none');
            if (subcategoryList.children.length === 0) {
                addNewField();
            }
        } else {
            subcategoryFields.classList.add('d-none');
            subcategoryList.innerHTML = '';
        }
    });

    function addNewField() {
        const newField = document.createElement('div');
        newField.classList.add('input-group', 'mb-2');
        newField.innerHTML = `
            <input type="text" name="subcategories[]" class="form-control" placeholder="Enter Sub Category">
            <button class="btn btn-outline-danger remove-btn" type="button"><i class="fa fa-times"></i></button>
        `;
        subcategoryList.appendChild(newField);
        newField.querySelector('.remove-btn').addEventListener('click', () => {
            newField.remove();
        });
    }

    addMoreBtn.addEventListener('click', () => {
        addNewField();
    });

    saveBtn.addEventListener('click', () => {
        alert('Form saved successfully!');
    });
});
</script>
@endpush
