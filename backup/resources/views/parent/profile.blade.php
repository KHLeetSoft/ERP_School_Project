@extends('parent.layout.app')

@section('title', 'Profile')

@section('content')
<div class="page-header">
    <h1 class="page-title">My Profile</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-user me-2"></i>Profile Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('parent.profile') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="primary_contact_name" class="form-label">Full Name *</label>
                                <input type="text" 
                                       class="form-control @error('primary_contact_name') is-invalid @enderror" 
                                       id="primary_contact_name" 
                                       name="primary_contact_name" 
                                       value="{{ old('primary_contact_name', $parent->primary_contact_name) }}" 
                                       required>
                                @error('primary_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone_primary" class="form-label">Primary Phone *</label>
                                <input type="tel" 
                                       class="form-control @error('phone_primary') is-invalid @enderror" 
                                       id="phone_primary" 
                                       name="phone_primary" 
                                       value="{{ old('phone_primary', $parent->phone_primary) }}" 
                                       required>
                                @error('phone_primary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email_primary" class="form-label">Primary Email *</label>
                                <input type="email" 
                                       class="form-control @error('email_primary') is-invalid @enderror" 
                                       id="email_primary" 
                                       name="email_primary" 
                                       value="{{ old('email_primary', $parent->email_primary) }}" 
                                       required>
                                @error('email_primary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone_secondary" class="form-label">Secondary Phone</label>
                                <input type="tel" 
                                       class="form-control @error('phone_secondary') is-invalid @enderror" 
                                       id="phone_secondary" 
                                       name="phone_secondary" 
                                       value="{{ old('phone_secondary', $parent->phone_secondary) }}">
                                @error('phone_secondary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="email_secondary" class="form-label">Secondary Email</label>
                        <input type="email" 
                               class="form-control @error('email_secondary') is-invalid @enderror" 
                               id="email_secondary" 
                               name="email_secondary" 
                               value="{{ old('email_secondary', $parent->email_secondary) }}">
                        @error('email_secondary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="father_name" class="form-label">Father's Name</label>
                                <input type="text" 
                                       class="form-control @error('father_name') is-invalid @enderror" 
                                       id="father_name" 
                                       name="father_name" 
                                       value="{{ old('father_name', $parent->father_name) }}">
                                @error('father_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="mother_name" class="form-label">Mother's Name</label>
                                <input type="text" 
                                       class="form-control @error('mother_name') is-invalid @enderror" 
                                       id="mother_name" 
                                       name="mother_name" 
                                       value="{{ old('mother_name', $parent->mother_name) }}">
                                @error('mother_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3">{{ old('address', $parent->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="occupation_father" class="form-label">Father's Occupation</label>
                                <input type="text" 
                                       class="form-control @error('occupation_father') is-invalid @enderror" 
                                       id="occupation_father" 
                                       name="occupation_father" 
                                       value="{{ old('occupation_father', $parent->occupation_father) }}">
                                @error('occupation_father')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="occupation_mother" class="form-label">Mother's Occupation</label>
                                <input type="text" 
                                       class="form-control @error('occupation_mother') is-invalid @enderror" 
                                       id="occupation_mother" 
                                       name="occupation_mother" 
                                       value="{{ old('occupation_mother', $parent->occupation_mother) }}">
                                @error('occupation_mother')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="income_range" class="form-label">Income Range</label>
                                <select class="form-control @error('income_range') is-invalid @enderror" 
                                        id="income_range" 
                                        name="income_range">
                                    <option value="">Select Income Range</option>
                                    <option value="0-25000" {{ old('income_range', $parent->income_range) == '0-25000' ? 'selected' : '' }}>₹0 - ₹25,000</option>
                                    <option value="25000-50000" {{ old('income_range', $parent->income_range) == '25000-50000' ? 'selected' : '' }}>₹25,000 - ₹50,000</option>
                                    <option value="50000-100000" {{ old('income_range', $parent->income_range) == '50000-100000' ? 'selected' : '' }}>₹50,000 - ₹1,00,000</option>
                                    <option value="100000+" {{ old('income_range', $parent->income_range) == '100000+' ? 'selected' : '' }}>₹1,00,000+</option>
                                </select>
                                @error('income_range')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="guardian_name" class="form-label">Guardian Name</label>
                                <input type="text" 
                                       class="form-control @error('guardian_name') is-invalid @enderror" 
                                       id="guardian_name" 
                                       name="guardian_name" 
                                       value="{{ old('guardian_name', $parent->guardian_name) }}">
                                @error('guardian_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                                <input type="text" 
                                       class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       id="emergency_contact_name" 
                                       name="emergency_contact_name" 
                                       value="{{ old('emergency_contact_name', $parent->emergency_contact_name) }}">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                                <input type="tel" 
                                       class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                       id="emergency_contact_phone" 
                                       name="emergency_contact_phone" 
                                       value="{{ old('emergency_contact_phone', $parent->emergency_contact_phone) }}">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3">{{ old('notes', $parent->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Account Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>Account Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Account Status</label>
                    <div>
                        <span class="badge {{ $parent->status == 'active' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($parent->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Member Since</label>
                    <div>{{ $parent->created_at->format('M d, Y') }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Last Updated</label>
                    <div>{{ $parent->updated_at->format('M d, Y H:i') }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Children Count</label>
                    <div>{{ $parent->getChildrenCount() }}</div>
                </div>
            </div>
        </div>
        
        <!-- Change Password -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-key me-2"></i>Change Password
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Click the button below to change your password.</p>
                <button class="btn btn-outline-primary w-100" onclick="changePassword()">
                    <i class="fas fa-key me-2"></i>Change Password
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function changePassword() {
        // Implement change password modal or redirect
        alert('Change password functionality will be implemented here.');
    }
</script>
@endpush
