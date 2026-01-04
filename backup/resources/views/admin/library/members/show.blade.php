@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="mb-0">Member Details</h4>
			<div>
				<form method="POST" action="{{ route('admin.library.members.quick-renew', $member->id) }}" class="d-inline">
					@csrf
					<button class="btn btn-outline-success btn-sm">Quick Renew +1y</button>
				</form>
				<a href="{{ route('admin.library.members.index') }}" class="btn btn-secondary btn-sm">Back</a>
			</div>
		</div>
		<div class="card-body">
			<div class="mb-3"><strong>Membership No:</strong> {{ $member->membership_no }}</div>
			<div class="mb-3"><strong>Name:</strong> {{ $member->name }}</div>
			<div class="mb-3"><strong>Email:</strong> {{ $member->email ?? '-' }}</div>
			<div class="mb-3"><strong>Phone:</strong> {{ $member->phone ?? '-' }}</div>
			<div class="mb-3"><strong>Type:</strong> {{ ucfirst($member->member_type) }}</div>
			<div class="mb-3"><strong>Status:</strong> <span class="badge bg-{{ $member->status === 'active' ? 'success' : ($member->status === 'expired' ? 'danger' : 'secondary') }}">{{ ucfirst($member->status) }}</span></div>
			<div class="mb-3"><strong>Joined:</strong> {{ $member->joined_at }}</div>
			<div class="mb-3"><strong>Expiry:</strong> {{ $member->expiry_at ?? '-' }}</div>
			<div class="mb-3"><strong>Address:</strong><br>{{ $member->address ?? '-' }}</div>
			<div class="mb-3"><strong>Notes:</strong><br>{{ $member->notes ?? '-' }}</div>
		</div>
	</div>
</div>
@endsection


