@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center">
			<h5 class="mb-0">Tabulation Details</h5>
			<div>
				<a href="{{ route('admin.exams.tabulation.edit', $tabulation->id) }}" class="btn btn-primary btn-sm">Edit</a>
				<a href="{{ route('admin.exams.tabulation.index') }}" class="btn btn-secondary btn-sm">Back</a>
			</div>
		</div>
		<div class="card-body">
			<div class="row g-3">
				<div class="col-md-4"><strong>Exam:</strong> {{ optional($tabulation->exam)->title }}</div>
				<div class="col-md-4"><strong>Class:</strong> {{ $tabulation->class_name }}</div>
				<div class="col-md-4"><strong>Section:</strong> {{ $tabulation->section_name }}</div>
				<div class="col-md-4"><strong>Student:</strong> {{ $tabulation->student_name }}</div>
				<div class="col-md-4"><strong>Admission No:</strong> {{ $tabulation->admission_no }}</div>
				<div class="col-md-4"><strong>Roll No:</strong> {{ $tabulation->roll_no }}</div>
				<div class="col-md-4"><strong>Total/Max:</strong> {{ $tabulation->total_marks }}/{{ $tabulation->max_total_marks }}</div>
				<div class="col-md-4"><strong>Percentage:</strong> {{ $tabulation->percentage }}</div>
				<div class="col-md-4"><strong>Grade:</strong> {{ $tabulation->grade }}</div>
				<div class="col-md-4"><strong>Result:</strong> {{ ucfirst($tabulation->result_status) }}</div>
				<div class="col-md-4"><strong>Rank:</strong> {{ $tabulation->rank }}</div>
				<div class="col-md-12"><strong>Remarks:</strong> {{ $tabulation->remarks }}</div>
			</div>
		</div>
	</div>
</div>
@endsection


