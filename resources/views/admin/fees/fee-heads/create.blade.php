@extends('admin.layout.app')

@section('title', 'Create Fee Head')
@section('page-title', 'Create Fee Head')
@section('page-description', 'Add a new fee head for fee management')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>Create New Fee Head
                </h5>
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

                <form method="POST" action="{{ route('admin.fees.fee-heads.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fee Head Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required 
                                   placeholder="e.g., Tuition Fee">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   name="code" value="{{ old('code') }}" required 
                                   placeholder="e.g., TUITION">
                            <small class="form-text text-muted">Unique code for this fee head (will be converted to uppercase)</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="3" 
                                  placeholder="Optional description about this fee head">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type *</label>
                            <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                <option value="">Select Type</option>
                                <option value="mandatory" @selected(old('type') == 'mandatory')>Mandatory</option>
                                <option value="optional" @selected(old('type') == 'optional')>Optional</option>
                            </select>
                            <small class="form-text text-muted">Mandatory fees are required for all students</small>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Frequency *</label>
                            <select class="form-select @error('frequency') is-invalid @enderror" name="frequency" required>
                                <option value="">Select Frequency</option>
                                <option value="monthly" @selected(old('frequency') == 'monthly')>Monthly</option>
                                <option value="quarterly" @selected(old('frequency') == 'quarterly')>Quarterly</option>
                                <option value="yearly" @selected(old('frequency') == 'yearly')>Yearly</option>
                                <option value="one_time" @selected(old('frequency') == 'one_time')>One Time</option>
                            </select>
                            @error('frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                   name="sort_order" value="{{ old('sort_order', 0) }}" min="0" 
                                   placeholder="0">
                            <small class="form-text text-muted">Lower numbers appear first in lists</small>
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.fees.fee-heads.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Fee Head</button>
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
                    <li><strong>Mandatory:</strong> Required for all students (e.g., Tuition Fee)</li>
                    <li><strong>Optional:</strong> Not required for all students (e.g., Transport Fee)</li>
                    <li><strong>Monthly:</strong> Charged every month</li>
                    <li><strong>Quarterly:</strong> Charged every 3 months</li>
                    <li><strong>Yearly:</strong> Charged once per year</li>
                    <li><strong>One Time:</strong> Charged only once (e.g., Admission Fee)</li>
                </ul>
            </div>
        </div>
        
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Common Fee Heads</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Mandatory:</small><br>
                        <small>• Tuition Fee</small><br>
                        <small>• Exam Fee</small><br>
                        <small>• Library Fee</small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Optional:</small><br>
                        <small>• Transport Fee</small><br>
                        <small>• Hostel Fee</small><br>
                        <small>• Sports Fee</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate code from name
    const nameInput = document.querySelector('input[name="name"]');
    const codeInput = document.querySelector('input[name="code"]');
    
    nameInput.addEventListener('input', function() {
        if (!codeInput.value) {
            const code = this.value
                .replace(/[^a-zA-Z0-9\s]/g, '')
                .replace(/\s+/g, '_')
                .toUpperCase();
            codeInput.value = code;
        }
    });
});
</script>
@endsection
