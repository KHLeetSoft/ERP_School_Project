@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Marksheet</h4>
        <a href="{{ route('admin.documents.marksheet.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <form method="POST" action="{{ route('admin.documents.marksheet.update', $ms) }}" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-person-badge me-2"></i> Student & Exam</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Student Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="student_name" value="{{ $ms->student_name }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Admission No</label>
                                <input class="form-control" name="admission_no" value="{{ $ms->admission_no }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Roll No</label>
                                <input class="form-control" name="roll_no" value="{{ $ms->roll_no }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Class</label>
                                <input class="form-control" name="class_name" value="{{ $ms->class_name }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Section</label>
                                <input class="form-control" name="section_name" value="{{ $ms->section_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Exam Name</label>
                                <input class="form-control" name="exam_name" value="{{ $ms->exam_name }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Term</label>
                                <input class="form-control" name="term" value="{{ $ms->term }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Academic Year</label>
                                <input class="form-control" name="academic_year" value="{{ $ms->academic_year }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-calculator me-2"></i> Marks & Result</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">MS Number</label>
                                <input class="form-control" name="ms_number" value="{{ $ms->ms_number }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Issue Date</label>
                                <input type="date" class="form-control" name="issue_date" value="{{ optional($ms->issue_date)->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total Marks</label>
                                <input type="number" step="0.01" class="form-control" name="total_marks" value="{{ $ms->total_marks }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Obtained Marks</label>
                                <input type="number" step="0.01" class="form-control" name="obtained_marks" value="{{ $ms->obtained_marks }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Percentage</label>
                                <input type="number" step="0.01" class="form-control" name="percentage" value="{{ $ms->percentage }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Grade</label>
                                <input class="form-control" name="grade" value="{{ $ms->grade }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Result</label>
                                <select class="form-select" name="result_status">
                                    <option value="pass" @selected($ms->result_status==='pass')>Pass</option>
                                    <option value="fail" @selected($ms->result_status==='fail')>Fail</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <input class="form-control" name="remarks" value="{{ $ms->remarks }}" placeholder="Optional note">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Marks JSON (optional)</label>
                                <textarea class="form-control" name="marks_json" rows="4">{{ $ms->marks_json }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-gear me-2"></i> Settings</strong>
                    </div>
                    <div class="card-body">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="draft" @selected($ms->status==='draft')>Draft</option>
                            <option value="issued" @selected($ms->status==='issued')>Issued</option>
                            <option value="cancelled" @selected($ms->status==='cancelled')>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Marksheet</button>
                    <a href="{{ route('admin.documents.marksheet.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


