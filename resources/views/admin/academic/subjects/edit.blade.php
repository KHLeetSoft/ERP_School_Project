@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-edit text-primary me-2"></i>Edit Subject
        </h4>
        <a href="{{ route('admin.academic.subjects.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <!-- Error Alert -->
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <!-- Form Card -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Subject Information</h5>
        </div>
        <div class="card-body p-4">

            <form method="POST" action="{{ route('admin.academic.subjects.update', $subject) }}">
                @method('PUT')
                @csrf

                <!-- Two Column Layout -->
                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $subject->name) }}" class="form-control" placeholder="Enter subject name" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Subject Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $subject->code) }}" class="form-control" placeholder="Enter subject code" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Type</label>
                        <select name="type" class="form-select">
                            <option value="core" {{ old('type', $subject->type) == 'core' ? 'selected' : '' }}>Core</option>
                            <option value="elective" {{ old('type', $subject->type) == 'elective' ? 'selected' : '' }}>Elective</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Credit Hours</label>
                        <input type="number" name="credit_hours" value="{{ old('credit_hours', $subject->credit_hours) }}" class="form-control" placeholder="Enter credit hours">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', $subject->status) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('status', $subject->status) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Enter subject description">{{ old('description', $subject->description) }}</textarea>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Update Subject
                    </button>
                    <a href="{{ route('admin.academic.subjects.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
