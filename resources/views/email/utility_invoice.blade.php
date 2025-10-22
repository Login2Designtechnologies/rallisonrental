<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice {{ $mailData['invoice_number'] }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .page-frame { max-width: 900px; margin: 24px auto; border: 4px solid #dbd8d8; padding: 16px; }
    .v-top { vertical-align: top; }
  </style>
</head>
<body>
<div class="page-frame">

  <table class="table table-bordered align-middle mb-0">
    <tr>
      <td class="v-top" rowspan="2" colspan="2">
        <strong>{{ config('app.name') }}</strong><br>
        {{ $mailData['property_address'] }}<br>
        {{ $mailData['tenant_email'] }}
      </td>
      <td class="v-top">
        <div class="text-muted text-small">Date</div>
        <div class="fw-semibold">{{ \Carbon\Carbon::parse($mailData['invoice_date'])->format('d M Y') }}</div>
      </td>
      <td class="v-top">
        <div class="text-muted text-small">Invoice #</div>
        <div class="fw-semibold">{{ $mailData['invoice_number'] }}</div>
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <div class="text-muted text-small">Bill To</div>
        <div><strong>{{ $mailData['tenant_name'] }}</strong></div>
        <div>{{ $mailData['tenant_email'] }}</div>
      </td>
    </tr>

    <tr class="table-light">
      <th>Item</th>
      <th>Description</th>
      <th class="text-end">Amount</th>
    </tr>

    @foreach($mailData['items'] as $index => $item)
    <tr>
      <td>{{ $index + 1 }}</td>
      <td>
        {{ $item['category'] }}
        @if($item['start_date'] && $item['end_date'])
          ({{ \Carbon\Carbon::parse($item['start_date'])->format('d M Y') }} –
           {{ \Carbon\Carbon::parse($item['end_date'])->format('d M Y') }})
        @endif
      </td>
      <td class="text-end">{{ number_format($item['amount'], 2) }}</td>
    </tr>
    @endforeach

    <tr>
      <td colspan="2" class="text-end"><strong>Total Due:</strong></td>
      <td class="text-end fw-bold">${{ number_format($mailData['total_amount'], 2) }}</td>
    </tr>

    <tr>
      <td colspan="4">
        <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($mailData['due_date'])->format('d M Y') }}<br>
        <small>Thank you for your payment!</small>
      </td>
    </tr>
  </table>

</div>
</body>
</html>
