@extends('admin.layout.app')

@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="mb-1">Experience Certificate</h4>
                <div class="text-muted">EC No: {{ $ec->ec_number ?? '-' }}</div>
            </div>
            <table class="table table-borderless">
                <tr><th style="width: 30%">Employee Name</th><td>{{ $ec->employee_name }}</td></tr>
                <tr><th>Employee ID</th><td>{{ $ec->employee_id ?? '-' }}</td></tr>
                <tr><th>Designation</th><td>{{ $ec->designation ?? '-' }}</td></tr>
                <tr><th>Department</th><td>{{ $ec->department ?? '-' }}</td></tr>
                <tr><th>Joining Date</th><td>{{ optional($ec->joining_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Relieving Date</th><td>{{ optional($ec->relieving_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Total Experience</th><td>{{ $ec->total_experience ?? '-' }}</td></tr>
                <tr><th>Issue Date</th><td>{{ optional($ec->issue_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Remarks</th><td>{{ $ec->remarks ?? '-' }}</td></tr>
            </table>
            <div class="mt-5 d-flex justify-content-between">
                <div>
                    <div class="border-top pt-2">HR Manager</div>
                </div>
                <div>
                    <div class="border-top pt-2">Principal</div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 text-center">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
        <a href="{{ route('admin.documents.experience-certificate.index') }}" class="btn btn-secondary">Back</a>
    </div>
 </div>
@endsection


