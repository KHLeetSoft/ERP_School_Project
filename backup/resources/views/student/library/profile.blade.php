@extends('student.layout.app')

@section('title', 'Library Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-user-cog me-2"></i>Library Profile
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.library.index') }}">Library</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Library Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.library.profile.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Member Since</label>
                                    <input type="text" class="form-control" value="{{ $libraryProfile['member_since'] ? \Carbon\Carbon::parse($libraryProfile['member_since'])->format('M d, Y') : 'N/A' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Membership Type</label>
                                    <input type="text" class="form-control" value="{{ $libraryProfile['membership_type'] ?? 'N/A' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Borrowing Limit</label>
                                    <input type="text" class="form-control" value="{{ $libraryProfile['borrowing_limit'] ?? 'N/A' }} books" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Currently Borrowed</label>
                                    <input type="text" class="form-control" value="{{ $libraryProfile['current_borrowed'] ?? 'N/A' }} books" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Total Books Borrowed</label>
                                    <input type="text" class="form-control" value="{{ $libraryProfile['total_books_borrowed'] ?? 'N/A' }} books" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Favorite Genres</label>
                                    <input type="text" class="form-control" value="{{ isset($libraryProfile['favorite_genres']) ? implode(', ', $libraryProfile['favorite_genres']) : 'N/A' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reading Goals</label>
                                    <textarea class="form-control" name="reading_goals" rows="3" placeholder="What are your reading goals?">{{ old('reading_goals', $libraryProfile['reading_goals'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Notifications</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="notifications" value="1" {{ old('notifications', $libraryProfile['notifications'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            Receive email notifications for due dates and new arrivals
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Auto Renewal</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="auto_renewal" value="1" {{ old('auto_renewal', $libraryProfile['auto_renewal'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            Automatically renew books when possible
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reading Preferences -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heart me-2"></i>Reading Preferences
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Preferred Languages:</label>
                                <p class="mb-0">
                                    @if(isset($readingPreferences['preferred_languages']) && count($readingPreferences['preferred_languages']) > 0)
                                        @foreach($readingPreferences['preferred_languages'] as $language)
                                            <span class="badge bg-primary me-1">{{ $language }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Preferred Formats:</label>
                                <p class="mb-0">
                                    @if(isset($readingPreferences['preferred_formats']) && count($readingPreferences['preferred_formats']) > 0)
                                        @foreach($readingPreferences['preferred_formats'] as $format)
                                            <span class="badge bg-info me-1">{{ $format }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Preferred Reading Time:</label>
                                <p class="mb-0">{{ $readingPreferences['reading_time'] ?? 'Not specified' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Favorite Authors:</label>
                                <p class="mb-0">
                                    @if(isset($readingPreferences['favorite_authors']) && count($readingPreferences['favorite_authors']) > 0)
                                        @foreach($readingPreferences['favorite_authors'] as $author)
                                            <span class="badge bg-success me-1">{{ $author }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Favorite Books -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>Favorite Books
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($favoriteBooks) > 0)
                        <div class="row">
                            @foreach($favoriteBooks as $book)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <div class="text-center mb-2">
                                            <i class="fas fa-book fa-2x text-primary"></i>
                                        </div>
                                        <h6 class="card-title">{{ $book['title'] }}</h6>
                                        <p class="card-text text-muted">{{ $book['author'] }}</p>
                                        <div class="mb-2">
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $book['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                Added: {{ \Carbon\Carbon::parse($book['added_date'])->format('M d, Y') }}
                                            </small>
                                        </div>
                                        <div class="d-grid">
                                            <a href="{{ route('student.library.book.details', $book['id']) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No favorite books yet</h5>
                            <p class="text-muted">Books you mark as favorites will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reading History -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Reading History
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($readingHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Read Date</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($readingHistory as $history)
                                    <tr>
                                        <td class="fw-bold">{{ $history['book_title'] }}</td>
                                        <td>{{ $history['author'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($history['read_date'])->format('M d, Y') }}</td>
                                        <td>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $history['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $history['status'] === 'Completed' ? 'success' : 'warning' }}">
                                                {{ $history['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBookDetails('{{ $history['book_title'] }}')">
                                                <i class="fas fa-eye"></i>
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
                            <h5 class="text-muted">No reading history found</h5>
                            <p class="text-muted">Your reading history will appear here once you start reading books.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Account Information -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Library Card Number:</label>
                        <p class="mb-0">{{ isset($studentUser) ? 'LIB-' . str_pad($studentUser->id, 6, '0', STR_PAD_LEFT) : 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Account Status:</label>
                        <p class="mb-0">
                            <span class="badge bg-success">Active</span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Last Login:</label>
                        <p class="mb-0">{{ now()->format('M d, Y H:i A') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Profile Updated:</label>
                        <p class="mb-0">{{ now()->format('M d, Y H:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.library.books') }}" class="btn btn-outline-primary">
                            <i class="fas fa-book me-2"></i>Browse Books
                        </a>
                        <a href="{{ route('student.library.search') }}" class="btn btn-outline-success">
                            <i class="fas fa-search me-2"></i>Search Library
                        </a>
                        <a href="{{ route('student.library.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>Borrowing History
                        </a>
                        <button class="btn btn-outline-warning" onclick="contactLibrarian()">
                            <i class="fas fa-envelope me-2"></i>Contact Librarian
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Guidelines -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>Library Guidelines
                </h6>
                <ul class="mb-0">
                    <li>Books can be borrowed for 7, 14, 21, or 30 days</li>
                    <li>Maximum of 5 books can be borrowed at a time</li>
                    <li>Books can be renewed up to 2 times if not reserved</li>
                    <li>Late fees apply for overdue books ($1 per day)</li>
                    <li>Books must be returned in good condition</li>
                    <li>Report any lost or damaged books immediately</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Are you sure you want to reset the form? All unsaved changes will be lost.')) {
        document.querySelector('form').reset();
    }
}

function viewBookDetails(bookTitle) {
    // Here you would typically navigate to the book details page
    alert('Viewing details for: ' + bookTitle);
}

function contactLibrarian() {
    // Here you would typically open a contact form or provide contact information
    alert('Contact librarian functionality would be implemented here.');
}
</script>
@endsection
