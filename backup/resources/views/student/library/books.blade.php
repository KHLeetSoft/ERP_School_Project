@extends('student.layout.app')

@section('title', 'Browse Books')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-book me-2"></i>Browse Books
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.library.index') }}">Library</a></li>
                        <li class="breadcrumb-item active">Books</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Search & Filter
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('student.library.books') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Search by title or author...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="all">All Categories</option>
                                    @if(isset($categories))
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Author</label>
                                <select class="form-select" name="author">
                                    <option value="all">All Authors</option>
                                    @if(isset($authors))
                                        @foreach($authors as $auth)
                                            <option value="{{ $auth }}" {{ $author === $auth ? 'selected' : '' }}>{{ $auth }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sort By</label>
                                <select class="form-select" name="sort">
                                    <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Title</option>
                                    <option value="author" {{ $sortBy === 'author' ? 'selected' : '' }}>Author</option>
                                    <option value="year" {{ $sortBy === 'year' ? 'selected' : '' }}>Year</option>
                                    <option value="rating" {{ $sortBy === 'rating' ? 'selected' : '' }}>Rating</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Books
                        @if($search || $category !== 'all' || $author !== 'all')
                            <small class="text-muted">(Filtered Results)</small>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($books) > 0)
                        <div class="row">
                            @foreach($books as $book)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <i class="fas fa-book fa-3x text-primary"></i>
                                        </div>
                                        <h6 class="card-title">{{ $book['title'] }}</h6>
                                        <p class="card-text text-muted">{{ $book['author'] }}</p>
                                        
                                        <div class="mb-2">
                                            <span class="badge bg-info">{{ $book['category'] }}</span>
                                            <span class="badge bg-secondary">{{ $book['year'] }}</span>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-star text-warning"></i>
                                                {{ $book['rating'] }}/5
                                            </small>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $book['location'] }}
                                            </small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <span class="badge bg-{{ $book['availability'] === 'Available' ? 'success' : 'warning' }}">
                                                {{ $book['availability'] }}
                                            </span>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('student.library.book.details', $book['id']) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                            @if($book['availability'] === 'Available')
                                                <button class="btn btn-success btn-sm" onclick="borrowBook('{{ $book['id'] }}')">
                                                    <i class="fas fa-hand-holding me-1"></i>Borrow
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-times me-1"></i>Not Available
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No books found</h5>
                            <p class="text-muted">
                                @if($search || $category !== 'all' || $author !== 'all')
                                    Try adjusting your search criteria.
                                @else
                                    No books are currently available in the library.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Borrow Book Modal -->
<div class="modal fade" id="borrowBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Borrow Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="borrowBookForm">
                    <div class="mb-3">
                        <label class="form-label">Book Title</label>
                        <input type="text" id="borrowBookTitle" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author</label>
                        <input type="text" id="borrowBookAuthor" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Borrow Duration <span class="text-danger">*</span></label>
                        <select class="form-select" id="borrowDuration" required>
                            <option value="">Select Duration</option>
                            <option value="7">7 days</option>
                            <option value="14">14 days</option>
                            <option value="21">21 days</option>
                            <option value="30">30 days</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purpose (Optional)</label>
                        <textarea class="form-control" id="borrowPurpose" rows="3" placeholder="What do you need this book for?"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitBorrowRequest()">Borrow Book</button>
            </div>
        </div>
    </div>
</div>

<script>
// Mock book data
const bookData = {
    'BK-001': {
        title: 'Introduction to Computer Science',
        author: 'John Smith',
        category: 'Computer Science',
        year: 2023,
        rating: 4.5,
        availability: 'Available',
        location: 'Shelf A-1'
    },
    'BK-002': {
        title: 'Data Structures and Algorithms',
        author: 'Jane Doe',
        category: 'Computer Science',
        year: 2022,
        rating: 4.7,
        availability: 'Borrowed',
        location: 'Shelf A-2'
    },
    'BK-003': {
        title: 'The Art of Programming',
        author: 'Donald Knuth',
        category: 'Computer Science',
        year: 2021,
        rating: 4.8,
        availability: 'Available',
        location: 'Shelf B-1'
    }
};

function borrowBook(bookId) {
    const book = bookData[bookId];
    if (book) {
        document.getElementById('borrowBookTitle').value = book.title;
        document.getElementById('borrowBookAuthor').value = book.author;
        
        const modal = new bootstrap.Modal(document.getElementById('borrowBookModal'));
        modal.show();
    }
}

function submitBorrowRequest() {
    const duration = document.getElementById('borrowDuration').value;
    const purpose = document.getElementById('borrowPurpose').value;
    
    if (!duration) {
        alert('Please select a borrow duration.');
        return;
    }
    
    // Here you would typically send an AJAX request to borrow the book
    fetch(`{{ route('student.library.book.borrow', '') }}/${getCurrentBookId()}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            borrow_duration: duration,
            purpose: purpose
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Book borrowed successfully!');
            location.reload();
        } else {
            alert('Failed to borrow book: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while borrowing the book.');
    });
}

function getCurrentBookId() {
    // This would be set when the modal is opened
    return window.currentBookId;
}

// Set book ID when modal is opened
document.getElementById('borrowBookModal').addEventListener('show.bs.modal', function (event) {
    // This would be set by the borrowBook function
    window.currentBookId = window.currentBookId || 'BK-001';
});
</script>
@endsection
