@extends('admin.layout.app')

@section('title', 'Librarians')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Librarians</h4>
                    <a href="{{ route('admin.users.librarians.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New Librarian
                    </a>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <select id="filterStatus" class="form-select form-select-sm" style="max-width:160px;">
                            <option value="All">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <input type="text" id="globalSearch" class="form-control form-control-sm" placeholder="Search name/email" style="max-width:220px;">
                        <a href="{{ route('admin.users.librarians.export') }}" class="btn btn-success btn-sm"><i class="bx bx-download"></i> Export</a>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bx bx-upload"></i> Import</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dt-responsive" id="librarians-table">
                        <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importModalLabel">Import Librarians</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('admin.users.librarians.import') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Upload File (.xlsx, .csv)</label>
            <input type="file" name="file" accept=".xlsx,.csv" class="form-control" required>
          </div>
          <small class="text-muted">Expected columns: name, email, password(optional), status(optional)</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </form>
    </div>
  </div>
 </div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this librarian?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Password Reset Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="resetPasswordForm">
      @csrf
      <input type="hidden" name="user_id" id="reset_user_id">
      <input type="hidden" name="librarian_id" id="reset_librarian_id">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body">
          <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" name="password" id="new_password" required minlength="8">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Reset</button>
        </div>
      </div>
    </form>
  </div>
</div>

@if(session('success'))
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>


  setTimeout(() => {
    Toastify({
      text: "{{ session('success') }}",
      duration: 4000,
      gravity: "top",
      position: "right",
      backgroundColor: "#4caf50"
    }).showToast();
  }, 500);
</script>
@endif
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script>
    let table;

function librarianListTable(status = 'All') {
  if (table) {
    table.destroy();
  }

  table = $('#librarians-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    
    ajax: {
      url:"{{ route('admin.users.librarians.index') }}",
      type: 'GET',
      data: { status: status }
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
              {
                extend: 'csv',
                className: 'btn btn-success btn-sm rounded-pill',
                text: '<i class="fas fa-file-csv me-1"></i> CSV'
              },
              {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm rounded-pill',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF'
              },
              {
                extend: 'print',
                className: 'btn btn-warning btn-sm rounded-pill',
                text: '<i class="fas fa-print me-1"></i> Print'
                
              },
              {
                extend: 'copy',
                className: 'btn btn-info btn-sm rounded-pill',
                text: '<i class="fas fa-copy me-1"></i> Copy'
              }
            ],
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'name', name: 'name' },
      { data: 'email', name: 'email' },
      { data: 'status', name: 'status' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false }
    ]
  });
}

librarianListTable();

$('#filterStatus').on('change', function () {
    librarianListTable($(this).val());
});

$('#globalSearch').on('keyup', function () {
  table.search(this.value).draw();
});

$(function() {
        // Delete confirmation
        $(document).on('click', '.delete-accountant-btn', function() {
            const id = $(this).data('id');
            $('#deleteForm').attr('action', `{{ url('admin/users/libreaians') }}/${id}`);
            $('#deleteModal').modal('show');
        });
 });
$(document).ready(function ()
 {
     // Open reset modal
      $(document).on('click', '.reset-password-btn', function () {
            var accountantId = $(this).data('id');
            $('#reset_librarian_id').val(accountantId);
            $('#resetPasswordModal').modal('show');
        });

    // Handle reset password form
    $('#resetPasswordForm').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize();

          $.ajax({
              url: '{{ route("admin.users.librarians.resetPassword") }}',
              method: 'POST',
              data: formData,
              success: function (response) {
                  $('#resetPasswordModal').modal('hide');
                  alert(response.message);
              },
              error: function (xhr) {
                  alert(xhr.responseJSON.message || 'Something went wrong');
              }
          });
      });
  });
</script>
@endsection