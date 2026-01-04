@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Student Health Records</h4>
    <div class="btn-group">
      <a href="{{ route('admin.students.health.create') }}" class="btn btn-primary btn-sm rounded-pill">
        <i class="fas fa-plus"></i> Add Health Record
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="row mb-3">
    <div class="col-md-4">
    <select id="filterClass" class="form-select form-select-sm">
        <option value="">All Classes</option>
        @foreach($classes as $class)
            <option value="{{ $class->id }}">{{ $class->name ?? $class->class_name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <select id="filterStudent" class="form-select form-select-sm">
        <option value="">All Students</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}">
                {{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->user->name ?? 'Student') }}
            </option>
        @endforeach
    </select>
</div>

      <div class="col-md-4">
        <button id="applyFilter" class="btn btn-sm btn-primary w-100">Apply</button>
      </div>
    </div>

    <div class="table-responsive">
      <table id="health-table" class="table table-bordered table-striped">
          <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Height (cm)</th>
                    <th>Weight (kg)</th>
                    <th>Blood Group</th>
                    <th>Allergies</th>
                    <th>Medical Conditions</th>
                    <th>Immunizations</th>
                    <th>Last Checkup</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteHealthModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">Are you sure you want to delete this health record?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="healthDeleteForm" method="POST" action="">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
let healthTable;
function loadHealthTable() {
  if (healthTable) healthTable.destroy();
  healthTable = $('#health-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: "{{ route('admin.students.health.index') }}",
      data: function(d) {
                d.class_id = $('#filterClass').val();
                d.student_id = $('#filterStudent').val();
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
        { data: 'class_name', name: 'class.name' },
        { data: 'height_cm', name: 'height_cm' },
        { data: 'weight_kg', name: 'weight_kg' },
        { data: 'blood_group', name: 'blood_group' },
        { data: 'allergies', name: 'allergies' },
        { data: 'medical_conditions', name: 'medical_conditions' },
        { data: 'immunizations', name: 'immunizations' },
        { data: 'last_checkup_date', name: 'last_checkup_date' },
        { data: 'notes', name: 'notes' },
        { data: 'actions', name: 'actions', orderable: false, searchable: false }
    ]
  });
}

$(document).ready(function(){
  loadHealthTable();
  $('#applyFilter').on('click', function(){ loadHealthTable(); });
  $(document).on('click', '.delete-health', function(){
    const id = $(this).data('id');
    $('#healthDeleteForm').attr('action', `{{ url('admin/students/health') }}/${id}`);
    $('#deleteHealthModal').modal('show');
  });
});
</script>
@endsection
