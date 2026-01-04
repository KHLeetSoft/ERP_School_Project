@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Grade</h4>
        <a href="{{ route('admin.exams.grades.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
    </div>

    <form method="POST" action="{{ route('admin.exams.grades.update', $grade) }}" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Details</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-2"><label class="form-label">Grade <span class="text-danger">*</span></label><input class="form-control" name="grade" value="{{ $grade->grade }}" required></div>
                            <div class="col-md-2"><label class="form-label">Point</label><input type="number" step="0.01" class="form-control" name="grade_point" value="{{ $grade->grade_point }}"></div>
                            <div class="col-md-4"><label class="form-label">Min %</label><input type="number" step="0.01" class="form-control" name="min_percentage" value="{{ $grade->min_percentage }}"></div>
                            <div class="col-md-4"><label class="form-label">Max %</label><input type="number" step="0.01" class="form-control" name="max_percentage" value="{{ $grade->max_percentage }}"></div>
                            <div class="col-md-6"><label class="form-label">Remark</label><input class="form-control" name="remark" value="{{ $grade->remark }}"></div>
                            <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2">{{ $grade->description }}</textarea></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Settings</strong></div>
                    <div class="card-body">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="active" @selected($grade->status==='active')>Active</option>
                            <option value="inactive" @selected($grade->status==='inactive')>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Grade</button>
                    <a href="{{ route('admin.exams.grades.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


