<div class="container-fluid p-3">
  @foreach($data['invoices'] as $index => $inv)
    @php
      $tenant = $tenants[$inv['tenant_id']] ?? null;
    @endphp
    <div class="border rounded shadow-sm p-4 mb-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <img src="{{ asset('assets/images/authentication/logo2.png') }}" alt="Logo" width="150" class="mb-2"><br>
          <strong>{{ config('app.name') }}</strong><br>
          <small>{{ $property->name ?? 'N/A' }}</small><br>
          <small>{{ $property->address ?? '' }}</small>
        </div>
        <div class="text-end">
          <div><strong>Date:</strong> {{ now()->format('d M Y') }}</div>
          <div><strong>Invoice #:</strong> TEMP-{{ $index + 1 }}</div>
          <div><strong>Month:</strong> {{ $data['invoice_month'] }}</div>
        </div>
      </div>

      <div class="mb-3">
        <h6 class="text-muted">Bill To:</h6>
        <div>{{ optional($tenant->user)->first_name }} {{ optional($tenant->user)->last_name }}</div>
        <div>{{ optional($tenant->user)->email }}</div>
      </div>

      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Utility</th>
            <th>Period</th>
            <th class="text-end">Amount ($)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($inv['details'] as $i => $d)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $d['category'] }}</td>              
              <td>
                 @if(!empty($d['start_date']) && !empty($d['end_date']))
                  {{ \Carbon\Carbon::parse($d['start_date'])->format('d M Y') }}
                  –
                  {{ \Carbon\Carbon::parse($d['end_date'])->format('d M Y') }}
                @else
                  —
                @endif
              </td>
              <td class="text-end">${{ number_format($d['amount'], 2) }}</td>
            </tr>
          @endforeach
          <tr class="table-light">
            <td colspan="3" class="text-end fw-bold">Total</td>
            <td class="text-end fw-bold">${{ number_format($inv['amount'], 2) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  @endforeach
</div>
