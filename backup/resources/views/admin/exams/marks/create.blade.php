@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-plus-square me-2 text-primary"></i> Add Mark</h4>
        <a href="{{ route('admin.exams.marks.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
    </div>

    <form method="POST" action="{{ route('admin.exams.marks.store') }}" class="needs-validation" novalidate>
        @csrf
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light"><strong>Details</strong></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Exam <span class="text-danger">*</span></label>
                                <select class="form-select" name="exam_id" required>
                                    <option value="">-- Select --</option>
                                    @foreach($exams as $e)
                                        <option value="{{ $e->id }}">{{ $e->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3"><label class="form-label">Class</label><input class="form-control" name="class_name"></div>
                            <div class="col-md-3"><label class="form-label">Section</label><input class="form-control" name="section_name"></div>
                            <div class="col-md-6"><label class="form-label">Student Name <span class="text-danger">*</span></label><input class="form-control" name="student_name" required></div>
                            <div class="col-md-3"><label class="form-label">Admission No</label><input class="form-control" name="admission_no"></div>
                            <div class="col-md-3"><label class="form-label">Roll No</label><input class="form-control" name="roll_no"></div>
                            <div class="col-md-6"><label class="form-label">Subject <span class="text-danger">*</span></label><input class="form-control" name="subject_name" required></div>
                            <div class="col-md-3"><label class="form-label">Max Marks</label><input type="number" step="0.01" class="form-control" name="max_marks" value="100"></div>
                            <div class="col-md-3"><label class="form-label">Obtained</label><input type="number" step="0.01" class="form-control" name="obtained_marks"></div>
                            <div class="col-md-3"><label class="form-label">Percentage</label><input type="number" step="0.01" class="form-control" name="percentage"></div>
                            <div class="col-md-3"><label class="form-label">Grade</label><input class="form-control" name="grade"></div>
                            <div class="col-md-3"><label class="form-label">Result</label>
                                <select class="form-select" name="result_status">
                                    <option value="pass">Pass</option>
                                    <option value="fail">Fail</option>
                                </select>
                            </div>
                            <div class="col-12"><label class="form-label">Remarks</label><textarea class="form-control" name="remarks" rows="2"></textarea></div>
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
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                    <a href="{{ route('admin.exams.marks.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


