@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center">
			<h5 class="mb-0">Category Details</h5>
			<div>
				<a href="{{ route('admin.exams.question-bank.categories.edit', $category->id) }}" class="btn btn-primary btn-sm">Edit</a>
				<a href="{{ route('admin.exams.question-bank.categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
			</div>
		</div>
		<div class="card-body">
			<div class="row g-3">
				<div class="col-md-6"><strong>Name:</strong> {{ $category->name }}</div>
				<div class="col-md-6"><strong>Status:</strong> {{ ucfirst($category->status) }}</div>
				<div class="col-md-6"><strong>Icon:</strong> {{ $category->icon }}</div>
				<div class="col-md-12"><strong>Description:</strong> {{ $category->description }}</div>
			</div>
		</div>
	</div>
</div>
@endsection


