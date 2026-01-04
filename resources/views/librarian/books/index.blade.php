@extends('librarian.layout.app')

@section('title', 'Books Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Books Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('librarian.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Books</li>
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
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['total_books'] }}</h3>
                            <p class="stats-label">Total Books</p>
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
                            <h3 class="stats-number">{{ $stats['available_books'] }}</h3>
                            <p class="stats-label">Available</p>
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
                            <i class="fas fa-hand-holding"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="stats-number">{{ $stats['checked_out_books'] }}</h3>
                            <p class="stats-label">Checked Out</p>
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
                            <i class="fas fa-list me-2"></i>Books List
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('librarian.books.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add New Book
                            </a>
                            <a href="{{ route('librarian.book-issues.create') }}" class="btn btn-success">
                                <i class="fas fa-hand-holding me-2"></i>Issue Book
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('librarian.books.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="Search books..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                                <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('librarian.books.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </div>

                    <!-- Books Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>ISBN</th>
                                    <th>Genre</th>
                                    <th>Stock</th>
                                    <th>Available</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $book)
                                    <tr>
                                        <td>
                                            @if($book->cover_image)
                                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                                     alt="Book Cover" class="book-cover">
                                            @else
                                                <div class="book-cover-placeholder">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="book-title">{{ $book->title }}</div>
                                            <small class="text-muted">{{ $book->published_year }}</small>
                                        </td>
                                        <td>{{ $book->author }}</td>
                                        <td>{{ $book->isbn ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $book->genre ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $book->stock_quantity }}</td>
                                        <td>
                                            <span class="badge {{ $book->available_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $book->available_quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($book->status)
                                                @case('available')
                                                    <span class="badge bg-success">Available</span>
                                                    @break
                                                @case('checked_out')
                                                    <span class="badge bg-warning">Checked Out</span>
                                                    @break
                                                @case('lost')
                                                    <span class="badge bg-danger">Lost</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('librarian.books.show', $book) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('librarian.books.edit', $book) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($book->is_available)
                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                            title="Issue Book" onclick="issueBook({{ $book->id }})">
                                                        <i class="fas fa-hand-holding"></i>
                                                    </button>
                                                @endif
                                                <form action="{{ route('librarian.books.destroy', $book) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this book?')">
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
                                        <td colspan="9" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                                <h5>No books found</h5>
                                                <p class="text-muted">Start by adding your first book to the library.</p>
                                                <a href="{{ route('librarian.books.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Add New Book
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($books->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $books->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Issue Book Modal -->
<div class="modal fade" id="issueBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Issue Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="issueBookForm" method="POST" action="{{ route('librarian.books.issue') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="issue_book_id" name="book_id">
                    
                    <div class="mb-3">
                        <label for="issue_student_id" class="form-label">Student</label>
                        <select name="student_id" id="issue_student_id" class="form-select" required>
                            <option value="">Select Student</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="issue_due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="issue_due_date" 
                               class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="issue_notes" class="form-label">Notes (Optional)</label>
                        <textarea name="notes" id="issue_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Issue Book</button>
                </div>
            </form>
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

.book-cover {
    width: 50px;
    height: 70px;
    object-fit: cover;
    border-radius: 0.25rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.book-cover-placeholder {
    width: 50px;
    height: 70px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.book-title {
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
</style>

<script>
function issueBook(bookId) {
    document.getElementById('issue_book_id').value = bookId;
    
    // Fetch students for the dropdown
    fetch(`/librarian/books/${bookId}/issue-form-data`)
        .then(response => response.json())
        .then(data => {
            const studentSelect = document.getElementById('issue_student_id');
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            
            data.students.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = `${student.name} (${student.admission_number})`;
                studentSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching students:', error);
        });
    
    // Show modal
    new bootstrap.Modal(document.getElementById('issueBookModal')).show();
}
</script>
@endsection
