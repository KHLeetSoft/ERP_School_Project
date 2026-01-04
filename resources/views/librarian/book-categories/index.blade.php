@extends('librarian.layout.app')

@section('title', 'Book Categories')
@section('page-title', 'Book Categories')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Book Categories Management</h5>
                <a href="{{ route('librarian.book-categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add New Category
                </a>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <div class="row g-3 align-items-end mb-3">
                    <form method="GET" action="{{ route('librarian.book-categories.index') }}" class="row g-3 align-items-end mb-0">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search categories...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Status</label>
                            <select class="form-select" name="status" onchange="this.form.submit()">
                                <option value="">All</option>
                                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Categories Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Books Count</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $category->name }}</h6>
                                            <small class="text-muted">{{ $category->slug }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ Str::limit($category->description, 50) ?: 'No description' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $category->active_books_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $category->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('librarian.book-categories.show', $category) }}" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('librarian.book-categories.edit', $category) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('librarian.book-categories.toggle-status', $category) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-{{ $category->status === 'active' ? 'warning' : 'success' }}"
                                                    title="{{ $category->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $category->status === 'active' ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('librarian.book-categories.destroy', $category) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-folder-open fa-2x mb-2"></i>
                                        <p>No book categories found.</p>
                                        <a href="{{ route('librarian.book-categories.create') }}" class="btn btn-primary">
                                            Create First Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($categories->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
