@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="d-flex justify-content-between align-items-center mb-2">
		<h5 class="mb-0">AI Paper Preview</h5>
		<div class="btn-group">
			<a href="{{ route('admin.ai.paper-generator.download', $paper->id) }}" class="btn btn-outline-primary">Download PDF</a>
			<form method="POST" action="{{ route('admin.ai.paper-generator.destroy', $paper->id) }}" onsubmit="return confirm('Delete this AI paper?')">
				@csrf
				@method('DELETE')
				<button class="btn btn-outline-danger">Delete</button>
			</form>
			<a href="{{ route('admin.ai.paper-generator.to-question-paper', $paper->id) }}" class="btn btn-success">Create Question Paper</a>
		</div>
	</div>
	<div class="card shadow-sm border-0">
		<div class="card-body">
			@if(!empty($paper->pdf_path))
				<div class="mb-2">
					<a href="{{ asset(str_starts_with($paper->pdf_path,'public/') ? str_replace('public/','storage/',$paper->pdf_path) : $paper->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View saved PDF</a>
				</div>
			@endif
			@php $src = $paper->payload['source_file_path'] ?? null; @endphp
			@if(!empty($src))
				<div class="mb-2">
					<a href="{{ asset(str_starts_with($src,'public/') ? str_replace('public/','storage/',$src) : $src) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View uploaded source</a>
				</div>
			@endif
			<div class="mb-3">
				<div class="h5">{{ $data['title'] ?? ($paper->subject.' Paper') }}</div>
				@if(!empty($data['instructions']))
					<div class="text-muted">{{ $data['instructions'] }}</div>
				@endif
			</div>
			<ol>
				@foreach(($data['questions'] ?? []) as $i => $q)
					<li class="mb-3">
						<div><strong>({{ $q['marks'] ?? 1 }} marks)</strong> {{ $q['question'] ?? '' }}</div>
						@if(!empty($q['options']))
							<ol type="A">
								@foreach($q['options'] as $opt)
									<li>{{ $opt }}</li>
								@endforeach
							</ol>
						@endif
						@if(!empty($q['answer']))
							<div class="text-muted small">Answer: {{ $q['answer'] }}</div>
						@endif
					</li>
				@endforeach
			</ol>
		</div>
	</div>
</div>
@endsection


