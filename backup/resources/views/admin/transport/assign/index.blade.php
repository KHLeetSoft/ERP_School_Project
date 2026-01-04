@extends('admin.layout.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Transport Assignments</h4>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.transport.assign.dashboard') }}" class="btn btn-sm btn-info rounded-pill">
        <i class="fas fa-chart-pie"></i> Dashboard
      </a>
      <a href="{{ route('admin.transport.assign.create') }}" class="btn btn-sm btn-primary rounded-pill">
        <i class="fas fa-plus"></i> New Assignment
      </a>
    </div>
  </div>
  <div class="card-body">
    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-primary text-white">
          <div class="card-body text-center">
            <h5 class="card-title">Total</h5>
            <h3 class="mb-0">{{ $stats['total_assignments'] ?? 0 }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-success text-white">
          <div class="card-body text-center">
            <h5 class="card-title">Active</h5>
            <h3 class="mb-0">{{ $stats['active_assignments'] ?? 0 }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-warning text-white">
          <div class="card-body text-center">
            <h5 class="card-title">Pending</h5>
            <h3 class="mb-0">{{ $stats['pending_assignments'] ?? 0 }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-info text-white">
          <div class="card-body text-center">
            <h5 class="card-title">Today</h5>
            <h3 class="mb-0">{{ $stats['today_assignments'] ?? 0 }}</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts and Analytics Section -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Status Distribution</h6>
          </div>
          <div class="card-body">
            <canvas id="statusChart" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Monthly Assignments</h6>
          </div>
          <div class="card-body">
            <canvas id="monthlyChart" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Statistics Cards -->
    <div class="row mb-4">
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-danger text-white">
          <div class="card-body text-center">
            <h5 class="card-title">Completed</h5>
            <h3 class="mb-0">{{ $stats['completed_assignments'] ?? 0 }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-secondary text-white">
          <div class="card-body text-center">
            <h5 class="card-title">Cancelled</h5>
            <h3 class="mb-0">{{ $stats['cancelled_assignments'] ?? 0 }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-dark text-white">
          <div class="card-body text-center">
            <h5 class="card-title">Delayed</h5>
            <h3 class="mb-0">{{ $stats['delayed_assignments'] ?? 0 }}</h3>
            </div>
                </div>
                </div>
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card bg-light text-dark">
          <div class="card-body text-center">
            <h5 class="card-title">Utilization</h5>
            <h3 class="mb-0">{{ $stats['utilization_percentage'] ?? 0 }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="statusFilter" class="form-select form-select-sm">
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="pending">Pending</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
          <option value="delayed">Delayed</option>
        </select>
      </div>
      <div class="col-md-3">
        <select id="vehicleFilter" class="form-select form-select-sm">
          <option value="">All Vehicles</option>
          @foreach($vehicles as $vehicle)
            <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_number }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="routeFilter" class="form-select form-select-sm">
          <option value="">All Routes</option>
          @foreach($routes as $route)
            <option value="{{ $route->id }}">{{ $route->route_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <button id="resetFilters" class="btn btn-outline-secondary btn-sm w-100">
          <i class="fas fa-refresh"></i> Reset Filters
        </button>
      </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions mb-3" id="bulkActions" style="display: none;">
      <div class="alert alert-info d-flex justify-content-between align-items-center">
        <span>Selected <span id="selectedCount">0</span> assignment(s)</span>
        <div class="d-flex gap-2">
          <button class="btn btn-success btn-sm" onclick="bulkAction('activate')">
            <i class="fas fa-play"></i> Activate
          </button>
          <button class="btn btn-info btn-sm" onclick="bulkAction('complete')">
            <i class="fas fa-check"></i> Complete
          </button>
          <button class="btn btn-warning btn-sm" onclick="bulkAction('cancel')">
            <i class="fas fa-ban"></i> Cancel
          </button>
          <button class="btn btn-danger btn-sm" onclick="bulkAction('delete')">
            <i class="fas fa-trash"></i> Delete
          </button>
        </div>
        </div>
    </div>

            <div class="table-responsive">
      <table id="assignments-table" class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
            <th width="50">
              <input type="checkbox" id="selectAll" class="form-check-input">
            </th>
            <th>#</th>
            <th>Assignment Info</th>
                            <th>Vehicle</th>
                            <th>Route</th>
            <th>Staff</th>
                            <th>Schedule</th>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let table;
$(document).ready(function(){
  table = $('#assignments-table').DataTable({
        processing: true,
        serverSide: true,
    ajax: {
      url: "{{ route('admin.transport.assign.index') }}",
      data: function(d) {
        d.status = $('#statusFilter').val();
        d.vehicle_id = $('#vehicleFilter').val();
        d.route_id = $('#routeFilter').val();
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
      { 
        data: 'id', 
        orderable: false, 
        searchable: false,
        render: function(data) {
          return `<input type="checkbox" class="form-check-input assignment-checkbox" value="${data}">`;
        }
      },
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'assignment_info', name: 'assignment_info' },
      { data: 'vehicle_info', name: 'vehicle_info' },
      { data: 'route_info', name: 'route_info' },
      { data: 'staff_info', name: 'staff_info' },
      { data: 'schedule_info', name: 'schedule_info' },
      { data: 'status_badge', name: 'status' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false },
    ]
  });

  // Filter handlers
  $('#statusFilter, #vehicleFilter, #routeFilter').on('change', function() {
    table.ajax.reload();
  });

  // Reset filters
  $('#resetFilters').on('click', function() {
    $('#statusFilter, #vehicleFilter, #routeFilter').val('');
    table.ajax.reload();
    
    Swal.fire({
      icon: 'success',
      title: 'Filters Reset!',
      text: 'All filters have been cleared.',
      timer: 1500,
      showConfirmButton: false
    });
  });

  // Select All functionality
    $('#selectAll').on('change', function() {
        $('.assignment-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

  $(document).on('change', '.assignment-checkbox', function() {
    updateBulkActions();
    
    // Update select all checkbox
    let totalCheckboxes = $('.assignment-checkbox').length;
    let checkedCheckboxes = $('.assignment-checkbox:checked').length;
    $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
    $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
  });

    function updateBulkActions() {
    let selectedCount = $('.assignment-checkbox:checked').length;
    $('#selectedCount').text(selectedCount);
    $('#bulkActions').toggle(selectedCount > 0);
  }

  // Delete assignment
  $(document).on('click', '.delete-assignment', function() {
    let assignmentId = $(this).data('id');
    
    Swal.fire({
      title: 'Are you sure?',
      text: "This assignment will be permanently deleted!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e53e3e',
      cancelButtonColor: '#718096',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/admin/transport/assign/${assignmentId}`,
          type: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              Swal.fire('Deleted!', response.message, 'success');
              table.ajax.reload();
            } else {
              Swal.fire('Error!', response.message, 'error');
            }
          },
          error: function() {
            Swal.fire('Error!', 'Something went wrong!', 'error');
          }
        });
      }
    });
  });

  // Toggle status
  $(document).on('click', '.toggle-status', function() {
    let assignmentId = $(this).data('id');
    
    $.ajax({
      url: `/admin/transport/assign/${assignmentId}/toggle-status`,
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Updated!', response.message, 'success');
          table.ajax.reload();
        } else {
          Swal.fire('Error!', response.message, 'error');
        }
      },
      error: function() {
        Swal.fire('Error!', 'Something went wrong!', 'error');
      }
    });
  });
});

// Bulk Action Function
function bulkAction(action) {
  let selected = $('.assignment-checkbox:checked').map(function() {
    return $(this).val();
  }).get();

  if (selected.length === 0) {
    Swal.fire('Warning!', 'Please select at least one assignment.', 'warning');
    return;
  }

  let actionText = action.charAt(0).toUpperCase() + action.slice(1);
  
  Swal.fire({
    title: `${actionText} Assignments?`,
    text: `Are you sure you want to ${action} ${selected.length} assignment(s)?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#667eea',
    cancelButtonColor: '#718096',
    confirmButtonText: `Yes, ${action}!`
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '{{ route("admin.transport.assign.bulk-action") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            action: action,
            assignments: selected
        },
        success: function(response) {
          if (response.success) {
            Swal.fire('Success!', response.message, 'success');
            $('#assignments-table').DataTable().ajax.reload();
            $('#bulkActions').hide();
            $('#selectAll').prop('checked', false);
          } else {
            Swal.fire('Error!', response.message, 'error');
          }
        },
        error: function() {
          Swal.fire('Error!', 'Something went wrong!', 'error');
        }
      });
    }
  });
}

// Charts and Analytics
$(document).ready(function() {
  // Status Distribution Chart
  const statusCtx = document.getElementById('statusChart').getContext('2d');
  const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
      labels: ['Active', 'Pending', 'Completed', 'Cancelled', 'Delayed'],
      datasets: [{
        data: [
          {{ $stats['active_assignments'] ?? 0 }},
          {{ $stats['pending_assignments'] ?? 0 }},
          {{ $stats['completed_assignments'] ?? 0 }},
          {{ $stats['cancelled_assignments'] ?? 0 }},
          {{ $stats['delayed_assignments'] ?? 0 }}
        ],
        backgroundColor: [
          '#28a745',
          '#ffc107',
          '#17a2b8',
          '#6c757d',
          '#343a40'
        ],
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 20,
            usePointStyle: true
          }
        }
      }
    }
  });

  // Monthly Assignments Chart
  const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
  const monthlyChart = new Chart(monthlyCtx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
        label: 'Assignments',
        data: [{{ implode(',', $stats['monthly_data'] ?? [0,0,0,0,0,0,0,0,0,0,0,0]) }}],
        backgroundColor: 'rgba(54, 162, 235, 0.8)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 5
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
});
</script>
@endsection
