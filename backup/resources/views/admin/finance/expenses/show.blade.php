@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Expense #{{ $expense->id }}</h6>
                    <a href="{{ route('admin.finance.expenses.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div><strong>Date:</strong> {{ $expense->expense_date->format('d M Y') }}</div>
                            <div><strong>Category:</strong> {{ $expense->category ?? '-' }}</div>
                            <div><strong>Vendor:</strong> {{ $expense->vendor ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div><strong>Amount:</strong> {{ number_format($expense->amount, 2) }}</div>
                            <div><strong>Method:</strong> {{ ucfirst($expense->method) }}</div>
                            <div><strong>Status:</strong> {{ ucfirst($expense->status) }}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-2">Description</div>
                        <div>{{ $expense->description }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-2">Reference</div>
                        <div>{{ $expense->reference ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="fw-bold mb-2">Notes</div>
                        <div>{{ $expense->notes }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


