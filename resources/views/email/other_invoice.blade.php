<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice {{ $otherInvoice->invoice_no }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .page-frame{max-width:900px;margin:24px auto;border:4px solid #dbd8d8;padding:16px}
        .page-frame .input-group .btn.btn-primary{background-color:#2ca58d}

    @media print{.no-print{display:none}}
    .text-small{font-size:.9rem}
    .w-20{width:20%}.w-25{width:25%}.w-30{width:30%}.w-35{width:35%}.w-50{width:50%}
    .v-top{vertical-align:top}
  </style>
   
</head>
<body>
<div class="page-frame">

    <div class="d-flex justify-content-between mb-3 no-print">
        <div>
            <span class="badge bg-warning text-dark">Unpaid</span>
        </div>        
    </div>  
    <table class="table table-bordered align-middle mb-0">
        <!-- Row 1: Company info (left) + Date/Invoice# (right) -->
        <tr>
        <td class="v-top w-50" rowspan="2" colspan="2">
            <div class="fw-semibold">Insert Company Logo</div>
            <img src="{{ asset('assets/images/authentication/logo2.png') }}" alt="image" class="img-fluid brand-logo">
            <div class="mt-2 fw-bold">{{ $otherInvoice->owner->company_name ?? 'Company Name' }}</div>
            <div>{{ $otherInvoice->owner->address ?? 'Street Address' }}</div>
            <div>{{ $otherInvoice->owner->city ?? 'CITY, ST ZIP' }}</div>
            <div>Phone: {{ $otherInvoice->owner->phone ?? 'XXX.XXX.XXXX' }}</div>
            <div class="text-muted">{{ $otherInvoice->owner->email ?? 'email@example.com' }}</div>
        </td>
        <td class="w-25" rowspan="2" style="vertical-align: top;">
            <div class="text-muted text-small">Date</div>
            <div class="fw-semibold">{{ \Carbon\Carbon::parse($otherInvoice->invoice_date)->format('d M Y') }}</div>
        </td>
        <td class="w-25" rowspan="2" style="vertical-align: top;">
            <div class="text-muted text-small">Invoice #</div>
            <div class="fw-semibold">{{ $otherInvoice->invoice_no }}</div>
        </td>
        </tr>
        <tr>
        <!-- <td colspan="4">
            <div class="text-muted text-small">Start with 1001 per property</div>
            <div><span class="fw-semibold">Property:</span> My Property</div>
        </td> -->
        </tr>

        <!-- Row 2: Bill To / Renter Info -->
        <tr>
            <td colspan="4">
                <div class="text-muted text-small mb-1">Bill To</div>
                <div class="border p-2">
                <div class="fw-semibold">{{ $otherInvoice->tenant->name }}</div>
                <div>{{ $otherInvoice->tenant->address ?? '' }}</div>
                <div>{{ $otherInvoice->tenant->city ?? '' }}</div>
                <div>{{ $otherInvoice->tenant->email ?? '' }}</div>
                </div>
            </td>
        </tr>

        <!-- Row 3: Items header -->
        <tr class="table-light">
        <th class="w-20">Item</th>
        <th class="w-25 text-end">Total Amount</th>
        <th class="w-25 text-end">Your Amount Due</th>
        </tr>

        <!-- Row 4+: Items -->
        @php $subtotal = 0; @endphp
        @foreach($otherInvoice->items as $item)
            @php $subtotal += $item->price; @endphp
            <tr>
                <td class="v-top">{{ $item->item ?? '' }}</td>
                <td class="text-end v-top">{{ number_format($item->price, 2) }}</td>
                <td class="text-end v-top">{{ number_format($item->price, 2) }}</td>
            </tr>
        @endforeach
        
        <!-- Row N: Reason / Property Name / Due Date -->
        <tr>
            <td colspan="2" class="v-top">
                <div class="fw-semibold">REASON FOR INVOICE</div>
                <div class="text-muted text-small">{{ $otherInvoice->subject }}</div>
            </td>
            <td class="v-top">
                <div class="text-muted text-small">Property Name</div>
                <div class="fw-semibold">{{ $otherInvoice->property->name ?? '' }}</div>
            </td>
            <td class="v-top">
                <div class="text-muted text-small">Due Date</div>
                <div class="fw-semibold">{{ \Carbon\Carbon::parse($otherInvoice->due_date)->format('d M Y') }}</div>
            </td>
        </tr>


        <!-- Row N+1: Custom message + totals -->
        <tr>
            <td colspan="3" class="v-top">
                <div class="fw-semibold">CUSTOM MESSAGE</div>
                <div class="text-muted text-small">
                Thank you for your prompt payment. Please contact us for any questions.
                </div>
            </td>
            <td class="v-top">
                <table class="table table-borderless mb-0">
                <tr>
                    <td class="text-end text-small">Subtotal:</td>
                    <td class="text-end">{{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end fw-semibold">Total Due:</td>
                    <td class="text-end fw-bold">{{ number_format($subtotal, 2) }}</td>
                </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="mt-1 mb-2 no-print text-right">
        <a href="{{ route('payment.pay_now', ['otherInvoice' => $otherInvoice->id]) }}" class="btn btn-success btn-sm">Pay Now</a>        
    </div>
</div>
@if(isset($isPreview) && $isPreview)
<div class="page-frame text-center no-print">
    <form action="{{ route('other_invoices.send_email', ['otherInvoice' => $otherInvoice->id]) }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm">Send</button>
    </form>
</div>
@endif
</body>
</html>
