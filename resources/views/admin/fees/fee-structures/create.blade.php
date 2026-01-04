@extends('admin.layout.app')

@section('title', 'Create Fee Structure')
@section('page-title', 'Create Fee Structure')
@section('page-description', 'Add a new fee structure for classes or students')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>Create New Fee Structure
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

                <form method="POST" action="{{ route('admin.fee-structures.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Structure Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required 
                                   placeholder="e.g., Grade 1 Monthly Tuition Fee">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fee Head *</label>
                            <select class="form-select @error('fee_head_id') is-invalid @enderror" 
                                    name="fee_head_id" required>
                                <option value="">Select Fee Head</option>
                                @foreach($feeHeads as $feeHead)
                                    <option value="{{ $feeHead->id }}" @selected(old('fee_head_id') == $feeHead->id)>
                                        {{ $feeHead->name }} ({{ $feeHead->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('fee_head_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">School Class</label>
                            <select class="form-select @error('school_class_id') is-invalid @enderror" 
                                    name="school_class_id" id="school_class_id">
                                <option value="">Select Class (Optional)</option>
                                @foreach($schoolClasses as $class)
                                    <option value="{{ $class->id }}" @selected(old('school_class_id') == $class->id)>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('school_class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section</label>
                            <select class="form-select @error('section_id') is-invalid @enderror" 
                                    name="section_id" id="section_id">
                                <option value="">Select Section (Optional)</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" @selected(old('section_id') == $section->id)>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount *</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       name="amount" value="{{ old('amount') }}" required 
                                       step="0.01" min="0" placeholder="0.00">
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Frequency *</label>
                            <select class="form-select @error('frequency') is-invalid @enderror" 
                                    name="frequency" required>
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
                            <label class="form-label">Effective From *</label>
                            <input type="date" class="form-control @error('effective_from') is-invalid @enderror" 
                                   name="effective_from" value="{{ old('effective_from', date('Y-m-d')) }}" required>
                            @error('effective_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Effective To</label>
                            <input type="date" class="form-control @error('effective_to') is-invalid @enderror" 
                                   name="effective_to" value="{{ old('effective_to') }}">
                            <small class="form-text text-muted">Leave empty for ongoing</small>
                            @error('effective_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="3" 
                                  placeholder="Optional description about this fee structure">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                   id="is_active" @checked(old('is_active', true))>
                            <label class="form-check-label" for="is_active">
                                Active (Enable this fee structure)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.fee-structures.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Fee Structure</button>
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
                    <li><strong>Class-specific:</strong> Apply to all students in a class</li>
                    <li><strong>Section-specific:</strong> Apply to students in a specific section</li>
                    <li><strong>Student-specific:</strong> Apply to individual students</li>
                    <li><strong>General:</strong> Apply to all students (leave class/section empty)</li>
                    <li><strong>Effective Period:</strong> Set when this fee structure is valid</li>
                </ul>
            </div>
        </div>
        
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Fee Structure Types</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Monthly:</strong><br>
                    <small class="text-muted">Charged every month (e.g., Tuition Fee)</small>
                </div>
                <div class="mb-3">
                    <strong>Quarterly:</strong><br>
                    <small class="text-muted">Charged every 3 months (e.g., Exam Fee)</small>
                </div>
                <div class="mb-3">
                    <strong>Yearly:</strong><br>
                    <small class="text-muted">Charged once per year (e.g., Annual Fee)</small>
                </div>
                <div class="mb-0">
                    <strong>One Time:</strong><br>
                    <small class="text-muted">Charged only once (e.g., Admission Fee)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate name from selections
    const classSelect = document.getElementById('school_class_id');
    const sectionSelect = document.getElementById('section_id');
    const feeHeadSelect = document.querySelector('select[name="fee_head_id"]');
    const frequencySelect = document.querySelector('select[name="frequency"]');
    const nameInput = document.querySelector('input[name="name"]');
    
    function generateName() {
        if (feeHeadSelect.value && frequencySelect.value) {
            const feeHead = feeHeadSelect.selectedOptions[0].text.split(' (')[0];
            const frequency = frequencySelect.value;
            const classText = classSelect.value ? classSelect.selectedOptions[0].text : '';
            const sectionText = sectionSelect.value ? sectionSelect.selectedOptions[0].text : '';
            
            let name = '';
            if (classText) {
                name += classText + ' ';
            }
            if (sectionText) {
                name += sectionText + ' ';
            }
            name += feeHead + ' (' + frequency.charAt(0).toUpperCase() + frequency.slice(1) + ')';
            
            nameInput.value = name;
        }
    }
    
    classSelect.addEventListener('change', generateName);
    sectionSelect.addEventListener('change', generateName);
    feeHeadSelect.addEventListener('change', generateName);
    frequencySelect.addEventListener('change', generateName);
});
</script>
@endsection
