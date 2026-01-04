@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center">
			<h5 class="mb-0">Question Details</h5>
			<div>
				<a href="{{ route('admin.exams.question-bank.questions.edit', $question->id) }}" class="btn btn-primary btn-sm">Edit</a>
				<a href="{{ route('admin.exams.question-bank.questions.index') }}" class="btn btn-secondary btn-sm">Back</a>
			</div>
		</div>
		<div class="card-body">
			<div class="row g-3">
				<div class="col-md-6"><strong>Category:</strong> {{ optional($question->category)->name }}</div>
				<div class="col-md-3"><strong>Type:</strong> {{ strtoupper($question->type) }}</div>
				<div class="col-md-3"><strong>Difficulty:</strong> {{ ucfirst($question->difficulty) }}</div>
				<div class="col-12"><strong>Question:</strong><div class="mt-1">{{ $question->question_text }}</div></div>
				<div class="col-12"><strong>Options:</strong><pre class="mt-1 mb-0">{{ json_encode($question->options, JSON_PRETTY_PRINT) }}</pre></div>
				<div class="col-md-6"><strong>Correct Answer:</strong> {{ $question->correct_answer }}</div>
				<div class="col-md-3"><strong>Marks:</strong> {{ $question->marks }}</div>
				<div class="col-md-3"><strong>Status:</strong> {{ ucfirst($question->status) }}</div>
				<div class="col-12"><strong>Explanation:</strong><div class="mt-1">{{ $question->explanation }}</div></div>
			</div>
		</div>
	</div>
</div>
@endsection


