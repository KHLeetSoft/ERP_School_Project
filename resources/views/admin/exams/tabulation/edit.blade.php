@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light"><h5 class="mb-0">Edit Tabulation</h5></div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.exams.tabulation.update', $tabulation->id) }}">
				@csrf
				@method('PUT')
				<div class="row g-3">
					<div class="col-md-4">
						<label class="form-label">Exam</label>
						<select name="exam_id" class="form-select" required>
							@foreach($exams as $e)
								<option value="{{ $e->id }}" @selected($tabulation->exam_id==$e->id)>{{ $e->title }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label">Class</label>
						<input name="class_name" class="form-control" value="{{ $tabulation->class_name }}">
					</div>
					<div class="col-md-4">
						<label class="form-label">Section</label>
						<input name="section_name" class="form-control" value="{{ $tabulation->section_name }}">
					</div>
					<div class="col-md-6">
						<label class="form-label">Student Name</label>
						<input name="student_name" class="form-control" value="{{ $tabulation->student_name }}" required>
					</div>
					<div class="col-md-3">
						<label class="form-label">Admission No</label>
						<input name="admission_no" class="form-control" value="{{ $tabulation->admission_no }}">
					</div>
					<div class="col-md-3">
						<label class="form-label">Roll No</label>
						<input name="roll_no" class="form-control" value="{{ $tabulation->roll_no }}">
					</div>
					<div class="col-md-3">
						<label class="form-label">Total Marks</label>
						<input name="total_marks" class="form-control" type="number" step="0.01" value="{{ $tabulation->total_marks }}">
					</div>
					<div class="col-md-3">
						<label class="form-label">Max Total Marks</label>
						<input name="max_total_marks" class="form-control" type="number" step="0.01" value="{{ $tabulation->max_total_marks }}">
					</div>
					<div class="col-md-3">
						<label class="form-label">Percentage</label>
						<input name="percentage" class="form-control" type="number" step="0.01" value="{{ $tabulation->percentage }}">
					</div>
					<div class="col-md-3">
						<label class="form-label">Grade</label>
						<input name="grade" class="form-control" value="{{ $tabulation->grade }}">
					</div>
					<div class="col-md-3">
						<label class="form-label">Rank</label>
						<input name="rank" class="form-control" type="number" value="{{ $tabulation->rank }}">
					</div>
					<div class="col-md-12">
						<label class="form-label">Remarks</label>
						<textarea name="remarks" class="form-control" rows="3">{{ $tabulation->remarks }}</textarea>
					</div>
					<div class="col-md-3">
						<label class="form-label">Status</label>
						<select name="status" class="form-select" required>
							<option value="draft" @selected($tabulation->status==='draft')>Draft</option>
							<option value="published" @selected($tabulation->status==='published')>Published</option>
						</select>
					</div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary">Update</button>
					<a href="{{ route('admin.exams.tabulation.index') }}" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


