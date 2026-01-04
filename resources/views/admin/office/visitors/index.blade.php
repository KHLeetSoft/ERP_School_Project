@extends('admin.layout.app')

@section('title','Visitors')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Visitor Log</h4>
    <div class="btn-group" role="group">
      <a href="{{ route('admin.office.visitors.export') }}" class="btn btn-success btn-sm rounded-pill"><i class="bx bx-download"></i> Export</a>
      <button class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bx bx-upload"></i> Import</button>
      <a href="{{ route('admin.office.visitors.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="fas fa-plus"></i> Add Visitor</a>
    </div>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="filterPurpose" class="form-select form-select-sm">
          <option value="">All Purpose</option>
          <option value="Enquiry">Enquiry</option>
          <option value="Delivery">Delivery</option>
          <option value="Meeting">Meeting</option>
        </select>
      </div>
      <div class="col-md-3">
        <input type="date" id="filterFrom" class="form-control form-control-sm" placeholder="From">
      </div>
      <div class="col-md-3">
        <input type="date" id="filterTo" class="form-control form-control-sm" placeholder="To">
      </div>
      <div class="col-md-3">
        <button id="applyFilter" class="btn btn-sm btn-primary" style="float:right;">Apply</button>
      </div>
    </div>
    <div class="table-responsive">
      <table id="visitors-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Purpose</th>
            <th>Phone</th>
            <th>Date</th>
            <th>In</th>
            <th>Out</th>
            <th>Note</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
<!-- Delete Modal -->
<div class="modal fade" id="deleteVisitorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">Are you sure you want to delete this visitor?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="visitorDeleteForm" method="POST" action="">
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
      <div class="modal-header"><h5 class="modal-title">Import Visitors</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="{{ route('admin.office.visitors.import') }}" method="POST" enctype="multipart/form-data">
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

        table = $('#visitors-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('admin.office.visitors.index') }}",
                data: function(d){
                   d.purpose = $('#filterPurpose').val();
                   d.date_from = $('#filterFrom').val();
                   d.date_to = $('#filterTo').val();
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables Ajax error:', error);
                    console.error('Response:', xhr.responseText);
                    alert('Error loading data: ' + (xhr.responseJSON?.error || thrown || 'Unknown error'));
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
        { data: 'visitor_name', name: 'visitor_name' },
        { data: 'purpose', name: 'purpose' },
        { data: 'phone', name: 'phone' },
        { data: 'date', name: 'date' },
        { data: 'in_time', name: 'in_time' },
        { data: 'out_time', name: 'out_time' },
        { data: 'note', name: 'note' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
  });
}
$(document).ready(function(){
    loadTable(); // 👈 Important: Load data when page loads

    // Delete modal trigger
    $(document).on('click','.delete-visitor-btn',function(){
        const id = $(this).data('id');
        $('#visitorDeleteForm').attr('action', `{{ url('admin/office/visitors') }}/${id}`);
        $('#deleteVisitorModal').modal('show');
    });

    // Mark out
    $(document).on('click','.mark-out-btn',function(){
     const id = $(this).data('id');
        $.ajax({
            url:`{{ url('admin/office/visitors') }}/${id}/mark-out`,
            type:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
            success:function(res){
              table.ajax.reload(null,false);
            }
        });
     });

     $('#applyFilter').on('click',function(){
        loadTable();
     });
});
</script>
@endsection 