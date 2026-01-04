@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="card shadow-sm mb-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-book-open me-2 text-primary"></i> Syllabus Management
        </h4>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.academic.syllabus.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add Syllabus
            </a>
            <a href="{{ route('admin.academic.syllabus.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-export me-1"></i> Export CSV
            </a>
            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import me-1"></i> Import CSV
            </button>
            <button class="btn btn-outline-danger btn-sm" type="submit" form="bulk-form" onclick="return confirm('Delete selected syllabuses?')">
                <i class="fas fa-trash-alt me-1"></i> Bulk Delete
            </button>
        </div>
    </div>
</div>


{{-- Custom Styling --}}
<style>
    .icon-circle {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>


    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">{{ $errors->first() }}</div>
    @endif

    {{-- Data Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.academic.syllabus.bulk-delete') }}" id="bulk-form">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="syllabus-table">
                        <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="select-all" /></th>
                                <th>Subject</th>
                                <th>Term</th>
                                <th>Title</th>
                                <th>Total Units</th>
                                <th>Completed</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button class="btn btn-outline-danger btn-sm rounded-pill" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash-alt me-1"></i> Bulk Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.academic.syllabus.import') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-import me-2"></i> Import Syllabus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="file" name="file" class="form-control" accept=".csv" required>
                <small class="text-muted">Only CSV files allowed.</small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm rounded-pill" type="submit">Import</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Confirm Bulk Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the selected syllabus records? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-sm rounded-pill" type="submit" form="bulk-form">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
let table;
function loadTable() {
    if (table) table.destroy();
    table = $('#syllabus-table').DataTable({
        processing: true,
        serverSide: true,
        order: [[3, 'asc']],
        ajax: {
            url: "{{ route('admin.academic.syllabus.index') }}",
            type: 'GET'
        },
           dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +             // Left side: Show
                '<"col-md-6 text-end"f>' +    // Right side: Search
            '>' +
            '<"row mb-3"' +
                '<"col-12 text-end"B>' +      // Next line: Buttons full width right aligned
            '>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            { extend: 'csv', className: 'btn btn-success btn-sm rounded-pill', text: '<i class="fas fa-file-csv me-1"></i> CSV' },
            { extend: 'pdf', className: 'btn btn-danger btn-sm rounded-pill', text: '<i class="fas fa-file-pdf me-1"></i> PDF' },
            { extend: 'print', className: 'btn btn-warning btn-sm rounded-pill', text: '<i class="fas fa-print me-1"></i> Print' },
            { extend: 'copy', className: 'btn btn-info btn-sm rounded-pill', text: '<i class="fas fa-copy me-1"></i> Copy' }
        ],
        columns: [
            { data: 'select', orderable: false, searchable: false },
            { data: 'subject', name: 'subject' },
            { data: 'term', name: 'term' },
            { data: 'title', name: 'title' },
            { data: 'total_units', name: 'total_units' },
            { data: 'completed_units', name: 'completed_units' },
            { data: 'progress', orderable: false, searchable: false },
            { data: 'status', name: 'status' },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' },
        ]
    });
}

document.getElementById('select-all')?.addEventListener('change', function(e) {
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = e.target.checked);
});

$(document).ready(loadTable);
</script>
@endsection
