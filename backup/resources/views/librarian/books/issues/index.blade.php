@extends('librarian.layout.app')

@section('title', 'Book Issues Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Book Issues Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('librarian.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('librarian.books.index') }}">Books</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Issues</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-hand-holding"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['total_issues'] }}</h3>
                            <p class="stats-label">Total Issues</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['active_issues'] }}</h3>
                            <p class="stats-label">Active Issues</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['returned_books'] }}</h3>
                            <p class="stats-label">Returned</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['overdue_books'] }}</h3>
                            <p class="stats-label">Overdue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Book Issues List
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('librarian.book-issues.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Issue New Book
                            </a>
                            <a href="{{ route('librarian.book-issues.overdue') }}" class="btn btn-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>Overdue Books
                            </a>
                            <form action="{{ route('librarian.book-issues.mark-overdue') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-sync me-2"></i>Mark Overdue
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('librarian.book-issues.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="Search by book or student..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Issued</option>
                                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="overdue" value="1" 
                                       id="overdueFilter" {{ request('overdue') ? 'checked' : '' }} 
                                       onchange="this.form.submit()">
                                <label class="form-check-label" for="overdueFilter">
                                    Show Overdue Only
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('librarian.book-issues.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </div>

                    <!-- Issues Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Issue ID</th>
                                    <th>Book</th>
                                    <th>Student</th>
                                    <th>Issued Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Fine</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookIssues as $issue)
                                    <tr class="{{ $issue->status === 'overdue' ? 'table-danger' : '' }}">
                                        <td>
                                            <span class="badge bg-secondary">#{{ $issue->id }}</span>
                                        </td>
                                        <td>
                                            <div class="book-info">
                                                <div class="book-title">{{ $issue->book->title }}</div>
                                                <small class="text-muted">by {{ $issue->book->author }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="student-info">
                                                <div class="student-name">{{ $issue->student->first_name }} {{ $issue->student->last_name }}</div>
                                                <small class="text-muted">{{ $issue->student->admission_number }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $issue->issued_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="{{ $issue->due_date < now() && $issue->status === 'issued' ? 'text-danger fw-bold' : '' }}">
                                                {{ $issue->due_date->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($issue->status)
                                                @case('issued')
                                                    <span class="badge bg-warning">Issued</span>
                                                    @break
                                                @case('returned')
                                                    <span class="badge bg-success">Returned</span>
                                                    @break
                                                @case('overdue')
                                                    <span class="badge bg-danger">Overdue</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($issue->fine_amount > 0)
                                                <span class="text-danger fw-bold">${{ number_format($issue->fine_amount, 2) }}</span>
                                            @else
                                                <span class="text-muted">$0.00</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('librarian.book-issues.show', $issue) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($issue->status === 'issued')
                                                    <a href="{{ route('librarian.book-issues.return', $issue) }}" 
                                                       class="btn btn-sm btn-outline-success" title="Return Book">
                                                        <i class="fas fa-undo"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('librarian.book-issues.edit', $issue) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('librarian.book-issues.destroy', $issue) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this issue?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-hand-holding fa-3x text-muted mb-3"></i>
                                                <h5>No book issues found</h5>
                                                <p class="text-muted">Start by issuing a book to a student.</p>
                                                <a href="{{ route('librarian.book-issues.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Issue New Book
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($bookIssues->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookIssues->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0.5rem;
}

.page-header-content h1 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.breadcrumb {
    margin-bottom: 0;
    background: rgba(255,255,255,0.1);
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
}

.breadcrumb-item a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: white;
}

.breadcrumb-item.active {
    color: white;
}

.stats-card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border-radius: 0.5rem;
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #2c3e50;
}

.stats-label {
    color: #7f8c8d;
    margin-bottom: 0;
    font-size: 0.9rem;
}

.card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border-radius: 0.5rem;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.book-title, .student-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
    color: #2c3e50;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
}
</style>
@endsection
