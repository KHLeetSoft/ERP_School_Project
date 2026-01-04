@extends('admin.layout.app')

@section('content')
<div class="container">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-fill text-primary me-2"></i> PTM Dashboard
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.academic.ptm.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-list"></i> PTM Index
            </a>
            <a href="{{ route('admin.academic.ptm.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> New PTM
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Total</h6>
                <div class="fs-3 fw-bold">{{ array_sum($statusCounts->toArray()) }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Scheduled</h6>
                <div class="fs-3 text-warning fw-bold">{{ $statusCounts['scheduled'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Completed</h6>
                <div class="fs-3 text-success fw-bold">{{ $statusCounts['completed'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Cancelled</h6>
                <div class="fs-3 text-secondary fw-bold">{{ $statusCounts['cancelled'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4">
        <!-- Pie Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-pie-chart me-2 text-primary"></i> PTM Status Distribution
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Donut Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-circle-half me-2 text-success"></i> Upcoming vs Completed PTMs
                </div>
                <div class="card-body">
                    <canvas id="compareChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-bar-chart-line-fill me-2 text-info"></i> Monthly PTM Count
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming PTM List -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-calendar-event me-2"></i> Upcoming PTMs
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
                    @forelse($upcoming as $ptm)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($ptm->date)->format('d M Y') }}</td>
                            <td>{{ $ptm->title }}</td>
                            <td>
                                <span class="badge 
                                    @if($ptm->status === 'completed') bg-success 
                                    @elseif($ptm->status === 'scheduled') bg-warning 
                                    @elseif($ptm->status === 'cancelled') bg-secondary 
                                    @else bg-info @endif">
                                    {{ ucfirst($ptm->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No upcoming PTMs</td>
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
    // Status Pie Chart
    const statusData = @json($statusCounts);
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#4caf50','#f44336','#ff9800','#2196f3']
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // Compare Donut
    new Chart(document.getElementById('compareChart'), {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Upcoming'],
            datasets: [{
                data: [{{ $completed }}, {{ $upcomingCount }}],
                backgroundColor: ['#009688','#ff5722']
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // Monthly Bar Chart
    const monthlyData = @json($monthlyCounts);
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(monthlyData),
            datasets: [{
                label: 'PTMs',
                data: Object.values(monthlyData),
                backgroundColor: '#3f51b5'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
