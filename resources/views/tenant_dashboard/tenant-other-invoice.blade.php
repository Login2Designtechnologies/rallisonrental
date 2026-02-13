@extends('layouts.app')
@section('page-title')
    {{ __('other Invoice') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('other Invoice') }}</li>
    
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <form action="" id="" class="search-form">
            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="form-group d-flex align-items-center">
                        <div class="search-button">
                            <input type="text" id="tableFilter" class="form-control" placeholder="Search by name..." />
                            <i class="ti ti-search"></i>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle" id="invoiceTable">
                <thead class="table-dark">
                    <tr>
                        <th>Property Name</th>
                        <th>Tenant Name</th>
                        <th>Invoice No.</th>
                        <th>Invoice Date</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($otherInvoices as $otherInvoice)
                        <tr>
                            <td>{{ $otherInvoice->property->name ?? 'N/A' }}</td>
                            <td>{{ $otherInvoice->tenant->user->name ?? 'N/A' }}</td>
                            <td>{{ $otherInvoice->invoice_no }}</td>
                            <td>{{ $otherInvoice->invoice_date->format('m-d-Y') }}</td>
                            <td>${{ number_format($otherInvoice->amount, 2) }}</td>
                            <td>
                                <a href="{{ route('other_invoices.email_preview', $otherInvoice->id) }}" data-bs-toggle="tooltip" title="View">
                                    <i class="ti ti-eye mx-1"></i>
                                </a>
                                <a href="" data-bs-toggle="tooltip" title="Download">
                                    <i class="ti ti-download mx-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No invoices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 🔍 Simple Client-side Search --}}
<script>
    document.getElementById('tableFilter').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#invoiceTable tbody tr').forEach(row => {
            const propertyName = row.cells[0].textContent.toLowerCase();
            const invoiceNo = row.cells[2].textContent.toLowerCase();
            row.style.display = (propertyName.includes(filter) || invoiceNo.includes(filter)) ? '' : 'none';
        });
    });
</script>
@endsection
