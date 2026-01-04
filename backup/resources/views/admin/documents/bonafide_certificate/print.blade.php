@extends('admin.layout.app')

@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="mb-1">Bonafide Certificate</h4>
                <div class="text-muted">BC No: {{ $bc->bc_number ?? '-' }}</div>
            </div>
            <table class="table table-borderless">
                <tr><th style="width: 30%">Student Name</th><td>{{ $bc->student_name }}</td></tr>
                <tr><th>Admission No</th><td>{{ $bc->admission_no ?? '-' }}</td></tr>
                <tr><th>Date of Birth</th><td>{{ optional($bc->date_of_birth)->format('d-m-Y') }}</td></tr>
                <tr><th>Father's Name</th><td>{{ $bc->father_name ?? '-' }}</td></tr>
                <tr><th>Mother's Name</th><td>{{ $bc->mother_name ?? '-' }}</td></tr>
                <tr><th>Class/Section</th><td>{{ $bc->class_name }} {{ $bc->section_name }}</td></tr>
                <tr><th>Purpose</th><td>{{ $bc->purpose ?? '-' }}</td></tr>
                <tr><th>Issue Date</th><td>{{ optional($bc->issue_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Remarks</th><td>{{ $bc->remarks ?? '-' }}</td></tr>
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
        <a href="{{ route('admin.documents.bonafide-certificate.index') }}" class="btn btn-secondary">Back</a>
    </div>
 </div>
@endsection


