@extends('admin.layout.app')

@section('title', 'Parents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Parents</h4>
                    <a href="{{ route('admin.users.parents.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add New Parent
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dt-responsive" id="parents-table">
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this parent?</p>
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
       <input type="hidden" name="parent_id" id="reset_parent_id">

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

function teacherListTable(status = 'All') {
  if (table) {
    table.destroy();
  }

  table = $('#parents-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    
    ajax: {
      url:"{{ route('admin.users.parents.index') }}",
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

teacherListTable();

$('#filterStatus').on('change', function () {
    teacherListTable($(this).val());
});

$('#globalSearch').on('keyup', function () {
  table.search(this.value).draw();
});
    $(function() {
        // Delete confirmation
        $(document).on('click', '.delete-parent-btn', function() {
            const id = $(this).data('id');
            $('#deleteForm').attr('action', `{{ url('admin/users/parents') }}/${id}`);
            $('#deleteModal').modal('show');
        });
    });
    $(document).ready(function () {
     // Open reset modal
      $(document).on('click', '.reset-password-btn', function () {
            var accountantId = $(this).data('id');
            $('#reset_parent_id').val(accountantId);
            $('#resetPasswordModal').modal('show');
        });

    // Handle reset password form
    $('#resetPasswordForm').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize();

          $.ajax({
              url: '{{ route("admin.users.accountants.resetPassword") }}',
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