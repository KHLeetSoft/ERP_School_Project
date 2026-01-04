@extends('admin.layout.app')

@section('title', 'Allocations Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Allocations Dashboard</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.allocation.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> View All Allocations
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalAllocations }}</h3>
                                    <p>Total Allocations</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $activeAllocations }}</h3>
                                    <p>Active Allocations</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $leftAllocations }}</h3>
                                    <p>Left Allocations</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-times"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ $activeAllocations > 0 ? round(($activeAllocations / $totalAllocations) * 100, 1) : 0 }}%</h3>
                                    <p>Occupancy Rate</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allocations by Hostel Chart -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Allocations Distribution by Hostel</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Hostel Name</th>
                                                    <th>Total Allocations</th>
                                                    <th>Active</th>
                                                    <th>Left</th>
                                                    <th>Occupancy Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($allocationsByHostel as $hostelData)
                                                <tr>
                                                    <td>{{ $hostelData->hostel->name ?? 'Unknown Hostel' }}</td>
                                                    <td>{{ $hostelData->total }}</td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            {{ $hostelData->hostel->allocations()->where('status', 'active')->count() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">
                                                            {{ $hostelData->hostel->allocations()->where('status', 'left')->count() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $totalRooms = $hostelData->hostel->rooms()->count();
                                                            $activeAllocations = $hostelData->hostel->allocations()->where('status', 'active')->count();
                                                            $occupancyRate = $totalRooms > 0 ? round(($activeAllocations / $totalRooms) * 100, 1) : 0;
                                                        @endphp
                                                        <span class="badge badge-{{ $occupancyRate > 80 ? 'success' : ($occupancyRate > 50 ? 'warning' : 'danger') }}">
                                                            {{ $occupancyRate }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No data available</td>
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
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Quick Actions</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.allocation.create') }}" class="btn btn-primary btn-block">
                                                <i class="fas fa-plus"></i> New Allocation
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.allocation.index') }}" class="btn btn-info btn-block">
                                                <i class="fas fa-list"></i> View All Allocations
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.allocation.export') }}" class="btn btn-success btn-block">
                                                <i class="fas fa-download"></i> Export Data
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-warning btn-block" onclick="importData()">
                                                <i class="fas fa-upload"></i> Import Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function importData() {
    // Implementation for import functionality
    alert('Import functionality will be implemented soon');
}
</script>
@endsection
