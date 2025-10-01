@extends('layouts.app')
@section('page-title')
    {{ __('View Payments') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('View Payments') }}</li>
@endsection
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@push('script-page')
<script>
 
    </script>
@endpush

<style>
    .card.bg-custom.radius-40.bg-box{border: 1px solid #34a891;}
    .balance-box {
      text-align: center;
      padding: 30px;
    }
    .balance-box h2 {
      font-size: 2.5rem;
      font-weight: bold;
    }
    .quick-links .circle {
      width: 70px;
      height: 70px;
      background: #e6faff;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      margin: auto;
      font-size: 24px;
    }
    .quick-links a {
      margin-top: 10px;
      font-size: 14px;
    }
    .tabs.nav-tabs .nav-link{ border-radius: 30px;
      padding: 5px 15px;}
   .tabs.nav-tabs .nav-link.active {
      background-color: #00c4b4;
      color: #fff !important;
      border: none;
     
    }
</style>
@section('content')
<div class="card border bg-custom w-100">
    <!-- Balance Section -->
    <div class="balance-box">
        <p class="text-muted">Your Balance Due</p>
        <h2>$1,563.00</h2>
        <a href="{{ url('make-payment') }}" class="btn btn-info text-white px-4 mt-2">Make Payment</a>
        <div class="mt-2"><a href="#" class="text-info text-decoration-none">View Details</a></div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs justify-content-center my-4 tabs">
        <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Events</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Announcements</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Amenities</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Classifieds</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Clubs</a></li>
    </ul>

    <!-- Quick Links -->
    <div class="">
        <div class="container">
            <h5 class="mb-4"><b>Quick Links</b></h5>
            <div class="row text-center quick-links">
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <div class="circle"><i class="bi bi-tools"></i></div>
                    <a href="">Request Maintenance</a>
                    </div>
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                <div class="circle"><i class="bi bi-shield-check"></i></div>
                 <a href="">Add / Update Insurance</a>
                </div>
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                <div class="circle"><i class="bi bi-telephone"></i></div>
                 <a href="">Contact Us</a>
                </div>
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                <div class="circle"><i class="bi bi-file-earmark-text"></i></div>
                 <a href="">View Documents</a>
                </div>
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                <div class="circle"><i class="bi bi-calendar-check"></i></div>
                 <a href="">Reserve Amenity</a>
                </div>
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                <div class="circle"><i class="bi bi-box-seam"></i></div>
                 <a href="">Rent Items</a>
                </div>
                <!-- <div class="col-6 col-md-3 col-lg-2 mb-4">
                <div class="circle"><i class="bi bi-people"></i></div>
                 <a href="">Refer a Friend</a>
                </div> -->
            </div>
        </div>
    </div>
</div>

<!-- Views -->



@endsection
