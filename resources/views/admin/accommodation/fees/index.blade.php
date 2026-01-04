@extends('admin.layout.app')

@section('title', 'Hostel Fees')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hostel Fees Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.fees.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Fee
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
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                                <option value="waived">Waived</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="monthFilter" onchange="filterTable()">
                                <option value="">All Months</option>
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="yearFilter" onchange="filterTable()">
                                <option value="">All Years</option>
                                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success" onclick="markSelectedAsPaid()">
                                <i class="fas fa-check"></i> Mark as Paid
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="feesTable">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Student</th>
                                    <th>Hostel</th>
                                    <th>Month/Year</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fees as $fee)
                                <tr data-hostel="{{ $fee->allocation->hostel->name ?? '' }}" 
                                    data-status="{{ $fee->status }}" 
                                    data-month="{{ $fee->month }}" 
                                    data-year="{{ $fee->year }}">
                                    <td>
                                        <input type="checkbox" class="fee-checkbox" value="{{ $fee->id }}">
                                    </td>
                                    <td>{{ $fee->allocation->student->user->name ?? 'N/A' }}</td>
                                    <td>{{ $fee->allocation->hostel->name ?? 'N/A' }}</td>
                                    <td>{{ $fee->getMonthName() }} {{ $fee->year }}</td>
                                    <td>₹{{ number_format($fee->amount, 2) }}</td>
                                    <td>{{ $fee->due_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $fee->status == 'paid' ? 'success' : ($fee->status == 'overdue' ? 'danger' : ($fee->status == 'waived' ? 'info' : 'warning')) }}">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $fee->payment_date ? $fee->payment_date->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.accommodation.fees.show', $fee->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.accommodation.fees.edit', $fee->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($fee->status == 'pending')
                                        <button class="btn btn-sm btn-success" onclick="markAsPaid({{ $fee->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                        <button class="btn btn-sm btn-danger" onclick="deleteFee({{ $fee->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No fees found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($fees->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $fees->links() }}
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
    const monthFilter = document.getElementById('monthFilter').value;
    const yearFilter = document.getElementById('yearFilter').value;
    
    const rows = document.querySelectorAll('#feesTable tbody tr');
    
    rows.forEach(row => {
        const hostelMatch = !hostelFilter || row.dataset.hostel === hostelFilter;
        const statusMatch = !statusFilter || row.dataset.status === statusFilter;
        const monthMatch = !monthFilter || row.dataset.month === monthFilter;
        const yearMatch = !yearFilter || row.dataset.year === yearFilter;
        
        if (hostelMatch && statusMatch && monthMatch && yearMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function clearFilters() {
    document.getElementById('hostelFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('monthFilter').value = '';
    document.getElementById('yearFilter').value = '';
    filterTable();
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.fee-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function markSelectedAsPaid() {
    const selectedFees = Array.from(document.querySelectorAll('.fee-checkbox:checked')).map(cb => cb.value);
    
    if (selectedFees.length === 0) {
        alert('Please select fees to mark as paid');
        return;
    }
    
    if (confirm(`Are you sure you want to mark ${selectedFees.length} fees as paid?`)) {
        // Implementation for bulk mark as paid
        alert('Bulk mark as paid functionality will be implemented soon');
    }
}

function markAsPaid(id) {
    if (confirm('Are you sure you want to mark this fee as paid?')) {
        fetch(`/admin/accommodation/fees/${id}/mark-paid`, {
            method: 'POST',
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
                alert(data.message || 'Error marking fee as paid');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error marking fee as paid');
        });
    }
}

function deleteFee(id) {
    if (confirm('Are you sure you want to delete this fee?')) {
        fetch(`/admin/accommodation/fees/${id}`, {
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
                alert(data.message || 'Error deleting fee');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting fee');
        });
    }
}
</script>
@endsection
