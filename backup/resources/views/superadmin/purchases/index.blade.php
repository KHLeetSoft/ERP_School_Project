@extends('superadmin.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4>Purchases</h4>
    <a href="{{ route('superadmin.purchases.create') }}" class="btn btn-sm btn-primary">+ Add Purchase</a>
  </div>
  <div class="card-body">

    <!-- Filters -->
    <!-- Filters and Search aligned -->
<div class="row mb-3 align-items-end">
  <div class="col-md-2">
    <label>Status</label>
    <select class="form-control form-control-sm" id="filterStatus">
      <option value="All">All</option>
      <option value="Pending">Pending</option>
      <option value="Completed">Completed</option>
    </select>
  </div>
  <div class="col-md-2">
    <label>Date Type</label>
    <select class="form-control form-control-sm" id="filterDateType">
      <option value="">-- Select --</option>
      <option value="Today">Today</option>
      <option value="Tomorrow">Tomorrow</option>
      <option value="Custom">Custom</option>
    </select>
  </div>
  <div class="col-md-3" id="customDateRange" style="display:none;">
    <label>Date Range</label>
    <input type="text" id="filterDateRange" class="form-control form-control-sm daterange-picker" autocomplete="off">
  </div>
  <div class="col-md-3 ms-auto text-end">
    <label>&nbsp;</label>
    <input type="search" id="globalSearch" class="form-control form-control-sm" placeholder="Search...">
  </div>
</div>


    <!-- Table -->
    <table id="purchase-datatable" class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>School</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Purchase Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>

  </div>
</div>
@endsection

@section('scripts')
<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- DateRange Init -->
<script>
$(function () {
  $('.daterange-picker').daterangepicker({
    locale: { format: 'YYYY-MM-DD' }
  });
});
</script>

<!-- DataTable Init -->
<script>
let table;

function purchasesListTable(status = "All", date_type = "", date_range = "") {
  if (table) {
    table.destroy();
  }

  table = $('#purchase-datatable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: '{{ route("superadmin.purchases.datatable") }}',
      data: {
        status: status,
        date_type: date_type,
        date_range: date_range
      }
    },
    dom:
      '<"row mb-3 align-items-end"<"col-md-3"f><"col-md-2"l><"col-md-7 d-flex justify-content-end gap-2"B>>' +
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
      { data: 'school_name', name: 'school.name' },
      { data: 'item_name', name: 'item_name' },
      { data: 'quantity', name: 'quantity' },
      { data: 'price', name: 'price' },
      { data: 'purchase_date', name: 'purchase_date' },
      { data: 'status', name: 'status' },
      { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
  });
}

purchasesListTable();

$('#filterStatus, #filterDateType, #filterDateRange').on('change', function () {
  let status = $('#filterStatus').val();
  let date_type = $('#filterDateType').val();
  let date_range = $('#filterDateRange').val();
  purchasesListTable(status, date_type, date_range);
});

$('#filterDateType').on('change', function () {
  $('#customDateRange').toggle($(this).val() === 'Custom');
});

$('#globalSearch').on('keyup', function () {
  table.search(this.value).draw();
});
</script>
@endsection
