@extends('student.layout.app')

@section('title', 'Transport History')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-history me-2"></i>Transport History
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.transport.index') }}">Transport</a></li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $monthlyStats['total_trips'] ?? 0 }}</h3>
                            <p class="mb-0">Total Trips</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-route fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $monthlyStats['completed'] ?? 0 }}</h3>
                            <p class="mb-0">Completed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $monthlyStats['missed'] ?? 0 }}</h3>
                            <p class="mb-0">Missed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ number_format($monthlyStats['attendance_rate'] ?? 0, 1) }}%</h3>
                            <p class="mb-0">Attendance Rate</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Filter History
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('student.transport.history') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Date From</label>
                                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date To</label>
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="missed" {{ request('status') === 'missed' ? 'selected' : '' }}>Missed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Trip History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Trip History
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($trips) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Pickup Time</th>
                                        <th>Drop Time</th>
                                        <th>Status</th>
                                        <th>Route</th>
                                        <th>Driver</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trips as $trip)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($trip['date'])->format('M d, Y') }}</td>
                                        <td class="fw-bold">{{ $trip['day'] }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $trip['pickup_time'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $trip['drop_time'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $trip['status'] === 'Completed' ? 'success' : 'warning' }}">
                                                {{ $trip['status'] }}
                                            </span>
                                        </td>
                                        <td>{{ $trip['route'] }}</td>
                                        <td>{{ $trip['driver'] }}</td>
                                        <td>
                                            @if($trip['notes'])
                                                <span class="text-muted" title="{{ $trip['notes'] }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewTripDetails('{{ $trip['date'] }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted mb-0">
                                    Showing {{ count($trips) }} trips
                                </p>
                            </div>
                            <div>
                                <button class="btn btn-outline-primary" onclick="exportHistory()">
                                    <i class="fas fa-download me-2"></i>Export History
                                </button>
                                <button class="btn btn-outline-secondary" onclick="printHistory()">
                                    <i class="fas fa-print me-2"></i>Print
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No trip history found</h5>
                            <p class="text-muted">Your transport history will appear here once you start using the service.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    @if(count($trips) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Attendance Trend
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Trip Details Modal -->
<div class="modal fade" id="tripDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trip Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Trip Information</h6>
                        <p><strong>Date:</strong> <span id="tripDate">-</span></p>
                        <p><strong>Day:</strong> <span id="tripDay">-</span></p>
                        <p><strong>Status:</strong> <span id="tripStatus">-</span></p>
                        <p><strong>Route:</strong> <span id="tripRoute">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Timing</h6>
                        <p><strong>Pickup Time:</strong> <span id="tripPickupTime">-</span></p>
                        <p><strong>Drop Time:</strong> <span id="tripDropTime">-</span></p>
                        <p><strong>Duration:</strong> <span id="tripDuration">-</span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-info">Staff Information</h6>
                        <p><strong>Driver:</strong> <span id="tripDriver">-</span></p>
                        <p><strong>Contact:</strong> <span id="tripDriverContact">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Additional Notes</h6>
                        <p id="tripNotes">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printTripDetails()">
                    <i class="fas fa-print me-2"></i>Print Details
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Mock data for trip details
const tripDetails = {
    '2024-03-15': {
        date: 'March 15, 2024',
        day: 'Friday',
        status: 'Completed',
        route: 'Route A - Downtown',
        pickupTime: '07:30 AM',
        dropTime: '03:30 PM',
        duration: '8 hours',
        driver: 'John Smith',
        driverContact: '+1-555-0123',
        notes: 'On time, no issues reported'
    },
    '2024-03-14': {
        date: 'March 14, 2024',
        day: 'Thursday',
        status: 'Completed',
        route: 'Route A - Downtown',
        pickupTime: '07:30 AM',
        dropTime: '03:30 PM',
        duration: '8 hours',
        driver: 'John Smith',
        driverContact: '+1-555-0123',
        notes: 'Slight delay due to traffic'
    }
};

function viewTripDetails(date) {
    const trip = tripDetails[date] || {
        date: date,
        day: 'Unknown',
        status: 'Unknown',
        route: 'Unknown',
        pickupTime: '-',
        dropTime: '-',
        duration: '-',
        driver: '-',
        driverContact: '-',
        notes: 'No additional information available'
    };

    // Populate modal with data
    document.getElementById('tripDate').textContent = trip.date;
    document.getElementById('tripDay').textContent = trip.day;
    document.getElementById('tripStatus').innerHTML = `<span class="badge bg-${trip.status === 'Completed' ? 'success' : 'warning'}">${trip.status}</span>`;
    document.getElementById('tripRoute').textContent = trip.route;
    document.getElementById('tripPickupTime').textContent = trip.pickupTime;
    document.getElementById('tripDropTime').textContent = trip.dropTime;
    document.getElementById('tripDuration').textContent = trip.duration;
    document.getElementById('tripDriver').textContent = trip.driver;
    document.getElementById('tripDriverContact').textContent = trip.driverContact;
    document.getElementById('tripNotes').textContent = trip.notes;

    const modal = new bootstrap.Modal(document.getElementById('tripDetailsModal'));
    modal.show();
}

function exportHistory() {
    // Here you would typically generate and download a CSV or PDF file
    alert('Export functionality would be implemented here. This would generate a CSV or PDF file with your transport history.');
}

function printHistory() {
    window.print();
}

function printTripDetails() {
    // Print only the modal content
    const printContent = document.querySelector('#tripDetailsModal .modal-body').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Trip Details</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .row { display: flex; margin-bottom: 10px; }
                    .col-md-6 { flex: 1; padding: 0 10px; }
                    h6 { color: #007bff; margin-bottom: 10px; }
                    p { margin: 5px 0; }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Initialize attendance chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attendanceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Attendance Rate (%)',
                    data: [95, 88, 92, 96],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
});
</script>
@endsection
