@extends('superadmin.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4>Product Plans</h4>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createPlanModal">+ Add Plan</button>
  </div>
  <div class="card-body">
    <table id="plans-datatable" class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Price</th>
          <th>Features</th>
          <th>Max Users</th>
           <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Create Plan Modal -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="createPlanForm" method="POST" action="{{ route('superadmin.productplans.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createPlanLabel">Add Product Plan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Price</label>
            <input type="number" name="price" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Max Users</label>
            <input type="number" name="max_users" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Features</label>
            <textarea name="features" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Plan Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editPlanForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPlanModalLabel">Edit Product Plan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">

          <div class="mb-3">
            <label for="edit_title" class="form-label">Title</label>
            <input type="text" class="form-control" id="edit_title" name="title" required>
          </div>

          <div class="mb-3">
            <label for="edit_price" class="form-label">Price</label>
            <input type="number" class="form-control" id="edit_price" name="price" required>
          </div>

          <div class="mb-3">
            <label for="edit_features" class="form-label">Features</label>
            <textarea class="form-control" id="edit_features" name="features" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label for="edit_max_users" class="form-label">Max Users</label>
            <input type="number" class="form-control" id="edit_max_users" name="max_users" required>
          </div>

          <div class="mb-3">
            <label for="edit_status" class="form-label">Status</label>
            <select class="form-control" id="edit_status" name="status">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

@if(session('success'))
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>


  setTimeout(() => {
    Toastify({
      text: "{{ session('success') }}",
      duration: 4000,
      gravity: "top",
      position: "right",
      backgroundColor: "#4caf50"
    }).showToast();
  }, 500);
</script>
@endif
@endsection

@section('scripts')
<!-- DateRange Init -->
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

<script>
$(function () {
  $('.daterange-picker').daterangepicker({
    locale: { format: 'YYYY-MM-DD' }
  });
});
</script>

<!-- DataTable Init for Product Plans -->
<script>
    $(document).on('click', '.edit-btn', function () {
    let id = $(this).data('id');

    $.get(`/superadmin/productplans/${id}/edit`, function (data) {
        $('#edit_id').val(data.id);
        $('#edit_title').val(data.title);
        $('#edit_price').val(data.price);
        $('#edit_features').val(data.features);
        $('#edit_max_users').val(data.max_users);
        $('#edit_status').val(data.status);

        $('#editPlanModal').modal('show');
    });
});

    $('#editPlanForm').on('submit', function (e) {
    e.preventDefault();

    let id = $('#edit_id').val();
    let formData = $(this).serialize();

    $.ajax({
        url: `/superadmin/productplans/${id}`,
        method: 'POST',
        data: formData,
        success: function (res) {
            $('#editPlanModal').modal('hide');
            $('#purchase-datatable').DataTable().ajax.reload(); // update table
            alert('Product Plan updated successfully!');
        },
        error: function (xhr) {
            alert('Something went wrong. Please check form inputs.');
        }
    });
});

    $(document).on('click', '.delete-btn', function () {
    let id = $(this).data('id');
    if (confirm('Are you sure to delete this product plan?')) {
        $.ajax({
            url: `/superadmin/productplans/${id}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) {
                    alert(res.message);
                    $('#your-datatable-id').DataTable().ajax.reload();
                }
            }
        });
    }
});
let table;

function productPlansListTable(status = "All", date_type = "", date_range = "") {
  if (table) {
    table.destroy();
  }

  table = $('#plans-datatable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: '{{ route("superadmin.productplans.index") }}',
      data: function (d) {
        d.status = $('#filterStatus').val();
        d.date_type = $('#filterDateType').val();
        d.date_range = $('#filterDateRange').val();
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
      { data: 'title', name: 'title' },
      { data: 'price', name: 'price' },
      { data: 'features', name: 'features' },
      { data: 'max_users', name: 'max_users' },
      { data: 'status', name: 'status' },
      { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
  });
}

productPlansListTable();

// Reload table on filter change
$('#filterStatus, #filterDateType, #filterDateRange').on('change', function () {
  productPlansListTable();
});

// Toggle custom date range field
$('#filterDateType').on('change', function () {
  $('#customDateRange').toggle($(this).val() === 'Custom');
});

// Global search input
$('#globalSearch').on('keyup', function () {
  table.search(this.value).draw();
});

// Send Notification
$('#plans-datatable').on('click', '.send-btn', function () {
  if (!confirm('Send this plan to all admins?')) return;
  const id = $(this).data('id');

  $.post({
    url: '{{ route("superadmin.productplans.notify") }}',
    data: { id: id, _token: '{{ csrf_token() }}' },
    success: function (res) {
      alert(res.message);
    }
  });
});
</script>
@endsection
