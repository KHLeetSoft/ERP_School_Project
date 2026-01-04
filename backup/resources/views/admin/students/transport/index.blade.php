@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="fa fa-bus text-warning"></i> Student Transport</h4>
        <a href="{{ route('admin.students.transport.create') }}" class="btn btn-success shadow-sm">
            <i class="fa fa-plus-circle"></i> Assign Transport
        </a>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light fw-bold">
            <i class="fa fa-filter text-primary"></i> Filters
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Class</label>
                    <select id="filter_class" class="form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Student</label>
                    <select id="filter_student" class="form-select">
                        <option value="">All Students</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">
                                {{ trim(($st->first_name.' '.$st->last_name)) ?: ($st->user->name ?? 'Student #'.$st->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Route</label>
                    <select id="filter_route" class="form-select">
                        <option value="">All Routes</option>
                        @foreach($routes as $rt)
                            <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Vehicle</label>
                    <select id="filter_vehicle" class="form-select">
                        <option value="">All Vehicles</option>
                        @foreach($vehicles as $vh)
                            <option value="{{ $vh->id }}">{{ $vh->vehicle_no }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="transportTable" style="width: 100%;">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Route</th>
                            <th>Vehicle</th>
                            <th>Fare</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = $('#transportTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('admin.students.transport.index') }}",
            data: function(d) {
                d.class_id = $('#filter_class').val();
                d.student_id = $('#filter_student').val();
                d.route_id = $('#filter_route').val();
                d.vehicle_id = $('#filter_vehicle').val();
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
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'student_name' },
            { data: 'class_name' },
            { data: 'route' },
            { data: 'vehicle' },
            { data: 'fare' },
            { data: 'actions', orderable: false, searchable: false, className: 'text-center' },
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search transport records..."
        }
    });

    $('#filter_class, #filter_student, #filter_route, #filter_vehicle').on('change', function() {
        table.ajax.reload();
    });

    $(document).on('click', '.delete-transport', function() {
        if(!confirm('Delete this assignment?')) return;
        const id = $(this).data('id');
        $.ajax({
            url: `{{ url('admin/students/transport') }}/${id}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() { table.ajax.reload(); },
            error: function() { alert('Failed to delete'); }
        });
    });
});
</script>
@endsection
