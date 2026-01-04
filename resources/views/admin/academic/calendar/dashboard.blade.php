@extends('admin.layout.app')

@section('content')
<div class="container">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-fill text-primary me-2"></i> Academic Calendar Dashboard
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.academic.calendar.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-list"></i> Events
            </a>
            <a href="{{ route('admin.academic.calendar.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> New Event
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 text-center p-3 rounded-3">
                <i class="bi bi-collection fs-3 text-primary mb-2"></i>
                <h6 class="text-muted mb-1">Total</h6>
                <div class="fs-5 fw-bold">{{ array_sum($statusCounts->toArray()) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 text-center p-3 rounded-3">
                <i class="bi bi-clock-history fs-3 text-warning mb-2"></i>
                <h6 class="text-muted mb-1">Scheduled</h6>
                <div class="fs-5 text-warning fw-bold">{{ $statusCounts['scheduled'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 text-center p-3 rounded-3">
                <i class="bi bi-check-circle fs-3 text-success mb-2"></i>
                <h6 class="text-muted mb-1">Completed</h6>
                <div class="fs-5 text-success fw-bold">{{ $statusCounts['completed'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 text-center p-3 rounded-3">
                <i class="bi bi-x-circle fs-3 text-secondary mb-2"></i>
                <h6 class="text-muted mb-1">Cancelled</h6>
                <div class="fs-5 text-secondary fw-bold">{{ $statusCounts['cancelled'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Graphs -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light fw-bold small">
                    <i class="bi bi-pie-chart me-2 text-primary"></i> Status Distribution
                </div>
                <div class="card-body text-center">
                    <canvas id="statusChart" style="max-height:220px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light fw-bold small">
                    <i class="bi bi-circle-half me-2 text-success"></i> Upcoming vs Completed
                </div>
                <div class="card-body text-center">
                    <canvas id="compareChart" style="max-height:220px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light fw-bold small">
                    <i class="bi bi-bar-chart-line-fill me-2 text-info"></i> Monthly Events
                </div>
                <div class="card-body text-center">
                    <canvas id="monthlyChart" style="max-height:220px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="card shadow-sm border-0 mt-4 rounded-3">
        <div class="card-header bg-primary text-white fw-bold small">
            <i class="bi bi-calendar-event me-2"></i> Upcoming Events
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcoming as $event)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</td>
                            <td>{{ $event->title }}</td>
                            <td>
                                <span class="badge 
                                    @if($event->status === 'completed') bg-success 
                                    @elseif($event->status === 'scheduled') bg-warning 
                                    @elseif($event->status === 'cancelled') bg-secondary 
                                    @else bg-info @endif">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No upcoming events</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Pie
    const statusData = @json($statusCounts);
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#4caf50','#f44336','#ff9800','#2196f3'],
                borderWidth: 0
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // Upcoming vs Completed Donut
    new Chart(document.getElementById('compareChart'), {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Upcoming'],
            datasets: [{
                data: [{{ $completed }}, {{ $upcomingCount }}],
                backgroundColor: ['#009688','#ff5722'],
                borderWidth: 0
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // Monthly Bar
    const monthlyData = @json($monthlyCounts);
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(monthlyData),
            datasets: [{
                label: 'Events',
                data: Object.values(monthlyData),
                backgroundColor: '#3f51b5',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
@endsection
