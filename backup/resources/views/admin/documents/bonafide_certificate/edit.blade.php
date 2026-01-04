@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Bonafide Certificate</h4>
        <a href="{{ route('admin.documents.bonafide-certificate.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <form method="POST" action="{{ route('admin.documents.bonafide-certificate.update', $bc) }}" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

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
                                <input class="form-control" name="student_name" value="{{ $bc->student_name }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Admission No</label>
                                <input class="form-control" name="admission_no" value="{{ $bc->admission_no }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">DOB</label>
                                <input type="date" class="form-control" name="date_of_birth" value="{{ optional($bc->date_of_birth)->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Class</label>
                                <input class="form-control" name="class_name" value="{{ $bc->class_name }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Section</label>
                                <input class="form-control" name="section_name" value="{{ $bc->section_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father's Name</label>
                                <input class="form-control" name="father_name" value="{{ $bc->father_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mother's Name</label>
                                <input class="form-control" name="mother_name" value="{{ $bc->mother_name }}">
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
                                <label class="form-label">BC Number</label>
                                <input class="form-control" name="bc_number" value="{{ $bc->bc_number }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Issue Date</label>
                                <input type="date" class="form-control" name="issue_date" value="{{ optional($bc->issue_date)->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purpose</label>
                                <input class="form-control" name="purpose" value="{{ $bc->purpose }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <input class="form-control" name="remarks" value="{{ $bc->remarks }}" placeholder="Optional note">
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
                            <option value="draft" @selected($bc->status==='draft')>Draft</option>
                            <option value="issued" @selected($bc->status==='issued')>Issued</option>
                            <option value="cancelled" @selected($bc->status==='cancelled')>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Certificate</button>
                    <a href="{{ route('admin.documents.bonafide-certificate.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
