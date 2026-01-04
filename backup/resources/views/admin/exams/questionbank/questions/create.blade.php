@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light"><h5 class="mb-0">Create Question</h5></div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.exams.question-bank.questions.store') }}">
				@csrf
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label">Category</label>
						<select name="question_category_id" class="form-select">
							<option value="">-- None --</option>
							@foreach($categories as $c)
								<option value="{{ $c->id }}">{{ $c->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-3"><label class="form-label">Type</label>
						<select name="type" class="form-select" required>
							<option value="mcq">MCQ</option>
							<option value="boolean">True/False</option>
							<option value="short">Short</option>
							<option value="long">Long</option>
						</select>
					</div>
					<div class="col-md-3"><label class="form-label">Difficulty</label>
						<select name="difficulty" class="form-select">
							<option value="">-- Any --</option>
							<option value="easy">Easy</option>
							<option value="medium">Medium</option>
							<option value="hard">Hard</option>
						</select>
					</div>
					<div class="col-12"><label class="form-label">Question</label><textarea name="question_text" rows="3" class="form-control" required></textarea></div>
					<div class="col-12"><label class="form-label">Options (JSON for MCQ/Boolean)</label><textarea name="options" rows="2" class="form-control" placeholder='["A","B","C","D"]'></textarea></div>
					<div class="col-md-6"><label class="form-label">Correct Answer</label><input name="correct_answer" class="form-control"></div>
					<div class="col-md-3"><label class="form-label">Marks</label><input name="marks" type="number" step="0.01" class="form-control" value="1"></div>
					<div class="col-md-3"><label class="form-label">Status</label>
						<select name="status" class="form-select" required>
							<option value="active">Active</option>
							<option value="inactive">Inactive</option>
						</select>
					</div>
					<div class="col-12"><label class="form-label">Explanation</label><textarea name="explanation" rows="2" class="form-control"></textarea></div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary">Save</button>
					<a href="{{ route('admin.exams.question-bank.questions.index') }}" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


