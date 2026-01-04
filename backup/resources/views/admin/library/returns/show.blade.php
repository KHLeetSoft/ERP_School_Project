@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="mb-0">Return Details</h4>
			<div>
				<a href="{{ route('admin.library.returns.print', $return->id) }}" class="btn btn-outline-secondary btn-sm">Print</a>
				<a href="{{ route('admin.library.returns.index') }}" class="btn btn-secondary btn-sm">Back</a>
			</div>
		</div>
		<div class="card-body">
			<div class="mb-3"><strong>Book:</strong> {{ $return->issue->book->title ?? '-' }}</div>
			<div class="mb-3"><strong>Student:</strong> {{ $return->issue->student->full_name ?? '-' }}</div>
			<div class="mb-3"><strong>Returned at:</strong> {{ $return->returned_at }}</div>
			<div class="mb-3"><strong>Condition:</strong> {{ $return->condition ?? '-' }}</div>
			<div class="mb-3"><strong>Fine Paid:</strong> {{ number_format($return->fine_paid,2) }}</div>
			<div class="mb-3"><strong>Remarks:</strong><br>{{ $return->remarks ?? '-' }}</div>
		</div>
	</div>
</div>
@endsection


