@extends('admin.layout.app')

@section('title', 'Bulk Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bulk Attendance Entry</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.attendance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Attendance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.accommodation.attendance.bulk-store') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" 
                                           value="{{ old('date', date('Y-m-d')) }}" required onchange="loadAllocations()">
                                    @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="default_status">Default Status</label>
                                    <select name="default_status" id="default_status" class="form-control" onchange="setDefaultStatus()">
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                        <option value="leave">Leave</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5>Student Attendance</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Hostel</th>
                                                <th>Room</th>
                                                <th>Status</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="attendanceTableBody">
                                            @foreach($allocations as $allocation)
                                            <tr>
                                                <td>
                                                    {{ $allocation->student->user->name ?? 'N/A' }}
                                                    <input type="hidden" name="attendances[{{ $loop->index }}][allocation_id]" value="{{ $allocation->id }}">
                                                </td>
                                                <td>{{ $allocation->hostel->name ?? 'N/A' }}</td>
                                                <td>{{ $allocation->room->room_no ?? 'N/A' }}</td>
                                                <td>
                                                    <select name="attendances[{{ $loop->index }}][status]" class="form-control form-control-sm" required onchange="toggleTimeFields({{ $loop->index }})">
                                                        <option value="present">Present</option>
                                                        <option value="absent">Absent</option>
                                                        <option value="late">Late</option>
                                                        <option value="leave">Leave</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="time" name="attendances[{{ $loop->index }}][check_in_time]" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input type="time" name="attendances[{{ $loop->index }}][check_out_time]" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input type="text" name="attendances[{{ $loop->index }}][remarks]" class="form-control form-control-sm" placeholder="Remarks">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Bulk Attendance
                            </button>
                            <button type="button" class="btn btn-warning" onclick="setAllStatus()">
                                <i class="fas fa-users"></i> Set All to Default
                            </button>
                            <a href="{{ route('admin.accommodation.attendance.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setDefaultStatus() {
    const defaultStatus = document.getElementById('default_status').value;
    const statusSelects = document.querySelectorAll('select[name*="[status]"]');
    
    statusSelects.forEach(select => {
        select.value = defaultStatus;
        const rowIndex = select.name.match(/\[(\d+)\]/)[1];
        toggleTimeFields(rowIndex);
    });
}

function setAllStatus() {
    setDefaultStatus();
}

function toggleTimeFields(rowIndex) {
    const statusSelect = document.querySelector(`select[name="attendances[${rowIndex}][status]"]`);
    const checkInField = document.querySelector(`input[name="attendances[${rowIndex}][check_in_time]"]`);
    const checkOutField = document.querySelector(`input[name="attendances[${rowIndex}][check_out_time]"]`);
    
    if (statusSelect.value === 'present' || statusSelect.value === 'late') {
        checkInField.disabled = false;
        checkOutField.disabled = false;
    } else {
        checkInField.disabled = true;
        checkOutField.disabled = true;
        checkInField.value = '';
        checkOutField.value = '';
    }
}

function loadAllocations() {
    // This function can be used to load allocations for a specific date
    // Implementation can be added if needed
}
</script>
@endsection
