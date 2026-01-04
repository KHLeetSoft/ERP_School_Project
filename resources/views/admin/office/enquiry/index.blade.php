@extends('admin.layout.app')

@section('title', 'Admission Enquiries')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Admission Enquiries</h4>
    <a href="{{ route('admin.office.enquiry.create') }}" class="btn btn-primary btn-sm rounded-pill">
      <i class="fas fa-plus"></i> Add Enquiry
    </a>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="filterStatus" class="form-select form-select-sm">
          <option value="">All Status</option>
          <option value="New">New</option>
          <option value="In Progress">In Progress</option>
          <option value="Converted">Converted</option>
          <option value="Closed">Closed</option>
        </select>
      </div>
      <div class="col-md-3">
        <input type="text" id="globalSearch" class="form-control form-control-sm" placeholder="Search...">
      </div>
    </div>
    <div class="table-responsive">
      <table id="enquiry-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Parent</th>
            <th>Class</th>
            <th>Status</th>
            <th>Received</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
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
                <p>Are you sure you want to delete this enquiry?</p>
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
<script>
$(document).ready(function () {

    let table;
    function loadTable(status = '') {
        if (table) table.destroy();

        table = $('#enquiry-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('admin.office.enquiry.index') }}",
                data: { status: status },
                error: function(xhr, error, thrown) {
                    console.error('DataTables AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                    alert('Error loading data: ' + error);
                }
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
                { data: 'student_name', name: 'student_name' },
                { data: 'parent_name', name: 'parent_name' },
                { data: 'class', name: 'class' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    loadTable();

    $('#filterStatus').on('change', function () {
        loadTable(this.value);
    });

    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });
    $(function() {
        // Delete confirmation
        $(document).on('click', '.delete-enquiry-btn', function() {
            const id = $(this).data('id');
            $('#deleteForm').attr('action', `{{ url('admin/office/enquiry') }}/${id}`);
            $('#deleteModal').modal('show');
        });
    });
});
</script>
@endsection 