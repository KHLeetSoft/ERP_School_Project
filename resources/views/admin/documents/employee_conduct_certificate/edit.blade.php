@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Conduct Certificate (Employee)</h4>
        <a href="{{ route('admin.documents.employee-conduct-certificate.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <form method="POST" action="{{ route('admin.documents.employee-conduct-certificate.update', $ecc) }}" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <strong><i class="bi bi-person-badge me-2"></i> Employee Details</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="employee_name" value="{{ $ecc->employee_name }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Employee ID</label>
                                <input class="form-control" name="employee_id" value="{{ $ecc->employee_id }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Designation</label>
                                <input class="form-control" name="designation" value="{{ $ecc->designation }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Department</label>
                                <input class="form-control" name="department" value="{{ $ecc->department }}">
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
                                <label class="form-label">ECC Number</label>
                                <input class="form-control" name="ecc_number" value="{{ $ecc->ecc_number }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Issue Date</label>
                                <input type="date" class="form-control" name="issue_date" value="{{ optional($ecc->issue_date)->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Conduct</label>
                                <input class="form-control" name="conduct" value="{{ $ecc->conduct }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <input class="form-control" name="remarks" value="{{ $ecc->remarks }}" placeholder="Optional note">
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
                            <option value="draft" @selected($ecc->status==='draft')>Draft</option>
                            <option value="issued" @selected($ecc->status==='issued')>Issued</option>
                            <option value="cancelled" @selected($ecc->status==='cancelled')>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Certificate</button>
                    <a href="{{ route('admin.documents.employee-conduct-certificate.index') }}" class="btn btn-light border"><i class="bi bi-x-circle me-1"></i> Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection












