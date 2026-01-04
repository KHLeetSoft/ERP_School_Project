@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="mb-0">Category Details</h4>
			<a href="{{ route('admin.library.categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
		</div>
		<div class="card-body">
			<div class="mb-3"><strong>Name:</strong> {{ $category->name }}</div>
			<div class="mb-3"><strong>Slug:</strong> {{ $category->slug }}</div>
			<div class="mb-3"><strong>Status:</strong> <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($category->status) }}</span></div>
			<div class="mb-3"><strong>Description:</strong><br>{{ $category->description }}</div>
		</div>
	</div>
</div>
@endsection


