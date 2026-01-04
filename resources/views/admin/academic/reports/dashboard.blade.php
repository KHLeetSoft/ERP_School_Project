@extends('admin.layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-fill text-primary me-2"></i> Reports Dashboard
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.academic.reports.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-list"></i> Reports
            </a>
            <a href="{{ route('admin.academic.reports.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> New Report
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Draft</h6>
                <div class="fs-3 text-warning fw-bold">{{ $statusCounts['draft'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Published</h6>
                <div class="fs-3 text-success fw-bold">{{ $statusCounts['published'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-3">
                <h6 class="text-muted">Archived</h6>
                <div class="fs-3 text-secondary fw-bold">{{ $statusCounts['archived'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-bar-chart me-2 text-info"></i> Monthly Reports
        </div>
        <div class="card-body">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-clock-history me-2"></i> Recent Reports
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
                    @forelse($recent as $r)
                        <tr>
                            <td>{{ optional($r->report_date)->format('d M Y') }}</td>
                            <td>{{ $r->title }}</td>
                            <td>
                                <span class="badge 
                                    @if($r->status === 'published') bg-success 
                                    @elseif($r->status === 'draft') bg-warning 
                                    @else bg-secondary @endif">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No recent reports</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyData = @json($monthlyCounts);
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(monthlyData),
            datasets: [{
                label: 'Reports',
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


