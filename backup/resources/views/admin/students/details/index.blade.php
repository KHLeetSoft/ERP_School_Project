@extends('admin.layout.app')

@section('title','Student Details')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Student Details</h4>
    <div class="btn-group" role="group">
      <a href="{{ route('admin.students.details.export') }}" class="btn btn-success btn-sm rounded-pill"><i class="bx bx-download"></i> Export</a>
      <button class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bx bx-upload"></i> Import</button>
      <a href="{{ route('admin.students.details.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="fas fa-plus"></i> Add Student</a>
    </div>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="filterClass" class="form-select form-select-sm">
          <option value="">All Classes</option>
          {{-- @foreach($classes as $class) --}}
          <option value="1">Class 1</option>
          <option value="2">Class 2</option>
          {{-- @endforeach --}}
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterSection" class="form-select form-select-sm">
          <option value="">All Sections</option>
          <option value="A">A</option>
          <option value="B">B</option>
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterStatus" class="form-select form-select-sm">
          <option value="">All Status</option>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>
      <div class="col-md-3">
        <button id="applyStudentFilter" class="btn btn-sm btn-primary w-100">Apply</button>
      </div>
    </div>

    <div class="table-responsive">
      <table id="students-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Admission No</th>
            <th>Roll No</th>
            <th>Class</th>
            <th>Section</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">Are you sure you want to delete this student?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="studentDeleteForm" method="POST" action="">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Import Students</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="{{ route('admin.students.details.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
        </div>
        <div class="modal-footer"><button class="btn btn-primary">Upload</button></div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
let table;
function loadStudentTable() {
  if (table) table.destroy();
  table = $('#students-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: "{{ route('admin.students.details.index') }}",
      data: function(d){
        d.class_id = $('#filterClass').val();
        d.section = $('#filterSection').val();
        d.status = $('#filterStatus').val();
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
      { data: 'admission_no', name: 'admission_no' },
      { data: 'roll_no', name: 'roll_no' },
      { data: 'class', name: 'class' },
      { data: 'section', name: 'section' },
      { data: 'status', name: 'status' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false }
    ]
  });
}

$(document).ready(function(){
  loadStudentTable();

  $('#applyStudentFilter').on('click', function(){
    loadStudentTable();
  });

  $(document).on('click','.delete-student-btn',function(){
    const id = $(this).data('id');
    $('#studentDeleteForm').attr('action', `{{ url('admin/students/details') }}/${id}`);
    $('#deleteStudentModal').modal('show');
  });
});
</script>
@endsection
