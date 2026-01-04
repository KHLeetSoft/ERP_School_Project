@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="mb-0">Issue Details</h4>
			<a href="{{ route('admin.library.issues.index') }}" class="btn btn-secondary btn-sm">Back</a>
		</div>
		<div class="card-body">
			<div class="mb-3"><strong>Book:</strong> {{ $issue->book->title ?? '-' }}</div>
			<div class="mb-3"><strong>Student:</strong> {{ $issue->student->full_name ?? '-' }}</div>
			<div class="mb-3"><strong>Issued at:</strong> {{ $issue->issued_at }}</div>
			<div class="mb-3"><strong>Due date:</strong> {{ $issue->due_date }}</div>
			<div class="mb-3"><strong>Returned at:</strong> {{ $issue->returned_at ?? '-' }}</div>
			<div class="mb-3"><strong>Status:</strong> <span class="badge bg-{{ $issue->status === 'overdue' ? 'danger' : ($issue->status === 'returned' ? 'success' : 'secondary') }}">{{ ucfirst($issue->status) }}</span></div>
			<div class="mb-3"><strong>Fine:</strong> {{ number_format($issue->fine_amount,2) }}</div>
			<div class="mb-3"><strong>Notes:</strong><br>{{ $issue->notes }}</div>
		</div>
	</div>
</div>
@endsection


