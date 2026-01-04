@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    <!-- Stats Cards -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Records</h6>
                        <h3 class="mb-0">{{ $totals['all'] ?? 0 }}</h3>
                    </div>
                    <div class="text-primary fs-2"><i class="bi bi-collection"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-success border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Published</h6>
                        <h3 class="mb-0">{{ $totals['published'] ?? 0 }}</h3>
                    </div>
                    <div class="text-success fs-2"><i class="bi bi-check-circle-fill"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-warning border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Draft</h6>
                        <h3 class="mb-0">{{ $totals['draft'] ?? 0 }}</h3>
                    </div>
                    <div class="text-warning fs-2"><i class="bi bi-file-earmark-text"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4 g-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Exam Status Distribution</strong></div>
                <div class="card-body">
                    <div id="statusChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Topper by Class (Graph)</strong></div>
                <div class="card-body">
                    <div id="topperChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Extra Graphs -->
    <div class="row mt-4 g-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Exam Trends (Last 10)</strong></div>
                <div class="card-body">
                    <div id="trendChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Pass Percentage</strong></div>
                <div class="card-body">
                    <div id="passChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Topper by Class Table -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-light"><strong>Topper by Class</strong></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark"><tr><th>Class</th><th>Best Rank</th></tr></thead>
                    <tbody>
                        @forelse($topperByClass as $class => $best)
                            <tr><td>{{ $class }}</td><td>{{ $best }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Entries -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-light"><strong>Recent Entries</strong></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Exam</th>
                            <th>Class</th>
                            <th>Student</th>
                            <th>Total</th>
                            <th>%</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent as $r)
                            <tr>
                                <td>{{ optional($r->exam)->title }}</td>
                                <td>{{ $r->class_name }}</td>
                                <td>{{ $r->student_name }}</td>
                                <td>{{ $r->total_marks }}/{{ $r->max_total_marks }}</td>
                                <td>{{ $r->percentage }}</td>
                                <td>
                                    <span class="badge 
                                        @if($r->status=='published') bg-success 
                                        @elseif($r->status=='draft') bg-warning text-dark 
                                        @else bg-secondary @endif">
                                        {{ ucfirst($r->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function(){

    // Pie Chart - Status Distribution
    var statusOptions = {
        chart: { type: 'donut', height: 300 },
        labels: ['Published','Draft'],
        series: [{{ $totals['published'] ?? 0 }}, {{ $totals['draft'] ?? 0 }}],
        colors: ['#28a745','#ffc107'],
        legend: { position: 'bottom' },
        dataLabels: { style: { fontSize: '14px' } }
    };
    new ApexCharts(document.querySelector("#statusChart"), statusOptions).render();

    // Bar Chart - Topper by Class
    var topperOptions = {
        chart: { type: 'bar', height: 300 },
        series: [{ name:'Best Rank', data: @json(array_values($topperByClass)) }],
        xaxis: { categories: @json(array_keys($topperByClass)) },
        colors: ['#0d6efd'],
        dataLabels: { enabled: true }
    };
    new ApexCharts(document.querySelector("#topperChart"), topperOptions).render();

    // Line Chart - Exam Trends
    var trendOptions = {
        chart: { type: 'line', height: 300 },
        series: [{ name: 'Percentage', data: @json($recent->pluck('percentage')->toArray()) }],
        xaxis: { categories: @json($recent->pluck('student_name')->toArray()) },
        colors: ['#6610f2'],
        markers: { size: 4 }
    };
    new ApexCharts(document.querySelector("#trendChart"), trendOptions).render();

    // Radial Bar - Pass Percentage (example only)
    var passOptions = {
        chart: { type: 'radialBar', height: 300 },
        series: [Math.floor(({{ $totals['published'] ?? 0 }} / ({{ $totals['all'] ?? 1 }})) * 100)],
        labels: ['Pass %'],
        colors: ['#20c997'],
        plotOptions: { radialBar: { hollow: { size: '60%' } } }
    };
    new ApexCharts(document.querySelector("#passChart"), passOptions).render();

});
</script>
@endsection
