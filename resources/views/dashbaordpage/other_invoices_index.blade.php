@extends('layouts.app')
@section('page-title')
    {{ __('Property Create') }}
@endsection

@section('content')

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5>All Invoices</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('other-invoices.create') }}" class="btn btn-secondary  ">
                                <i class="ti ti-circle-plus align-text-bottom"></i> Add New
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>For</th>
                                    <th>Invoice / Due</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($invoices as $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}
                                        </td>

                                        <td style="max-width: 420px;">
                                            <div class="fw-semibold">
                                                <a href="{{ route('other-invoices.show', $invoice) }}"
                                                    class="text-decoration-none">
                                                    {{ $invoice->subject }}
                                                </a>
                                            </div>
                                            <div class="small text-muted">
                                                #{{ $invoice->id }}
                                                @if ($invoice->property ?? null)
                                                    • {{ $invoice->property->name }}
                                                @endif
                                            </div>


                                            {{-- show a few line items as badges (like your permissions style) --}}
                                            <div class="mt-1" style="max-width: 480px; display:flex; flex-wrap:wrap;">
                                                @forelse($invoice->details->take(5) as $it)
                                                    <span class="badge bg-secondary text-white border me-1 mb-1">
                                                        {{ $it->item }} (x{{ $it->qty }})
                                                    </span>
                                                @empty
                                                    <span class="text-muted">—</span>
                                                @endforelse
                                                @if ($invoice->details->count() > 5)
                                                    <span
                                                        class="badge bg-light text-dark border me-1 mb-1">+{{ $invoice->details->count() - 5 }}
                                                        more</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            @if ($invoice->tenant ?? null)
                                                <div class="fw-semibold">{{ $invoice->tenant->user->name }}</div>

                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            <div class="small">
                                                <span class="text-muted">Inv:</span>
                                                {{ optional($invoice->invoice_date)->format('d M Y') }}
                                            </div>
                                            <div class="small">
                                                <span class="text-muted">Due:</span>
                                                {{ optional($invoice->due_date)->format('d M Y') }}
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <strong>₹{{ number_format($invoice->amount, 2) }}</strong>
                                        </td>

                                        <td class="align-middle">
                                            @php
                                                $map = [
                                                    'draft' => 'secondary',
                                                    'sent' => 'info',
                                                    'paid' => 'success',
                                                    'partial' => 'warning',
                                                    'overdue' => 'danger',
                                                    'void' => 'dark',
                                                ];
                                                $badge = $map[$invoice->status] ?? 'secondary';
                                            @endphp
                                            <span
                                                class="badge bg-{{ $badge }}">{{ ucfirst($invoice->status) }}</span>
                                        </td>

                                        <td class="text-end align-middle">
                                            <a href="{{ route('other-invoices.show', $invoice) }}"
                                                class="btn btn-sm btn-outline-secondary">View</a>
                                            <a href="{{ route('other-invoices.edit', $invoice) }}"
                                                class="btn btn-sm btn-outline-primary">Edit</a>

                                            <form action="{{ route('other-invoices.destroy', $invoice) }}" method="post"
                                                class="d-inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-button">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No invoices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <!-- [ Main Content ] end -->


    @stop

    @push('script')
    @endpush
