@extends('student.layout.app')

@section('title', 'Library Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-book-open me-2"></i>Library Dashboard
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Library</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>Library Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Library Name:</label>
                                <p class="mb-0">{{ $libraryInfo['library_name'] ?? 'Not Available' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Address:</label>
                                <p class="mb-0">{{ $libraryInfo['address'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Contact Phone:</label>
                                <p class="mb-0">
                                    <a href="tel:{{ $libraryInfo['contact_phone'] ?? '' }}" class="text-decoration-none">
                                        {{ $libraryInfo['contact_phone'] ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Contact Email:</label>
                                <p class="mb-0">
                                    <a href="mailto:{{ $libraryInfo['contact_email'] ?? '' }}" class="text-decoration-none">
                                        {{ $libraryInfo['contact_email'] ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Librarian:</label>
                                <p class="mb-0">{{ $libraryInfo['librarian_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Librarian Phone:</label>
                                <p class="mb-0">
                                    <a href="tel:{{ $libraryInfo['librarian_phone'] ?? '' }}" class="text-decoration-none">
                                        {{ $libraryInfo['librarian_phone'] ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Opening Hours:</label>
                                <p class="mb-0">{{ $libraryInfo['opening_hours'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Seating Capacity:</label>
                                <p class="mb-0">{{ $libraryInfo['seating_capacity'] ?? 'N/A' }} seats</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ number_format($libraryInfo['total_books'] ?? 0) }}</h3>
                            <p class="mb-0">Total Books</p>
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
                            <h3 class="mb-0">{{ number_format($libraryInfo['total_journals'] ?? 0) }}</h3>
                            <p class="mb-0">Journals</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-newspaper fa-2x"></i>
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
                            <h3 class="mb-0">{{ number_format($libraryInfo['total_ebooks'] ?? 0) }}</h3>
                            <p class="mb-0">E-books</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tablet-alt fa-2x"></i>
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
                            <h3 class="mb-0">{{ $libraryStats['current_borrowed'] ?? 0 }}</h3>
                            <p class="mb-0">Currently Borrowed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hand-holding fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Books -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bookmark me-2"></i>Currently Borrowed Books
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($currentBooks) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>ISBN</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currentBooks as $book)
                                    <tr>
                                        <td class="fw-bold">{{ $book['title'] }}</td>
                                        <td>{{ $book['author'] }}</td>
                                        <td>{{ $book['isbn'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($book['issue_date'])->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ \Carbon\Carbon::parse($book['due_date'])->isPast() ? 'danger' : 'success' }}">
                                                {{ \Carbon\Carbon::parse($book['due_date'])->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $book['status'] }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning" onclick="renewBook('{{ $book['id'] }}')">
                                                <i class="fas fa-redo"></i> Renew
                                            </button>
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
                            <p class="text-muted">Your borrowed books will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Books and New Arrivals -->
    <div class="row mb-4">
        <!-- Featured Books -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>Featured Books
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($featuredBooks) > 0)
                        <div class="row">
                            @foreach($featuredBooks as $book)
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <div class="mb-2">
                                            <i class="fas fa-book fa-2x text-primary"></i>
                                        </div>
                                        <h6 class="card-title">{{ $book['title'] }}</h6>
                                        <p class="card-text text-muted">{{ $book['author'] }}</p>
                                        <div class="mb-2">
                                            <span class="badge bg-info">{{ $book['category'] }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $book['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <a href="{{ route('student.library.book.details', $book['id']) }}" class="btn btn-sm btn-outline-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No featured books available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- New Arrivals -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>New Arrivals
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($newArrivals) > 0)
                        <div class="row">
                            @foreach($newArrivals as $book)
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <div class="mb-2">
                                            <i class="fas fa-book fa-2x text-success"></i>
                                        </div>
                                        <h6 class="card-title">{{ $book['title'] }}</h6>
                                        <p class="card-text text-muted">{{ $book['author'] }}</p>
                                        <div class="mb-2">
                                            <span class="badge bg-success">{{ $book['category'] }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                Added: {{ \Carbon\Carbon::parse($book['arrival_date'])->format('M d, Y') }}
                                            </small>
                                        </div>
                                        <a href="{{ route('student.library.book.details', $book['id']) }}" class="btn btn-sm btn-outline-success">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-plus-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No new arrivals available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($recentActivity) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Activity</th>
                                        <th>Details</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivity as $activity)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($activity['date'])->format('M d, Y') }}</td>
                                        <td>{{ $activity['time'] }}</td>
                                        <td>{{ $activity['activity'] }}</td>
                                        <td>{{ $activity['details'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $activity['type'] === 'borrow' ? 'success' : ($activity['type'] === 'return' ? 'info' : 'warning') }}">
                                                {{ ucfirst($activity['type']) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.library.books') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-book me-2"></i>Browse Books
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.library.search') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-search me-2"></i>Search Library
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.library.history') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-history me-2"></i>Borrowing History
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('student.library.profile') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-user-cog me-2"></i>Library Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function renewBook(bookId) {
    if (confirm('Are you sure you want to renew this book?')) {
        // Here you would typically send an AJAX request to renew the book
        fetch(`{{ route('student.library.book.renew', '') }}/${bookId}`, {
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
</script>
@endsection
