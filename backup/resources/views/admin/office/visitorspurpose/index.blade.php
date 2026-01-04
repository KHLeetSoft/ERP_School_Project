@extends('admin.layout.app')

@section('title','Visitors Purposes')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Visitor Purposes</h4>
    <div class="btn-group" role="group">
      <a href="{{ route('admin.office.visitorspurpose.export') }}" class="btn btn-success btn-sm rounded-pill"><i class="bx bx-download"></i> Export</a>
      <button class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bx bx-upload"></i> Import</button>
      <a href="{{ route('admin.office.visitorspurpose.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="fas fa-plus"></i> Add Purpose</a>
    </div>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="filterStatus" class="form-select form-select-sm">
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
      <div class="col-md-9">
        <button id="applyFilter" class="btn btn-sm btn-primary" style="float:right;">Apply</button>
      </div>
    </div>
    <div class="table-responsive">
      <table id="purposes-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data will be loaded via DataTables -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deletePurposeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Purpose</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this purpose?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="purposeDeleteForm" method="POST" action="">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Import Visitor Purposes</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="{{ route('admin.office.visitorspurpose.import') }}" method="POST" enctype="multipart/form-data">
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
    function loadTable(status = '') {
        if (table) table.destroy();

        table = $('#purposes-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("admin.office.visitorspurpose.index") }}',
                data: function(d){
                   d.purpose = $('#filterPurpose').val();
                   d.date_from = $('#filterFrom').val();
                   d.date_to = $('#filterTo').val();
                },
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
          {data: 'name', name: 'name'},
          {data: 'description', name: 'description'},
          {data: 'status', name: 'status'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
      ],
  });
} 
  $(document).ready(function() {
    loadTable(); // Load data when page loads

    // Delete modal trigger
    $(document).on('click', '.delete-purpose-btn', function() {
      const id = $(this).data('id');
      $('#purposeDeleteForm').attr('action', `{{ url('admin/office/visitorspurpose') }}/${id}`);
      $('#deletePurposeModal').modal('show');
    });

    // Apply filters
    $('#applyFilter').click(function() {
      table.draw();
    });
  });
</script>
@endsection