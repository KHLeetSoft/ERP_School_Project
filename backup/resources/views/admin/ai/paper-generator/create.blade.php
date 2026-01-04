@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light"><h5 class="mb-0">Generate AI Paper</h5></div>
		<div class="card-body">
			@if(session('error'))
				<div class="alert alert-danger">{{ session('error') }}</div>
			@endif
			<form method="POST" action="{{ route('admin.ai.paper-generator.store') }}" enctype="multipart/form-data">
				@csrf
				<div class="row g-3">
					<div class="col-md-4">
						<label class="form-label">Subject</label>
						<input name="subject" class="form-control" required>
					</div>
					<div class="col-md-4">
						<label class="form-label">Topic</label>
						<input name="topic" class="form-control">
					</div>
					<div class="col-md-4">
						<label class="form-label">Type</label>
						<select name="type" class="form-select" required>
							<option value="mcq">MCQ</option>
							<option value="subjective">Subjective</option>
							<option value="mixed">Mixed</option>
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label">Difficulty</label>
						<select name="difficulty" class="form-select" required>
							<option value="easy">Easy</option>
							<option value="medium" selected>Medium</option>
							<option value="hard">Hard</option>
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label">Number of Questions</label>
						<input type="number" min="1" max="50" name="num_questions" value="10" class="form-control" required>
					</div>
					<div class="col-12">
						<label class="form-label">Additional Notes/Constraints</label>
						<textarea name="notes" rows="5" class="form-control" placeholder="Syllabus coverage, chapters, Bloom levels, etc."></textarea>
					</div>
					<div class="col-md-6">
						<label class="form-label">Upload Syllabus/Content (optional)</label>
						<input type="file" name="source_file" class="form-control" accept=".txt,.pdf,.doc,.docx">
						<div class="form-text">Accepted: .txt, .pdf, .doc, .docx (max 10MB)</div>
					</div>
				</div>
				<div class="mt-3 d-flex gap-2">
					<button class="btn btn-primary">Generate</button>
					<a href="{{ route('admin.ai.paper-generator.index') }}" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


