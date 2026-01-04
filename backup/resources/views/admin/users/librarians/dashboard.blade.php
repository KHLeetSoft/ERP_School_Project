@extends('admin.layout.app')

@section('title', 'Librarian Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $libraryStats['total_books'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Books Available</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $libraryStats['books_available'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Books Issued</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $libraryStats['books_issued'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-log-out-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Books Overdue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $libraryStats['books_overdue'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-time fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Book Transactions</h4>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Student</th>
                                    <th>Issue Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->book_title ?? 'N/A' }}</td>
                                    <td>{{ $transaction->student_name ?? 'N/A' }}</td>
                                    <td>{{ $transaction->issue_date ?? 'N/A' }}</td>
                                    <td>{{ $transaction->due_date ?? 'N/A' }}</td>
                                    <td>
                                        @if($transaction->status === 'returned')
                                            <span class="badge badge-pill badge-light-success">Returned</span>
                                        @elseif($transaction->status === 'overdue')
                                            <span class="badge badge-pill badge-light-danger">Overdue</span>
                                        @else
                                            <span class="badge badge-pill badge-light-info">Issued</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p>No recent transactions found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection