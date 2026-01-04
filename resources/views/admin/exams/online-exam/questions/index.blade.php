@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm mb-4 border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
			<h4 class="mb-0"><i class="bx bx-question-mark me-2 text-primary"></i> Online Exam Questions</h4>
			<div class="btn-group" role="group">
				<a href="{{ route('admin.online-exam.questions.create') }}" class="btn btn-primary btn-sm" title="Add Question" data-bs-toggle="tooltip"><i class="fas fa-plus"></i></a>
				<a href="{{ route('admin.online-exam.manage') }}" class="btn btn-secondary btn-sm" title="Manage Exams" data-bs-toggle="tooltip"><i class="bx bx-cog"></i></a>
			</div>
		</div>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<i class="fas fa-check-circle me-2"></i> {{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	@endif

	<div class="card shadow-sm border-0 mb-3">
		<div class="card-body">
			<form method="GET" action="{{ route('admin.online-exam.questions.index') }}" class="row g-3 align-items-end">
				<div class="col-md-4">
					<label class="form-label">Category</label>
					<select name="category_id" class="form-select">
						<option value="">All</option>
						@foreach($categories as $category)
							<option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Type</label>
					<select name="type" class="form-select">
						<option value="">All</option>
						@foreach(['mcq' => 'MCQ', 'true_false' => 'True/False', 'short_answer' => 'Short Answer', 'essay' => 'Essay'] as $key=>$label)
							<option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Difficulty</label>
					<select name="difficulty" class="form-select">
						<option value="">All</option>
						@foreach(['easy','medium','hard'] as $d)
							<option value="{{ $d }}" {{ request('difficulty') == $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">Status</label>
					<select name="status" class="form-select">
						<option value="">All</option>
						@foreach(['active','inactive'] as $st)
							<option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-12 d-flex gap-2">
					<button class="btn btn-primary btn-sm" type="submit" title="Apply Filters" data-bs-toggle="tooltip"><i class="bx bx-filter"></i></button>
					<a href="{{ route('admin.online-exam.questions.index') }}" class="btn btn-secondary btn-sm" title="Reset Filters" data-bs-toggle="tooltip"><i class="bx bx-reset"></i></a>
				</div>
			</form>
		</div>
	</div>

	<div class="card shadow-sm border-0">
		<div class="table-responsive p-3">
			<table class="table table-striped align-middle w-100">
				<thead class="table-dark">
					<tr>
						<th>#</th>
						<th>Question</th>
						<th>Category</th>
						<th>Type</th>
						<th>Difficulty</th>
						<th>Marks</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($questions as $idx => $q)
						<tr>
							<td>{{ $questions->firstItem() + $idx }}</td>
							<td><small>{{ Str::limit($q->question_text, 100) }}</small></td>
							<td>{{ $q->category->name ?? 'N/A' }}</td>
							<td><span class="badge bg-info">{{ strtoupper($q->type) }}</span></td>
							<td>{{ ucfirst($q->difficulty ?? 'medium') }}</td>
							<td>{{ $q->marks }}</td>
							<td>
								@if(($q->status ?? 'active') === 'active')
									<span class="badge bg-success">Active</span>
								@else
									<span class="badge bg-secondary">Inactive</span>
								@endif
							</td>
							<td>
								<div class="btn-group" role="group">
									<a href="{{ route('admin.online-exam.questions.edit', $q) }}" class="btn btn-sm btn-outline-primary" title="Edit" data-bs-toggle="tooltip"><i class="bx bx-edit"></i></a>
									<form action="{{ route('admin.online-exam.questions.destroy', $q) }}" method="POST" onsubmit="return confirm('Delete this question?')" class="d-inline">
										@csrf
										@method('DELETE')
										<button class="btn btn-sm btn-outline-danger" title="Delete" data-bs-toggle="tooltip" type="submit"><i class="bx bx-trash"></i></button>
									</form>
								</div>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="8" class="text-center py-4">
								<div class="text-muted">
									<i class="bx bx-question-mark" style="font-size: 48px;"></i>
									<p class="mt-2">No questions found. Create your first question.</p>
									<a href="{{ route('admin.online-exam.questions.create') }}" class="btn btn-primary" title="Add Question" data-bs-toggle="tooltip"><i class="fas fa-plus"></i></a>
								</div>
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@if($questions->hasPages())
			<div class="card-footer">
				{{ $questions->links() }}
			</div>
		@endif
	</div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	tooltipTriggerList.map(function (tooltipTriggerEl) {return new bootstrap.Tooltip(tooltipTriggerEl)})
});
</script>
@endsection
