@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light"><h5 class="mb-0">Edit Question Category</h5></div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.exams.question-bank.categories.update', $category->id) }}">
				@csrf
				@method('PUT')
				<div class="row g-3">
					<div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ $category->name }}" required></div>
					<div class="col-md-3"><label class="form-label">Icon (optional)</label><input name="icon" class="form-control" value="{{ $category->icon }}"></div>
					<div class="col-md-3"><label class="form-label">Status</label>
						<select name="status" class="form-select">
							<option value="active" @selected($category->status==='active')>Active</option>
							<option value="inactive" @selected($category->status==='inactive')>Inactive</option>
						</select>
					</div>
					<div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="3" class="form-control">{{ $category->description }}</textarea></div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary">Update</button>
					<a href="{{ route('admin.exams.question-bank.categories.index') }}" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


