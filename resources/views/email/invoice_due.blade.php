@component('mail::message')
# Invoice Due Reminder

Dear {{ $contract->tenant->name }},

This is a friendly reminder that your rent payment of **${{ number_format($contract->standard_rent, 2) }}** is due today ({{ now()->format('F j, Y') }}).

Please make your payment on time to avoid any late fees.

@component('mail::button', ['url' => route('tenant.payment', $contract->tenant_id)])
Pay Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
