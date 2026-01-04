@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light"><h5 class="mb-0">Edit Progress Card</h5></div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.exams.progress-card.update', $card->id) }}">
				@csrf
				@method('PUT')
				<div class="row g-3">
					<div class="col-md-4">
						<label class="form-label">Exam</label>
						<select name="exam_id" class="form-select" required>
							@foreach($exams as $e)
								<option value="{{ $e->id }}" @selected($card->exam_id==$e->id)>{{ $e->title }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4"><label class="form-label">Class</label><input name="class_name" class="form-control" value="{{ $card->class_name }}"></div>
					<div class="col-md-4"><label class="form-label">Section</label><input name="section_name" class="form-control" value="{{ $card->section_name }}"></div>
					<div class="col-md-6"><label class="form-label">Student Name</label><input name="student_name" class="form-control" value="{{ $card->student_name }}" required></div>
					<div class="col-md-3"><label class="form-label">Admission No</label><input name="admission_no" class="form-control" value="{{ $card->admission_no }}"></div>
					<div class="col-md-3"><label class="form-label">Roll No</label><input name="roll_no" class="form-control" value="{{ $card->roll_no }}"></div>
					<div class="col-md-3"><label class="form-label">Overall %</label><input name="overall_percentage" type="number" step="0.01" class="form-control" value="{{ $card->overall_percentage }}"></div>
					<div class="col-md-3"><label class="form-label">Overall Grade</label><input name="overall_grade" class="form-control" value="{{ $card->overall_grade }}"></div>
					<div class="col-md-3"><label class="form-label">Result</label>
						<select name="overall_result_status" class="form-select">
							<option value="pass" @selected($card->overall_result_status==='pass')>Pass</option>
							<option value="fail" @selected($card->overall_result_status==='fail')>Fail</option>
						</select>
					</div>
					<div class="col-md-12"><label class="form-label">Remarks</label><textarea name="remarks" rows="3" class="form-control">{{ $card->remarks }}</textarea></div>
					<div class="col-md-3"><label class="form-label">Status</label>
						<select name="status" class="form-select" required>
							<option value="draft" @selected($card->status==='draft')>Draft</option>
							<option value="published" @selected($card->status==='published')>Published</option>
						</select>
					</div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary">Update</button>
					<a href="{{ route('admin.exams.progress-card.index') }}" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


