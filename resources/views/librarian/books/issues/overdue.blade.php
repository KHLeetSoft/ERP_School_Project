@extends('librarian.layout.app')

@section('title', 'Overdue Books')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Overdue Books</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('librarian.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('librarian.books.index') }}">Books</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('librarian.book-issues.index') }}">Issues</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Overdue</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['overdue_count'] }}</h3>
                            <p class="stats-label">Overdue Books</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">₹{{ number_format($stats['total_fine'], 2) }}</h3>
                            <p class="stats-label">Total Fines</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Overdue Books Management</h5>
                            <p class="text-muted mb-0">Manage overdue books and collect fines</p>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('librarian.book-issues.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>All Issues
                            </a>
                            <form action="{{ route('librarian.book-issues.mark-overdue') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to mark all due books as overdue?')">
                                    <i class="fas fa-sync me-2"></i>Mark Overdue
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Books Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Overdue Books List</h5>
                </div>
                <div class="card-body">
                    @if($overdueBooks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Book Details</th>
                                        <th>Student Details</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Days Overdue</th>
                                        <th>Fine Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overdueBooks as $index => $bookIssue)
                                        <tr class="table-danger">
                                            <td>{{ $overdueBooks->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="book-cover me-3">
                                                        @if($bookIssue->book->cover_image)
                                                            <img src="{{ asset('storage/' . $bookIssue->book->cover_image) }}" 
                                                                 alt="{{ $bookIssue->book->title }}" 
                                                                 class="img-thumbnail" 
                                                                 style="width: 50px; height: 70px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                                 style="width: 50px; height: 70px;">
                                                                <i class="fas fa-book text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $bookIssue->book->title }}</h6>
                                                        <small class="text-muted">
                                                            <strong>Author:</strong> {{ $bookIssue->book->author }}<br>
                                                            <strong>ISBN:</strong> {{ $bookIssue->book->isbn }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ $bookIssue->student->first_name }} {{ $bookIssue->student->last_name }}</h6>
                                                    <small class="text-muted">
                                                        <strong>Class:</strong> {{ $bookIssue->student->classSection->class->name ?? 'N/A' }}<br>
                                                        <strong>Roll No:</strong> {{ $bookIssue->student->roll_no }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ \Carbon\Carbon::parse($bookIssue->issued_at)->format('M d, Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    {{ \Carbon\Carbon::parse($bookIssue->due_date)->format('M d, Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $daysOverdue = \Carbon\Carbon::parse($bookIssue->due_date)->diffInDays(\Carbon\Carbon::now());
                                                @endphp
                                                <span class="badge bg-danger">
                                                    {{ $daysOverdue }} {{ $daysOverdue == 1 ? 'day' : 'days' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-danger">
                                                    ₹{{ number_format($bookIssue->fine_amount, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('librarian.book-issues.show', $bookIssue->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('librarian.book-issues.return', $bookIssue->id) }}" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Process Return">
                                                        <i class="fas fa-undo"></i>
                                                    </a>
                                                    <a href="{{ route('librarian.book-issues.edit', $bookIssue->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $overdueBooks->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-muted">No Overdue Books</h4>
                            <p class="text-muted">All books are returned on time! Great job managing the library.</p>
                            <a href="{{ route('librarian.book-issues.index') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>View All Issues
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.15s ease-in-out;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #2c3e50;
}

.stats-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.book-cover img {
    border-radius: 4px;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection
