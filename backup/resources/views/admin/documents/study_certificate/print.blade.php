@extends('admin.layout.app')

@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="mb-1">Study Certificate</h4>
                <div class="text-muted">SC No: {{ $sc->sc_number ?? '-' }}</div>
            </div>
            <table class="table table-borderless">
                <tr><th style="width: 30%">Student Name</th><td>{{ $sc->student_name }}</td></tr>
                <tr><th>Admission No</th><td>{{ $sc->admission_no ?? '-' }}</td></tr>
                <tr><th>Roll No</th><td>{{ $sc->roll_no ?? '-' }}</td></tr>
                <tr><th>Class/Section</th><td>{{ $sc->class_name }} {{ $sc->section_name }}</td></tr>
                <tr><th>DOB</th><td>{{ optional($sc->date_of_birth)->format('d-m-Y') }}</td></tr>
                <tr><th>Academic Year</th><td>{{ $sc->academic_year ?? '-' }}</td></tr>
                <tr><th>Issue Date</th><td>{{ optional($sc->issue_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Remarks</th><td>{{ $sc->remarks ?? '-' }}</td></tr>
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
        <a href="{{ route('admin.documents.study-certificate.index') }}" class="btn btn-secondary">Back</a>
    </div>
 </div>
@endsection


