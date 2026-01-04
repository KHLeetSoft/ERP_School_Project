@extends('admin.layout.app')

@section('title', 'Attendance Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Attendance Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.attendance.edit', $attendance->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.accommodation.attendance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Attendance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Student Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>{{ $attendance->allocation->student->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Admission No:</th>
                                    <td>{{ $attendance->allocation->student->admission_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Class:</th>
                                    <td>{{ $attendance->allocation->student->class ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Section:</th>
                                    <td>{{ $attendance->allocation->student->section ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Accommodation Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Hostel:</th>
                                    <td>{{ $attendance->allocation->hostel->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Room:</th>
                                    <td>{{ $attendance->allocation->room->room_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Bed No:</th>
                                    <td>{{ $attendance->allocation->bed_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Allocation Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $attendance->allocation->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($attendance->allocation->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Attendance Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Date:</th>
                                    <td>{{ $attendance->date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $attendance->getStatusBadgeClass() }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Check In Time:</th>
                                    <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('h:i A') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Check Out Time:</th>
                                    <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('h:i A') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Additional Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Duration:</th>
                                    <td>{{ $attendance->getDuration() ? $attendance->getDuration() . ' hours' : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $attendance->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated:</th>
                                    <td>{{ $attendance->updated_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Recorded By:</th>
                                    <td>{{ $attendance->created_by ? $attendance->createdBy->name : 'System' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($attendance->remarks)
                    <div class="row">
                        <div class="col-12">
                            <h5>Remarks:</h5>
                            <p class="text-muted">{{ $attendance->remarks }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Student's Recent Attendance -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Recent Attendance for {{ $attendance->allocation->student->user->name ?? 'Student' }}</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendance->allocation->attendances->take(10) as $recentAttendance)
                                        <tr class="{{ $recentAttendance->id == $attendance->id ? 'table-info' : '' }}">
                                            <td>{{ $recentAttendance->date->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $recentAttendance->getStatusBadgeClass() }}">
                                                    {{ ucfirst($recentAttendance->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $recentAttendance->check_in_time ? $recentAttendance->check_in_time->format('h:i A') : 'N/A' }}</td>
                                            <td>{{ $recentAttendance->check_out_time ? $recentAttendance->check_out_time->format('h:i A') : 'N/A' }}</td>
                                            <td>{{ $recentAttendance->getDuration() ? $recentAttendance->getDuration() . ' hours' : 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
