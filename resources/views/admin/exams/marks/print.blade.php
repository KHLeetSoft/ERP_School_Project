@extends('admin.layout.app')

@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-3">
                <h5 class="mb-1">Mark Statement</h5>
                <div class="text-muted">Exam: {{ optional($mark->exam)->title }}</div>
            </div>
            <table class="table table-borderless">
                <tr><th style="width: 30%">Student</th><td>{{ $mark->student_name }}</td></tr>
                <tr><th>Admission No</th><td>{{ $mark->admission_no ?? '-' }}</td></tr>
                <tr><th>Class/Section</th><td>{{ $mark->class_name }} {{ $mark->section_name }}</td></tr>
                <tr><th>Subject</th><td>{{ $mark->subject_name }}</td></tr>
                <tr><th>Marks</th><td>{{ $mark->obtained_marks }}/{{ $mark->max_marks }}</td></tr>
                <tr><th>Percentage</th><td>{{ $mark->percentage }}%</td></tr>
                <tr><th>Grade</th><td>{{ $mark->grade ?? '-' }}</td></tr>
                <tr><th>Result</th><td class="text-capitalize">{{ $mark->result_status ?? '-' }}</td></tr>
                <tr><th>Status</th><td class="text-capitalize">{{ $mark->status }}</td></tr>
                <tr><th>Remarks</th><td>{{ $mark->remarks ?? '-' }}</td></tr>
            </table>
        </div>
    </div>
    <div class="mt-3 text-center">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
        <a href="{{ route('admin.exams.marks.index') }}" class="btn btn-secondary">Back</a>
    </div>
 </div>
@endsection


