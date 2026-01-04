@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light"><h5 class="mb-0">Edit Question</h5></div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.exams.question-bank.questions.update', $question->id) }}">
				@csrf
				@method('PUT')
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label">Category</label>
						<select name="question_category_id" class="form-select">
							<option value="">-- None --</option>
							@foreach($categories as $c)
								<option value="{{ $c->id }}" @selected($question->question_category_id==$c->id)>{{ $c->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-3"><label class="form-label">Type</label>
						<select name="type" class="form-select" required>
							<option value="mcq" @selected($question->type==='mcq')>MCQ</option>
							<option value="boolean" @selected($question->type==='boolean')>True/False</option>
							<option value="short" @selected($question->type==='short')>Short</option>
							<option value="long" @selected($question->type==='long')>Long</option>
						</select>
					</div>
					<div class="col-md-3"><label class="form-label">Difficulty</label>
						<select name="difficulty" class="form-select">
							<option value="" @selected(!$question->difficulty)>-- Any --</option>
							<option value="easy" @selected($question->difficulty==='easy')>Easy</option>
							<option value="medium" @selected($question->difficulty==='medium')>Medium</option>
							<option value="hard" @selected($question->difficulty==='hard')>Hard</option>
						</select>
					</div>
					<div class="col-12"><label class="form-label">Question</label><textarea name="question_text" rows="3" class="form-control" required>{{ $question->question_text }}</textarea></div>
					<div class="col-12"><label class="form-label">Options (JSON for MCQ/Boolean)</label><textarea name="options" rows="2" class="form-control">{{ json_encode($question->options) }}</textarea></div>
					<div class="col-md-6"><label class="form-label">Correct Answer</label><input name="correct_answer" class="form-control" value="{{ $question->correct_answer }}"></div>
					<div class="col-md-3"><label class="form-label">Marks</label><input name="marks" type="number" step="0.01" class="form-control" value="{{ $question->marks }}"></div>
					<div class="col-md-3"><label class="form-label">Status</label>
						<select name="status" class="form-select" required>
							<option value="active" @selected($question->status==='active')>Active</option>
							<option value="inactive" @selected($question->status==='inactive')>Inactive</option>
						</select>
					</div>
					<div class="col-12"><label class="form-label">Explanation</label><textarea name="explanation" rows="2" class="form-control">{{ $question->explanation }}</textarea></div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary">Update</button>
					<a href="{{ route('admin.exams.question-bank.questions.index') }}" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


