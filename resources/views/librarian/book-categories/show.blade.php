@extends('librarian.layout.app')

@section('title', 'View Book Category')
@section('page-title', 'View Book Category')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Category Details</h5>
                <div class="btn-group">
                    <a href="{{ route('librarian.book-categories.edit', $bookCategory) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('librarian.book-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    {{ strtoupper(substr($bookCategory->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="mb-0">{{ $bookCategory->name }}</h3>
                                    <p class="text-muted mb-0">{{ $bookCategory->slug }}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Status</label>
                                    <div>
                                        <span class="badge bg-{{ $bookCategory->status === 'active' ? 'success' : 'secondary' }} fs-6">
                                            {{ ucfirst($bookCategory->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Books Count</label>
                                    <div>
                                        <span class="badge bg-info fs-6">{{ $bookCategory->active_books_count }} books</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($bookCategory->description)
                            <div class="mb-3">
                                <label class="form-label text-muted">Description</label>
                                <p class="mb-0">{{ $bookCategory->description }}</p>
                            </div>
                            @endif
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Created</label>
                                    <p class="mb-0">{{ $bookCategory->created_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <p class="mb-0">{{ $bookCategory->updated_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Books in this Category -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Books in this Category</h5>
            </div>
            <div class="card-body">
                @if($books->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>ISBN</th>
                                <th>Status</th>
                                <th>Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            @if($book->cover_image)
                                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                                     alt="{{ $book->title }}" 
                                                     class="rounded" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-book text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $book->title }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->isbn }}</td>
                                <td>
                                    <span class="badge bg-{{ $book->status === 'available' ? 'success' : 'warning' }}">
                                        {{ ucfirst(str_replace('_', ' ', $book->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $book->created_at->format('M d, Y') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($books->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $books->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-book fa-2x mb-2"></i>
                        <p>No books found in this category.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
