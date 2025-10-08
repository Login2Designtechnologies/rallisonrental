@extends('layouts.app')
@section('page-title')
    {{ __('other Invoice') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('other Invoice') }}</li>
    
@endsection

@section('content')
@if(session('preview_invoice_id'))
    <script>
        window.open("{{ route('other_invoices.email_preview', session('preview_invoice_id')) }}", "_blank");
    </script>
@endif
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
                <div class="col-sm-3 d-flex align-items-center justify-content-end">
                    <a href="{{url('add-other')}}" class="btn btn-secondary text-white"><i class="ti ti-circle-plus align-text-bottom"></i> Add New</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle" id="otherInvoicesTable">
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
                            <td>{{ $otherInvoice->invoice_date->format('Y-m-d') }}</td>
                            <td>${{ number_format($otherInvoice->amount, 2) }}</td>
                            <td>
                                <a href="{{ route('edit_other_invoice', $otherInvoice->id) }}">
                                    <i class="ti ti-pencil editRow fs-4" data-bs-toggle="tooltip" title="Edit"></i>
                                </a>
                                <a href="{{ route('other_invoices.email_preview', $otherInvoice->id) }}" target="_blank">
                                    <i class="ti ti-send sendRow fs-4" data-bs-toggle="tooltip" title="Send Invoice"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No invoices found.</td>
                        </tr>
                    @endforelse                   
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
document.getElementById('tableFilter').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#otherInvoicesTable tbody tr');

    rows.forEach(row => {
        let tenantName = row.cells[1].textContent.toLowerCase();
        row.style.display = tenantName.includes(filter) ? '' : 'none';
    });
});
</script>
<script>
    document.getElementById('tableFilter').addEventListener('keyup', function() {
        let search = this.value;
        fetch(`{{ route('other') }}?search=${search}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector('#otherInvoicesTable tbody').innerHTML = html;
        });
    });
</script>
@endsection
