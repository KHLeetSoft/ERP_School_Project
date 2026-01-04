@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <h4>   
        <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Index
        </a>
    </div>
    <div class="row g-3">
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-info text-white"><div class="card-body text-center"><div class="fw-bold">Scheduled</div><div class="display-6">{{ $statusCounts['scheduled'] ?? 0 }}</div></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-success text-white"><div class="card-body text-center"><div class="fw-bold">Completed</div><div class="display-6">{{ $statusCounts['completed'] ?? 0 }}</div></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-warning text-dark"><div class="card-body text-center"><div class="fw-bold">Ongoing Today</div><div class="display-6">{{ $ongoingExams }}</div></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-secondary text-white"><div class="card-body text-center"><div class="fw-bold">Upcoming</div><div class="display-6">{{ $upcomingExams }}</div><div class="small mt-1">Overdue: <span class="fw-bold">{{ $overdueExams }}</span></div></div></div></div>
    </div>

    <div class="row mt-3 g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted">Avg Duration</div>
                    <div class="display-6">{{ number_format($avgDurationDays, 1) }}<span class="fs-6 ms-1">days</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted">Completion Rate</div>
                    <div class="display-6">{{ $completionRate }}<span class="fs-6 ms-1">%</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted">Busiest Month</div>
                    <div class="h5 mb-0">{{ $busiestMonthLabel ?? '-' }}</div>
                    <div class="small">{{ $busiestMonthCount }} exams</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Status Distribution</div>
                <div class="card-body">
                    <div id="statusChart" style="height:260px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Exams by Type</div>
                <div class="card-body">
                    <div id="typeChart" style="height:260px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-header fw-bold">Monthly Trend</div>
        <div class="card-body">
            <div id="monthlyChart" style="height:280px;"></div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-header fw-bold">Monthly Status Breakdown</div>
        <div class="card-body">
            <div id="monthlyStackedChart" style="height:300px;"></div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Next 5 Exams</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>Title</th><th>Start</th><th>End</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($nextExams as $e)
                                <tr>
                                    <td>{{ $e->title }}</td>
                                    <td>{{ optional($e->start_date)->format('Y-m-d') }}</td>
                                    <td>{{ optional($e->end_date)->format('Y-m-d') }}</td>
                                    <td class="text-capitalize">{{ $e->status }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No upcoming exams</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">This Month</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>Title</th><th>Start</th><th>End</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($thisMonth as $e)
                                <tr>
                                    <td>{{ $e->title }}</td>
                                    <td>{{ optional($e->start_date)->format('Y-m-d') }}</td>
                                    <td>{{ optional($e->end_date)->format('Y-m-d') }}</td>
                                    <td class="text-capitalize">{{ $e->status }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No exams this month</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-header fw-bold">Upcoming/Recent Exams</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Academic Year</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent as $r)
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>{{ $r->title }}</td>
                                <td>{{ $r->exam_type }}</td>
                                <td>{{ $r->academic_year }}</td>
                                <td>{{ optional($r->start_date)->format('Y-m-d') }}</td>
                                <td>{{ optional($r->end_date)->format('Y-m-d') }}</td>
                                <td class="text-capitalize">{{ $r->status }}</td>
                                <td><a href="{{ route('admin.exams.show', $r) }}" class="btn btn-sm btn-outline-secondary">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted">No records</td></tr>
                        @endforelse
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
    (function(){
        const statusCounts = @json($statusCounts);
        const typeCounts = @json($typeCounts);
        const monthlyCounts = @json($monthlyCounts);

        const statusLabels = ['scheduled','completed','cancelled','draft'];
        const statusSeries = statusLabels.map(k => Number(statusCounts[k] || 0));
        new ApexCharts(document.querySelector('#statusChart'), {
            chart: { type: 'donut', height: 260 },
            labels: statusLabels.map(s=>s.charAt(0).toUpperCase()+s.slice(1)),
            series: statusSeries,
            colors: ['#0dcaf0','#198754','#dc3545','#6c757d'],
            legend: { position: 'bottom' }
        }).render();

        const typeLabels = Object.keys(typeCounts || {});
        const typeSeries = typeLabels.map(k => Number(typeCounts[k] || 0));
        new ApexCharts(document.querySelector('#typeChart'), {
            chart: { type: 'bar', height: 260, toolbar:{show:false} },
            xaxis: { categories: typeLabels },
            series: [{ name: 'Exams', data: typeSeries }],
            colors: ['#7367f0']
        }).render();

        const monthLabels = Object.keys(monthlyCounts || {});
        const monthSeries = monthLabels.map(k => Number(monthlyCounts[k] || 0));
        new ApexCharts(document.querySelector('#monthlyChart'), {
            chart: { type: 'area', height: 280, toolbar:{show:false} },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: { categories: monthLabels },
            series: [{ name: 'Exams', data: monthSeries }],
            colors: ['#28c76f'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 0.7, opacityFrom: 0.6, opacityTo: 0.2 } }
        }).render();

        const stackedSeries = [
            { name: 'Scheduled', data: monthLabels.map(m => Number((monthlyCounts && monthlyCounts[m]) ? (monthlyCounts[m]) : 0)) }
        ];
        // Build stacked by using monthlyStatus map
        const ms = @json($monthlyStatus);
        const statuses = ['scheduled','completed','cancelled','draft'];
        const seriesStacked = statuses.map((s, idx) => ({ name: s.charAt(0).toUpperCase()+s.slice(1), data: monthLabels.map(m => Number((ms[m] ? ms[m][s] : 0))) }));
        new ApexCharts(document.querySelector('#monthlyStackedChart'), {
            chart: { type: 'bar', stacked: true, height: 300, toolbar:{show:false} },
            xaxis: { categories: monthLabels },
            series: seriesStacked,
            colors: ['#0dcaf0','#198754','#dc3545','#6c757d']
        }).render();

        // Inline sparkline for next 6 months forecast (using monthly counts)
        const next6 = @json($nextSixMonthsCounts);
        const nextLabels = Object.keys(next6 || {});
        const nextSeries = nextLabels.map(k => Number(next6[k] || 0));
        // Optionally could add a small chart elsewhere; skipping DOM add for brevity
    })();
</script>
@endsection


