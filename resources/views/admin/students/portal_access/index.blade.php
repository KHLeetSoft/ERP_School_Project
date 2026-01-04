@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Student Portal Access</h4>
    <a href="{{ route('admin.students.portal-access.create') }}" class="btn btn-sm btn-primary rounded-pill">
      <i class="fas fa-plus"></i> Add Access
    </a>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-4">
        <select id="filterStudent" class="form-select form-select-sm">
          <option value="">All Students</option>
          @foreach($students as $student)
            <option value="{{ $student->id }}">{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->user->name ?? 'Student') }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterEnabled" class="form-select form-select-sm">
          <option value="">Any Status</option>
          <option value="1">Enabled</option>
          <option value="0">Disabled</option>
        </select>
      </div>
      <div class="col-md-3">
        <button id="applyFilter" class="btn btn-sm btn-primary w-100">Apply</button>
      </div>
    </div>
    <div class="table-responsive">
      <table id="access-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Username</th>
            <th>Email</th>
            <th>Enabled</th>
            <th>Last Login</th>
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
function loadAccess(){
  if (table) table.destroy();
  table = $('#access-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('admin.students.portal-access.index') }}",
      data: function(d){
        d.student_id = $('#filterStudent').val();
        d.is_enabled = $('#filterEnabled').val();
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
      { data: 'username', name: 'username' },
      { data: 'email', name: 'email' },
      { data: 'enabled', name: 'is_enabled' },
      { data: 'last_login_at', name: 'last_login_at' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false },
    ]
  });
}

$(document).ready(function(){
  loadAccess();
  $('#applyFilter').on('click', function(){ loadAccess(); });
  $(document).on('click','.delete-access', function(){
    const id = $(this).data('id');
    if (!confirm('Delete this portal access?')) return;
    $.ajax({ url: `{{ url('admin/students/portal-access') }}/${id}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
      .done(() => table.ajax.reload());
  });
});
</script>
@endsection


