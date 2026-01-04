@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Student Communication</h4>
    <a href="{{ route('admin.students.communication.create') }}" class="btn btn-sm btn-primary rounded-pill">
      <i class="fas fa-plus"></i> New Message
    </a>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="filterClass" class="form-select form-select-sm">
          <option value="">All Classes</option>
          @foreach($classes as $class)
            <option value="{{ $class->id }}">{{ $class->name ?? $class->class_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterStudent" class="form-select form-select-sm">
          <option value="">All Students</option>
          @foreach($students as $student)
            <option value="{{ $student->id }}">{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->user->name ?? 'Student') }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterChannel" class="form-select form-select-sm">
          <option value="">All Channels</option>
          <option value="sms">SMS</option>
          <option value="email">Email</option>
          <option value="notice">Notice</option>
        </select>
      </div>
      <div class="col-md-3">
        <button id="applyFilter" class="btn btn-sm btn-primary w-100">Apply</button>
      </div>
    </div>

    <div class="table-responsive">
      <table id="comm-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Class</th>
            <th>Subject</th>
            <th>Channel</th>
            <th>Status</th>
            <th>Sent At</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
let table;
function loadComms(){
  if (table) table.destroy();
  table = $('#comm-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('admin.students.communication.index') }}",
      data: function(d){
        d.class_id = $('#filterClass').val();
        d.student_id = $('#filterStudent').val();
        d.channel = $('#filterChannel').val();
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
      { data: 'subject', name: 'subject' },
      { data: 'channel', name: 'channel' },
      { data: 'status', name: 'status' },
      { data: 'sent_at', name: 'sent_at' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false },
    ]
  });
}

$(document).ready(function(){
  loadComms();
  $('#applyFilter').on('click', function(){ loadComms(); });
  $(document).on('click','.delete-comm', function(){
    const id = $(this).data('id');
    if (!confirm('Delete this communication?')) return;
    $.ajax({ url: `{{ url('admin/students/communication') }}/${id}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
      .done(() => table.ajax.reload());
  });
});
</script>
@endsection


