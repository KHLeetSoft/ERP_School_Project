@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="mb-0">Record Return</h4>
			<a href="{{ route('admin.library.returns.index') }}" class="btn btn-secondary btn-sm">Back</a>
		</div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.library.returns.store') }}">
				@csrf
				@include('admin.library.returns.partials.form')
				<button type="submit" class="btn btn-primary">Save</button>
			</form>
		</div>
	</div>
</div>
@endsection


