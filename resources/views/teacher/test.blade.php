@extends('teacher.layout.app')

@section('title', 'Teacher Test')
@section('page-title', 'Teacher Authentication Test')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Teacher Authentication Test</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h6><i class="fas fa-check-circle me-2"></i>Authentication Successful!</h6>
                    <p class="mb-2"><strong>User:</strong> {{ auth()->user()->name }}</p>
                    <p class="mb-2"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p class="mb-2"><strong>Role:</strong> {{ auth()->user()->userRole ? auth()->user()->userRole->name : 'No Role' }}</p>
                    <p class="mb-0"><strong>User ID:</strong> {{ auth()->id() }}</p>
                </div>
                
                <div class="mt-4">
                    <h6>Test Form Submission</h6>
                    <form method="POST" action="{{ route('teacher.diary.store') }}" id="testForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Test Title</label>
                            <input type="text" class="form-control" name="title" value="Test Entry">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Entry Date</label>
                            <input type="date" class="form-control" name="entry_date" value="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" name="content" rows="3" required>This is a test diary entry.</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Test Form</button>
                    </form>
                </div>
                
                <div class="mt-4">
                    <h6>Available Routes</h6>
                    <ul>
                        <li><a href="{{ route('teacher.diary.index') }}">Diary Index</a></li>
                        <li><a href="{{ route('teacher.diary.create') }}">Diary Create</a></li>
                        <li><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('teacher.profile') }}">Profile</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('testForm').addEventListener('submit', function(e) {
    console.log('Form submitted!');
    console.log('Form data:', new FormData(this));
});
</script>
@endsection