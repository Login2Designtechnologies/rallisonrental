@extends('layouts.app')
@section('page-title')
    {{ __('Packages') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Packages') }}</li>
@endsection

@section('content')
<style>
    .box {
  position: relative;
  overflow: hidden;
  width: 100%;
  height: 100%;
  padding: 20px;
  box-sizing: border-box;
  text-align: center;
  background: linear-gradient(0deg, #202020, #171940);
  border-top-left-radius: 50px;
  border-top-right-radius: 50px;
  border-bottom-left-radius: 150px;
  border-bottom-right-radius: 150px;
  border: 3px solid #28a56d;
  box-shadow: 0 0 0 6px #323232, 0 0 0 10px #16a968, 0 0 0 20px #323232, 0 10px 15px rgb(0, 0, 0);
  transition: 0.5s;
}

@media (min-width: 840px) {
  .box:hover {
    transform: scale(1.1);
  }
}

.box::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 50%;
  height: 100%;
  background: rgba(255, 255, 255, 0.1);
  pointer-events: none;
}

.box .title .fa {
  margin-top: 20px;
  font-size: 60px;
  color: #28a56d;
}

.box .title h2 {
  color: #fff;
  margin: 20px 0 0;
  padding: 0;
}

.box .price h4 {
  font-size: 60px;
  color: #28a56d;
  margin: 10px 0;
  padding: 0;
}

.box .option ul {
  margin: 20px 0;
  padding: 0;
  list-style: none;
}

.box .option ul li {
  color: #fff;
  padding: 10px 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.box .option ul li:last-child {
  border-bottom: none;
}

.box .btn {
  display: inline-block;
  background: #ffc98e;
  color: #262626;
  font-weight: bold;
  padding: 10px 30px;
  margin-top: 20px;
  text-decoration: none;
  border-radius: 10px;
}
.box small{color: #fff;}
  
</style>
    <div class="">
        <div class="">

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5>{{ __('Pricing Packages List') }}</h5>
                        </div>
                        @if (
                            \Auth::user()->type == 'super admin' &&
                                (subscriptionPaymentSettings()['STRIPE_PAYMENT'] == 'on' ||
                                    subscriptionPaymentSettings()['paypal_payment'] == 'on' ||
                                    subscriptionPaymentSettings()['bank_transfer_payment'] == 'on'))
                            <div class="col-auto">
                                <a href="#" class="btn btn-secondary customModal" data-size="md"
                                    data-url="{{ route('subscriptions.create') }}" data-title="{{ __('Create Package') }}">
                                    <i class="ti ti-circle-plus align-text-bottom"></i> {{ __('Create Package') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- <div class="table-responsive">
                    <div class="price-card2">
                        @php
                            $features = [
                                __('User Limit'),
                                __('Property Limit'),
                                __('Tenant Limit'),
                                __('Enabled Logged History'),
                                __('Coupon Applicable'),
                            ];
                        @endphp
                        <table class="table table-striped m-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Features') }}</th>
                                    @foreach ($subscriptions as $subscription)
                                        <th>
                                            <div class="card-body border-start text-center py-5 py-md-5">
                                                <h3 class="text-primary"><b> {{ $subscription->title }}</b></h3>
                                                <h3 class="text-muted mb-0 mt-5">
                                                    <b>
                                                        <sup>{{ subscriptionPaymentSettings()['CURRENCY_SYMBOL'] }}</sup>
                                                        {{ $subscription->package_amount }}
                                                        <span>/{{ $subscription->interval }}</span>
                                                    </b>
                                                </h3>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($features as $feature)
                                    <tr>
                                        <td>{{ __($feature) }}</td>
                                        @foreach ($subscriptions as $subscription)
                                            <td class="text-center">
                                                @switch($feature)
                                                    @case(__('User Limit'))
                                                        {{ $subscription->user_limit }}
                                                    @break

                                                    @case(__('Property Limit'))
                                                        {{ $subscription->property_limit }}
                                                    @break

                                                    @case(__('Tenant Limit'))
                                                        {{ $subscription->tenant_limit }}
                                                    @break

                                                    @case(__('Enabled Logged History'))
                                                        @if ($subscription->enabled_logged_history)
                                                            <div class="bg-success text-white avtar avtar-xs icon">
                                                                <i class="ti ti-check f-20"></i>
                                                            </div>
                                                        @else
                                                            <div class="bg-danger text-white avtar avtar-xs icon">
                                                                <i class="ti ti-x f-20"></i>
                                                            </div>
                                                        @endif
                                                    @break

                                                    @case(__('Coupon Applicable'))
                                                        @if ($subscription->couponCheck() > 0)
                                                            <div class="bg-success text-white avtar avtar-xs icon">
                                                                <i class="ti ti-check f-20"></i>
                                                            </div>
                                                        @else
                                                            <div class="bg-danger text-white avtar avtar-xs icon">
                                                                <i class="ti ti-x f-20"></i>
                                                            </div>
                                                        @endif
                                                    @break
                                                @endswitch
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    @foreach ($subscriptions as $subscription)
                                        <td class="text-center">
                                            @if (\Auth::user()->type != 'super admin' && \Auth::user()->subscription == $subscription->id)
                                                <span class="badge text-bg-success">{{ __('Active') }}</span>
                                                <br>
                                                <span>{{ \Auth::user()->subscription_expire_date ? dateFormat(\Auth::user()->subscription_expire_date) : __('Unlimited') }}</span>
                                                {{ __('Expiry Date') }}
                                            @else
                                                @if (\Auth::user()->type == 'owner' && \Auth::user()->subscription != $subscription->id)
                                                    <div class="border-start py-4 py-md-5">
                                                        <a href="{{ route('subscriptions.show', \Illuminate\Support\Facades\Crypt::encrypt($subscription->id)) }}"
                                                            class="btn btn-outline-primary bg-light text-primary">
                                                            {{ __('Purchase Now') }}
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['subscriptions.destroy', $subscription->id]]) !!}
                                            @can('edit pricing packages')
                                                <a class="text-secondary customModal" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Edit') }}" href="#"
                                                    data-url="{{ route('subscriptions.edit', $subscription->id) }}"
                                                    data-title="{{ __('Edit Package') }}"> <i data-feather="edit"></i></a>
                                            @endcan
                                            @if ($subscription->id != 1)
                                                @can('delete pricing packages')
                                                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Detete') }}" href="#"> <i
                                                            data-feather="trash-2"></i></a>
                                                @endcan
                                            @endif
                                            {!! Form::close() !!}

                                        </td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div> -->

<!-- New Code -->
<div class="card">
    <div class="card-body">
        <div class="row p-3">
            @foreach ($subscriptions as $subscription)
                <div class="col-md-4 mb-4">
                    <div class="box">
                        <div class="title">
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            <h2>{{ $subscription->title }}</h2>
                        </div>

                        <div class="price">
                            <h4>
                                <sup>{{ subscriptionPaymentSettings()['CURRENCY_SYMBOL'] }}</sup>{{ $subscription->package_amount }}
                            </h4>
                            <small>/{{ ucfirst($subscription->interval) }}</small>
                        </div>

                        <div class="option">
                            <ul>
                                <li><i class="fa fa-check"></i> User Limit: {{ $subscription->user_limit ?? '-' }}</li>
                                <li><i class="fa fa-check"></i> Property Limit: {{ $subscription->property_limit ?? '-' }}</li>
                                <li><i class="fa fa-check"></i> Tenant Limit: {{ $subscription->tenant_limit ?? '-' }}</li>

                                <li>
                                    @if ($subscription->enabled_logged_history)
                                        <i class="fa fa-check text-success"></i> Logged History Enabled
                                    @else
                                        <i class="fa fa-times text-danger"></i> Logged History Disabled
                                    @endif
                                </li>

                                <li>
                                    @if ($subscription->couponCheck() > 0)
                                        <i class="fa fa-check text-success"></i> Coupon Applicable
                                    @else
                                        <i class="fa fa-times text-danger"></i> Coupon Not Applicable
                                    @endif
                                </li>
                            </ul>
                        </div>

                        <div class="text-center mt-3">
                            @if (\Auth::user()->type != 'super admin' && \Auth::user()->subscription == $subscription->id)
                                <span class="badge bg-success">{{ __('Active') }}</span><br>
                                <small>
                                    {{ \Auth::user()->subscription_expire_date
                                        ? dateFormat(\Auth::user()->subscription_expire_date)
                                        : __('Unlimited') }}
                                    {{ __('Expiry Date') }}
                                </small>
                            @else
                                @if (\Auth::user()->type == 'owner' && \Auth::user()->subscription != $subscription->id)
                                    <a href="{{ route('subscriptions.show', \Illuminate\Support\Facades\Crypt::encrypt($subscription->id)) }}"
                                    class="btn btn-primary mt-2">
                                        {{ __('Purchase Now') }}
                                    </a>
                                @endif
                            @endif

                            {{-- Admin Edit/Delete Buttons --}}
                            {!! Form::open(['method' => 'DELETE', 'route' => ['subscriptions.destroy', $subscription->id]]) !!}
                            @can('edit pricing packages')
                                <a class="text-secondary customModal" data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                href="#"
                                data-url="{{ route('subscriptions.edit', $subscription->id) }}"
                                data-title="{{ __('Edit Package') }}">
                                    <i data-feather="edit"></i>
                                </a>
                            @endcan

                            @if ($subscription->id != 1)
                                @can('delete pricing packages')
                                    <a class="text-danger confirm_dialog" data-bs-toggle="tooltip" title="{{ __('Delete') }}" href="#">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                @endcan
                            @endif
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- ./New Code -->
            </div>
        </div>
    </div>
@endsection
