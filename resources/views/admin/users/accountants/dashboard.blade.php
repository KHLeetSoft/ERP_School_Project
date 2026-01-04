@extends('admin.layout.master')

@section('title', 'Accountant Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold">👤 Welcome, {{ $accountant->name }}</h4>
            <p class="text-muted mb-0">Here is your financial overview for {{ \Carbon\Carbon::create()->month($currentMonth)->format('F') }} {{ $currentYear }}</p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card border-start border-success border-5 shadow-sm">
                <div class="card-body">
                    <h6 class="text-success">Total Income</h6>
                    <h3 class="fw-bold">₹{{ number_format($monthlyTransactions['income'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-danger border-5 shadow-sm">
                <div class="card-body">
                    <h6 class="text-danger">Total Expense</h6>
                    <h3 class="fw-bold">₹{{ number_format($monthlyTransactions['expense'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-warning border-5 shadow-sm">
                <div class="card-body">
                    <h6 class="text-warning">Pending Amount</h6>
                    <h3 class="fw-bold">₹{{ number_format($monthlyTransactions['pending'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly Comparison --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">📊 Monthly Comparison</h5>
                    <p>Previous Month: ₹{{ number_format($monthlyComparison['previous'], 2) }}</p>
                    <p>Current Month: ₹{{ number_format($monthlyComparison['current'], 2) }}</p>
                    <p>Change: 
                        @if($monthlyComparison['percentage'] >= 0)
                            <span class="text-success fw-bold">+{{ number_format($monthlyComparison['percentage'], 2) }}%</span>
                        @else
                            <span class="text-danger fw-bold">{{ number_format($monthlyComparison['percentage'], 2) }}%</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">🧾 Recent Transactions</h5>

                    @if($recentTransactions->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $index => $transaction)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transaction->date }}</td>
                                        <td>{{ ucfirst($transaction->type) }}</td>
                                        <td>₹{{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No recent transactions found.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
