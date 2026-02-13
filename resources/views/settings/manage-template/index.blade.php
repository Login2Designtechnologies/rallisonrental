@extends('layouts.app')
@section('page-title')
    {{ __('Manage Notice') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Manage Template List') }}</li>
@endsection


@section('content')

<style>
    .dt-buttons.btn-group.flex-wrap {
        display: none;
    }
    @media (min-width: 991px) {
  div.dt-container div.dt-search {
    position: absolute;
    top: 12px;
    left: 100px;
  }
}
</style>

<div class="card bg-custom border p-25">
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header py-2 px-0">
                    <div class="row align-items-center g-2">
                        <div class="col">
                        </div>
                            <div class="col-auto">
                                <a href="{{url('add-template')}}" class="btn btn-secondary customModal" data-size="lg"
                                    data-url="{{url('add-template')}}" data-title="{{ __('Create template') }}"> <i
                                        class="ti ti-circle-plus align-text-bottom"></i> Create Manage Template</a>
                            </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                        @foreach($templatedata as $value)
                            <tr>
                                <td>{{$value->id}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->subject}}</td>
                                <td>
                                  @if($value->status == '1')
                                     Active
                                  @else
                                     Inactive
                                  @endif
                                    <!-- <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                    </div> -->
                                </td>
                                <td>
                                    <a href="{{url('edit-template/'.$value->id)}}">
                                        <i class="ti ti-pencil me-2 text-warning editRow fs-4" data-bs-toggle="tooltip" title="Edit"></i>
                                    </a>
                                    <!-- <i class="ti ti-trash text-danger deleteRow fs-4" data-bs-toggle="tooltip" title="Delete"></i> -->
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
@endsection
