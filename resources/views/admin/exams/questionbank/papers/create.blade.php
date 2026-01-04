@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light"><h5 class="mb-0">Generate Question Paper (AI-assisted)</h5></div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.exams.question-bank.papers.store') }}">
				@csrf
				<div class="row g-3">
					<div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control" required></div>
					<div class="col-md-6"><label class="form-label">Subject (name)</label><input name="subject_name" class="form-control"></div>
					<div class="col-md-3"><label class="form-label">Total Marks</label><input name="total_marks" type="number" class="form-control" value="100" min="1" required></div>
					<div class="col-md-3"><label class="form-label">Duration (mins)</label><input name="duration_mins" type="number" class="form-control" value="60" min="1" required></div>
					<div class="col-12"><label class="form-label">Constraints (JSON)</label><textarea name="constraints" class="form-control" rows="4" placeholder='{"category_id": 1, "difficulty_mix": {"easy":10, "medium":10, "hard":5}}'></textarea></div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary">Generate</button>
					<a href="{{ route('admin.exams.question-bank.papers.index') }}" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


