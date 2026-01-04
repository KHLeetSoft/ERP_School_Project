@extends('admin.layout.app')

@section('title','Postal Receive')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Postal Receive</h4>
    <div class="btn-group">
      <a href="{{ route('admin.office.receive.export') }}" class="btn btn-success btn-sm rounded-pill"><i class="bx bx-download"></i> Export</a>
      <button class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bx bx-upload"></i> Import</button>
      <a href="{{ route('admin.office.receive.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="fas fa-plus"></i> Add Receive</a>
    </div>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-4">
        <input type="text" id="filterFrom" class="form-control form-control-sm" placeholder="From Title">
      </div>
      <div class="col-md-3">
        <input type="date" id="filterDateFrom" class="form-control form-control-sm" placeholder="From">
      </div>
      <div class="col-md-3">
        <input type="date" id="filterDateTo" class="form-control form-control-sm" placeholder="To">
      </div>
      <div class="col-md-2">
        <button id="applyFilter" class="btn btn-sm btn-primary float-end">Apply</button>
      </div>
    </div>
    <div class="table-responsive">
      <table id="receive-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>From</th>
            <th>Reference</th>
            <th>Address</th>
            <th>To</th>
            <th>Date</th>
            <th>Note</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
<!-- Delete Modal -->
<div class="modal fade" id="deleteReceiveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">Are you sure you want to delete this receive record?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="receiveDeleteForm" method="POST" action="">
          @csrf @method('DELETE')
          <button class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Import Receive</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="{{ route('admin.office.receive.import') }}" method="POST" enctype="multipart/form-data">
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

        table = $('#receive-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('admin.office.receive.index') }}",
                data: function(d){
                  d.from_title=$('#filterFrom').val();
                  d.date_from=$('#filterDateFrom').val();
                  d.date_to=$('#filterDateTo').val();
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
      {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
      {data:'from_title', name:'from_title'},
      {data:'reference_no', name:'reference_no'},
      {data:'address', name:'address'},
      {data:'to_title', name:'to_title'},
      {data:'date', name:'date'},
      {data:'note', name:'note'},
      {data:'action', name:'action', orderable:false, searchable:false},
    ]
  });
}

$(document).ready(function(){
  loadTable();
  $(document).on('click','.delete-receive-btn',function(){
    const id=$(this).data('id');
    $('#receiveDeleteForm').attr('action',`{{ url('admin/office/receive') }}/${id}`);
    $('#deleteReceiveModal').modal('show');
  });
  $('#applyFilter').on('click',function(){
        loadTable();
    });
});
</script>
@endsection