@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Student Results</h4>
    <div class="btn-group" role="group">
      <a href="{{ route('admin.students.results.export') }}" class="btn btn-success btn-sm rounded-pill">
        <i class="bx bx-download"></i> Export
      </a>
      <button class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="bx bx-upload"></i> Import
      </button>
      <a href="{{ route('admin.students.results.create') }}" class="btn btn-primary btn-sm rounded-pill">
        <i class="fas fa-plus"></i> Add Result
      </a>
    </div>
  </div>

  <div class="card-body">
    {{-- Filters --}}
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="filterClass" class="form-select form-select-sm">
          <option value="">All Classes</option>
          @foreach($classes ?? [] as $class)
            <option value="{{ $class->id }}">{{ $class->name ?? $class->class_name }}</option>
            @endforeach

        </select>
      </div>
      <div class="col-md-3">
        <select id="filterSubject" class="form-select form-select-sm">
          <option value="">All Subjects</option>
          @foreach($subjects as $subject)
            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterExamType" class="form-select form-select-sm">
          <option value="">All Exam Types</option>
          <option value="Midterm">Midterm</option>
          <option value="Final">Final</option>
        </select>
      </div>
      <div class="col-md-3">
        <button id="applyResultFilter" class="btn btn-sm btn-primary w-100">Apply</button>
      </div>
    </div>

    {{-- DataTable --}}
    <div class="table-responsive">
      <table id="results-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Class</th>
            <th>Subject</th>
            <th>Exam Type</th>
            <th>Marks</th>
            <th>Grade</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteResultModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">Are you sure you want to delete this result?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="resultDeleteForm" method="POST" action="">
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
      <div class="modal-header"><h5 class="modal-title">Import Results</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="{{ route('admin.students.results.import') }}" method="POST" enctype="multipart/form-data">
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
function loadResultsTable() {
  if (table) table.destroy();
  table = $('#results-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: "{{ route('admin.students.results.index') }}",
      data: function(d){
        d.class_id = $('#filterClass').val();
        d.subject_id = $('#filterSubject').val();
        d.exam_type = $('#filterExamType').val();
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
      { data: 'student_name', name: 'student.first_name' },
      { data: 'class_name', name: 'schoolClass.name' },
      { data: 'subject_name', name: 'subject.subject_name' },
      { data: 'exam_type', name: 'exam_type' },
      { data: 'marks_obtained', name: 'marks_obtained' },
      { data: 'grade', name: 'grade' },
      { data: 'result_status', name: 'result_status' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false }
    ]
  });
}

$(document).ready(function(){
  loadResultsTable();

  $('#applyResultFilter').on('click', function(){
    loadResultsTable();
  });

  $(document).on('click','.delete-result-btn',function(){
    const id = $(this).data('id');
    $('#resultDeleteForm').attr('action', `{{ url('admin/students/results') }}/${id}`);
    $('#deleteResultModal').modal('show');
  });
});
</script>
@endsection
