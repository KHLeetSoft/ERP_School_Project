@extends('student.layout.app')

@section('title', 'Book Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-book me-2"></i>Book Details
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.library.index') }}">Library</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.library.books') }}">Books</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Book Information -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-book fa-5x text-primary"></i>
                    </div>
                    <h5 class="card-title">{{ $book['title'] ?? 'N/A' }}</h5>
                    <p class="card-text text-muted">{{ $book['author'] ?? 'N/A' }}</p>
                    <div class="mb-3">
                        <span class="badge bg-{{ $book['availability'] === 'Available' ? 'success' : 'warning' }} fs-6">
                            {{ $book['availability'] ?? 'Unknown' }}
                        </span>
                    </div>
                    <div class="d-grid gap-2">
                        @if($book['availability'] === 'Available')
                            <button class="btn btn-success" onclick="borrowBook('{{ $book['id'] }}')">
                                <i class="fas fa-hand-holding me-2"></i>Borrow Book
                            </button>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-times me-2"></i>Not Available
                            </button>
                        @endif
                        <button class="btn btn-outline-primary" onclick="addToFavorites('{{ $book['id'] }}')">
                            <i class="fas fa-heart me-2"></i>Add to Favorites
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Book Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Title:</label>
                                <p class="mb-0">{{ $book['title'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Author:</label>
                                <p class="mb-0">{{ $book['author'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">ISBN:</label>
                                <p class="mb-0">{{ $book['isbn'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Category:</label>
                                <p class="mb-0">
                                    <span class="badge bg-info">{{ $book['category'] ?? 'N/A' }}</span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Publisher:</label>
                                <p class="mb-0">{{ $book['publisher'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Year:</label>
                                <p class="mb-0">{{ $book['year'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pages:</label>
                                <p class="mb-0">{{ $book['pages'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Language:</label>
                                <p class="mb-0">{{ $book['language'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Edition:</label>
                                <p class="mb-0">{{ $book['edition'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Location:</label>
                                <p class="mb-0">{{ $book['location'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description:</label>
                                <p class="mb-0">{{ $book['description'] ?? 'No description available.' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Subjects:</label>
                                <div class="d-flex flex-wrap gap-1">
                                    @if(isset($book['subjects']) && count($book['subjects']) > 0)
                                        @foreach($book['subjects'] as $subject)
                                            <span class="badge bg-secondary">{{ $subject }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No subjects listed</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Availability and Related Books -->
    <div class="row mb-4">
        <!-- Availability -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Availability
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Total Copies:</label>
                                <p class="mb-0 fs-5">{{ $availability['total_copies'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Available Copies:</label>
                                <p class="mb-0 fs-5 text-success">{{ $availability['available_copies'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Borrowed Copies:</label>
                                <p class="mb-0 fs-5 text-warning">{{ $availability['borrowed_copies'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Reserved Copies:</label>
                                <p class="mb-0 fs-5 text-info">{{ $availability['reserved_copies'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @if(isset($availability['estimated_return']) && $availability['estimated_return'])
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Estimated Return:</strong> {{ \Carbon\Carbon::parse($availability['estimated_return'])->format('M d, Y') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Books -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bookmark me-2"></i>Related Books
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($relatedBooks) > 0)
                        @foreach($relatedBooks as $relatedBook)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="fas fa-book fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $relatedBook['title'] }}</h6>
                                <p class="mb-1 text-muted">{{ $relatedBook['author'] }}</p>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $relatedBook['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('student.library.book.details', $relatedBook['id']) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No related books found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>Reviews
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($reviews) > 0)
                        @foreach($reviews as $review)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $review['user_name'] }}</h6>
                                    <div class="rating mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($review['date'])->format('M d, Y') }}</small>
                            </div>
                            <p class="mb-0">{{ $review['comment'] }}</p>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No reviews yet</h5>
                            <p class="text-muted">Be the first to review this book!</p>
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
function borrowBook(bookId) {
    document.getElementById('borrowBookTitle').value = '{{ $book["title"] ?? "" }}';
    document.getElementById('borrowBookAuthor').value = '{{ $book["author"] ?? "" }}';
    
    const modal = new bootstrap.Modal(document.getElementById('borrowBookModal'));
    modal.show();
}

function submitBorrowRequest() {
    const duration = document.getElementById('borrowDuration').value;
    const purpose = document.getElementById('borrowPurpose').value;
    
    if (!duration) {
        alert('Please select a borrow duration.');
        return;
    }
    
    // Here you would typically send an AJAX request to borrow the book
    fetch(`{{ route('student.library.book.borrow', $book['id'] ?? '') }}`, {
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

function addToFavorites(bookId) {
    // Here you would typically send an AJAX request to add to favorites
    alert('Book added to favorites!');
}
</script>
@endsection
