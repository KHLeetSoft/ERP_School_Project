@extends('student.layout.app')

@section('title', 'Transport Schedule')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-calendar-alt me-2"></i>Transport Schedule
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.transport.index') }}">Transport</a></li>
                        <li class="breadcrumb-item active">Schedule</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Week Schedule -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-week me-2"></i>This Week's Schedule
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Day</th>
                                    <th>Date</th>
                                    <th>Pickup Time</th>
                                    <th>Drop Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($currentWeek as $day)
                                <tr class="{{ $day['status'] === 'Scheduled' ? 'table-success' : ($day['status'] === 'Weekend' ? 'table-warning' : 'table-secondary') }}">
                                    <td class="fw-bold">{{ $day['day'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($day['date'])->format('M d, Y') }}</td>
                                    <td>
                                        @if($day['pickup'])
                                            <span class="badge bg-primary">{{ $day['pickup'] }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($day['drop'])
                                            <span class="badge bg-info">{{ $day['drop'] }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $day['status'] === 'Scheduled' ? 'success' : ($day['status'] === 'Weekend' ? 'warning' : 'secondary') }}">
                                            {{ $day['status'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($day['status'] === 'Scheduled')
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewTripDetails('{{ $day['date'] }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar me-2"></i>Weekly Schedule Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($schedule as $day => $times)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-{{ $times['status'] === 'Active' ? 'success' : ($times['status'] === 'Weekend' ? 'warning' : 'secondary') }} text-white">
                                    <h6 class="card-title mb-0 text-capitalize">{{ $day }}</h6>
                                </div>
                                <div class="card-body text-center">
                                    @if($times['pickup'] && $times['drop'])
                                        <div class="mb-3">
                                            <i class="fas fa-arrow-up text-success fa-2x mb-2"></i>
                                            <h6 class="text-success">Pickup</h6>
                                            <h4 class="text-primary">{{ $times['pickup'] }}</h4>
                                        </div>
                                        <div class="mb-3">
                                            <i class="fas fa-arrow-down text-danger fa-2x mb-2"></i>
                                            <h6 class="text-danger">Drop</h6>
                                            <h4 class="text-primary">{{ $times['drop'] }}</h4>
                                        </div>
                                        <div class="mb-0">
                                            <span class="badge bg-{{ $times['status'] === 'Active' ? 'success' : 'warning' }}">
                                                {{ $times['status'] }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="py-4">
                                            <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                                            <h6 class="text-muted">No Service</h6>
                                            <span class="badge bg-secondary">{{ $times['status'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Legend -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Schedule Legend
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-success me-3">Active</span>
                                <span>Regular school days with transport service</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-warning me-3">Weekend</span>
                                <span>Weekend service with modified schedule</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-secondary me-3">No Service</span>
                                <span>No transport service available</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-2"></i>Important Notes
                </h6>
                <ul class="mb-0">
                    <li>Please arrive at the pickup point 5 minutes before the scheduled time</li>
                    <li>Transport service may be delayed due to traffic or weather conditions</li>
                    <li>In case of emergency, contact the driver or transport office immediately</li>
                    <li>Weekend service is available only for special events or activities</li>
                    <li>Schedule may change during holidays or special occasions</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Trip Details Modal -->
<div class="modal fade" id="tripDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trip Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Pickup Information</h6>
                        <p><strong>Time:</strong> <span id="pickupTime">-</span></p>
                        <p><strong>Location:</strong> <span id="pickupLocation">-</span></p>
                        <p><strong>Driver:</strong> <span id="driverName">-</span></p>
                        <p><strong>Contact:</strong> <span id="driverContact">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger">Drop Information</h6>
                        <p><strong>Time:</strong> <span id="dropTime">-</span></p>
                        <p><strong>Location:</strong> <span id="dropLocation">-</span></p>
                        <p><strong>Conductor:</strong> <span id="conductorName">-</span></p>
                        <p><strong>Contact:</strong> <span id="conductorContact">-</span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-info">Additional Information</h6>
                        <p><strong>Vehicle Number:</strong> <span id="vehicleNumber">-</span></p>
                        <p><strong>Route:</strong> <span id="routeName">-</span></p>
                        <p><strong>Estimated Duration:</strong> <span id="duration">-</span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printSchedule()">
                    <i class="fas fa-print me-2"></i>Print Schedule
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function viewTripDetails(date) {
    // Mock data - replace with actual data from your controller
    const tripData = {
        pickupTime: '07:30 AM',
        pickupLocation: 'Main Street Bus Stop',
        driverName: 'John Smith',
        driverContact: '+1-555-0123',
        dropTime: '03:30 PM',
        dropLocation: 'School Main Gate',
        conductorName: 'Jane Doe',
        conductorContact: '+1-555-0124',
        vehicleNumber: 'BUS-001',
        routeName: 'Route A - Downtown',
        duration: '25 minutes'
    };

    // Populate modal with data
    document.getElementById('pickupTime').textContent = tripData.pickupTime;
    document.getElementById('pickupLocation').textContent = tripData.pickupLocation;
    document.getElementById('driverName').textContent = tripData.driverName;
    document.getElementById('driverContact').textContent = tripData.driverContact;
    document.getElementById('dropTime').textContent = tripData.dropTime;
    document.getElementById('dropLocation').textContent = tripData.dropLocation;
    document.getElementById('conductorName').textContent = tripData.conductorName;
    document.getElementById('conductorContact').textContent = tripData.conductorContact;
    document.getElementById('vehicleNumber').textContent = tripData.vehicleNumber;
    document.getElementById('routeName').textContent = tripData.routeName;
    document.getElementById('duration').textContent = tripData.duration;

    const modal = new bootstrap.Modal(document.getElementById('tripDetailsModal'));
    modal.show();
}

function printSchedule() {
    window.print();
}
</script>
@endsection
