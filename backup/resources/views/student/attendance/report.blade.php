@extends('student.layout.app')

@section('title', 'Attendance Report')
@section('page-title', 'Attendance Report')

@section('content')
<div class="row">
    <!-- Report Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Report Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('student.attendance.report') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Generate Report
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="printReport()">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Report Summary -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Total Days</h6>
                            <h4 class="mb-0">{{ $report['total_days'] }}</h4>
                            <small class="text-muted">{{ $report['period'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Present Days</h6>
                            <h4 class="mb-0">{{ $report['present_days'] }}</h4>
                            <small class="text-muted">{{ $report['attendance_percentage'] }}%</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Absent Days</h6>
                            <h4 class="mb-0">{{ $report['absent_days'] }}</h4>
                            <small class="text-muted">{{ $report['total_days'] > 0 ? round(($report['absent_days'] / $report['total_days']) * 100, 2) : 0 }}%</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Late Days</h6>
                            <h4 class="mb-0">{{ $report['late_days'] }}</h4>
                            <small class="text-muted">{{ $report['leave_days'] }} Leave</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Attendance Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Attendance Statistics -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span>Attendance Rate</span>
                        <span class="fw-bold">{{ $report['attendance_percentage'] }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $report['attendance_percentage'] }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span>Present Days</span>
                        <span class="fw-bold text-success">{{ $report['present_days'] }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $report['total_days'] > 0 ? ($report['present_days'] / $report['total_days']) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span>Absent Days</span>
                        <span class="fw-bold text-danger">{{ $report['absent_days'] }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-danger" style="width: {{ $report['total_days'] > 0 ? ($report['absent_days'] / $report['total_days']) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span>Late Days</span>
                        <span class="fw-bold text-warning">{{ $report['late_days'] }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ $report['total_days'] > 0 ? ($report['late_days'] / $report['total_days']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Attendance Records -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detailed Records</h5>
            </div>
            <div class="card-body">
                @if($report['attendance_records']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Status</th>
                                    <th>Check-in Time</th>
                                    <th>Check-out Time</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report['attendance_records'] as $record)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($record->date)->format('l') }}</td>
                                        <td>
                                            @if($record->status === 'present')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($record->status === 'absent')
                                                <span class="badge bg-danger">Absent</span>
                                            @elseif($record->status === 'late')
                                                <span class="badge bg-warning">Late</span>
                                            @elseif($record->status === 'leave')
                                                <span class="badge bg-info">Leave</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->check_in_time)
                                                {{ \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->check_out_time)
                                                {{ \Carbon\Carbon::parse($record->check_out_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->remarks)
                                                {{ $record->remarks }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No attendance records found for the selected period.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.attendance.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Attendance
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-info" onclick="exportToPDF()">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="exportToExcel()">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
    }

    @media print {
        .btn, .card-header, .card-footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Attendance Chart
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Present', 'Absent', 'Late', 'Leave'],
            datasets: [{
                data: [
                    {{ $report['present_days'] }},
                    {{ $report['absent_days'] }},
                    {{ $report['late_days'] }},
                    {{ $report['leave_days'] }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#dc3545',
                    '#ffc107',
                    '#17a2b8'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Print Report
    function printReport() {
        window.print();
    }

    // Export to PDF (placeholder)
    function exportToPDF() {
        alert('PDF export functionality would be implemented here.');
    }

    // Export to Excel (placeholder)
    function exportToExcel() {
        alert('Excel export functionality would be implemented here.');
    }
</script>
@endsection
