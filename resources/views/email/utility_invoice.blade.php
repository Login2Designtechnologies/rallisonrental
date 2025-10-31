<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice {{ $mailData['invoice_number'] }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .page-frame { max-width: 900px; margin: 24px auto; border: 4px solid #dbd8d8; padding: 16px; background: #fff; }
    .page-frame .input-group .btn.btn-primary { background-color: #2ca58d; }
    .text-small { font-size: .9rem; }
    .w-20 { width: 20%; } .w-25 { width: 25%; } .w-50 { width: 50%; }
    .v-top { vertical-align: top; }
    .fw-semibold { font-weight: 600; }
    .table-light th { background-color: #f2f4f6 !important; }
  </style>
</head>
<body>
<div class="page-frame">
  <table class="table table-bordered align-middle mb-0">

    <!-- Header -->
    <tr>
      <td class="v-top w-50" rowspan="2" colspan="2">
        <div class="fw-semibold mb-2">
          <img src="{{ asset('assets/images/authentication/logo2.png') }}" alt="Company Logo" width="150"><br>
        </div>
        <div class="mt-2 fw-bold">{{ config('app.name') }}</div>
        <div>{{ $mailData['property_name'] ?? 'Property Management' }}</div>
        <div>{{ $mailData['property_address'] ?? '' }}</div>
      </td>
      <td class="w-25 text-end v-top">
        <div class="text-muted text-small">Date</div>
        <div class="fw-semibold">{{ \Carbon\Carbon::parse($mailData['invoice_date'])->format('d M Y') }}</div>
      </td>
      <td class="w-25 text-end v-top">
        <div class="text-muted text-small">Invoice #</div>
        <div class="fw-semibold">{{ $mailData['invoice_number'] }}</div>
      </td>
    </tr>

    <!-- Bill To -->
    <tr>
      <td colspan="4">
        <div class="text-muted text-small mb-1">Bill To</div>
        <div class="border p-2">
          <div class="fw-semibold">{{ $mailData['tenant_name'] }}</div>
          <div>{{ $mailData['tenant_email'] }}</div>
        </div>
      </td>
    </tr>

    <!-- Items Header -->
    <tr class="table-light">
      <th class="w-20">#</th>
      <th>Utility</th>
      <th class="w-25 text-end">Period</th>
      <th class="w-25 text-end">Amount ($)</th>
    </tr>

    <!-- Invoice Items -->
    @foreach($mailData['items'] as $index => $item)
      @php
        try {
            $start = $item['start_date']
                ? \Carbon\Carbon::parse($item['start_date'])->format('d M Y')
                : 'N/A';
        } catch (\Exception $e) {
            $start = 'N/A';
        }

        try {
            $end = $item['end_date']
                ? \Carbon\Carbon::parse($item['end_date'])->format('d M Y')
                : 'N/A';
        } catch (\Exception $e) {
            $end = 'N/A';
        }
      @endphp

      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item['category'] }}</td>
        <td class="text-end">{{ $start }} - {{ $end }}</td>
        <td class="text-end">${{ number_format($item['amount'], 2) }}</td>
      </tr>
    @endforeach

    <!-- Total -->
    <tr class="table-light">
      <td colspan="3" class="text-end fw-bold">Total</td>
      <td class="text-end fw-bold">${{ number_format($mailData['total_amount'], 2) }}</td>
    </tr>

    <!-- Footer -->
    <tr>
      <td colspan="4">
        <div class="text-muted text-small">
          Thank you for your payment!
        </div>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
