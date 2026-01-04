@extends('student.layout.app')

@section('title', 'Search Library')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-search me-2"></i>Search Library
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.library.index') }}">Library</a></li>
                        <li class="breadcrumb-item active">Search</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-search me-2"></i>Search Library
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('student.library.search') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Search Query</label>
                                    <input type="text" class="form-control" name="q" value="{{ $query }}" placeholder="Search for books, authors, subjects, or keywords...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" name="type">
                                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All</option>
                                        <option value="books" {{ $type === 'books' ? 'selected' : '' }}>Books</option>
                                        <option value="journals" {{ $type === 'journals' ? 'selected' : '' }}>Journals</option>
                                        <option value="ebooks" {{ $type === 'ebooks' ? 'selected' : '' }}>E-books</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-2"></i>Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Suggestions -->
    @if(isset($searchSuggestions) && count($searchSuggestions) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Search Suggestions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($searchSuggestions as $suggestion)
                        <a href="{{ route('student.library.search', ['q' => $suggestion]) }}" class="btn btn-outline-primary btn-sm">
                            {{ $suggestion }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Search Results -->
    @if($query)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Search Results for "{{ $query }}"
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($results) && (count($results['books']) > 0 || count($results['journals']) > 0 || count($results['ebooks']) > 0))
                        <!-- Books Results -->
                        @if(count($results['books']) > 0)
                        <div class="mb-4">
                            <h6 class="text-primary">
                                <i class="fas fa-book me-2"></i>Books ({{ count($results['books']) }})
                            </h6>
                            <div class="row">
                                @foreach($results['books'] as $book)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <div class="text-center mb-2">
                                                <i class="fas fa-book fa-2x text-primary"></i>
                                            </div>
                                            <h6 class="card-title">{{ $book['title'] }}</h6>
                                            <p class="card-text text-muted">{{ $book['author'] }}</p>
                                            <div class="mb-2">
                                                <span class="badge bg-info">{{ $book['category'] }}</span>
                                                <span class="badge bg-{{ $book['availability'] === 'Available' ? 'success' : 'warning' }}">
                                                    {{ $book['availability'] }}
                                                </span>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    Relevance: {{ $book['relevance_score'] }}%
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
                        </div>
                        @endif

                        <!-- Journals Results -->
                        @if(count($results['journals']) > 0)
                        <div class="mb-4">
                            <h6 class="text-success">
                                <i class="fas fa-newspaper me-2"></i>Journals ({{ count($results['journals']) }})
                            </h6>
                            <div class="row">
                                @foreach($results['journals'] as $journal)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <div class="text-center mb-2">
                                                <i class="fas fa-newspaper fa-2x text-success"></i>
                                            </div>
                                            <h6 class="card-title">{{ $journal['title'] }}</h6>
                                            <p class="card-text text-muted">{{ $journal['author'] }}</p>
                                            <div class="mb-2">
                                                <span class="badge bg-success">{{ $journal['category'] }}</span>
                                                <span class="badge bg-{{ $journal['availability'] === 'Available' ? 'success' : 'warning' }}">
                                                    {{ $journal['availability'] }}
                                                </span>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    Relevance: {{ $journal['relevance_score'] }}%
                                                </small>
                                            </div>
                                            <div class="d-grid">
                                                <button class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- E-books Results -->
                        @if(count($results['ebooks']) > 0)
                        <div class="mb-4">
                            <h6 class="text-warning">
                                <i class="fas fa-tablet-alt me-2"></i>E-books ({{ count($results['ebooks']) }})
                            </h6>
                            <div class="row">
                                @foreach($results['ebooks'] as $ebook)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <div class="text-center mb-2">
                                                <i class="fas fa-tablet-alt fa-2x text-warning"></i>
                                            </div>
                                            <h6 class="card-title">{{ $ebook['title'] }}</h6>
                                            <p class="card-text text-muted">{{ $ebook['author'] }}</p>
                                            <div class="mb-2">
                                                <span class="badge bg-warning">{{ $ebook['category'] }}</span>
                                                <span class="badge bg-{{ $ebook['availability'] === 'Available' ? 'success' : 'warning' }}">
                                                    {{ $ebook['availability'] }}
                                                </span>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    Relevance: {{ $ebook['relevance_score'] }}%
                                                </small>
                                            </div>
                                            <div class="d-grid">
                                                <button class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No results found</h5>
                            <p class="text-muted">Try adjusting your search terms or browse our collection.</p>
                            <div class="mt-3">
                                <a href="{{ route('student.library.books') }}" class="btn btn-primary">
                                    <i class="fas fa-book me-2"></i>Browse Books
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Search Tips -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Search Tips
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Search Tips</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Use specific keywords for better results</li>
                                <li><i class="fas fa-check text-success me-2"></i>Try searching by author name</li>
                                <li><i class="fas fa-check text-success me-2"></i>Use book titles or ISBN numbers</li>
                                <li><i class="fas fa-check text-success me-2"></i>Search by subject or category</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Popular Searches</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('student.library.search', ['q' => 'computer science']) }}" class="btn btn-outline-primary btn-sm">
                                    Computer Science
                                </a>
                                <a href="{{ route('student.library.search', ['q' => 'mathematics']) }}" class="btn btn-outline-primary btn-sm">
                                    Mathematics
                                </a>
                                <a href="{{ route('student.library.search', ['q' => 'programming']) }}" class="btn btn-outline-primary btn-sm">
                                    Programming
                                </a>
                                <a href="{{ route('student.library.search', ['q' => 'algorithms']) }}" class="btn btn-outline-primary btn-sm">
                                    Algorithms
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
