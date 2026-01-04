@extends('admin.layout.app')

@section('title', 'Communication Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Parent Communication Dashboard</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.parents.communication.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> New Communication
                    </a>
                    <a href="{{ route('admin.parents.communication.index') }}" class="btn btn-secondary">
                        <i class="bx bx-list-ul"></i> View All
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Communications</p>
                            <h4 class="mb-0">{{ number_format($totalCommunications) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-message-square font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Recent (7 days)</p>
                            <h4 class="mb-0">{{ number_format($recentCommunications) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-calendar font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Pending Responses</p>
                            <h4 class="mb-0">{{ number_format($pendingResponses) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-time font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Failed</p>
                            <h4 class="mb-0">{{ number_format($failedCommunications) }}</h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-danger align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-x-circle font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cost Analysis -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Cost Analysis</h4>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3">
                                <h5 class="text-primary">${{ number_format($totalCost, 2) }}</h5>
                                <p class="text-muted mb-0">Total Cost</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3">
                                <h5 class="text-success">${{ number_format($monthlyCost, 2) }}</h5>
                                <p class="text-muted mb-0">This Month</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Communication Status</h4>
                    <div class="row text-center">
                        @foreach($communicationsByStatus as $status)
                        <div class="col-3">
                            <div class="p-3">
                                <h5 class="text-primary">{{ $status->count }}</h5>
                                <p class="text-muted mb-0">{{ ucfirst($status->status) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Communication by Type</h4>
                    <div class="row">
                        @foreach($communicationsByType as $type)
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    @php
                                        $icons = [
                                            'email' => 'bx bx-envelope text-primary',
                                            'sms' => 'bx bx-message-square text-success',
                                            'phone' => 'bx bx-phone text-info',
                                            'meeting' => 'bx bx-calendar text-warning',
                                            'letter' => 'bx bx-file text-secondary'
                                        ];
                                        $icon = $icons[$type->communication_type] ?? 'bx bx-message text-muted';
                                    @endphp
                                    <i class="{{ $icon }} font-size-20"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ ucfirst($type->communication_type) }}</h6>
                                    <p class="text-muted mb-0">{{ $type->count }} communications</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Priority Distribution</h4>
                    <div class="row">
                        @php
                            $priorities = ['low', 'normal', 'high', 'urgent'];
                            $priorityColors = [
                                'low' => 'text-secondary',
                                'normal' => 'text-primary',
                                'high' => 'text-warning',
                                'urgent' => 'text-danger'
                            ];
                        @endphp
                        @foreach($priorities as $priority)
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bx bx-flag {{ $priorityColors[$priority] }} font-size-20"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ ucfirst($priority) }}</h6>
                                    <p class="text-muted mb-0">
                                        {{ $latestCommunications->where('priority', $priority)->count() }} communications
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Communications -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Recent Communications</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Parent</th>
                                    <th>Type</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestCommunications as $communication)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-light text-dark">
                                                        {{ substr($communication->parentDetail->primary_contact_name ?? $communication->parentDetail->user->name ?? 'P', 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 font-size-14">
                                                    {{ $communication->parentDetail->primary_contact_name ?? $communication->parentDetail->user->name ?? 'Parent #' . $communication->parentDetail->id }}
                                                </h6>
                                                @if($communication->student)
                                                <small class="text-muted">
                                                    {{ $communication->student->first_name }} {{ $communication->student->last_name }}
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $icons = [
                                                'email' => 'bx bx-envelope text-primary',
                                                'sms' => 'bx bx-message-square text-success',
                                                'phone' => 'bx bx-phone text-info',
                                                'meeting' => 'bx bx-calendar text-warning',
                                                'letter' => 'bx bx-file text-secondary'
                                            ];
                                            $icon = $icons[$communication->communication_type] ?? 'bx bx-message text-muted';
                                        @endphp
                                        <i class="{{ $icon }}"></i>
                                        {{ ucfirst($communication->communication_type) }}
                                    </td>
                                    <td>
                                        {{ Str::limit($communication->subject ?: $communication->message, 30) }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $communication->status_badge }}">
                                            {{ ucfirst($communication->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $communication->priority_badge }}">
                                            {{ ucfirst($communication->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $communication->created_at->format('M d, H:i') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.parents.communication.show', $communication->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        No communications found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Quick Actions</h4>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.parents.communication.create') }}" class="btn btn-primary w-100">
                                <i class="bx bx-plus-circle me-2"></i>
                                New Communication
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.parents.communication.export') }}" class="btn btn-success w-100">
                                <i class="bx bx-export me-2"></i>
                                Export Data
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.parents.communication.index') }}" class="btn btn-info w-100">
                                <i class="bx bx-list-ul me-2"></i>
                                View All
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.parents.details.index') }}" class="btn btn-warning w-100">
                                <i class="bx bx-user me-2"></i>
                                Manage Parents
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh dashboard every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000); // 5 minutes
});
</script>
@endsection
