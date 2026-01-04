@extends('admin.layout.app')

@section('content')
<div class="container">

    <!-- Page Header with Gradient -->
    <div class="p-4 mb-4 rounded shadow-sm" 
         style="background: linear-gradient(90deg, #007bff, #00c6ff); color: #fff;">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">
                <i class="bi bi-plus-circle me-2"></i> Create New Report
            </h4>
            <a href="{{ route('admin.academic.reports.index') }}" class="btn btn-light btn-sm shadow-sm">
                <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.academic.reports.store') }}">
                @csrf
                <div class="row g-4">

                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" class="form-control shadow-sm rounded-3" name="title" placeholder="Enter report title" required>
                    </div>

                    <!-- Date -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" class="form-control shadow-sm rounded-3" name="report_date" required>
                    </div>

                    <!-- Type -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Type</label>
                        <input type="text" class="form-control shadow-sm rounded-3" name="type" placeholder="E.g., Exam, Activity, General">
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select shadow-sm rounded-3">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control shadow-sm rounded-3" rows="4" name="description" placeholder="Write details about the report..."></textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end mt-4 gap-3">
                    <a href="{{ route('admin.academic.reports.index') }}" 
                       class="btn btn-outline-secondary px-4 rounded-pill shadow-sm">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button class="btn btn-primary px-4 rounded-pill shadow-sm" type="submit">
                        <i class="bi bi-save"></i> Save Report
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
