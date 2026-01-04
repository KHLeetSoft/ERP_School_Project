@extends('student.layout.app')

@section('title', 'Student Profile')
@section('page-title', 'Profile')

@section('content')
<div class="row">
    <!-- Profile Information -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profile Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if(isset($student) && $student)
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" 
                                       value="{{ $student->first_name }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" 
                                       value="{{ $student->last_name }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="admission_no" class="form-label">Admission Number</label>
                                <input type="text" class="form-control" 
                                       value="{{ $student->admission_no }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="roll_no" class="form-label">Roll Number</label>
                                <input type="text" class="form-control" 
                                       value="{{ $student->roll_no }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $student->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="text" class="form-control" 
                                       value="{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : 'N/A' }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <input type="text" class="form-control" 
                                       value="{{ ucfirst($student->gender) }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="class_name" class="form-label">Class</label>
                                <input type="text" class="form-control" 
                                       value="{{ $student->class_name }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" rows="3" readonly>{{ $student->address }}</textarea>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Picture & Quick Info -->
    <div class="col-lg-4 mb-4">
        <!-- Profile Picture -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Profile Picture</h5>
            </div>
            <div class="card-body text-center">
                @if(isset($student) && $student && $student->profile_image)
                    <img src="{{ asset('storage/' . $student->profile_image) }}" 
                         alt="Profile Picture" 
                         class="rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user text-white" style="font-size: 4rem;"></i>
                    </div>
                @endif
                <p class="text-muted">Profile picture managed by admin</p>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quick Info</h5>
            </div>
            <div class="card-body">
                @if(isset($student) && $student)
                    <div class="mb-3">
                        <strong>Admission Date:</strong><br>
                        <span class="text-muted">
                            {{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M Y') : 'N/A' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>
                    
                    @if($student->blood_group)
                        <div class="mb-3">
                            <strong>Blood Group:</strong><br>
                            <span class="text-muted">{{ $student->blood_group }}</span>
                        </div>
                    @endif
                    
                    @if($student->religion)
                        <div class="mb-3">
                            <strong>Religion:</strong><br>
                            <span class="text-muted">{{ $student->religion }}</span>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.change-password') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const passwordForm = document.querySelector('form[action="{{ route('student.change-password') }}"]');
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        
        function validatePassword() {
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.setCustomValidity('Passwords do not match');
            } else {
                passwordConfirmation.setCustomValidity('');
            }
        }
        
        password.addEventListener('change', validatePassword);
        passwordConfirmation.addEventListener('keyup', validatePassword);
    });
</script>
@endsection
