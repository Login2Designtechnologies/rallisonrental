@extends('layouts.app')
@section('page-title')
    {{ __('Edit Manage Template') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('manage-template') }}">{{ __('Manage Template List') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Edit') }}</li>
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form>
            <!-- Template Name -->
            <div class="mb-3">
            <label for="template" class="form-label">Template Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="template" placeholder="Enter Template name" >
            </div>

            <!-- Subject -->
            <div class="mb-3">
            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="subject" placeholder="Enter subject">
            </div>

            <!-- Send Email Status -->
            <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="sendStatus" checked>
            <label class="form-check-label" for="sendStatus">
                Send Email Status
            </label>
            </div>

            <!-- Message (CKEditor) -->
            <div class="mb-3">
            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
            <textarea id="editor">
                Message
            </textarea>
            </div>

            
            <!-- Preview -->
            <div class="mb-3">
            <label for="template" class="form-label">Template Test Email</label>
            <input type="text" class="form-control" id="template" placeholder="Template Test Email">
            </div>


            <!-- Submit Button -->
            <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-4">
                Submit
            </button>
            </div>
        </form>
    </div>
</div>


<!-- CKEditor 5 Classic Build -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });
</script>
@endsection
