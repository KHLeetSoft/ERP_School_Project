@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Create Expense Category</h6>
                    <a href="{{ route('admin.finance.expense-categories.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.finance.expense-categories.store') }}">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Category Name *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" placeholder="e.g., Utilities" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Category Code *</label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       value="{{ old('code') }}" placeholder="e.g., UTIL" required maxlength="20">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Unique code for the category (max 20 characters)</div>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" placeholder="Brief description of this expense category">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Color *</label>
                                <div class="input-group">
                                    <input type="color" name="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                           value="{{ old('color', '#3b82f6') }}" title="Choose category color">
                                    <input type="text" class="form-control" value="{{ old('color', '#3b82f6') }}" 
                                           id="colorText" placeholder="#3b82f6">
                                </div>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Icon *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-category"></i></span>
                                    <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                                           value="{{ old('icon', 'bx bx-category') }}" placeholder="bx bx-category">
                                </div>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Boxicons class name (e.g., bx bx-bulb)</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Budget Limit</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="budget_limit" class="form-control @error('budget_limit') is-invalid @enderror" 
                                           value="{{ old('budget_limit') }}" placeholder="50000" min="0" step="0.01">
                                </div>
                                @error('budget_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty for no budget limit</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Budget Period *</label>
                                <select name="budget_period" class="form-select @error('budget_period') is-invalid @enderror" required>
                                    <option value="monthly" {{ old('budget_period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ old('budget_period') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="yearly" {{ old('budget_period') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                                @error('budget_period')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Category
                                    </label>
                                </div>
                                <div class="form-text">Inactive categories won't appear in expense forms</div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Create Category
                            </button>
                            <a href="{{ route('admin.finance.expense-categories.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
    // Color picker synchronization
    $('input[name="color"]').on('input', function() {
        $('#colorText').val($(this).val());
    });
    
    $('#colorText').on('input', function() {
        $('input[name="color"]').val($(this).val());
    });
    
    // Auto-generate code from name
    $('input[name="name"]').on('input', function() {
        const name = $(this).val();
        if (name && !$('input[name="code"]').val()) {
            const code = name.replace(/[^A-Za-z]/g, '').substring(0, 20).toUpperCase();
            $('input[name="code"]').val(code);
        }
    });
});
</script>
@endsection 