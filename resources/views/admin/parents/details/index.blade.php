@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Parent Details</h4>
    <a href="{{ route('admin.parents.details.create') }}" class="btn btn-sm btn-primary rounded-pill">
      <i class="fas fa-plus"></i> Add Parent Details
    </a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="parent-details-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Parent</th>
            <th>Phone</th>
            <th>Students</th>
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
$(document).ready(function(){
  table = $('#parent-details-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('admin.parents.details.index') }}",
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
      { data: 'parent_name', name: 'primary_contact_name' },
      { data: 'phone', name: 'phone_primary' },
      { data: 'students', name: 'students' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false },
    ]
  });

  $(document).on('click','.delete-parent-detail', function(){
    const id = $(this).data('id');
    if (!confirm('Delete this record?')) return;
    $.ajax({ url: `{{ url('admin/parents/details') }}/${id}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
      .done(() => table.ajax.reload());
  });
});
</script>
@endsection


