@extends('admin.layout.app')

@section('content')
<div class="container py-4">
	<div class="card shadow-sm">
		<div class="card-body">
			<h4 class="mb-3">Return Receipt</h4>
			<p><strong>Book:</strong> {{ $return->issue->book->title ?? '-' }}</p>
			<p><strong>Student:</strong> {{ $return->issue->student->full_name ?? '-' }}</p>
			<p><strong>Returned at:</strong> {{ $return->returned_at }}</p>
			<p><strong>Fine Paid:</strong> {{ number_format($return->fine_paid,2) }}</p>
			<p><strong>Remarks:</strong> {{ $return->remarks ?? '-' }}</p>
			<hr>
			<button class="btn btn-primary" onclick="window.print()"><i class="bx bx-printer me-1"></i> Print</button>
			<a href="{{ route('admin.library.returns.index') }}" class="btn btn-secondary">Back</a>
		</div>
	</div>
</div>
@endsection


