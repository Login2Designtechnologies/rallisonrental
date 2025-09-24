@extends('layouts.app')
@section('page-title')
    {{ __('Add Manage Notice') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('ticket-support') }}">{{ __('Ticket List') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Add') }}</li>
    
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                    <input class="form-control @error('subject') is-invalid @enderror" placeholder="Enter Subject" required="required" name="subject" type="text" value="{{ old('subject') }}" id="subject" required>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-lg-6">
                    <label for="photo" class="form-label"> Photo </label>
                    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-lg-6">
                    <label for="" class="form-label"> Provide a detailed description </label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6">
                    <label for="" class="form-label"> Select Category </label>                
                    <select name="category" class="form-control  @error('category') is-invalid @enderror" required>
                        <option value="">-- Select --</option>
                        @foreach(\App\Enums\TicketCategory::ALL as $category)
                            <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ ucwords($category) }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-rounded">Submit</button>
                </div>
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
