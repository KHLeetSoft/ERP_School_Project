@extends('teacher.layout.app')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')
@section('page-description', 'Update student information')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i>Edit Student Information</h5>
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

                <form method="POST" action="{{ route('teacher.students.update', $student) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   name="first_name" value="{{ old('first_name', $student->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   name="last_name" value="{{ old('last_name', $student->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email', $student->user->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" value="{{ old('phone', $student->phone) }}" 
                                   placeholder="e.g., +1234567890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth) }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" @selected(old('gender', $student->gender) == 'male')>Male</option>
                                <option value="female" @selected(old('gender', $student->gender) == 'female')>Female</option>
                                <option value="other" @selected(old('gender', $student->gender) == 'other')>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Class</label>
                            <input type="text" class="form-control" 
                                   value="{{ $student->class->name ?? 'Not assigned' }}" readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Student ID</label>
                            <input type="text" class="form-control @error('student_id') is-invalid @enderror" 
                                   name="student_id" value="{{ old('student_id', $student->student_id) }}" 
                                   placeholder="e.g., STU001">
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  name="address" rows="3" 
                                  placeholder="Enter student's address">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent/Guardian Name</label>
                            <input type="text" class="form-control @error('parent_name') is-invalid @enderror" 
                                   name="parent_name" value="{{ old('parent_name', $student->parent_name) }}" 
                                   placeholder="e.g., John Doe">
                            @error('parent_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Parent/Guardian Phone</label>
                            <input type="text" class="form-control @error('parent_phone') is-invalid @enderror" 
                                   name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" 
                                   placeholder="e.g., +1234567890">
                            @error('parent_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Emergency Contact Name</label>
                            <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                   name="emergency_contact_name" value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}" 
                                   placeholder="e.g., John Doe">
                            @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Emergency Contact Phone</label>
                            <input type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                   name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $student->emergency_contact_phone) }}" 
                                   placeholder="e.g., +1234567890">
                            @error('emergency_contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Relation</label>
                            <input type="text" class="form-control @error('emergency_contact_relation') is-invalid @enderror" 
                                   name="emergency_contact_relation" value="{{ old('emergency_contact_relation', $student->emergency_contact_relation) }}" 
                                   placeholder="e.g., Father, Mother, Uncle">
                            @error('emergency_contact_relation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card modern-card">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Student Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Student ID:</strong><br>
                    <span class="text-muted">{{ $student->student_id ?? 'Not assigned' }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Current Class:</strong><br>
                    <span class="text-muted">{{ $student->class->name ?? 'Not assigned' }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Status:</strong><br>
                    <span class="badge bg-{{ $student->status ? 'success' : 'secondary' }}">
                        {{ $student->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Created:</strong><br>
                    <small class="text-muted">{{ $student->created_at->format('M d, Y \a\t g:i A') }}</small>
                </div>
                
                <div class="mb-0">
                    <strong>Last Updated:</strong><br>
                    <small class="text-muted">{{ $student->updated_at->format('M d, Y \a\t g:i A') }}</small>
                </div>
            </div>
        </div>
        
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-tools me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-2"></i>View Details
                    </a>
                    <a href="{{ route('teacher.attendance.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-check-circle me-2"></i>Take Attendance
                    </a>
                    <a href="{{ route('teacher.grades.index') }}" class="btn btn-outline-warning">
                        <i class="fas fa-graduation-cap me-2"></i>Manage Grades
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Basic validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
    });
    
    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endsection
