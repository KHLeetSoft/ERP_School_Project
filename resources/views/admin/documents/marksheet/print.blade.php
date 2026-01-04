@extends('admin.layout.app')

@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="mb-1">Marksheet</h4>
                <div class="text-muted">MS No: {{ $ms->ms_number ?? '-' }}</div>
            </div>
            <table class="table table-borderless">
                <tr><th style="width: 30%">Student Name</th><td>{{ $ms->student_name }}</td></tr>
                <tr><th>Admission No</th><td>{{ $ms->admission_no ?? '-' }}</td></tr>
                <tr><th>Roll No</th><td>{{ $ms->roll_no ?? '-' }}</td></tr>
                <tr><th>Class/Section</th><td>{{ $ms->class_name }} {{ $ms->section_name }}</td></tr>
                <tr><th>Exam</th><td>{{ $ms->exam_name ?? '-' }}</td></tr>
                <tr><th>Issue Date</th><td>{{ optional($ms->issue_date)->format('d-m-Y') }}</td></tr>
                <tr><th>Total Marks</th><td>{{ $ms->total_marks ?? '-' }}</td></tr>
                <tr><th>Obtained Marks</th><td>{{ $ms->obtained_marks ?? '-' }}</td></tr>
                <tr><th>Percentage</th><td>{{ $ms->percentage ?? '-' }}%</td></tr>
                <tr><th>Grade</th><td>{{ $ms->grade ?? '-' }}</td></tr>
                <tr><th>Result</th><td>{{ ucfirst($ms->result_status ?? '-') }}</td></tr>
                <tr><th>Remarks</th><td>{{ $ms->remarks ?? '-' }}</td></tr>
            </table>
            @if($ms->marks_json)
            <div class="mt-4">
                <h6>Subject-wise Marks</h6>
                <table class="table table-sm table-bordered">
                    <thead><tr><th>Subject</th><th class="text-end">Marks</th></tr></thead>
                    <tbody>
                        @foreach(json_decode($ms->marks_json, true) as $row)
                            <tr><td>{{ $row['subject'] ?? '-' }}</td><td class="text-end">{{ $row['marks'] ?? '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
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
        <a href="{{ route('admin.documents.marksheet.index') }}" class="btn btn-secondary">Back</a>
    </div>
 </div>
@endsection


