@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header bg-light"><h5 class="mb-0">Create Question Category</h5></div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.exams.question-bank.categories.store') }}">
				@csrf
				<div class="row g-3">
					<div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
					<div class="col-md-3"><label class="form-label">Icon (optional)</label><input name="icon" class="form-control" placeholder="bx bx-book"></div>
					<div class="col-md-3"><label class="form-label">Status</label>
						<select name="status" class="form-select">
							<option value="active">Active</option>
							<option value="inactive">Inactive</option>
						</select>
					</div>
					<div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="3" class="form-control"></textarea></div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary">Save</button>
					<a href="{{ route('admin.exams.question-bank.categories.index') }}" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


