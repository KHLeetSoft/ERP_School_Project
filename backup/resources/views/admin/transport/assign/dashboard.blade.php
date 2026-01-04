@extends('admin.layout.app')

@section('title', 'Transport Assignment Dashboard')

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card bg-gradient-primary text-white">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h2 class="mb-2"><i class="fas fa-bus me-3"></i>Transport Assignment Dashboard</h2>
              <p class="mb-0">Comprehensive overview of transport operations and analytics</p>
            </div>
            <div class="col-md-4 text-end">
              <a href="{{ route('admin.transport.assign.index') }}" class="btn btn-light btn-sm rounded-pill">
                <i class="fas fa-list me-1"></i> View All Assignments
              </a>
              <a href="{{ route('admin.transport.assign.create') }}" class="btn btn-light btn-sm rounded-pill ms-2">
                <i class="fas fa-plus me-1"></i> New Assignment
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Key Statistics Cards -->
  <div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Assignments</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_assignments'] ?? 0 }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Assignments</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_assignments'] ?? 0 }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-play-circle fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Assignments</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_assignments'] ?? 0 }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clock fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today's Assignments</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_assignments'] ?? 0 }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row mb-4">
    <!-- Status Distribution Chart -->
    <div class="col-xl-6 col-lg-6">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-pie me-2"></i>Assignment Status Distribution
          </h6>
        </div>
        <div class="card-body">
          <canvas id="statusChart" width="400" height="200"></canvas>
        </div>
      </div>
    </div>

    <!-- Monthly Trend Chart -->
    <div class="col-xl-6 col-lg-6">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-line me-2"></i>Monthly Assignment Trends
          </h6>
        </div>
        <div class="card-body">
          <canvas id="monthlyTrendChart" width="400" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Analytics Row -->
  <div class="row mb-4">
    <!-- Vehicle Utilization Chart -->
    <div class="col-xl-4 col-lg-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-tachometer-alt me-2"></i>Vehicle Utilization
          </h6>
        </div>
        <div class="card-body">
          <canvas id="utilizationChart" width="400" height="200"></canvas>
        </div>
      </div>
    </div>

    <!-- Shift Distribution Chart -->
    <div class="col-xl-4 col-lg-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-clock me-2"></i>Shift Distribution
          </h6>
        </div>
        <div class="card-body">
          <canvas id="shiftChart" width="400" height="200"></canvas>
        </div>
      </div>
    </div>

    <!-- Route Performance Chart -->
    <div class="col-xl-4 col-lg-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-route me-2"></i>Top Routes
          </h6>
        </div>
        <div class="card-body">
          <canvas id="routeChart" width="400" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Activity and Today's Schedule -->
  <div class="row mb-4">
    <!-- Recent Assignments -->
    <div class="col-xl-6 col-lg-6">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-history me-2"></i>Recent Assignments
          </h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Assignment</th>
                  <th>Vehicle</th>
                  <th>Status</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentAssignments as $assignment)
                <tr>
                  <td>
                    <strong>#{{ $assignment->id }}</strong>
                    <br><small class="text-muted">{{ $assignment->route->route_name ?? 'N/A' }}</small>
                  </td>
                  <td>{{ $assignment->vehicle->vehicle_number ?? 'N/A' }}</td>
                  <td>
                    <span class="badge bg-{{ $assignment->status === 'active' ? 'success' : ($assignment->status === 'pending' ? 'warning' : 'secondary') }}">
                      {{ ucfirst($assignment->status) }}
                    </span>
                  </td>
                  <td>{{ $assignment->assignment_date ? $assignment->assignment_date->format('M d') : 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">No recent assignments</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Today's Schedule -->
    <div class="col-xl-6 col-lg-6">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-calendar-day me-2"></i>Today's Schedule
          </h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Time</th>
                  <th>Vehicle</th>
                  <th>Route</th>
                  <th>Driver</th>
                </tr>
              </thead>
              <tbody>
                @forelse($todaySchedule as $assignment)
                <tr>
                  <td>
                    <strong>{{ $assignment->start_time ? $assignment->start_time->format('H:i') : 'N/A' }}</strong>
                    <br><small class="text-muted">{{ ucfirst($assignment->shift_type ?? 'N/A') }}</small>
                  </td>
                  <td>{{ $assignment->vehicle->vehicle_number ?? 'N/A' }}</td>
                  <td>{{ $assignment->route->route_name ?? 'N/A' }}</td>
                  <td>{{ $assignment->driver->name ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">No assignments scheduled for today</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Performance Metrics -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-bar me-2"></i>Performance Metrics
          </h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3 text-center">
              <div class="border-right">
                <h4 class="text-success">{{ $stats['utilization_percentage'] ?? 0 }}%</h4>
                <p class="text-muted">Vehicle Utilization</p>
              </div>
            </div>
            <div class="col-md-3 text-center">
              <div class="border-right">
                <h4 class="text-info">{{ $stats['this_week_assignments'] ?? 0 }}</h4>
                <p class="text-muted">This Week</p>
              </div>
            </div>
            <div class="col-md-3 text-center">
              <div class="border-right">
                <h4 class="text-warning">{{ $stats['this_month_assignments'] ?? 0 }}</h4>
                <p class="text-muted">This Month</p>
              </div>
            </div>
            <div class="col-md-3 text-center">
              <div>
                <h4 class="text-danger">{{ $stats['overdue_assignments'] ?? 0 }}</h4>
                <p class="text-muted">Overdue</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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

  // Monthly Trend Chart
  const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
  const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
        label: 'Assignments',
        data: [{{ implode(',', $stats['monthly_data'] ?? [0,0,0,0,0,0,0,0,0,0,0,0]) }}],
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderWidth: 3,
        fill: true,
        tension: 0.4
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

  // Vehicle Utilization Chart
  const utilizationCtx = document.getElementById('utilizationChart').getContext('2d');
  const utilizationChart = new Chart(utilizationCtx, {
    type: 'bar',
    data: {
      labels: ['Utilized', 'Available'],
      datasets: [{
        label: 'Vehicles',
        data: [
          {{ $stats['active_assignments'] ?? 0 }},
          {{ max(0, ($stats['total_vehicles'] ?? 10) - ($stats['active_assignments'] ?? 0)) }}
        ],
        backgroundColor: [
          'rgba(40, 167, 69, 0.8)',
          'rgba(108, 117, 125, 0.8)'
        ],
        borderColor: [
          'rgba(40, 167, 69, 1)',
          'rgba(108, 117, 125, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });

  // Shift Distribution Chart
  const shiftCtx = document.getElementById('shiftChart').getContext('2d');
  const shiftChart = new Chart(shiftCtx, {
    type: 'polarArea',
    data: {
      labels: ['Morning', 'Afternoon', 'Evening', 'Night', 'Full Day'],
      datasets: [{
        data: [25, 20, 15, 10, 30],
        backgroundColor: [
          'rgba(255, 99, 132, 0.8)',
          'rgba(54, 162, 235, 0.8)',
          'rgba(255, 205, 86, 0.8)',
          'rgba(75, 192, 192, 0.8)',
          'rgba(153, 102, 255, 0.8)'
        ],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });

  // Route Performance Chart
  const routeCtx = document.getElementById('routeChart').getContext('2d');
  const routeChart = new Chart(routeCtx, {
    type: 'horizontalBar',
    data: {
      labels: ['Route A', 'Route B', 'Route C', 'Route D', 'Route E'],
      datasets: [{
        label: 'Assignments',
        data: [45, 38, 32, 28, 25],
        backgroundColor: 'rgba(54, 162, 235, 0.8)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          beginAtZero: true
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
