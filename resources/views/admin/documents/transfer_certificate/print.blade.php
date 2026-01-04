@extends('admin.layout.app')

@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="mb-1">Transfer Certificate</h4>
                <div class="text-muted">TC No: {{ $tc->tc_number ?? '-' }}</div>
            </div>
            <table class="table table-borderless">
                <tr><th style="width: 30%">Student Name</th><td>{{ $tc->student_name }}</td></tr>
                <tr><th>Admission No</th><td>{{ $tc->admission_no ?? '-' }}</td></tr>
                <tr><th>Date of Birth</th><td>{{ optional($tc->date_of_birth)->format('d-m-Y') }}</td></tr>
                <tr><th>Father's Name</th><td>{{ $tc->father_name ?? '-' }}</td></tr>
                <tr><th>Mother's Name</th><td>{{ $tc->mother_name ?? '-' }}</td></tr>
                <tr><th>Class/Section</th><td>{{ $tc->class_name }} {{ $tc->section_name }}</td></tr>
                <tr><th>Admission Date</th><td>{{ optional($tc->admission_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Leaving Date</th><td>{{ optional($tc->leaving_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Conduct</th><td>{{ $tc->conduct ?? '-' }}</td></tr>
                <tr><th>Reason For Leaving</th><td>{{ $tc->reason_for_leaving ?? '-' }}</td></tr>
                <tr><th>Issue Date</th><td>{{ optional($tc->issue_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Remarks</th><td>{{ $tc->remarks ?? '-' }}</td></tr>
            </table>
            <div class="mt-5 d-flex justify-content-between">
                <div>
                    <div class="border-top pt-2">Class Teacher</div>
                </div>
                <div>
                    <div class="border-top pt-2">Principal</div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 text-center">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
        <a href="{{ route('admin.documents.transfer-certificate.index') }}" class="btn btn-secondary">Back</a>
    </div>
 </div>
@endsection


