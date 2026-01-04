@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="mb-0">New Member</h4>
			<a href="{{ route('admin.library.members.index') }}" class="btn btn-secondary btn-sm">Back</a>
		</div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.library.members.store') }}">
				@csrf
				@include('admin.library.members.partials.form')
				<button type="submit" class="btn btn-primary">Save</button>
			</form>
		</div>
	</div>
</div>
@endsection


