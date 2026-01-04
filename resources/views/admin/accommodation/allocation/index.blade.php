@extends('admin.layout.app')

@section('title', 'Hostel Allocations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hostel Allocations Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.allocation.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Allocation
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="hostelFilter" onchange="filterTable()">
                                <option value="">All Hostels</option>
                                @foreach($hostels as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter" onchange="filterTable()">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="left">Left</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="studentFilter" onchange="filterTable()">
                                <option value="">All Students</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->user->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-secondary" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Clear Filters
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="allocationsTable">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Hostel</th>
                                    <th>Room</th>
                                    <th>Bed No</th>
                                    <th>Join Date</th>
                                    <th>Leave Date</th>
                                    <th>Status</th>
                                    <th>Monthly Fee</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocations as $allocation)
                                <tr data-hostel="{{ $allocation->hostel_id }}" data-status="{{ $allocation->status }}" data-student="{{ $allocation->student_id }}">
                                    <td>{{ $allocation->student->user->name ?? 'N/A' }}</td>
                                    <td>{{ $allocation->hostel->name ?? 'N/A' }}</td>
                                    <td>{{ $allocation->room->room_no ?? 'N/A' }}</td>
                                    <td>{{ $allocation->bed_no ?? 'N/A' }}</td>
                                    <td>{{ $allocation->join_date ? $allocation->join_date->format('d M Y') : 'N/A' }}</td>
                                    <td>{{ $allocation->leave_date ? $allocation->leave_date->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $allocation->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($allocation->status) }}
                                        </span>
                                    </td>
                                    <td>₹{{ number_format($allocation->monthly_fee, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.accommodation.allocation.show', $allocation->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.accommodation.allocation.edit', $allocation->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="deleteAllocation({{ $allocation->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No allocations found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($allocations->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $allocations->links() }}
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
    const studentFilter = document.getElementById('studentFilter').value;
    
    const rows = document.querySelectorAll('#allocationsTable tbody tr');
    
    rows.forEach(row => {
        const hostelMatch = !hostelFilter || row.dataset.hostel === hostelFilter;
        const statusMatch = !statusFilter || row.dataset.status === statusFilter;
        const studentMatch = !studentFilter || row.dataset.student === studentFilter;
        
        if (hostelMatch && statusMatch && studentMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function clearFilters() {
    document.getElementById('hostelFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('studentFilter').value = '';
    filterTable();
}

function deleteAllocation(id) {
    if (confirm('Are you sure you want to delete this allocation?')) {
        fetch(`/admin/accommodation/allocation/${id}`, {
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
                alert(data.message || 'Error deleting allocation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting allocation');
        });
    }
}
</script>
@endsection
