@extends('admin.layout.app')

@section('title', 'Attendance Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Attendance Dashboard</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.attendance.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> View All Attendance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalAttendance }}</h3>
                                    <p>Total Records</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $presentCount }}</h3>
                                    <p>Present</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $absentCount }}</h3>
                                    <p>Absent</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $lateCount }}</h3>
                                    <p>Late</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Summary -->
                    <div class="row mt-4">
                        <div class="col-lg-4 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-calendar-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Leave Records</span>
                                    <span class="info-box-number">{{ $leaveCount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Attendance Rate</span>
                                    <span class="info-box-number">
                                        {{ $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Absentee Rate</span>
                                    <span class="info-box-number">
                                        {{ $totalAttendance > 0 ? round(($absentCount / $totalAttendance) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Attendance Chart -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Monthly Attendance Overview (Last 12 Months)</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Month/Year</th>
                                                    <th>Total Records</th>
                                                    <th>Present</th>
                                                    <th>Absent</th>
                                                    <th>Late</th>
                                                    <th>Leave</th>
                                                    <th>Attendance Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($attendanceByMonth as $monthData)
                                                <tr>
                                                    <td>{{ date('F', mktime(0, 0, 0, $monthData->month, 1)) }} {{ $monthData->year }}</td>
                                                    <td>{{ $monthData->total }}</td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            {{ $monthData->present_count ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-danger">
                                                            {{ $monthData->absent_count ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">
                                                            {{ $monthData->late_count ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ $monthData->leave_count ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $attendanceRate = $monthData->total > 0 ? round((($monthData->present_count ?? 0) / $monthData->total) * 100, 1) : 0;
                                                        @endphp
                                                        <span class="badge badge-{{ $attendanceRate > 80 ? 'success' : ($attendanceRate > 60 ? 'warning' : 'danger') }}">
                                                            {{ $attendanceRate }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No attendance data available</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance by Hostel -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Attendance by Hostel</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Hostel</th>
                                                    <th>Total Records</th>
                                                    <th>Present</th>
                                                    <th>Absent</th>
                                                    <th>Attendance Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($attendanceByHostel as $hostelData)
                                                <tr>
                                                    <td>{{ $hostelData->hostel_name ?? 'Unknown Hostel' }}</td>
                                                    <td>{{ $hostelData->total }}</td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            {{ $hostelData->present_count ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-danger">
                                                            {{ $hostelData->absent_count ?? 0 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $hostelAttendanceRate = $hostelData->total > 0 ? round((($hostelData->present_count ?? 0) / $hostelData->total) * 100, 1) : 0;
                                                        @endphp
                                                        <span class="badge badge-{{ $hostelAttendanceRate > 80 ? 'success' : ($hostelAttendanceRate > 60 ? 'warning' : 'danger') }}">
                                                            {{ $hostelAttendanceRate }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No hostel data available</td>
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
                                            <a href="{{ route('admin.accommodation.attendance.create') }}" class="btn btn-primary btn-block">
                                                <i class="fas fa-plus"></i> New Attendance
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.attendance.bulk-create') }}" class="btn btn-success btn-block">
                                                <i class="fas fa-users"></i> Bulk Attendance
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.accommodation.attendance.index') }}" class="btn btn-info btn-block">
                                                <i class="fas fa-list"></i> View All Records
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-warning btn-block" onclick="generateReport()">
                                                <i class="fas fa-chart-bar"></i> Generate Report
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
function generateReport() {
    // Implementation for report generation
    alert('Report generation functionality will be implemented soon');
}
</script>
@endsection
