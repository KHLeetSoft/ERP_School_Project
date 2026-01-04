@extends('admin.layout.app')

@section('title', 'Parent Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Children</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $parentStats['total_children'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Children</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $parentStats['active_children'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pending Fees</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $parentStats['pending_fees'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-money fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Messages</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $parentStats['messages'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Activities</h4>
                </div>
                <div class="card-body">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Activity</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                <tr>
                                    <td>{{ $activity['description'] ?? 'N/A' }}</td>
                                    <td>{{ $activity['date'] ?? 'N/A' }}</td>
                                    <td>
                                        @if(($activity['status'] ?? '') === 'completed')
                                            <span class="badge badge-pill badge-light-success">Completed</span>
                                        @elseif(($activity['status'] ?? '') === 'pending')
                                            <span class="badge badge-pill badge-light-warning">Pending</span>
                                        @else
                                            <span class="badge badge-pill badge-light-info">Info</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p>No recent activities found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 