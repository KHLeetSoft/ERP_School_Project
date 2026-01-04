@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Student Fees</h4>
    <div class="btn-group">
      <a href="{{ route('admin.students.fees.create') }}" class="btn btn-primary btn-sm rounded-pill">
        <i class="fas fa-plus"></i> Add Fee
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
            <option value="{{ $student->id }}">{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->user->name ?? 'Student') }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <button id="applyFilter" class="btn btn-sm btn-primary w-100">Apply</button>
      </div>
    </div>

    <div class="table-responsive">
      <table id="fees-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Class</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Mode</th>
            <th>Txn ID</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteFeeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">Are you sure you want to delete this fee record?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="feeDeleteForm" method="POST" action="">
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
let feeTable;
function loadFeesTable() {
  if (feeTable) feeTable.destroy();
  feeTable = $('#fees-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: "{{ route('admin.students.fees.index') }}",
      data: function(d){
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
      { data: 'class_name', name: 'schoolClass.name' },
      { data: 'amount', name: 'amount' },
      { data: 'fee_date', name: 'fee_date' },
      { data: 'payment_mode', name: 'payment_mode' },
      { data: 'transaction_id', name: 'transaction_id' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false }
    ]
  });
}

$(document).ready(function(){
  loadFeesTable();
  $('#applyFilter').on('click', function(){ loadFeesTable(); });
  $(document).on('click', '.delete-fee', function(){
    const id = $(this).data('id');
    $('#feeDeleteForm').attr('action', `{{ url('admin/students/fees') }}/${id}`);
    $('#deleteFeeModal').modal('show');
  });
});
</script>
@endsection


