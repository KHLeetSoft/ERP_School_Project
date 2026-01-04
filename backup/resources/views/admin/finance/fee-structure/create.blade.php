@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Create New Fee Structure</h6>
            <a href="{{ route('admin.finance.fee-structure.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-left-arrow-alt"></i> Back to List
            </a>
        </div>
        <form method="POST" action="{{ route('admin.finance.fee-structure.store') }}">
            @csrf
            <div class="card-body">
                <div class="row g-3">
                    <!-- Class Selection -->
                    <div class="col-md-6">
                        <label class="form-label">Class *</label>
                        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Academic Year -->
                    <div class="col-md-6">
                        <label class="form-label">Academic Year *</label>
                        <select name="academic_year" class="form-select @error('academic_year') is-invalid @enderror" required>
                            <option value="">Select Academic Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fee Type -->
                    <div class="col-md-6">
                        <label class="form-label">Fee Type *</label>
                        <select name="fee_type" class="form-select @error('fee_type') is-invalid @enderror" required>
                            <option value="">Select Fee Type</option>
                            @foreach($feeTypes as $type)
                                <option value="{{ $type }}" {{ old('fee_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('fee_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div class="col-md-6">
                        <label class="form-label">Amount (₹) *</label>
                        <input type="number" name="amount" step="0.01" min="0" 
                               class="form-control @error('amount') is-invalid @enderror" 
                               value="{{ old('amount') }}" placeholder="0.00" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Frequency -->
                    <div class="col-md-6">
                        <label class="form-label">Frequency *</label>
                        <select name="frequency" class="form-select @error('frequency') is-invalid @enderror" required>
                            <option value="">Select Frequency</option>
                            @foreach($frequencies as $key => $label)
                                <option value="{{ $key }}" {{ old('frequency') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('frequency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-6">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" 
                               class="form-control @error('due_date') is-invalid @enderror" 
                               value="{{ old('due_date') }}">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Late Fee -->
                    <div class="col-md-6">
                        <label class="form-label">Late Fee (₹)</label>
                        <input type="number" name="late_fee" step="0.01" min="0" 
                               class="form-control @error('late_fee') is-invalid @enderror" 
                               value="{{ old('late_fee', 0) }}" placeholder="0.00">
                        @error('late_fee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Discount Applicable -->
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="discount_applicable" class="form-check-input" 
                                   id="discount_applicable" value="1" {{ old('discount_applicable') ? 'checked' : '' }}>
                            <label class="form-check-label" for="discount_applicable">
                                Discount Applicable
                            </label>
                        </div>
                    </div>

                    <!-- Max Discount -->
                    <div class="col-md-6">
                        <label class="form-label">Maximum Discount (₹)</label>
                        <input type="number" name="max_discount" step="0.01" min="0" 
                               class="form-control @error('max_discount') is-invalid @enderror" 
                               value="{{ old('max_discount', 0) }}" placeholder="0.00">
                        @error('max_discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" class="form-check-input" 
                                   id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Enter description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Create Fee Structure
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
    // Show/hide max discount field based on discount applicable checkbox
    $('#discount_applicable').on('change', function() {
        if ($(this).is(':checked')) {
            $('input[name="max_discount"]').prop('disabled', false);
        } else {
            $('input[name="max_discount"]').prop('disabled', true).val(0);
        }
    });

    // Trigger change event on page load
    $('#discount_applicable').trigger('change');
});
</script>
@endsection
