@extends('layouts.app')
@section('page-title')
    {{ __('System Settings') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('System Settings') }}</li>
@endsection
@php
    $admin_logo = getSettingsValByName('company_logo');
    $profile = asset(Storage::url('upload/profile'));
    $activeTab = session('tab', 'user_profile_settings');
@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box-card w-100">
                <div class="">
                    <div class="row">
                        <div class="col-md-3 d-flex">
                            <ul class="nav flex-column nav-tabs account-tabs card box-card custom-theme w-100" id="myTab" role="tablist">
                                @if (Gate::check('manage account settings'))
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profile-tab-1" data-bs-toggle="tab"
                                            href="#user_profile_settings" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ti ti-user-check me-2 f-20"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('User Profile') }}</h5>
                                                    <small class="text-muted">{{ __('User Profile Settings') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::check('manage password settings'))
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab-2" data-bs-toggle="tab"
                                            href="#password_settings" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ti ti-key me-2 f-20"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('Password') }}</h5>
                                                    <small class="text-muted">{{ __('Password Settings') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::check('manage general settings'))
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab-3" data-bs-toggle="tab" href="#general_settings"
                                            role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ti ti-settings me-2 f-20"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('General') }}</h5>
                                                    <small class="text-muted">{{ __('General Settings') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::check('manage company settings'))
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab-4" data-bs-toggle="tab" href="#company_settings"
                                            role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ti ti-settings me-2 f-20"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('Company') }}</h5>
                                                    <small class="text-muted">{{ __('Company Settings') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::check('manage email settings'))
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" id="profile-tab-5" data-bs-toggle="tab"
                                            href="#email_SMTP_settings" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ti ti-mail me-2 f-20"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('Email') }}</h5>
                                                    <small class="text-muted">{{ __('Email SMTP Settings') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li> -->
                                @endif
                                @if (Gate::check('manage payment settings'))
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" id="profile-tab-6" data-bs-toggle="tab" href="#payment_settings"
                                            role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ti ti-credit-card me-2 f-20"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('Payment') }}</h5>
                                                    <small class="text-muted">{{ __('Payment Settings') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li> -->
                                @endif
                                @if (Gate::check('manage seo settings'))
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab-7" data-bs-toggle="tab"
                                            href="#site_SEO_settings" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ti ti-sitemap me-2 f-20"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('Site SEO') }}</h5>
                                                    <small class="text-muted">{{ __('Site SEO Settings') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::check('manage google recaptcha settings'))
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab-8" data-bs-toggle="tab"
                                            href="#google_recaptcha_settings" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ti ti-brand-google me-2 f-20"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('Google Recaptcha') }}</h5>
                                                    <small
                                                        class="text-muted">{{ __('Google Recaptcha Settings') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::check('manage 2FA settings'))
                                    <!-- <li class="nav-item">
                                        <a class="nav-link {{ empty($activeTab) || $activeTab == '2FA' ? ' active ' : '' }} "
                                            id="profile-tab-9" data-bs-toggle="tab" href="#2FA" role="tab"
                                            aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="ti ti-barcode me-2 f-20"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('2 Factors Authentication') }}</h5>
                                                    <small
                                                        class="text-muted">{{ __('2 Factors Authentication Settings') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li> -->
                                @endif

                                    <!-- <li class="nav-item">
                                        <a class="nav-link" id="tab-state" data-bs-toggle="tab" href="#pane-state" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0"><i class="ti ti-map me-2 f-20"></i></div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('State') }}</h5>
                                                    <small class="text-muted">{{ __('Master for State') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li> -->
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" id="tab-city" data-bs-toggle="tab" href="#pane-city" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0"><i class="ti ti-building-skyscraper me-2 f-20"></i></div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('City') }}</h5>
                                                    <small class="text-muted">{{ __('Master for City') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li> -->
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" id="tab-amenities" data-bs-toggle="tab" href="#pane-amenities" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0"><i class="ti ti-tools me-2 f-20"></i></div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('Amenities') }}</h5>
                                                    <small class="text-muted">{{ __('Master for Amenities') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-utilities" data-bs-toggle="tab" href="#pane-utilities" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0"><i class="ti ti-bulb me-2 f-20"></i></div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h5 class="mb-0">{{ __('Utilities') }}</h5>
                                                    <small class="text-muted">{{ __('Master for Utilities') }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li> -->
                                </ul>
                            </div>
                            <div class="col-md-9 d-flex">
                               <div class="card w-100">
                                 <div class="tab-content card-body">
                                    @if (Gate::check('manage account settings'))
                                        <div class="tab-pane {{ empty($activeTab) || $activeTab == 'user_profile_settings' ? ' active show ' : '' }}" id="user_profile_settings" role="tabpanel"
                                            aria-labelledby="user_profile_settings">
                                            {{ Form::model($loginUser, ['route' => ['setting.account'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ !empty($users->profile) ? $profile . '/' . $users->profile : $profile . '/avatar.png' }}"
                                                        alt="user-image" class="img-fluid rounded-circle wid-80" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                                                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter your name'), 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('email', __('Email Address'), ['class' => 'form-label']) }}
                                                        {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter your email'), 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('profile', __('Profile'), ['class' => 'form-label']) }}
                                                        {{ Form::file('profile', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-6"></div>
                                                <div class="col-6 text-end">
                                                    {{ Form::submit(__('Save'), ['class' => 'btn btn-secondary btn-rounded']) }}
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    @endif
                                    @if (Gate::check('manage password settings'))
                                        <div class="tab-pane {{ !empty($activeTab) && $activeTab == 'password_settings' ? ' active show ' : '' }}" id="password_settings" role="tabpanel"
                                            aria-labelledby="password_settings">
                                            {{ Form::model($loginUser, ['route' => ['setting.password'], 'method' => 'post']) }}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('current_password', __('Current Password'), ['class' => 'form-label']) }}
                                                        {{ Form::password('current_password', ['class' => 'form-control', 'placeholder' => __('Enter your current password'), 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('new_password', __('New Password'), ['class' => 'form-label']) }}
                                                        {{ Form::password('new_password', ['class' => 'form-control', 'placeholder' => __('Enter your new password'), 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('confirm_password', __('Confirm New Password'), ['class' => 'form-label']) }}
                                                        {{ Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => __('Enter your confirm new password'), 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-6"></div>
                                                <div class="col-6 text-end">
                                                    {{ Form::submit(__('Save'), ['class' => 'btn btn-secondary btn-rounded']) }}
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    @endif
                                    @if (Gate::check('manage general settings'))
                                        <div class="tab-pane {{ !empty($activeTab) && $activeTab == 'general_settings' ? ' active show ' : '' }}" id="general_settings" role="tabpanel"
                                            aria-labelledby="general_settings">
                                            {{ Form::model($settings, ['route' => ['setting.general'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('application_name', __('Application Name'), ['class' => 'form-label']) }}
                                                        {{ Form::text('application_name', !empty($settings['app_name']) ? $settings['app_name'] : env('APP_NAME'), ['class' => 'form-control', 'placeholder' => __('Enter your application name'), 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{ Form::label('logo', __('Logo'), ['class' => 'form-label']) }}
                                                        <a href="{{ asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png') }}" target="_blank"><i class="ti ti-eye ms-2 f-15"></i></a>
                                                        {{ Form::file('logo', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{ Form::label('favicon', __('Favicon'), ['class' => 'form-label']) }}
                                                        <a href="{{ asset(Storage::url('upload/logo')) . '/' . $settings['company_favicon'] }}" target="_blank"><i class="ti ti-eye ms-2 f-15"></i></a>
                                                        {{ Form::file('favicon', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{ Form::label('light_logo', __('Light Logo'), ['class' => 'form-label']) }}
                                                        <a href="{{ asset(Storage::url('upload/logo')) . '/' . $settings['light_logo'] }}"
                                                            target="_blank"><i class="ti ti-eye ms-2 f-15"></i></a>
                                                        {{ Form::file('light_logo', ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                                @if (\Auth::user()->type == 'super admin')
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{ Form::label('landing_logo', __('Landing Page Logo'), ['class' => 'form-label']) }}
                                                            <a href="{{ asset(Storage::url('upload/logo/landing_logo.png')) }}" target="_blank"><i class="ti ti-eye ms-2 f-15"></i></a>
                                                            {{ Form::file('landing_logo', ['class' => 'form-control']) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{ Form::label('landing_logo', __('Owner Email Verification'), ['class' => 'form-label']) }}
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" id="owner_email_verification"
                                                                            name="owner_email_verification" {{ $settings['owner_email_verification'] == 'on' ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{ Form::label('landing_logo', __('Registration Page'), ['class' => 'form-label']) }}
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" id="register_page" name="register_page"
                                                                        {{ $settings['register_page'] == 'on' ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{ Form::label('landing_logo', __('Landing Page'), ['class' => 'form-label']) }}
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" id="landing_page" name="landing_page" {{ $settings['landing_page'] == 'on' ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{ Form::label('pricing_feature', __('Pricing Feature'), ['class' => 'form-label']) }}
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" name="pricing_feature" value="off">
                                                                    <input class="form-check-input" type="checkbox" id="pricing_feature" name="pricing_feature" value="on" {{ $settings['pricing_feature'] == 'on' ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-6"></div>
                                                <div class="col-6 text-end">
                                                    {{ Form::submit(__('Save'), ['class' => 'btn btn-secondary btn-rounded']) }}
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    @endif
                                    @if (Gate::check('manage company settings'))
                                        <div class="tab-pane {{ !empty($activeTab) && $activeTab == 'company_settings' ? ' active show ' : '' }}" id="company_settings" role="tabpanel"
                                            aria-labelledby="company_settings">
                                            {{ Form::model($settings, ['route' => ['setting.company'], 'method' => 'post']) }}
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('company_name', __('Name'), ['class' => 'form-label']) }}
                                                    {{ Form::text('company_name', $settings['company_name'], ['class' => 'form-control', 'placeholder' => __('Enter company name')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('company_email', __('Email'), ['class' => 'form-label']) }}
                                                    {{ Form::text('company_email', $settings['company_email'], ['class' => 'form-control', 'placeholder' => __('Enter company email')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('company_phone', __('Phone Number'), ['class' => 'form-label']) }}
                                                    {{ Form::text('company_phone', $settings['company_phone'], ['class' => 'form-control', 'placeholder' => __('Enter company phone')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('company_address', __('Address'), ['class' => 'form-label']) }}
                                                    {{ Form::textarea('company_address', $settings['company_address'], ['class' => 'form-control', 'rows' => '2']) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('CURRENCY_SYMBOL', __('Currency Icon'), ['class' => 'form-label']) }}
                                                    {{ Form::text('CURRENCY_SYMBOL', $settings['CURRENCY_SYMBOL'], ['class' => 'form-control', 'placeholder' => __('Enter currency symbol')]) }}
                                                </div>
                                                <div class="col-md-6">
                                                    {{ Form::label('timezone', __('Timezone'), ['class' => 'form-label text-dark']) }}
                                                    <select type="text" name="timezone" class="form-control basic-select"
                                                        id="timezone">
                                                        <option value="">{{ __('Select Timezone') }}</option>
                                                        @foreach ($timezones as $k => $timezone)
                                                            <option value="{{ $k }}"
                                                                {{ $settings['timezone'] == $k ? 'selected' : '' }}>
                                                                {{ $timezone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    {{ Form::label('invoice_number_prefix', __('Invoice Number Prefix'), ['class' => 'form-label']) }}
                                                    {{ Form::text('invoice_number_prefix', $settings['invoice_number_prefix'], ['class' => 'form-control', 'placeholder' => __('Enter invoice number prefix')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('expense_number_prefix', __('Expense Number Prefix'), ['class' => 'form-label']) }}
                                                    {{ Form::text('expense_number_prefix', $settings['expense_number_prefix'], ['class' => 'form-control', 'placeholder' => __('Enter expense number prefix')]) }}
                                                </div>
                                                <div class="form-group col-md-3">
                                                    {{ Form::label('company_zipcode', __('System Date Format'), ['class' => 'form-label']) }}
                                                    <div class="">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="company_date_format1"
                                                                name="company_date_format" class="custom-control-input"
                                                                value="M j, Y"
                                                                {{ $settings['company_date_format'] == 'M j, Y' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="company_date_format1">{{ date('M d,Y') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="company_date_format2"
                                                                name="company_date_format" class="custom-control-input"
                                                                value="y-m-d"
                                                                {{ $settings['company_date_format'] == 'y-m-d' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="company_date_format2">{{ date('y-m-d') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="company_date_format3"
                                                                name="company_date_format" class="custom-control-input"
                                                                value="d-m-y"
                                                                {{ $settings['company_date_format'] == 'd-m-y' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="company_date_format3">{{ date('d-m-y') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="company_date_format4"
                                                                name="company_date_format" class="custom-control-input"
                                                                value="m-d-y"
                                                                {{ $settings['company_date_format'] == 'm-d-y' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="company_date_format4">{{ date('m-d-y') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    {{ Form::label('company_zipcode', __('System Time Format'), ['class' => 'form-label']) }}
                                                    <div class="">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="company_time_format1"
                                                                name="company_time_format" class="custom-control-input"
                                                                value="H:i"
                                                                {{ $settings['company_time_format'] == 'H:i' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="company_time_format1">{{ date('H:i') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="company_time_format2"
                                                                name="company_time_format" class="custom-control-input"
                                                                value="g:i A"
                                                                {{ $settings['company_time_format'] == 'g:i A' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="company_time_format2">{{ date('g:i A') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="company_time_format3"
                                                                name="company_time_format" class="custom-control-input"
                                                                value="g:i a"
                                                                {{ $settings['company_time_format'] == 'g:i a' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="company_time_format3">{{ date('g:i a') }}</label>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-6"></div>
                                                <div class="col-6 text-end">
                                                    {{ Form::submit(__('Save'), ['class' => 'btn btn-secondary btn-rounded']) }}
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    @endif
                                    @if (Gate::check('manage email settings'))
                                        <div class="tab-pane {{ !empty($activeTab) && $activeTab == 'email_SMTP_settings' ? ' active show ' : '' }}" id="email_SMTP_settings" role="tabpanel"
                                            aria-labelledby="email_SMTP_settings">
                                            {{ Form::model($settings, ['route' => ['setting.smtp'], 'method' => 'post']) }}
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('sender_name', __('Sender Name'), ['class' => 'form-label']) }}
                                                    {{ Form::text('sender_name', $settings['FROM_NAME'], ['class' => 'form-control', 'placeholder' => __('Enter sender name')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('sender_email', __('Sender Email'), ['class' => 'form-label']) }}
                                                    {{ Form::text('sender_email', $settings['FROM_EMAIL'], ['class' => 'form-control', 'placeholder' => __('Enter sender email')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('server_driver', __('SMTP Driver'), ['class' => 'form-label']) }}
                                                    {{ Form::text('server_driver', $settings['SERVER_DRIVER'], ['class' => 'form-control', 'placeholder' => __('Enter smtp driver')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('server_host', __('SMTP Host'), ['class' => 'form-label']) }}
                                                    {{ Form::text('server_host', $settings['SERVER_HOST'], ['class' => 'form-control ', 'placeholder' => __('Enter smtp host')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('server_username', __('SMTP Username'), ['class' => 'form-label']) }}
                                                    {{ Form::text('server_username', $settings['SERVER_USERNAME'], ['class' => 'form-control', 'placeholder' => __('Enter smtp username')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('server_password', __('SMTP Password'), ['class' => 'form-label']) }}
                                                    {{ Form::text('server_password', $settings['SERVER_PASSWORD'], ['class' => 'form-control', 'placeholder' => __('Enter smtp password')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('server_encryption', __('SMTP Encryption'), ['class' => 'form-label']) }}
                                                    {{ Form::text('server_encryption', $settings['SERVER_ENCRYPTION'], ['class' => 'form-control', 'placeholder' => __('Enter smtp encryption')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('server_port', __('SMTP Port'), ['class' => 'form-label']) }}
                                                    {{ Form::text('server_port', $settings['SERVER_PORT'], ['class' => 'form-control', 'placeholder' => __('Enter smtp port')]) }}
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-6"></div>
                                                <div class="col-6 text-end">
                                                    <a href="#" data-size="md" data-url="{{ route('setting.smtp.test') }}" data-title="{{__('Add Email')}}"  class='btn btn-secondary btn-rounded customModal me-1'> {{ __('Test Mail') }} </a>
                                                    {{ Form::submit(__('Save'), ['class' => 'btn btn-primary btn-rounded']) }}
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    @endif
                                    @if (Gate::check('manage payment settings'))
                                        <div class="tab-pane {{ !empty($activeTab) && $activeTab == 'payment_settings' ? ' active show ' : '' }}" id="payment_settings" role="tabpanel"
                                            aria-labelledby="payment_settings">

                                            {{ Form::model($settings, ['route' => ['setting.payment'], 'method' => 'post']) }}
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('CURRENCY_SYMBOL', __('Currency Icon'), ['class' => 'form-label']) }}
                                                    {{ Form::text('CURRENCY_SYMBOL', $settings['CURRENCY_SYMBOL'], ['class' => 'form-control', 'placeholder' => __('Enter currency icon'), 'required']) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('CURRENCY', __('Currency Code'), ['class' => 'form-label']) }}
                                                    {{ Form::text('CURRENCY', $settings['CURRENCY'], ['class' => 'form-control font-style', 'placeholder' => __('Enter currency code'), 'required']) }}
                                                </div>
                                            </div>
                                            <hr>

                                            {{-- ------------------------Stripe Payment settings------------------------------- --}}
                                            <div class="row mt-2">
                                                <div class="col-auto">
                                                    {{ Form::label('stripe_payment', __('Stripe Payment'), ['class' => 'form-label']) }}
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-check custom-chek">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="stripe_payment" id="stripe_payment"
                                                                {{ $settings['STRIPE_PAYMENT'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('stripe_key', __('Account Key'), ['class' => 'form-label']) }}
                                                    {{ Form::text('stripe_key', $settings['STRIPE_KEY'], ['class' => 'form-control', 'placeholder' => __('Enter stripe key')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('stripe_secret', __('Account Secret Key'), ['class' => 'form-label']) }}
                                                    {{ Form::text('stripe_secret', $settings['STRIPE_SECRET'], ['class' => 'form-control ', 'placeholder' => __('Enter stripe secret')]) }}
                                                </div>
                                            </div>
                                            <hr>
                                            {{-- ------------------------Paypal Payment settings------------------------------- --}}
                                            <div class="row mt-2">
                                                <div class="col-auto">
                                                    {{ Form::label('paypal_payment', __('Paypal Payment'), ['class' => 'form-label']) }}
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-check custom-chek">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="paypal_payment" id="paypal_payment"
                                                                {{ $settings['paypal_payment'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    {{ Form::label('paypal_mode', __('Account Mode'), ['class' => 'form-label me-2']) }}
                                                    <div class="form-check custom-chek form-check-inline">
                                                        <input class="form-check-input" type="radio" value="sandbox"
                                                            id="sandbox" name="paypal_mode"
                                                            {{ $settings['paypal_mode'] == 'sandbox' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="sandbox">{{ __('Sandbox') }}
                                                        </label>
                                                    </div>
                                                    <div class="form-check custom-chek form-check-inline">
                                                        <input class="form-check-input" type="radio" value="live"
                                                            id="live" name="paypal_mode"
                                                            {{ $settings['paypal_mode'] == 'live' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="live">{{ __('Live') }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('paypal_client_id', __('Account Client ID'), ['class' => 'form-label']) }}
                                                    {{ Form::text('paypal_client_id', $settings['paypal_client_id'], ['class' => 'form-control', 'placeholder' => __('Enter client id')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('paypal_secret_key', __('Account Secret Key'), ['class' => 'form-label']) }}
                                                    {{ Form::text('paypal_secret_key', $settings['paypal_secret_key'], ['class' => 'form-control ', 'placeholder' => __('Enter secret key')]) }}
                                                </div>
                                            </div>
                                            <hr>
                                            {{-- ------------------------Bank Transfer settings------------------------------- --}}
                                            <div class="row mt-2">
                                                <div class="col-auto">
                                                    {{ Form::label('bank_transfer_payment', __('Bank Transfer Payment'), ['class' => 'form-label']) }}
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-check custom-chek">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="bank_transfer_payment" id="bank_transfer_payment"
                                                                {{ $settings['bank_transfer_payment'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) }}
                                                    {{ Form::text('bank_name', $settings['bank_name'], ['class' => 'form-control', 'placeholder' => __('Enter bank name')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('bank_holder_name', __('Bank Holder Name'), ['class' => 'form-label']) }}
                                                    {{ Form::text('bank_holder_name', $settings['bank_holder_name'], ['class' => 'form-control', 'placeholder' => __('Enter bank holder name')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('bank_account_number', __('Bank Account Number'), ['class' => 'form-label']) }}
                                                    {{ Form::text('bank_account_number', $settings['bank_account_number'], ['class' => 'form-control', 'placeholder' => __('Enter bank account number')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('bank_ifsc_code', __('Bank IFSC'), ['class' => 'form-label']) }}
                                                    {{ Form::text('bank_ifsc_code', $settings['bank_ifsc_code'], ['class' => 'form-control', 'placeholder' => __('Enter bank ifsc code')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('bank_other_details', __('Other Details'), ['class' => 'form-label']) }}
                                                    {{ Form::textarea('bank_other_details', $settings['bank_other_details'], ['class' => 'form-control', 'rows' => 1, 'placeholder' => __('Enter bank other details')]) }}
                                                </div>
                                            </div>

                                            <hr>
                                            {{-- ------------------------Flutterwave settings------------------------------- --}}
                                            <div class="row mt-2">
                                                <div class="col-auto">
                                                    {{ Form::label('flutterwave_payment', __('Flutterwave Payment'), ['class' => 'form-label']) }}
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-check custom-chek">
                                                            <input class="form-check-input" type="checkbox" name="flutterwave_payment"
                                                                id="flutterwave_payment"
                                                                {{ $settings['flutterwave_payment'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('flutterwave_public_key', __('Public Key'), ['class' => 'form-label']) }}
                                                    {{ Form::text('flutterwave_public_key', $settings['flutterwave_public_key'], ['class' => 'form-control', 'placeholder' => __('Enter flutterwave public key')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('flutterwave_secret_key', __('Secret Key'), ['class' => 'form-label']) }}
                                                    {{ Form::text('flutterwave_secret_key', $settings['flutterwave_secret_key'], ['class' => 'form-control', 'placeholder' => __('Enter flutterwave secret key')]) }}
                                                </div>

                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-6"></div>
                                                <div class="col-6 text-end">
                                                    {{ Form::submit(__('Save'), ['class' => 'btn btn-secondary btn-rounded']) }}
                                                </div>
                                            </div>

                                            {{ Form::close() }}
                                        </div>
                                    @endif
                                    @if (Gate::check('manage seo settings'))
                                        <div class="tab-pane {{ !empty($activeTab) && $activeTab == 'site_SEO_settings' ? ' active show ' : '' }}" id="site_SEO_settings" role="tabpanel"
                                            aria-labelledby="site_SEO_settings">
                                            {{ Form::model($settings, ['route' => ['setting.site.seo'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                            <div class="row">

                                                <div class="col-xl-12 col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        {{ Form::label('meta_seo_title', __('Meta Title'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('meta_seo_title', $settings['meta_seo_title'], ['class' => 'form-control', 'placeholder' => __('Enter meta SEO title'), 'required' => 'required']) }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        {{ Form::label('meta_seo_keyword', __('Meta Keyword'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('meta_seo_keyword', $settings['meta_seo_keyword'], ['class' => 'form-control', 'placeholder' => __('Enter meta SEO keyword'), 'required' => 'required']) }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        {{ Form::label('meta_seo_description', __('Meta Description'), ['class' => 'form-label']) }}
                                                                        {{ Form::textarea('meta_seo_description', $settings['meta_seo_description'], ['class' => 'form-control', 'placeholder' => __('Enter meta SEO description'), 'required' => 'required', 'rows' => '2']) }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        {{ Form::label('meta_seo_image', __('Meta Image'), ['class' => 'form-label']) }}
                                                                        {{ Form::file('meta_seo_image', ['class' => 'form-control']) }}
                                                                    </div>
                                                                </div>
                                                                @if (!empty($settings['meta_seo_image']))
                                                                    <div class="col-12 mt-20">
                                                                        <img src="{{ asset(Storage::url('upload/seo')) . '/' . $settings['meta_seo_image'] }}"
                                                                            class="setting-logo" alt="">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col-6"></div>
                                                                <div class="col-6 text-end">
                                                                    {{ Form::submit(__('Save'), ['class' => 'btn btn-secondary btn-rounded']) }}
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{ Form::close() }}
                                        </div>
                                    @endif
                                    @if (Gate::check('manage google recaptcha settings'))
                                        <div class="tab-pane {{ !empty($activeTab) && $activeTab == 'google_recaptcha_settings' ? ' active show ' : '' }}" id="google_recaptcha_settings" role="tabpanel"
                                            aria-labelledby="google_recaptcha_settings">

                                            {{ Form::model($settings, ['route' => ['setting.google.recaptcha'], 'method' => 'post']) }}
                                            <div class="row mt-2">
                                                <div class="col-auto">
                                                    {{ Form::label('google_recaptcha', __('Google ReCaptcha Enable'), ['class' => 'form-label']) }}
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-check custom-chek">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="google_recaptcha" id="google_recaptcha"
                                                                {{ $settings['google_recaptcha'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('recaptcha_key', __('Recaptcha Key'), ['class' => 'form-label']) }}
                                                    {{ Form::text('recaptcha_key', $settings['recaptcha_key'], ['class' => 'form-control', 'placeholder' => __('Enter recaptcha key')]) }}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('recaptcha_secret', __('Recaptcha Secret'), ['class' => 'form-label']) }}
                                                    {{ Form::text('recaptcha_secret', $settings['recaptcha_secret'], ['class' => 'form-control ', 'placeholder' => __('Enter recaptcha secret')]) }}
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-6"></div>
                                                <div class="col-6 text-end">
                                                    {{ Form::submit(__('Save'), ['class' => 'btn btn-secondary btn-rounded']) }}
                                                </div>
                                            </div>
                                            {{ Form::close() }}

                                        </div>
                                    @endif
                                    @if (Gate::check('manage 2FA settings'))
                                        <div class="tab-pane {{ !empty($activeTab) && $activeTab == '2FA' ? ' active show ' : '' }}"
                                            id="2FA" role="tabpanel" aria-labelledby="2FA">

                                            {{ Form::model($settings, ['route' => ['setting.twofa.enable'], 'method' => 'post']) }}
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    @if (empty(\Auth::user()->twofa_secret))
                                                        <label class="form-label">
                                                            {{ __('2-factors authentication is currently') }}
                                                            <span class='badge bg-warning'>{{ __('disabled') }}</span>.
                                                            {{ __('To enable') }}:
                                                        </label>
                                                    @else
                                                    <h5>
                                                        {{ __('2-factors authentication is currently enable.') }}
                                                        <a href="{{ route('2fa.disable') }}" class="ms-2">
                                                            <span class='btn btn-danger btn-rounded'>{{ __('click to disabled') }}</span>
                                                        </a>
                                                    </h5>
                                                    @endif
                                                </div>
                                                @if (empty(\Auth::user()->twofa_secret))
                                                    <div class="col-12">
                                                        <ol class="list-left-align mt-10">
                                                            <li>
                                                                {!! __('Open your OTP app and <b>scan the following QR-code') !!}</b>
                                                                <p class="text-center">
                                                                    {!! QrCode2FA() !!}
                                                                </p>
                                                            </li>

                                                            <li>
                                                                {{ __('Generate a One Time Password (OTP) and enter the value below.') }}
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <input name="otp"
                                                                            class="form-control mr-1{{ $errors->has('otp') ? ' is-invalid' : '' }}"
                                                                            type="number" min="0" max="999999"
                                                                            step="1" required autocomplete="off">
                                                                        @if ($errors->has('otp'))
                                                                            <span class="invalid-feedback text-left">
                                                                                <strong>{{ $errors->first('otp') }}</strong>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ol>
                                                    </div>
                                                @endif
                                            </div>
                                            @if (empty(\Auth::user()->twofa_secret))
                                                <div class="row mt-3">
                                                    <div class="col-12 text-end">
                                                        {{ Form::submit(__('Verify'), ['class' => 'btn btn-secondary btn-rounded']) }}
                                                    </div>
                                                </div>
                                            @endif
                                            {{ Form::close() }}

                                        </div>
                                    @endif

                                    <div class="tab-pane fade" id="pane-state" role="tabpanel" aria-labelledby="tab-state">
                                        <div class="theme-card">
                                            <div class="d-flex justify-content-end">
                                                <a data-bs-toggle="modal" data-bs-target="#addStateModal" class="btn btn-secondary">Add New</a>
                                            </div>
                                            <div class="mt-3">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                    <thead class="table-theme">
                                                        <tr>
                                                        <th>#</th>
                                                        <th>State Name</th>
                                                        <th>Status</th>
                                                        <th style="width: 120px;">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                        <td>1</td>
                                                        <td>California</td>
                                                        <td><span class="badge bg-success">Active</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="ti ti-edit"></i></button>
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                        <td>2</td>
                                                        <td>Texas</td>
                                                        <td><span class="badge bg-danger">Inactive</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="ti ti-edit"></i></button>
                                                        </td>
                                                        </tr>
                                                        <!-- Add more rows as needed -->
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pane-city" role="tabpanel" aria-labelledby="tab-city">
                                        <div class="card theme-card">
                                            <div class="card-header d-flex justify-content-end">
                                                <a data-bs-toggle="modal" data-bs-target="#addCityModal" class="btn btn-secondary">Add New</a>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                    <thead class="table-theme">
                                                        <tr>
                                                        <th>#</th>
                                                        <th>City Name</th>
                                                        <th>Status</th>
                                                        <th style="width: 120px;">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                        <td>1</td>
                                                        <td>New York City</td>
                                                        <td><span class="badge bg-success">Active</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="ti ti-edit"></i></button>
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                        <td>2</td>
                                                        <td>Los Angeles</td>
                                                        <td><span class="badge bg-danger">Inactive</span></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="ti ti-edit"></i></button>
                                                        </td>
                                                        </tr>
                                                        <!-- Add more rows as needed -->
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pane-amenities" role="tabpanel" aria-labelledby="tab-amenities">
                                        <div class="card theme-card">
                                            <div class="card-header d-flex justify-content-end">
                                                <a data-bs-toggle="modal" data-bs-target="#addAmenitiesModal" class="btn btn-secondary">Add New</a>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead class="table-theme">
                                                            <tr>
                                                                <th>S.no</th>
                                                                <th>Amenities Name</th>
                                                                <th>Amount</th>
                                                                <th>Status</th>
                                                                <th style="width: 120px;">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Swimming Pool</td>
                                                                <td>$150</td>
                                                                <td><span class="badge bg-success">Active</span></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                                        <i class="ti ti-edit"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>Gym Access</td>
                                                                <td>$100</td>
                                                                <td><span class="badge bg-danger">Inactive</span></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                                        <i class="ti ti-edit"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <!-- Add more rows as needed -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pane-utilities" role="tabpanel" aria-labelledby="tab-utilities">
                                        <div class="card theme-card">
                                            <div class="card-header d-flex justify-content-end">
                                                <a data-bs-toggle="modal" data-bs-target="#addUtilitiesModal" class="btn btn-secondary">Add New</a>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead class="table-theme">
                                                            <tr>
                                                                <th>S.no</th>
                                                                <th>Utility Name</th>
                                                                <th>Amount</th>
                                                                <th>Status</th>
                                                                <th style="width: 120px;">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Electricity</td>
                                                                <td>$200</td>
                                                                <td><span class="badge bg-success">Active</span></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                                        <i class="ti ti-edit"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>Water Supply</td>
                                                                <td>$50</td>
                                                                <td><span class="badge bg-danger">Inactive</span></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                                        <i class="ti ti-edit"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <!-- Add more rows as needed -->
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
            </div>
        </div>
    </div>
@endsection


<!-- Add State Modal -->
<div class="modal fade" id="addStateModal" tabindex="-1" aria-labelledby="addStateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addStateModalLabel">Add New State</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="stateName" class="form-label">State Name</label>
            <input type="text" class="form-control" id="stateName" placeholder="Enter state name">
          </div>
          <div class="mb-3">
            <label for="stateStatus" class="form-label">Status</label>
            <select class="form-control form-select" id="stateStatus">
                <option value="active">-- Select Status --</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add State</button>
        </div>
      </div>
    </form>
  </div>
</div>



<!-- Add State Modal -->
<div class="modal fade" id="addCityModal" tabindex="-1" aria-labelledby="addStateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addStateModalLabel">Add New City</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="stateName" class="form-label">City Name</label>
            <input type="text" class="form-control" id="stateName" placeholder="Enter state name">
          </div>
          <div class="mb-3">
            <label for="stateStatus" class="form-label">Status</label>
            <select class="form-control form-select" id="stateStatus">
                <option value="active">-- Select Status --</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add City</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Add Utilities Modal -->
<div class="modal fade" id="addUtilitiesModal" tabindex="-1" aria-labelledby="addUtilitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        
            <div class="modal-header">
                <h5 class="modal-title" id="addUtilitiesModalLabel">Add Utility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form id="utilitiesForm">
                    <div class="mb-3">
                        <label for="utilityName" class="form-label">Utility Name</label>
                        <input type="text" class="form-control" id="utilityName" placeholder="Enter utility name">
                    </div>

                    <div class="mb-3">
                        <label for="utilityAmount" class="form-label">Amount ($)</label>
                        <input type="number" class="form-control" id="utilityAmount" placeholder="Enter amount">
                    </div>

                    <div class="mb-3">
                        <label for="utilityStatus" class="form-label">Status</label>
                        <select class="form-control form-select" id="utilityStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="utilitiesForm" class="btn btn-primary">Save</button>
            </div>

        </div>
    </div>
</div>


<!-- Add Amenities Modal -->
<div class="modal fade" id="addAmenitiesModal" tabindex="-1" aria-labelledby="addAmenitiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        
            <div class="modal-header">
                <h5 class="modal-title" id="addAmenitiesModalLabel">Add Amenity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form id="amenitiesForm">
                    <div class="mb-3">
                        <label for="amenityName" class="form-label">Amenity Name</label>
                        <input type="text" class="form-control" id="amenityName" placeholder="Enter amenity name">
                    </div>

                    <div class="mb-3">
                        <label for="amenityAmount" class="form-label">Amount ($)</label>
                        <input type="number" class="form-control" id="amenityAmount" placeholder="Enter amount">
                    </div>

                    <div class="mb-3">
                        <label for="amenityStatus" class="form-label">Status</label>
                        <select class="form-control form-select" id="amenityStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="amenitiesForm" class="btn btn-primary">Save</button>
            </div>

        </div>
    </div>
</div>
