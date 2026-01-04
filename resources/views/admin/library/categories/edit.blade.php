@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="mb-0">Edit Category</h4>
			<a href="{{ route('admin.library.categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
		</div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.library.categories.update', $category->id) }}">
				@csrf
				@method('PUT')
				@include('admin.library.categories.partials.form')
				<button type="submit" class="btn btn-primary">Update</button>
			</form>
		</div>
	</div>
</div>
@endsection


