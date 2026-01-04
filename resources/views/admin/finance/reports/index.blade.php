@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h6 class="mb-0">Finance Reports</h6>
		<div>
			<a href="{{ route('admin.finance.reports.dashboard') }}" class="btn btn-sm btn-dark"><i class="bx bx-bar-chart"></i> Dashboard</a>
		</div>
	</div>

	<div class="card shadow-sm border-0">
		<div class="card-body">
			<form class="row g-3" method="get">
				<div class="col-md-3">
					<label class="form-label">Start Date</label>
					<input type="date" name="start_date" class="form-control" value="{{ optional($start)->toDateString() }}">
				</div>
				<div class="col-md-3">
					<label class="form-label">End Date</label>
					<input type="date" name="end_date" class="form-control" value="{{ optional($end)->toDateString() }}">
				</div>
				<div class="col-md-3 align-self-end">
					<button class="btn btn-primary">Filter</button>
				</div>
			</form>

			<hr>
			<p class="text-muted mb-0">Use the Dashboard for consolidated visual analytics of income, expenses, and categories.</p>
		</div>
	</div>
</div>
@endsection


