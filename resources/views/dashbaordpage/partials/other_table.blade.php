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
