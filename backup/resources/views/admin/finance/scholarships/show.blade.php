@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h6 class="mb-0">Scholarship: {{ $scholarship->name }}</h6>
			<a href="{{ route('admin.finance.scholarships.index') }}" class="btn btn-sm btn-secondary"><i class="bx bx-left-arrow-alt"></i> Back</a>
		</div>
		<div class="card-body">
			<div class="row g-3">
				<div class="col-md-3"><div class="text-muted small">Code</div><div class="fw-semibold">{{ $scholarship->code }}</div></div>
				<div class="col-md-3"><div class="text-muted small">Amount</div><div class="fw-semibold">₹{{ number_format($scholarship->amount,2) }}</div></div>
				<div class="col-md-3"><div class="text-muted small">Status</div><div class="fw-semibold">{{ ucfirst($scholarship->status) }}</div></div>
				<div class="col-md-3"><div class="text-muted small">Awarded</div><div class="fw-semibold">{{ optional($scholarship->awarded_date)->toFormattedDateString() ?? '-' }}</div></div>
				<div class="col-12"><div class="text-muted small">Notes</div><div class="fw-semibold">{{ $scholarship->notes ?? '-' }}</div></div>
			</div>
		</div>
	</div>
</div>
@endsection


