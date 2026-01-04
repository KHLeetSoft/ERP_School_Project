@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-people me-2 text-primary"></i> Recipients - {{ $sms->title }}
            </h4>
            <small class="text-muted">List of all recipients for this SMS campaign</small>
        </div>
        <div>
            <a href="{{ route('admin.exams.sms.index') }}" class="btn btn-secondary btn-sm me-2">
                <i class="bi bi-list-ul"></i> All Campaigns
            </a>
            <a href="{{ route('admin.exams.sms.show', $sms->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filter by Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All</option>
                        <option value="sent">Sent</option>
                        <option value="failed">Failed</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filter by Type</label>
                    <select id="filterType" class="form-select">
                        <option value="">All</option>
                        <option value="student">Student</option>
                        <option value="parent">Parent</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Recipients Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-table me-2"></i> Recipients List
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="recipientsTable" class="table table-striped table-hover align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Recipient</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Error</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function(){
    const table = $('#recipientsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.exams.sms.recipients.index', $sms->id) }}',
            data: function(d){
                d.status = $('#filterStatus').val();
                d.type = $('#filterType').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'recipient_type', name: 'recipient_type',
                render: function(data){
                    return data === 'student' 
                        ? '<span class="badge bg-info">Student</span>'
                        : '<span class="badge bg-success">Parent</span>';
                }
            },
            { data: 'recipient_id', name: 'recipient_id' },
            { data: 'phone', name: 'phone' },
            { data: 'status', name: 'status',
                render: function(data){
                    if(data === 'sent') return '<span class="badge bg-primary">Sent</span>';
                    if(data === 'failed') return '<span class="badge bg-danger">Failed</span>';
                    return '<span class="badge bg-warning text-dark">Pending</span>';
                }
            },
            { data: 'sent_at', name: 'sent_at' },
            { data: 'error', name: 'error' },
        ]
    });

    $('#filterStatus, #filterType').on('change', function(){
        table.ajax.reload();
    });
});
</script>
@endsection
