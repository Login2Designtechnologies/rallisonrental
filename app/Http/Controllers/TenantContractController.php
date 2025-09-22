<?php

namespace App\Http\Controllers;

use App\Models\TenantContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class TenantContractController extends Controller
{
    public function store(Request $request)
    {
        dd('h');
        $validated = $request->validate([
            // required FKs
            'tenant_id'   => ['required','exists:tenants,id'],
            'property_id' => ['required','exists:properties,id'],
            'owner_id'    => ['nullable','exists:owners,id'],

            // contract core
            'start_date'  => ['required','date'],
            'end_date'    => ['nullable','date','after_or_equal:start_date'],
            'contract_renewal_month'  => ['required','integer','in:3,6,9,12'],
            'contract_renewal_amount' => ['nullable','numeric','gte:0'],

            // money + terms
            'standard_rent'        => ['nullable','numeric','gte:0'],
            'late_fee'             => ['nullable','numeric','gte:0'],
            'security_deposit'     => ['nullable','numeric','gte:0'],
            'notice_period_months' => ['nullable','integer','in:1,2,3'],

            // file
            'contract_doc'         => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:8192'],
        ]);

        // Server-side fallback: if end_date not provided, compute from start_date + renewal months
        if (empty($validated['end_date']) && !empty($validated['start_date']) && !empty($validated['contract_renewal_month'])) {
            $validated['end_date'] = Carbon::parse($validated['start_date'])
                ->addMonths((int) $validated['contract_renewal_month'])
                ->format('Y-m-d');
        }

        if ($request->hasFile('contract_doc')) {
            $validated['contract_doc'] = $request->file('contract_doc')->store('tenant_contracts', 'public');
        }

        $contract = TenantContract::create($validated);

        return redirect()
            ->route('tenant.show', $validated['tenant_id'])
            ->with('success', 'Tenant contract saved successfully.');
    }

    public function update(Request $request, \App\Models\TenantContract $tenant_contract)
{
    $validated = $request->validate([
        'tenant_id'   => ['required','exists:tenants,id'],
        'property_id' => ['required','exists:properties,id'],
        'owner_id'    => ['nullable','exists:owners,id'],

        'start_date'  => ['required','date'],
        'end_date'    => ['nullable','date','after_or_equal:start_date'],
        'contract_renewal_month'  => ['required','integer','in:3,6,9,12'],
        'contract_renewal_amount' => ['nullable','numeric','gte:0'],

        'standard_rent'        => ['nullable','numeric','gte:0'],
        'late_fee'             => ['nullable','numeric','gte:0'],
        'security_deposit'     => ['nullable','numeric','gte:0'],
        'notice_period_months' => ['nullable','integer','in:1,2,3'],

        'contract_doc'         => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:8192'],
    ]);

    if (empty($validated['end_date'])) {
        $validated['end_date'] = \Carbon\Carbon::parse($validated['start_date'])
            ->addMonths((int) $validated['contract_renewal_month'])
            ->format('Y-m-d');
    }

    if ($request->hasFile('contract_doc')) {
        // delete old if present
        if ($tenant_contract->contract_doc) {
            \Storage::disk('public')->delete($tenant_contract->contract_doc);
        }
        $validated['contract_doc'] = $request->file('contract_doc')->store('tenant_contracts','public');
    }

    $tenant_contract->update($validated);

    return redirect()
        ->route('tenant.show', $validated['tenant_id'])
        ->with('success','Tenant contract updated successfully.');
}
}
