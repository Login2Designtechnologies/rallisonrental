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

<div class="card bg-custom border p-25">
    <form action="{{url('addUtilities-store/'.$id)}}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="companyName" class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" id="companyName" name="name" class="form-control" placeholder="Enter Company Name" required>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="subcategorySelect" class="form-label">Sub Category <span class="text-danger">*</span></label>
                    <select id="subcategorySelect" class="form-control form-select" name="sub_category" required>
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-12">
                <div id="subcategoryFields" class="mb-3 d-none">
                    <label class="form-label">Enter Sub Categories <span class="text-danger">*</span></label>
                    <div id="subcategoryList"></div>
                    <div class="d-flex justify-content-end">
                        <button type="button" id="addMoreBtn" class="btn btn-outline-secondary btn-sm mb-3"> Add More
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
               <div class="mb-3">
                    <label for="subcategorySelect" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>


            <div class="col-lg-12 d-flex justify-content-center">
                <button type="submit" class="btn btn-secondary ">
                    Save
                </button>
            </div>
        </div>
        </form>
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
        if (subcategorySelect.value === '1') {
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
            <input type="text" name="sub_category_name[]" class="form-control" placeholder="Enter Sub Category">
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

    // saveBtn.addEventListener('click', () => {
    //     alert('Form saved successfully!');
    // });
});
</script>
@endpush
