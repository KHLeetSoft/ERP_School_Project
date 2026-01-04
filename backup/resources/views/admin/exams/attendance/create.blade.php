@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light"><h5 class="mb-0">Create Exam Attendance</h5></div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.exams.attendance.store') }}">
				@csrf
				<div class="row g-3">
					<div class="col-md-4">
						<label class="form-label">Exam</label>
						<select name="exam_id" class="form-select" required>
							@foreach($exams as $e)
								<option value="{{ $e->id }}">{{ $e->title }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label">Class</label>
						<input name="class_name" class="form-control">
					</div>
					<div class="col-md-4">
						<label class="form-label">Section</label>
						<input name="section_name" class="form-control">
					</div>
					<div class="col-md-6">
						<label class="form-label">Student Name</label>
						<input name="student_name" class="form-control" required>
					</div>
					<div class="col-md-3">
						<label class="form-label">Admission No</label>
						<input name="admission_no" class="form-control">
					</div>
					<div class="col-md-3">
						<label class="form-label">Roll No</label>
						<input name="roll_no" class="form-control">
					</div>
					<div class="col-md-4">
						<label class="form-label">Exam Date</label>
						<input name="exam_date" class="form-control" type="date">
					</div>
					<div class="col-md-4">
						<label class="form-label">Subject</label>
						<input name="subject_name" class="form-control">
					</div>
					<div class="col-md-4">
						<label class="form-label">Attendance</label>
						<select name="attendance_status" class="form-select" required>
							<option value="present">Present</option>
							<option value="absent">Absent</option>
							<option value="late">Late</option>
						</select>
					</div>
					<div class="col-md-12">
						<label class="form-label">Remarks</label>
						<textarea name="remarks" class="form-control" rows="3"></textarea>
					</div>
					<div class="col-md-3">
						<label class="form-label">Status</label>
						<select name="status" class="form-select" required>
							<option value="draft">Draft</option>
							<option value="published">Published</option>
						</select>
					</div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary">Save</button>
					<a href="{{ route('admin.exams.attendance.index') }}" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


