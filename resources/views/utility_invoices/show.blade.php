@extends('layouts.main')

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
                            <a href="{{ route('utility-invoices.index') }}" class="btn btn-secondary  ">
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
                                    <th>Invoice</th>
                                    <th>Tenant / Property</th>
                                    <th>Month</th>
                                    <th>Due</th>
                                    <th class="text-end">Amount</th>
                                    <th>Status</th>
                                    <th>Categories</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($invoices as $inv)
                                    <tr>
                                        {{-- row number (works with pagination) --}}
                                        <td>{{ ($invoices->firstItem() ?? 1) + $loop->index }}</td>

                                        {{-- invoice number + date --}}
                                        <td>
                                            <div class="fw-semibold">{{ $inv->invoice_number }}</div>
                                            <div class="text-muted small">
                                                {{ \Carbon\Carbon::parse($inv->invoice_date)->format('d M Y') }}
                                            </div>
                                        </td>

                                        {{-- tenant / property --}}
                                        <td>
                                            <div>{{ $inv->tenant->user->name ?? '—' }}</div>
                                            <div class="text-muted small">
                                                {{ $inv->property->name ?? 'Property #' . $inv->property_id }}
                                            </div>
                                        </td>

                                        {{-- invoice month --}}
                                        <td>
                                            {{ \Carbon\Carbon::parse($inv->invoice_month . '-01')->format('M Y') }}
                                        </td>

                                        {{-- due date --}}
                                        <td>
                                            {{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}
                                        </td>

                                        {{-- amount --}}
                                        <td class="text-end">
                                            {{ number_format($inv->amount, 2) }}
                                        </td>

                                        {{-- status badge --}}
                                        <td>
                                            @php
                                                $map = [
                                                    'paid' => 'success',
                                                    'unpaid' => 'warning',
                                                    'overdue' => 'danger',
                                                    'draft' => 'secondary',
                                                ];
                                                $color = $map[strtolower($inv->status ?? '')] ?? 'secondary';
                                            @endphp
                                            <span
                                                class="badge bg-{{ $color }}">{{ ucfirst($inv->status ?: '—') }}</span>
                                        </td>

                                        {{-- categories (like permissions badges) --}}
                                        <td style="max-width: 480px; display:flex; flex-wrap:wrap;">
                                            @forelse ($inv->details as $d)
                                                <span class="badge bg-light text-dark border me-1 mb-1">
                                                    {{ $d->category }}:
                                                    {{ rtrim(rtrim(number_format($d->amount, 2), '0'), '.') }}
                                                </span>
                                            @empty
                                                <span class="text-muted">—</span>
                                            @endforelse
                                        </td>

                                        {{-- actions --}}
                                        <td class="text-end">
                                            <a href="{{ route('utility-invoices.show', $inv) }}"
                                                class="btn btn-sm btn-outline-secondary">View</a>
                                            <a href="{{ route('utility-invoices.edit', $inv) }}"
                                                class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="{{ route('utility-invoices.destroy', $inv) }}" method="post"
                                                class="d-inline" onsubmit="return confirm('Delete this invoice?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">No invoices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>
                <div class="card-footer">
                    {{-- pagination --}}
@if($invoices instanceof \Illuminate\Pagination\LengthAwarePaginator)
  <div class="mt-3">
    {{ $invoices->withQueryString()->links() }}
  </div>
@endif
                </div>
            </div>
        </div>

        <!-- [ Main Content ] end -->


    @stop

    @push('script')
    @endpush
