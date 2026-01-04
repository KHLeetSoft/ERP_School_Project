@extends('teacher.layout.app')

@section('title', 'Create Class')
@section('page-title', 'Create New Class')
@section('page-description', 'Add a new teaching class to your schedule')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-plus me-2"></i>Create New Class</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('teacher.classes.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Class Name *</label>
                            <input type="text" class="form-control @error('class_name') is-invalid @enderror" 
                                   name="class_name" value="{{ old('class_name') }}" required 
                                   placeholder="e.g., Mathematics Grade 10">
                            @error('class_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subject *</label>
                            <select class="form-select @error('subject_id') is-invalid @enderror" 
                                    name="subject_id" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                        {{ $subject->subject_name }} ({{ $subject->subject_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">School Class *</label>
                            <select class="form-select @error('school_class_id') is-invalid @enderror" 
                                    name="school_class_id" required>
                                <option value="">Select School Class</option>
                                @foreach($schoolClasses as $schoolClass)
                                    <option value="{{ $schoolClass->id }}" @selected(old('school_class_id') == $schoolClass->id)>
                                        {{ $schoolClass->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Number</label>
                            <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                   name="room_number" value="{{ old('room_number') }}" 
                                   placeholder="e.g., Room 101">
                            @error('room_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time *</label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time *</label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Days of Week *</label>
                        <div class="row">
                            @foreach($daysOfWeek as $day)
                                <div class="col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="days[]" value="{{ $day }}" 
                                               id="day_{{ $loop->index }}"
                                               @checked(in_array($day, old('days', [])))>
                                        <label class="form-check-label" for="day_{{ $loop->index }}">
                                            {{ $day }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('days')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="3" 
                                  placeholder="Optional description about this class">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card modern-card">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Choose a descriptive class name that includes the subject and grade level.</li>
                    <li>Select the appropriate school class from the dropdown.</li>
                    <li>Set realistic start and end times for your class.</li>
                    <li>Select all days when this class will be held.</li>
                    <li>Add a room number if you have a fixed classroom.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Time validation
    const startTime = document.querySelector('input[name="start_time"]');
    const endTime = document.querySelector('input[name="end_time"]');
    
    function validateTimes() {
        if (startTime.value && endTime.value) {
            if (startTime.value >= endTime.value) {
                endTime.setCustomValidity('End time must be after start time');
            } else {
                endTime.setCustomValidity('');
            }
        }
    }
    
    startTime.addEventListener('change', validateTimes);
    endTime.addEventListener('change', validateTimes);
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Check if at least one day is selected
        const daysChecked = document.querySelectorAll('input[name="days[]"]:checked');
        if (daysChecked.length === 0) {
            e.preventDefault();
            alert('Please select at least one day for the class.');
            return false;
        }
        
        // Validate times
        validateTimes();
        if (endTime.checkValidity() === false) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection
