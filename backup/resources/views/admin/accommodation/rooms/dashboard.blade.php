@extends('admin.layout.app')

@section('title', 'Rooms Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rooms Dashboard</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.rooms.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> View All Rooms
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalRooms }}</h3>
                                    <p>Total Rooms</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $availableRooms }}</h3>
                                    <p>Available Rooms</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $maintenanceRooms }}</h3>
                                    <p>Under Maintenance</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $fullRooms }}</h3>
                                    <p>Full Rooms</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rooms by Hostel Chart -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Rooms Distribution by Hostel</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Hostel Name</th>
                                                    <th>Total Rooms</th>
                                                    <th>Available</th>
                                                    <th>Maintenance</th>
                                                    <th>Full</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($roomsByHostel as $hostelData)
                                                <tr>
                                                    <td>{{ $hostelData->hostel->name ?? 'Unknown Hostel' }}</td>
                                                    <td>{{ $hostelData->total }}</td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            {{ $hostelData->hostel->rooms()->where('status', 'available')->count() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">
                                                            {{ $hostelData->hostel->rooms()->where('status', 'maintenance')->count() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-danger">
                                                            {{ $hostelData->hostel->rooms()->where('status', 'full')->count() }}
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
                                            <a href="{{ route('admin.accommodation.rooms.create') }}" class="btn btn-primary btn-block">
                                                <i class="fas fa-plus"></i> Add New Room
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.rooms.index') }}" class="btn btn-info btn-block">
                                                <i class="fas fa-list"></i> View All Rooms
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.rooms.export') }}" class="btn btn-success btn-block">
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
