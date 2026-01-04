@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center">
			<h5 class="mb-0">Progress Card</h5>
			<div>
				<a href="{{ route('admin.exams.progress-card.edit', $card->id) }}" class="btn btn-primary btn-sm">Edit</a>
				<a href="{{ route('admin.exams.progress-card.index') }}" class="btn btn-secondary btn-sm">Back</a>
			</div>
		</div>
		<div class="card-body">
			<div class="row g-3">
				<div class="col-md-4"><strong>Exam:</strong> {{ optional($card->exam)->title }}</div>
				<div class="col-md-4"><strong>Class:</strong> {{ $card->class_name }}</div>
				<div class="col-md-4"><strong>Section:</strong> {{ $card->section_name }}</div>
				<div class="col-md-4"><strong>Student:</strong> {{ $card->student_name }}</div>
				<div class="col-md-4"><strong>Admission No:</strong> {{ $card->admission_no }}</div>
				<div class="col-md-4"><strong>Roll No:</strong> {{ $card->roll_no }}</div>
				<div class="col-md-4"><strong>Overall %:</strong> {{ $card->overall_percentage }}</div>
				<div class="col-md-4"><strong>Overall Grade:</strong> {{ $card->overall_grade }}</div>
				<div class="col-md-4"><strong>Result:</strong> {{ ucfirst($card->overall_result_status) }}</div>
				<div class="col-md-12"><strong>Remarks:</strong> {{ $card->remarks }}</div>
			</div>
			<hr>
			<h6>Subject-wise Details</h6>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead><tr><th>Subject</th><th>Marks</th><th>Grade</th></tr></thead>
					<tbody>
						@foreach(($card->data ?? []) as $row)
							<tr>
								<td>{{ $row['subject'] ?? '-' }}</td>
								<td>{{ $row['marks'] ?? '-' }}</td>
								<td>{{ $row['grade'] ?? '-' }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection


