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
<!-- JS -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    let totalBalance = 3500; // Total due
    let selectedAmount = 0;

    const selectedAmountEl = document.getElementById("selectedAmount");
    const remainingBalanceEl = document.getElementById("remainingBalance");

    document.querySelectorAll(".select-btn").forEach(button => {
      button.addEventListener("click", function () {
        const amount = parseFloat(this.dataset.amount);

        if (this.classList.contains("btn-primary")) {
          // Select item
          selectedAmount += amount;
          this.classList.remove("btn-primary");
          this.classList.add("btn-danger");
          this.textContent = "Remove";
        } else {
          // Deselect item
          selectedAmount -= amount;
          this.classList.remove("btn-danger");
          this.classList.add("btn-primary");
          this.textContent = "Select";
        }

        // Update totals
        selectedAmountEl.textContent = selectedAmount.toFixed(2);
        remainingBalanceEl.textContent = (totalBalance - selectedAmount).toFixed(2);
      });
    });
  });
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
<div class="card border bg-white w-100">
  <div class="container my-4 p-3">

    <p><strong>Do it like this (only show if amount is due)</strong></p>

        <!-- Payment Items -->
        <div class="d-flex flex-column gap-2">

            <ul class="ps-0">
            <li class="list-unstyled">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                <span class="first">May Rent Due</span>
                <span class="amount">$1500.00</span>
                <button class="btn btn-sm btn-primary select-btn" data-amount="1500">Select</button>
                </div>

                <ul class="ps-0">
                <li class="list-unstyled">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                    <span class="first ul-first">Past Due Rent</span>
                    <span class="amount">$1500.00</span>
                    <button class="btn btn-sm btn-primary select-btn" data-amount="1500">Select</button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                    <span class="first ul-first">Late Payments Due</span>
                    <span class="amount">$500.00</span>
                    <button class="btn btn-sm btn-primary select-btn" data-amount="500">Select</button>
                    </div>
                </li>
                </ul>
            </li>

            <li class="list-unstyled">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                <span class="first">May Utilities Payment</span>
                <span class="amount">$325.00</span>
                <button class="btn btn-sm btn-primary select-btn" data-amount="325">Select</button>
                </div>

                <ul class="ps-0">
                <li class="list-unstyled">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                    <span class="first ul-first">Past Due</span>
                    <span class="amount">$50.00</span>
                    <button class="btn btn-sm btn-primary select-btn" data-amount="50">Select</button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                    <span class="first ul-first">Late Payments</span>
                    <span class="amount">$25.00</span>
                    <button class="btn btn-sm btn-primary select-btn" data-amount="25">Select</button>
                    </div>
                </li>
                </ul>
            </li>
            </ul>
        </div>

        <!-- Totals Section -->
        <div class="text-center my-4">
            <p><strong>Your Balance Due</strong><br><span id="totalBalance">3500.00</span></p>
            <p><strong>Amount you have selected to Pay:</strong><br><span id="selectedAmount">0.00</span></p>
            <p><strong>Balance of <span id="remainingBalance">3500.00</span></strong></p>
            <button class="btn btn-success px-4">PAY NOW</button>
        </div>
  

      <!-- Quick Links -->
        <div class="row mt-4">
            <div class="col-lg-12 mx-auto">
                <div class="card mb-0 bg-light">
                    <div class="card-body">
                        <strong>Quick links</strong>
                        <div class="row text-center quick-links justify-content-center">
                            
                            <div class="col-6 col-md-3 col-lg-2 mb-4">
                            <div class="circle"><i class="bi bi-receipt"></i></div>
                            <a href="">Invoices</a>
                            </div>
                            
                            <div class="col-6 col-md-3 col-lg-2 mb-4">
                            <div class="circle"><i class="bi bi-file-text"></i></div>
                            <a href="">Statements</a>
                            </div>
                            
                            <div class="col-6 col-md-3 col-lg-2 mb-4">
                            <div class="circle"><i class="bi bi-credit-card"></i></div>
                            <a href="">Update Payment Method</a>
                            </div>
                            
                            <div class="col-6 col-md-3 col-lg-2 mb-4">
                            <div class="circle"><i class="bi bi-folder2-open"></i></div>
                            <a href="">View Documents</a>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
  </div>
</div>

<!-- Views -->



@endsection
