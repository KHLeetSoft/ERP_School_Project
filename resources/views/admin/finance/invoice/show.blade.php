@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Invoice {{ $invoice->invoice_number }}</h6>
                    <a href="{{ route('admin.finance.invoice.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div><strong>Bill To:</strong> {{ $invoice->bill_to }}</div>
                            <div><strong>Issue Date:</strong> {{ $invoice->issue_date->format('d M Y') }}</div>
                            <div><strong>Due Date:</strong> {{ optional($invoice->due_date)->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div><strong>Status:</strong> {{ ucfirst($invoice->status) }}</div>
                            <div><strong>Total:</strong> {{ number_format($invoice->total, 2) }}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-2">Items</div>
                        <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($invoice->items, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    <div>
                        <div class="fw-bold mb-2">Notes</div>
                        <div>{{ $invoice->notes }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


