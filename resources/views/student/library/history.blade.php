@extends('student.layout.app')

@section('title', 'Borrowing History')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-history me-2"></i>Borrowing History
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.library.index') }}">Library</a></li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $historyStats['total_books_borrowed'] ?? 0 }}</h3>
                            <p class="mb-0">Total Borrowed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $historyStats['total_books_returned'] ?? 0 }}</h3>
                            <p class="mb-0">Returned</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $historyStats['current_borrowed'] ?? 0 }}</h3>
                            <p class="mb-0">Currently Borrowed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hand-holding fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $historyStats['overdue_books'] ?? 0 }}</h3>
                            <p class="mb-0">Overdue</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Issues -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bookmark me-2"></i>Currently Borrowed Books
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($currentIssues) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Issue ID</th>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Renewals Left</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currentIssues as $issue)
                                    <tr>
                                        <td class="fw-bold">{{ $issue['id'] }}</td>
                                        <td>{{ $issue['book_title'] }}</td>
                                        <td>{{ $issue['author'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($issue['issue_date'])->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ \Carbon\Carbon::parse($issue['due_date'])->isPast() ? 'danger' : 'success' }}">
                                                {{ \Carbon\Carbon::parse($issue['due_date'])->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $issue['status'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $issue['renewals_left'] > 0 ? 'success' : 'warning' }}">
                                                {{ $issue['renewals_left'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($issue['renewals_left'] > 0)
                                                <button class="btn btn-sm btn-outline-warning" onclick="renewBook('{{ $issue['id'] }}')">
                                                    <i class="fas fa-redo"></i> Renew
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-times"></i> No Renewals
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No books currently borrowed</h5>
                            <p class="text-muted">Your currently borrowed books will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Books -->
    @if(count($overdueBooks) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Overdue Books
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>Important Notice
                        </h6>
                        <p class="mb-0">You have overdue books that need to be returned immediately. Please return them to avoid late fees.</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Author</th>
                                    <th>Due Date</th>
                                    <th>Days Overdue</th>
                                    <th>Late Fee</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overdueBooks as $book)
                                <tr>
                                    <td class="fw-bold">{{ $book['title'] }}</td>
                                    <td>{{ $book['author'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($book['due_date'])->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($book['due_date'])) }} days
                                        </span>
                                    </td>
                                    <td class="fw-bold text-danger">${{ number_format($book['late_fee'] ?? 0, 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" onclick="returnBook('{{ $book['id'] }}')">
                                            <i class="fas fa-undo"></i> Return Now
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Borrowing History -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Borrowing History
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($borrowingHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Issue ID</th>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Issue Date</th>
                                        <th>Return Date</th>
                                        <th>Status</th>
                                        <th>Days Borrowed</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowingHistory as $history)
                                    <tr>
                                        <td class="fw-bold">{{ $history['id'] }}</td>
                                        <td>{{ $history['book_title'] }}</td>
                                        <td>{{ $history['author'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($history['issue_date'])->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($history['return_date'])->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $history['status'] === 'Returned' ? 'success' : 'warning' }}">
                                                {{ $history['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $history['days_borrowed'] }} days</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewIssueDetails('{{ $history['id'] }}')">
                                                <i class="fas fa-eye"></i> Details
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No borrowing history found</h5>
                            <p class="text-muted">Your borrowing history will appear here once you start borrowing books.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reading Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Reading Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Average Borrowing Duration:</label>
                                <p class="mb-0 fs-5 text-primary">{{ $historyStats['average_borrowing_duration'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Favorite Category:</label>
                                <p class="mb-0 fs-5 text-success">{{ $historyStats['favorite_category'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Total Books Read:</label>
                                <p class="mb-0 fs-5 text-info">{{ $historyStats['total_books_returned'] ?? 0 }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Borrowing Rate:</label>
                                <p class="mb-0 fs-5 text-warning">
                                    {{ $historyStats['current_borrowed'] ?? 0 }}/5 books
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Issue Details Modal -->
<div class="modal fade" id="issueDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Issue Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Issue Information</h6>
                        <p><strong>Issue ID:</strong> <span id="issueId">-</span></p>
                        <p><strong>Book Title:</strong> <span id="issueBookTitle">-</span></p>
                        <p><strong>Author:</strong> <span id="issueAuthor">-</span></p>
                        <p><strong>Status:</strong> <span id="issueStatus">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Dates</h6>
                        <p><strong>Issue Date:</strong> <span id="issueDate">-</span></p>
                        <p><strong>Due Date:</strong> <span id="issueDueDate">-</span></p>
                        <p><strong>Return Date:</strong> <span id="issueReturnDate">-</span></p>
                        <p><strong>Days Borrowed:</strong> <span id="issueDaysBorrowed">-</span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printIssueDetails()">
                    <i class="fas fa-print me-2"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function renewBook(issueId) {
    if (confirm('Are you sure you want to renew this book?')) {
        fetch(`{{ route('student.library.book.renew', '') }}/${issueId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Book renewed successfully!');
                location.reload();
            } else {
                alert('Failed to renew book: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while renewing the book.');
        });
    }
}

function returnBook(bookId) {
    if (confirm('Are you sure you want to return this book?')) {
        // Here you would typically send an AJAX request to return the book
        alert('Book return request submitted!');
    }
}

function viewIssueDetails(issueId) {
    // Mock data - replace with actual data from your controller
    const issueData = {
        'ISS-001': {
            id: 'ISS-001',
            bookTitle: 'Introduction to Computer Science',
            author: 'John Smith',
            status: 'Returned',
            issueDate: '2024-02-15',
            dueDate: '2024-03-01',
            returnDate: '2024-03-01',
            daysBorrowed: 15
        }
    };

    const issue = issueData[issueId] || {
        id: issueId,
        bookTitle: 'Unknown Book',
        author: 'Unknown Author',
        status: 'Unknown',
        issueDate: 'N/A',
        dueDate: 'N/A',
        returnDate: 'N/A',
        daysBorrowed: 0
    };

    // Populate modal with data
    document.getElementById('issueId').textContent = issue.id;
    document.getElementById('issueBookTitle').textContent = issue.bookTitle;
    document.getElementById('issueAuthor').textContent = issue.author;
    document.getElementById('issueStatus').innerHTML = `<span class="badge bg-${issue.status === 'Returned' ? 'success' : 'warning'}">${issue.status}</span>`;
    document.getElementById('issueDate').textContent = issue.issueDate;
    document.getElementById('issueDueDate').textContent = issue.dueDate;
    document.getElementById('issueReturnDate').textContent = issue.returnDate;
    document.getElementById('issueDaysBorrowed').textContent = issue.daysBorrowed;

    const modal = new bootstrap.Modal(document.getElementById('issueDetailsModal'));
    modal.show();
}

function printIssueDetails() {
    const printContent = document.querySelector('#issueDetailsModal .modal-body').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Issue Details</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .row { display: flex; margin-bottom: 10px; }
                    .col-md-6 { flex: 1; padding: 0 10px; }
                    h6 { color: #007bff; margin-bottom: 10px; }
                    p { margin: 5px 0; }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection
