@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Substitution Management</h4>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Substitutions List</span>
       <div class="btn-group" role="group">
            <a href="{{ route('admin.academic.substitution.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add Substitutions
            </a>
            <a href="{{ route('admin.academic.substitution.export', request()->only('q','status')) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-export me-1"></i> Export CSV
            </a>
            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import me-1"></i> Import CSV
            </button>
        </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="substitutionTable" class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>ID</th>
                            <th>Teacher</th>
                            <th>Substitute</th>
                            <th>Date</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables Ajax -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle"></i> Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this substitution?
      </div>
      <div class="modal-footer">
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.academic.substitution.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
        @csrf
        <div class="modal-header bg-secondary text-white">
            <h5 class="modal-title" id="importModalLabel"><i class="bi bi-upload"></i> Import Substitutions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="importFile" class="form-label">Choose File (CSV/Excel)</label>
                <input type="file" class="form-control" id="importFile" name="import_file" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-secondary">Upload</button>
        </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    let table = $('#substitutionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.academic.substitution.index') }}",
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
            { data: null, orderable: false, searchable: false, render: function(data, type, row) {
                return '<input type="checkbox" class="form-check-input bulk-select" value="' + row.id + '">';
            }},
            { data: 'id', name: 'id' },
            { data: 'teacher_name', name: 'teacher_name' },
            { data: 'substitute_name', name: 'substitute_name' },
            { data: 'date', name: 'date' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'status', name: 'status', orderable: false, searchable: false, render: function(data, type, row) {
                let btnClass = row.status == 1 ? 'btn-success' : 'btn-secondary';
                let btnText = row.status == 1 ? 'Active' : 'Inactive';
                return '<button type="button" class="btn btn-sm ' + btnClass + ' toggle-status-btn" data-id="' + row.id + '">' + btnText + '</button>';
            }},
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: "text-center" }
        ]
   
});
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Handle delete confirmation
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var url = button.data('url'); // Extract info from data-* attributes
        var modal = $(this);
        modal.find('#deleteForm').attr('action', url);
    });
     // Select all checkboxes
    $('#select-all').on('click', function() {
        $('.bulk-select').prop('checked', this.checked);
    });

    // Toggle status button
    $(document).on('click', '.toggle-status-btn', function() {
        let id = $(this).data('id');
        let btn = $(this);
        $.post({
            url: '/admin/academic/substitution/' + id + '/toggle-status',
            data: {_token: '{{ csrf_token() }}'},
            success: function(res) {
                if(res.status == 1) {
                    btn.removeClass('btn-secondary').addClass('btn-success').text('Active');
                } else {
                    btn.removeClass('btn-success').addClass('btn-secondary').text('Inactive');
                }
            }
        });
    });

    // Bulk status update (example, you can add a button to trigger this)
    // $(document).on('click', '#bulk-status-btn', function() {
    //     let ids = $('.bulk-select:checked').map(function(){ return $(this).val(); }).get();
    //     let status = 1; // or 0
    //     $.post({
    //         url: '/admin/academic/substitution/bulk-status',
    //         data: {_token: '{{ csrf_token() }}', ids: ids, status: status},
    //         success: function(res) { table.ajax.reload(); }
    //     });
    // });
    });

    // delete button click
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let url = $(this).data('url');
        $('#deleteForm').attr('action', url);
        $('#deleteModal').modal('show');
    });
});
</script>
@endsection
