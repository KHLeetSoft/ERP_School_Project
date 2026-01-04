@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm mb-4 border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
			<h4 class="mb-0"><i class="bx bx-edit me-2 text-primary"></i> Edit Question</h4>
			<a href="{{ route('admin.online-exam.questions.index') }}" class="btn btn-secondary btn-sm" title="Back" data-bs-toggle="tooltip"><i class="bx bx-arrow-back"></i></a>
		</div>
	</div>

	@if($errors->any())
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<ul class="mb-0">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	@endif

	<form method="POST" action="{{ route('admin.online-exam.questions.update', $question) }}" id="questionForm">
		@csrf
		@method('PUT')
		<div class="row">
			<div class="col-lg-8">
				<div class="card shadow-sm border-0 mb-4">
					<div class="card-header bg-light"><h5 class="mb-0"><i class="bx bx-info-circle me-2"></i> Question Details</h5></div>
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label">Category <span class="text-danger">*</span></label>
							<select name="question_category_id" class="form-select" required>
								<option value="">Select Category</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}" {{ old('question_category_id', $question->question_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="form-label">Type <span class="text-danger">*</span></label>
								<select name="type" id="type" class="form-select" required>
									@foreach(['mcq' => 'MCQ', 'true_false' => 'True/False', 'short_answer' => 'Short Answer', 'essay' => 'Essay'] as $key=>$label)
										<option value="{{ $key }}" {{ old('type',$question->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Difficulty</label>
								<select name="difficulty" class="form-select">
									@foreach(['easy','medium','hard'] as $d)
										<option value="{{ $d }}" {{ old('difficulty',$question->difficulty ?? 'medium') == $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="mb-3">
							<label class="form-label">Question Text <span class="text-danger">*</span></label>
							<textarea name="question_text" class="form-control" rows="4" required>{{ old('question_text', $question->question_text) }}</textarea>
						</div>

						<div id="mcqOptions" style="display: none;">
							<label class="form-label">Options</label>
							<div class="row g-2">
								@foreach(['A','B','C','D'] as $opt)
									<div class="col-md-6">
										<input type="text" name="options[{{ $opt }}]" class="form-control" placeholder="Option {{ $opt }}" value="{{ old('options.'.$opt, $question->options[$opt] ?? '') }}">
									</div>
								@endforeach
							</div>
						</div>

						<div class="mb-3 mt-3">
							<label class="form-label">Correct Answer <span class="text-danger">*</span></label>
							<input type="text" name="correct_answer" class="form-control" value="{{ old('correct_answer', $question->correct_answer) }}" required>
						</div>

						<div class="mb-3">
							<label class="form-label">Explanation</label>
							<textarea name="explanation" class="form-control" rows="3">{{ old('explanation', $question->explanation) }}</textarea>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="card shadow-sm border-0 mb-4">
					<div class="card-header bg-light"><h5 class="mb-0"><i class="bx bx-cog me-2"></i> Settings</h5></div>
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label">Marks <span class="text-danger">*</span></label>
							<input type="number" name="marks" class="form-control" min="1" value="{{ old('marks', $question->marks) }}" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="status" class="form-select">
								<option value="active" {{ old('status',$question->status ?? 'active')=='active'?'selected':'' }}>Active</option>
								<option value="inactive" {{ old('status',$question->status ?? 'active')=='inactive'?'selected':'' }}>Inactive</option>
							</select>
						</div>
						<div class="d-grid gap-2">
							<button class="btn btn-primary" type="submit"><i class="bx bx-save me-1"></i> Update</button>
							<a href="{{ route('admin.online-exam.questions.index') }}" class="btn btn-secondary"><i class="bx bx-x me-1"></i> Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@section('scripts')
<script>
function toggleOptions(){
	const type = document.getElementById('type').value;
	document.getElementById('mcqOptions').style.display = (type === 'mcq' || type === 'true_false') ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function(){
	toggleOptions();
	document.getElementById('type').addEventListener('change', toggleOptions);
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	tooltipTriggerList.map(function (tooltipTriggerEl) {return new bootstrap.Tooltip(tooltipTriggerEl)})
});
</script>
@endsection
