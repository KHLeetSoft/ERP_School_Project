@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center">
			<h5 class="mb-0">Exam Attendance Details</h5>
			<div>
				<a href="{{ route('admin.exams.attendance.edit', $attendance->id) }}" class="btn btn-primary btn-sm">Edit</a>
				<a href="{{ route('admin.exams.attendance.index') }}" class="btn btn-secondary btn-sm">Back</a>
			</div>
		</div>
		<div class="card-body">
			<div class="row g-3">
				<div class="col-md-4"><strong>Exam:</strong> {{ optional($attendance->exam)->title }}</div>
				<div class="col-md-4"><strong>Class:</strong> {{ $attendance->class_name }}</div>
				<div class="col-md-4"><strong>Section:</strong> {{ $attendance->section_name }}</div>
				<div class="col-md-4"><strong>Student:</strong> {{ $attendance->student_name }}</div>
				<div class="col-md-4"><strong>Admission No:</strong> {{ $attendance->admission_no }}</div>
				<div class="col-md-4"><strong>Roll No:</strong> {{ $attendance->roll_no }}</div>
				<div class="col-md-4"><strong>Subject:</strong> {{ $attendance->subject_name }}</div>
				<div class="col-md-4"><strong>Date:</strong> {{ optional($attendance->exam_date)->format('Y-m-d') }}</div>
				<div class="col-md-4"><strong>Status:</strong> {{ ucfirst($attendance->attendance_status) }}</div>
				<div class="col-md-12"><strong>Remarks:</strong> {{ $attendance->remarks }}</div>
			</div>
		</div>
	</div>
</div>
@endsection


