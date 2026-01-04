@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-award me-2 text-primary"></i> Create Study Certificate</h4>
        <a href="{{ route('admin.documents.study-certificate.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <form method="POST" action="{{ route('admin.documents.study-certificate.store') }}" class="needs-validation" novalidate>
        @csrf

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-person-badge me-2"></i> Student Details</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Student Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="student_name" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Admission No</label>
                                <input class="form-control" name="admission_no">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Roll No</label>
                                <input class="form-control" name="roll_no">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Class</label>
                                <input class="form-control" name="class_name">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Section</label>
                                <input class="form-control" name="section_name">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">DOB</label>
                                <input type="date" class="form-control" name="date_of_birth">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father's Name</label>
                                <input class="form-control" name="father_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mother's Name</label>
                                <input class="form-control" name="mother_name">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-file-text me-2"></i> Certificate Details</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">SC Number</label>
                                <input class="form-control" name="sc_number" placeholder="Auto/Manual">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Issue Date</label>
                                <input type="date" class="form-control" name="issue_date">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Academic Year</label>
                                <input class="form-control" name="academic_year" placeholder="2024-2025">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <input class="form-control" name="remarks" placeholder="Optional note">
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
                            <option value="draft">Draft</option>
                            <option value="issued">Issued</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Certificate</button>
                    <a href="{{ route('admin.documents.study-certificate.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


