@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Student Documents</h4>
    <a href="{{ route('admin.students.documents.create') }}" class="btn btn-sm btn-primary rounded-pill">
      <i class="fas fa-plus"></i> Upload Document
    </a>
  </div>
  <div class="card-body">
    @include('admin.students.documents._upload_modal')
    <div class="row mb-3">
      <div class="col-md-6">
        <select id="filterStudent" class="form-select form-select-sm">
          <option value="">All Students</option>
          @foreach($students as $s)
            <option value="{{ $s->id }}">{{ trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? '')) ?: ($s->user->name ?? 'Student') }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button id="applyFilter" class="btn btn-sm btn-primary w-100">Apply</button>
      </div>
    </div>
    <div class="table-responsive">
      <table id="docs-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Title</th>
            <th>Type</th>
            <th>File</th>
            <th>Issued</th>
            <th>Expiry</th>
            <th>Status</th>
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
function loadDocs(){
  if (table) table.destroy();
  table = $('#docs-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('admin.students.documents.index') }}",
      data: function(d){ d.student_id = $('#filterStudent').val(); }
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
      { data: 'title', name: 'title' },
      { data: 'document_type', name: 'document_type' },
      { data: 'file', name: 'file', orderable: false, searchable: false },
      { data: 'issued_date', name: 'issued_date' },
      { data: 'expiry_date', name: 'expiry_date' },
      { data: 'status', name: 'status' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false },
    ]
  });
}

$(document).ready(function(){
  loadDocs();
  $('#applyFilter').on('click', function(){ loadDocs(); });
  $(document).on('click','.delete-document', function(){
    const id = $(this).data('id');
    if (!confirm('Delete this document?')) return;
    $.ajax({ url: `{{ url('admin/students/documents') }}/${id}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
      .done(() => table.ajax.reload());
  });
});
</script>
@endsection


