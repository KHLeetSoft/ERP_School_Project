@extends('admin.layout.app')

@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="mb-1">Conduct Certificate (Employee)</h4>
                <div class="text-muted">ECC No: {{ $ecc->ecc_number ?? '-' }}</div>
            </div>
            <table class="table table-borderless">
                <tr><th style="width: 30%">Employee Name</th><td>{{ $ecc->employee_name }}</td></tr>
                <tr><th>Employee ID</th><td>{{ $ecc->employee_id ?? '-' }}</td></tr>
                <tr><th>Designation</th><td>{{ $ecc->designation ?? '-' }}</td></tr>
                <tr><th>Department</th><td>{{ $ecc->department ?? '-' }}</td></tr>
                <tr><th>Conduct</th><td>{{ $ecc->conduct ?? '-' }}</td></tr>
                <tr><th>Issue Date</th><td>{{ optional($ecc->issue_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Remarks</th><td>{{ $ecc->remarks ?? '-' }}</td></tr>
            </table>
            <div class="mt-5 d-flex justify-content-between">
                <div><div class="border-top pt-2">HR Manager</div></div>
                <div><div class="border-top pt-2">Principal</div></div>
            </div>
        </div>
    </div>
    <div class="mt-3 text-center">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
        <a href="{{ route('admin.documents.employee-conduct-certificate.index') }}" class="btn btn-secondary">Back</a>
    </div>
 </div>
@endsection












