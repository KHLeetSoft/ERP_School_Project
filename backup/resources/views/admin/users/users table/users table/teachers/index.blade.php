@extends('admin.layout.app')

@section('title', 'Teachers')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Teacher List</h4>
    <a href="{{ route('admin.users.teachers.create') }}" class="btn btn-primary btn-sm rounded-pill">
      <i class="fas fa-plus"></i> Add Teacher
    </a>
  </div>

  <div class="card-body">
    <!-- Filters -->
   
    <!-- DataTable -->
    <div class="table-responsive">
      <table id="teachers-datatable" class="table table-bordered table-striped">
      <thead class="table-dark">
        
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
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

  table = $('#teachers-datatable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url:"{{ route('admin.users.teachers.index') }}",
      type: 'GET',
      data: { status: status }
    },
     dom:
     '<"row mb-3 align-items-end"<"col-md-3"f><"col-md-2"l><"col-md-7 d-flex justify-content-end gap-2"B>>' +
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
      { data: 'action', name: 'action', orderable: false, searchable: false }
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
</script>
@endsection
