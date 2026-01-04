@extends('librarian.layout.app')

@section('title', 'Issue New Book')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Issue New Book</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('librarian.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('librarian.books.index') }}">Books</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('librarian.book-issues.index') }}">Issues</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Issue New Book</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Issue Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Book Issue Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('librarian.book-issues.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="book_id" class="form-label">Select Book <span class="text-danger">*</span></label>
                                    <select name="book_id" id="book_id" class="form-select @error('book_id') is-invalid @enderror" required>
                                        <option value="">Choose a book...</option>
                                        @foreach($books as $book)
                                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                                {{ $book->title }} - {{ $book->author }} ({{ $book->isbn }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('book_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Select Student <span class="text-danger">*</span></label>
                                    <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                        <option value="">Choose a student...</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->first_name }} {{ $student->last_name }} - {{ $student->roll_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issued_at" class="form-label">Issue Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           name="issued_at" 
                                           id="issued_at" 
                                           class="form-control @error('issued_at') is-invalid @enderror" 
                                           value="{{ old('issued_at', now()->format('Y-m-d\TH:i')) }}" 
                                           required>
                                    @error('issued_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           name="due_date" 
                                           id="due_date" 
                                           class="form-control @error('due_date') is-invalid @enderror" 
                                           value="{{ old('due_date', now()->addDays(7)->format('Y-m-d\TH:i')) }}" 
                                           required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('librarian.book-issues.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-book me-2"></i>Issue Book
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
    // Auto-fill due date when issue date changes
    const issuedAtInput = document.getElementById('issued_at');
    const dueDateInput = document.getElementById('due_date');
    
    issuedAtInput.addEventListener('change', function() {
        if (this.value) {
            const issuedDate = new Date(this.value);
            const dueDate = new Date(issuedDate);
            dueDate.setDate(dueDate.getDate() + 7); // Add 7 days
            dueDateInput.value = dueDate.toISOString().slice(0, 16);
        }
    });
});
</script>
@endsection
