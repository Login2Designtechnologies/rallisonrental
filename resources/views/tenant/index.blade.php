@extends('layouts.app')
@section('page-title')
    {{ __('Tenant') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Tenant') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-custom border">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5>{{ __('Tenant List') }}</h5>
                        </div>
                        @can('create tenant')
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="{{ route('tenant.create') }}" data-size="md"> <i
                                        class="ti ti-circle-plus align-text-bottom"></i> {{ __('Create Tenant') }}</a>
                            </div>
                        @endcan
                    </div>
                </div>

                <div class="card-body w-100">
                    <div class="row">
                        @foreach ($tenants as $tenant)
                            <div class="col-xxl-3 col-xl-4 col-md-6 d-flex">
                                <div class="card follower-card w-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <div class="d-flex">
                                                    <img class="img-fluid wid-70 me-2 tenant-img"
                                                    src="{{ !empty($tenant->user) && !empty($tenant->user->profile) ? asset(Storage::url('upload/profile/' . $tenant->user->profile)) : asset(Storage::url('upload/profile/avatar.png')) }}"
                                                    alt="">

                                                   <div>
                                                    <a href="{{ route('tenant.show', $tenant->id) }}">
                                                        <h4>
                                                            <span class="d-block">{{ ucfirst(!empty($tenant->user) ? $tenant->user->first_name : '') }}</span>
                                                            <span class="d-block">{{ ucfirst(!empty($tenant->user) ? $tenant->user->last_name : '') }}</span>
                                                        </h4>
                                                    </a>
                                                        <!-- <a href="{{ route('tenant.show', $tenant->id) }}">
                                                            <h4>{{ ucfirst(!empty($tenant->user) ? $tenant->user->first_name : '') . ' ' . ucfirst(!empty($tenant->user) ? $tenant->user->last_name : '') }}
                                                            </h4>
                                                        </a> -->
                                                        <!-- <h6 class="text-truncate  d-flex align-items-center mb-1">
                                                            {{ !empty($tenant->user) ? $tenant->user->email : '-' }}
                                                        </h6> -->
                                                        <!-- <h6 class="text-truncate  d-flex align-items-center">{{ $tenant->address }}</h6> -->
                                                   </div>
                                                </div>
                                            </div>
                                            @if (Gate::check('edit tenant') || Gate::check('delete tenant') || Gate::check('show tenant'))
                                                <div class="flex-shrink-0">
                                                    <div class="dropdown">
                                                        <a class="dropdown-toggle text-primary opacity-50 arrow-none"
                                                            href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-dots f-16"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item"
                                                                href="{{ route('tenant.edit', $tenant->id) }}">
                                                                <i class="material-icons-two-tone">edit</i>
                                                                {{ __('Edit Tenant') }}
                                                            </a>

                                                            @can('show tenant')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('tenant.show', $tenant->id) }}">
                                                                    <i class="material-icons-two-tone">remove_red_eye</i>
                                                                    {{ __('View Tenant') }}
                                                                </a>
                                                            @endcan
                                                            @can('delete tenant')
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['tenant.destroy', $tenant->id],
                                                                    'id' => 'tenant-' . $tenant->id,
                                                                ]) !!}
                                                                <a class="dropdown-item" href="#">
                                                                    <i class="material-icons-two-tone">delete</i>
                                                                    {{ __('Delete Tenant') }}
                                                                </a>
                                                                {!! Form::close() !!}
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        

                                        <div class="row">
                                           {{-- <div class="col-sm-12 mb-2">
                                                <h5 class="text-primary"><i
                                                        class="ti ti-info-circle bg-light-info rounded-pill"></i>
                                                    {{ __('Infomation') }}
                                                </h5> 
                                                <p class="">{{ $tenant->address }}</p>
                                            </div>--}}
                                            
                                            <div class="col-sm-6 mb-3">
                                                <p class="mb-0 text-sm">{{ __('Property Name') }} :</p>
                                                <h6 class="mb-0">
                                                    {{ !empty($tenant->properties) ? $tenant->properties->name : '-' }}
                                                </h6>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <p class="mb-0 text-sm">{{ __('Unit (if exist) ') }} :</p>
                                                <h6 class="mb-0">
                                                    {{ !empty($tenant->units) ? $tenant->units->name : '-' }}
                                                </h6>
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <p class="mb-0  text-sm">{{ __('Lease Start Date') }} :</p>
                                                <!-- <h6 class="mb-0">{{ dateFormat($tenant->lease_start_date) }}</h6> -->
                                                {{--<!-- <h6 class="mb-0">{{ \Carbon\Carbon::parse($tenant->lease_start_date)->format('m-d-y') }}</h6> -->--}}
                                                <h6 class="mb-0 date-block">{{ $tenant->lease_start_date }}</h6>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <p class="mb-0  text-sm">{{ __('Lease End Date') }} :</p>
                                                <!-- <h6 class="mb-0">{{ dateFormat($tenant->lease_end_date) }}</h6> -->
                                                 {{--<!-- <h6 class="mb-0">{{ \Carbon\Carbon::parse($tenant->lease_end_date)->format('m-d-y') }}</h6> -->--}}
                                                 <h6 class="mb-0 date-block">{{ $tenant->lease_end_date }}</h6>
                                            </div>

                                            <!-- <div class="col-sm-6 mb-3">
                                                <p class="mb-0  text-sm">{{ __('Phone') }} :</p>
                                                <h6 class="mb-0">
                                                    {{ !empty($tenant->user) ? $tenant->user->phone_number : '-' }}</h6>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <p class="mb-0  text-sm">{{ __('Emergency Contact No.') }} :</p>
                                                <h6 class="mb-0">{{ !empty($tenant->user->emergency_phone_number) ? $tenant->user->emergency_phone_number : '-' }}</h6>
                                            </div> -->

                                            <div class="col-sm-6 mb-3">
                                                <p class="mb-0  text-sm">{{ __('Amount Due') }} :</p>
                                                <h6 class="mb-0">
                                                    $</h6>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <p class="mb-0  text-sm">{{ __('Amount Past Due') }} :</p>
                                                <h6 class="mb-0"> $</h6>
                                            </div>
                                            <!-- <div class="col-sm-6 mb-3">
                                                <p class="mb-0  text-sm">{{ __('Property') }} :</p>
                                                <h6 class="mb-0">
                                                    {{ !empty($tenant->properties) ? $tenant->properties->name : '-' }}
                                                </h6>
                                            </div> -->
                                            <!-- <div class="col-sm-6 mb-3">
                                                <p class="mb-0  text-sm">{{ __('Unit') }} :</p>
                                                <h6 class="mb-0">
                                                    {{ !empty($tenant->units) ? $tenant->units->name : '-' }}
                                                </h6>
                                            </div> -->
                                            <div class="btn-block">
                                                <a href="{{ route('tenant.show', $tenant->id) }}" class="btn btn-md btn-outline-secondary">Manage Tenant</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
