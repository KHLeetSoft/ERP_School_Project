@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Upload Bulk Attendance</h6>
                    <a href="{{ route('admin.attendance.bulk.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.attendance.bulk.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Batch Date *</label>
                            <input type="date" name="batch_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File (xlsx/csv) *</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
                            <div class="form-text">Columns: staff_id, attendance_date (YYYY-MM-DD), status, remarks</div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-upload me-1"></i> Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


