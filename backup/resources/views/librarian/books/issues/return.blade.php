@extends('librarian.layout.app')

@section('title', 'Process Book Return')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Process Book Return</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('librarian.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('librarian.books.index') }}">Books</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('librarian.book-issues.index') }}">Issues</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Return Book</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Issue Details -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Issue Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($bookIssue->book->cover_image)
                            <img src="{{ asset('storage/' . $bookIssue->book->cover_image) }}" 
                                 alt="{{ $bookIssue->book->title }}" 
                                 class="img-thumbnail" 
                                 style="width: 120px; height: 160px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 160px; margin: 0 auto;">
                                <i class="fas fa-book text-muted" style="font-size: 2rem;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h6 class="fw-bold">{{ $bookIssue->book->title }}</h6>
                    <p class="text-muted mb-2">
                        <strong>Author:</strong> {{ $bookIssue->book->author }}<br>
                        <strong>ISBN:</strong> {{ $bookIssue->book->isbn }}
                    </p>
                    
                    <hr>
                    
                    <h6 class="fw-bold">{{ $bookIssue->student->first_name }} {{ $bookIssue->student->last_name }}</h6>
                    <p class="text-muted mb-2">
                        <strong>Roll No:</strong> {{ $bookIssue->student->roll_no }}<br>
                        <strong>Class:</strong> {{ $bookIssue->student->classSection->class->name ?? 'N/A' }}
                    </p>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Issue Date:</small><br>
                            <strong>{{ \Carbon\Carbon::parse($bookIssue->issued_at)->format('M d, Y') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Due Date:</small><br>
                            <strong class="{{ \Carbon\Carbon::parse($bookIssue->due_date)->isPast() ? 'text-danger' : 'text-success' }}">
                                {{ \Carbon\Carbon::parse($bookIssue->due_date)->format('M d, Y') }}
                            </strong>
                        </div>
                    </div>
                    
                    @php
                        $daysOverdue = \Carbon\Carbon::parse($bookIssue->due_date)->diffInDays(\Carbon\Carbon::now());
                        $isOverdue = \Carbon\Carbon::parse($bookIssue->due_date)->isPast();
                    @endphp
                    
                    @if($isOverdue)
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Overdue by {{ $daysOverdue }} {{ $daysOverdue == 1 ? 'day' : 'days' }}</strong><br>
                            <small>Fine: ₹{{ $daysOverdue * 5 }} (₹5 per day)</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Return Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Return Book Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('librarian.book-issues.process-return', $bookIssue->id) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="returned_at" class="form-label">Return Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           name="returned_at" 
                                           id="returned_at" 
                                           class="form-control @error('returned_at') is-invalid @enderror" 
                                           value="{{ old('returned_at', now()->format('Y-m-d\TH:i')) }}" 
                                           required>
                                    @error('returned_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="condition" class="form-label">Book Condition <span class="text-danger">*</span></label>
                                    <select name="condition" id="condition" class="form-select @error('condition') is-invalid @enderror" required>
                                        <option value="">Select condition...</option>
                                        <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                                        <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                                        <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fine_paid" class="form-label">Fine Paid (₹)</label>
                                    <input type="number" 
                                           name="fine_paid" 
                                           id="fine_paid" 
                                           class="form-control @error('fine_paid') is-invalid @enderror" 
                                           value="{{ old('fine_paid', $isOverdue ? $daysOverdue * 5 : 0) }}" 
                                           min="0" 
                                           step="0.01">
                                    @error('fine_paid')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($isOverdue)
                                        <div class="form-text">Calculated fine: ₹{{ $daysOverdue * 5 }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-control-plaintext">
                                        @if($isOverdue)
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-success">On Time</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" 
                                      id="remarks" 
                                      class="form-control @error('remarks') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Any additional remarks about the return...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('librarian.book-issues.show', $bookIssue->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Process Return
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate fine when return date changes
    const returnedAtInput = document.getElementById('returned_at');
    const finePaidInput = document.getElementById('fine_paid');
    const dueDate = new Date('{{ $bookIssue->due_date }}');
    
    returnedAtInput.addEventListener('change', function() {
        if (this.value) {
            const returnDate = new Date(this.value);
            if (returnDate > dueDate) {
                const daysOverdue = Math.ceil((returnDate - dueDate) / (1000 * 60 * 60 * 24));
                finePaidInput.value = daysOverdue * 5;
            } else {
                finePaidInput.value = 0;
            }
        }
    });
});
</script>
@endsection
