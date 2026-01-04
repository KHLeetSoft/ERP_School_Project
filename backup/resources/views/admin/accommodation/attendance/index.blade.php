@extends('admin.layout.app')

@section('title', 'Hostel Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hostel Attendance Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.attendance.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Attendance
                        </a>
                        <a href="{{ route('admin.accommodation.attendance.bulk-create') }}" class="btn btn-success">
                            <i class="fas fa-users"></i> Bulk Attendance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <select class="form-control" id="hostelFilter" onchange="filterTable()">
                                <option value="">All Hostels</option>
                                @foreach($allocations->groupBy('hostel.name') as $hostelName => $allocationGroup)
                                <option value="{{ $hostelName }}">{{ $hostelName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="statusFilter" onchange="filterTable()">
                                <option value="">All Status</option>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="leave">Leave</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="dateFilter" onchange="filterTable()" placeholder="Filter by Date">
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="studentFilter" onchange="filterTable()">
                                <option value="">All Students</option>
                                @foreach($allocations as $allocation)
                                <option value="{{ $allocation->student_id }}">{{ $allocation->student->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info" onclick="exportAttendance()">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Hostel</th>
                                    <th>Room</th>
                                    <th>Status</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr data-hostel="{{ $attendance->allocation->hostel->name ?? '' }}" 
                                    data-status="{{ $attendance->status }}" 
                                    data-date="{{ $attendance->date->format('Y-m-d') }}" 
                                    data-student="{{ $attendance->allocation->student_id }}">
                                    <td>{{ $attendance->date->format('d M Y') }}</td>
                                    <td>{{ $attendance->allocation->student->user->name ?? 'N/A' }}</td>
                                    <td>{{ $attendance->allocation->hostel->name ?? 'N/A' }}</td>
                                    <td>{{ $attendance->allocation->room->room_no ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->getStatusBadgeClass() }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('h:i A') : 'N/A' }}</td>
                                    <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('h:i A') : 'N/A' }}</td>
                                    <td>{{ $attendance->getDuration() ? $attendance->getDuration() . ' hours' : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.accommodation.attendance.show', $attendance->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.accommodation.attendance.edit', $attendance->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="deleteAttendance({{ $attendance->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No attendance records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($attendances->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $attendances->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterTable() {
    const hostelFilter = document.getElementById('hostelFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const studentFilter = document.getElementById('studentFilter').value;
    
    const rows = document.querySelectorAll('#attendanceTable tbody tr');
    
    rows.forEach(row => {
        const hostelMatch = !hostelFilter || row.dataset.hostel === hostelFilter;
        const statusMatch = !statusFilter || row.dataset.status === statusFilter;
        const dateMatch = !dateFilter || row.dataset.date === dateFilter;
        const studentMatch = !studentFilter || row.dataset.student === studentFilter;
        
        if (hostelMatch && statusMatch && dateMatch && studentMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function clearFilters() {
    document.getElementById('hostelFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    document.getElementById('studentFilter').value = '';
    filterTable();
}

function exportAttendance() {
    // Implementation for export functionality
    alert('Export functionality will be implemented soon');
}

function deleteAttendance(id) {
    if (confirm('Are you sure you want to delete this attendance record?')) {
        fetch(`/admin/accommodation/attendance/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error deleting attendance record');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting attendance record');
        });
    }
}
</script>
@endsection
