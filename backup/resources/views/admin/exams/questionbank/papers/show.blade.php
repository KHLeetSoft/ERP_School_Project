@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center">
			<h5 class="mb-0">{{ $paper->title }}</h5>
			<div>
				<a href="{{ route('admin.exams.question-bank.papers.index') }}" class="btn btn-secondary btn-sm">Back</a>
			</div>
		</div>
		<div class="card-body">
			<div class="row g-3 mb-3">
				<div class="col-md-4"><strong>Subject:</strong> {{ $paper->subject_name }}</div>
				<div class="col-md-4"><strong>Total Marks:</strong> {{ $paper->total_marks }}</div>
				<div class="col-md-4"><strong>Duration:</strong> {{ $paper->duration_mins }} mins</div>
			</div>
			<h6>Questions</h6>
			<ol class="mt-2">
				@foreach($paper->questions as $pq)
					<li class="mb-2">
						<div><strong>[{{ $pq->marks }}]</strong> {{ optional($pq->question)->question_text }}</div>
						@if(optional($pq->question)->options)
							<ul class="mb-1">
								@foreach(optional($pq->question)->options as $opt)
									<li>{{ $opt }}</li>
								@endforeach
							</ul>
						@endif
					</li>
				@endforeach
			</ol>
		</div>
	</div>
</div>
@endsection


