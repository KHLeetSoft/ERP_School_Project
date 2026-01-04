@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Student Hostel</h4>
    <a href="{{ route('admin.students.hostel.create') }}" class="btn btn-sm btn-primary rounded-pill">
      <i class="fas fa-plus"></i> Assign Hostel
    </a>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="filterHostel" class="form-select form-select-sm">
          <option value="">All Hostels</option>
          @foreach($hostels as $h)
            <option value="{{ $h->id }}">{{ $h->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterRoom" class="form-select form-select-sm">
          <option value="">All Rooms</option>
          @foreach($rooms as $r)
            <option value="{{ $r->id }}">{{ $r->room_no }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterStudent" class="form-select form-select-sm">
          <option value="">All Students</option>
          @foreach($students as $s)
            <option value="{{ $s->id }}">{{ trim(($s->first_name ?? '') . ' ' . ($s->last_name ?? '')) ?: ($s->user->name ?? 'Student') }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <button id="applyFilter" class="btn btn-sm btn-primary w-100">Apply</button>
      </div>
    </div>

    <div class="table-responsive">
      <table id="hostel-table" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Hostel</th>
            <th>Room</th>
            <th>Bed</th>
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
function loadHostels(){
  if (table) table.destroy();
  table = $('#hostel-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('admin.students.hostel.index') }}",
      data: function(d){
        d.hostel_id = $('#filterHostel').val();
        d.room_id = $('#filterRoom').val();
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
      { data: 'hostel', name: 'hostel.name' },
      { data: 'room', name: 'room.room_no' },
      { data: 'bed_no', name: 'bed_no' },
      { data: 'status', name: 'status' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false },
    ]
  });
}

$(document).ready(function(){
  loadHostels();
  $('#applyFilter').on('click', function(){ loadHostels(); });
  $(document).on('click','.delete-hostel', function(){
    const id = $(this).data('id');
    if (!confirm('Delete this hostel assignment?')) return;
    $.ajax({ url: `{{ url('admin/students/hostel') }}/${id}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
      .done(() => table.ajax.reload());
  });
});
</script>
@endsection


