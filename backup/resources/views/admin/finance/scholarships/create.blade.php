@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h6 class="mb-0">New Scholarship</h6>
			<a href="{{ route('admin.finance.scholarships.index') }}" class="btn btn-sm btn-secondary"><i class="bx bx-left-arrow-alt"></i> Back</a>
		</div>
		<form method="POST" action="{{ route('admin.finance.scholarships.store') }}">
			@csrf
			<div class="card-body row g-3">
				<div class="col-md-6">
					<label class="form-label">Name *</label>
					<input type="text" name="name" class="form-control" required>
				</div>
				<div class="col-md-3">
					<label class="form-label">Code *</label>
					<input type="text" name="code" class="form-control" required>
				</div>
				<div class="col-md-3">
					<label class="form-label">Amount *</label>
					<input type="number" step="0.01" name="amount" class="form-control" required>
				</div>
				<div class="col-md-3">
					<label class="form-label">Status *</label>
					<select name="status" class="form-select" required>
						<option value="pending">Pending</option>
						<option value="approved">Approved</option>
						<option value="rejected">Rejected</option>
						<option value="paid">Paid</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Awarded Date</label>
					<input type="date" name="awarded_date" class="form-control">
				</div>
				<div class="col-12">
					<label class="form-label">Notes</label>
					<textarea name="notes" class="form-control" rows="3"></textarea>
				</div>
			</div>
			<div class="card-footer d-flex justify-content-end">
				<button class="btn btn-dark" type="submit"><i class="bx bx-save"></i> Save</button>
			</div>
		</form>
	</div>
</div>
@endsection


