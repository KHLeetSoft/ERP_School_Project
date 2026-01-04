@extends('librarian.layout.app')

@section('title', 'Book Issue Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Book Issue Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('librarian.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('librarian.books.index') }}">Books</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('librarian.book-issues.index') }}">Issues</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Issue #{{ $bookIssue->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Issue Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Issue Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Issue ID:</label>
                                <p class="form-control-plaintext">#{{ $bookIssue->id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <p>
                                    @if($bookIssue->status == 'issued')
                                        <span class="badge bg-warning">Issued</span>
                                    @elseif($bookIssue->status == 'returned')
                                        <span class="badge bg-success">Returned</span>
                                    @elseif($bookIssue->status == 'overdue')
                                        <span class="badge bg-danger">Overdue</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Issue Date:</label>
                                <p class="form-control-plaintext">
                                    {{ \Carbon\Carbon::parse($bookIssue->issued_at)->format('M d, Y H:i A') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Due Date:</label>
                                <p class="form-control-plaintext">
                                    {{ \Carbon\Carbon::parse($bookIssue->due_date)->format('M d, Y H:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($bookIssue->returned_at)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Return Date:</label>
                                    <p class="form-control-plaintext">
                                        {{ \Carbon\Carbon::parse($bookIssue->returned_at)->format('M d, Y H:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fine Amount:</label>
                                    <p class="form-control-plaintext fw-bold text-danger">
                                        ₹{{ number_format($bookIssue->fine_amount, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($bookIssue->notes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes:</label>
                            <p class="form-control-plaintext">{{ $bookIssue->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Book Details -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Book Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($bookIssue->book->cover_image)
                            <img src="{{ asset('storage/' . $bookIssue->book->cover_image) }}" 
                                 alt="{{ $bookIssue->book->title }}" 
                                 class="img-thumbnail" 
                                 style="width: 150px; height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 200px; margin: 0 auto;">
                                <i class="fas fa-book text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h6 class="fw-bold">{{ $bookIssue->book->title }}</h6>
                    <p class="text-muted mb-2">
                        <strong>Author:</strong> {{ $bookIssue->book->author }}<br>
                        <strong>ISBN:</strong> {{ $bookIssue->book->isbn }}<br>
                        <strong>Publisher:</strong> {{ $bookIssue->book->publisher }}<br>
                        <strong>Year:</strong> {{ $bookIssue->book->publication_year }}
                    </p>
                </div>
            </div>

            <!-- Student Details -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Student Information</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">{{ $bookIssue->student->first_name }} {{ $bookIssue->student->last_name }}</h6>
                    <p class="text-muted mb-2">
                        <strong>Roll No:</strong> {{ $bookIssue->student->roll_no }}<br>
                        <strong>Class:</strong> {{ $bookIssue->student->classSection->class->name ?? 'N/A' }}<br>
                        <strong>Section:</strong> {{ $bookIssue->student->classSection->section->name ?? 'N/A' }}<br>
                        <strong>Phone:</strong> {{ $bookIssue->student->phone ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Actions</h5>
                            <p class="text-muted mb-0">Manage this book issue</p>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('librarian.book-issues.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            @if($bookIssue->status != 'returned')
                                <a href="{{ route('librarian.book-issues.return', $bookIssue->id) }}" class="btn btn-success">
                                    <i class="fas fa-undo me-2"></i>Process Return
                                </a>
                            @endif
                            <a href="{{ route('librarian.book-issues.edit', $bookIssue->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
