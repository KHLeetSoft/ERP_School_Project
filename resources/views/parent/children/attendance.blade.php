@extends('parent.layout.app')

@section('title', $child->first_name . ' - Attendance')

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $child->first_name }} {{ $child->last_name }} - Attendance</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('parent.children') }}">My Children</a></li>
            <li class="breadcrumb-item"><a href="{{ route('parent.children.show', $child) }}">{{ $child->first_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Attendance</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Attendance Overview -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-calendar-check me-2"></i>Attendance Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stats-number">92%</div>
                            <div class="stats-label">Attendance</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="stats-number">180</div>
                            <div class="stats-label">Present Days</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="stats-number">15</div>
                            <div class="stats-label">Absent Days</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stats-number">3</div>
                            <div class="stats-label">Late Days</div>
                        </div>
                    </div>
                </div>
                
                <!-- Monthly Attendance Chart -->
                <h6 class="mb-3">Monthly Attendance (Last 6 Months)</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Late</th>
                                <th>Percentage</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>December 2024</td>
                                <td>18</td>
                                <td>2</td>
                                <td>0</td>
                                <td>90%</td>
                                <td><span class="badge badge-success">Good</span></td>
                            </tr>
                            <tr>
                                <td>November 2024</td>
                                <td>20</td>
                                <td>1</td>
                                <td>1</td>
                                <td>95%</td>
                                <td><span class="badge badge-success">Excellent</span></td>
                            </tr>
                            <tr>
                                <td>October 2024</td>
                                <td>19</td>
                                <td>3</td>
                                <td>0</td>
                                <td>86%</td>
                                <td><span class="badge badge-info">Good</span></td>
                            </tr>
                            <tr>
                                <td>September 2024</td>
                                <td>22</td>
                                <td>1</td>
                                <td>1</td>
                                <td>96%</td>
                                <td><span class="badge badge-success">Excellent</span></td>
                            </tr>
                            <tr>
                                <td>August 2024</td>
                                <td>21</td>
                                <td>2</td>
                                <td>1</td>
                                <td>91%</td>
                                <td><span class="badge badge-success">Good</span></td>
                            </tr>
                            <tr>
                                <td>July 2024</td>
                                <td>20</td>
                                <td>3</td>
                                <td>0</td>
                                <td>87%</td>
                                <td><span class="badge badge-info">Good</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent Attendance -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-history me-2"></i>Recent Attendance (Last 10 Days)
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
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
                            <tr>
                                <td>Dec 15, 2024</td>
                                <td>Monday</td>
                                <td><span class="badge badge-success">Present</span></td>
                                <td>08:30 AM</td>
                                <td>03:30 PM</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Dec 14, 2024</td>
                                <td>Sunday</td>
                                <td><span class="badge badge-secondary">Holiday</span></td>
                                <td>-</td>
                                <td>-</td>
                                <td>Weekend</td>
                            </tr>
                            <tr>
                                <td>Dec 13, 2024</td>
                                <td>Saturday</td>
                                <td><span class="badge badge-secondary">Holiday</span></td>
                                <td>-</td>
                                <td>-</td>
                                <td>Weekend</td>
                            </tr>
                            <tr>
                                <td>Dec 12, 2024</td>
                                <td>Friday</td>
                                <td><span class="badge badge-success">Present</span></td>
                                <td>08:25 AM</td>
                                <td>03:35 PM</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Dec 11, 2024</td>
                                <td>Thursday</td>
                                <td><span class="badge badge-warning">Late</span></td>
                                <td>09:15 AM</td>
                                <td>03:30 PM</td>
                                <td>Late arrival</td>
                            </tr>
                            <tr>
                                <td>Dec 10, 2024</td>
                                <td>Wednesday</td>
                                <td><span class="badge badge-success">Present</span></td>
                                <td>08:20 AM</td>
                                <td>03:25 PM</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Dec 9, 2024</td>
                                <td>Tuesday</td>
                                <td><span class="badge badge-danger">Absent</span></td>
                                <td>-</td>
                                <td>-</td>
                                <td>Sick leave</td>
                            </tr>
                            <tr>
                                <td>Dec 8, 2024</td>
                                <td>Monday</td>
                                <td><span class="badge badge-success">Present</span></td>
                                <td>08:35 AM</td>
                                <td>03:40 PM</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Dec 7, 2024</td>
                                <td>Sunday</td>
                                <td><span class="badge badge-secondary">Holiday</span></td>
                                <td>-</td>
                                <td>-</td>
                                <td>Weekend</td>
                            </tr>
                            <tr>
                                <td>Dec 6, 2024</td>
                                <td>Saturday</td>
                                <td><span class="badge badge-secondary">Holiday</span></td>
                                <td>-</td>
                                <td>-</td>
                                <td>Weekend</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Attendance Summary -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie me-2"></i>Attendance Summary
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="progress-circle mx-auto mb-3" style="width: 150px; height: 150px;">
                        <div class="progress-circle-inner d-flex align-items-center justify-content-center">
                            <div>
                                <div class="h3 mb-0">92%</div>
                                <small class="text-muted">Attendance</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="border-end">
                            <div class="h5 text-success mb-1">180</div>
                            <small class="text-muted">Present</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="h5 text-danger mb-1">15</div>
                        <small class="text-muted">Absent</small>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <div class="h5 text-warning mb-1">3</div>
                            <small class="text-muted">Late</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="h5 text-info mb-1">195</div>
                        <small class="text-muted">Total Days</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attendance Legend -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>Status Legend
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-success me-2">Present</span>
                    <small>Student attended the full day</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-danger me-2">Absent</span>
                    <small>Student was not present</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-warning me-2">Late</span>
                    <small>Student arrived after start time</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-secondary me-2">Holiday</span>
                    <small>No school on this day</small>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i>Download Report
                    </a>
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-envelope me-2"></i>Contact Teacher
                    </a>
                    <a href="{{ route('parent.children.show', $child) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress-circle {
        position: relative;
        border-radius: 50%;
        background: conic-gradient(#2ecc71 0deg 331deg, #e9ecef 331deg 360deg);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .progress-circle-inner {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 50%;
    }
</style>
@endpush
