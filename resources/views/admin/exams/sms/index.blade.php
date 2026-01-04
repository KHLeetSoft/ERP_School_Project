@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0"><i class="bx bx-message-square-dots me-2 text-primary"></i> Exam SMS</h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.exams.sms.dashboard') }}" class="btn btn-info btn-sm"><i class="bx bx-bar-chart"></i> Dashboard</a>
                <a href="{{ route('admin.exams.sms.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Create</a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="p-3">
            <div class="table-responsive">
                <table id="smsTable" class="table table-striped align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Exam</th>
                            <th>Audience</th>
                            <th>Class/Section</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Sent/Failed</th>
                            <th>Actions</th>
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
    const table = $('#smsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.exams.sms.index') }}" },
        dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +
                '<"col-md-6 text-end"f>' +
            '>' +
            '<"row mb-3"<"col-12 text-end"B>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            { extend: 'csv', className: 'btn btn-success btn-sm rounded-pill', text: '<i class="fas fa-file-csv me-1"></i> CSV' },
            { extend: 'pdf', className: 'btn btn-danger btn-sm rounded-pill', text: '<i class="fas fa-file-pdf me-1"></i> PDF' },
            { extend: 'print', className: 'btn btn-warning btn-sm rounded-pill', text: '<i class="fas fa-print me-1"></i> Print' },
            { extend: 'copy', className: 'btn btn-info btn-sm rounded-pill', text: '<i class="fas fa-copy me-1"></i> Copy' }
        ],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false},
            {data: 'title', name: 'title'},
            {data: 'exam_title', name: 'exam_title'},
            {data: 'audience_type', name: 'audience_type', render:(d)=> d?d.charAt(0).toUpperCase()+d.slice(1):''},
            {data: null, render:(d)=> (d.class_name||'-')+' '+(d.section_name||'')},
            {data: 'schedule_at', name: 'schedule_at'},
            {data: 'status', name: 'status', render:(d)=> {
                const map={draft:'secondary',scheduled:'warning',sent:'success'}; return `<span class="badge bg-${map[d]||'secondary'} text-uppercase">${d}</span>`;
            }},
            {data: null, render:(d)=> `${d.sent_count||0}/${d.failed_count||0}`},
            {data: 'actions', name: 'actions', orderable:false, searchable:false}
        ]
    });

    $(document).on('click', '.delete-sms-btn', function() {
        let action = $(this).data('action');
        if(confirm('Delete this SMS campaign?')) {
            $.post(action, {_method:'DELETE', _token:'{{ csrf_token() }}'}, function(){
                table.ajax.reload();
            });
        }
    });
});
</script>
@endsection


