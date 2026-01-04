@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Result Statistics Dashboard</h6>
                    <a href="{{ route('admin.result-announcement.statistics.index') }}" class="btn btn-sm btn-outline-secondary">Back to list</a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <div class="text-muted text-uppercase small">Total Students</div>
                                <div class="fs-4 fw-semibold">{{ $aggregate['total_students'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <div class="text-muted text-uppercase small">Appeared</div>
                                <div class="fs-4 fw-semibold">{{ $aggregate['appeared'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <div class="text-muted text-uppercase small">Passed</div>
                                <div class="fs-4 fw-semibold">{{ $aggregate['passed'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <div class="text-muted text-uppercase small">Pass %</div>
                                <div class="fs-4 fw-semibold">{{ $aggregate['pass_percentage'] }}%</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-3 mb-3">
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header"><h6 class="mb-0">Overall Pass vs Fail</h6></div>
                                <div class="card-body"><canvas id="overallDonut"></canvas></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header"><h6 class="mb-0">Pass % by Statistic</h6></div>
                                <div class="card-body"><canvas id="passBar"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header"><h6 class="mb-0">Average Score by Statistic</h6></div>
                                <div class="card-body"><canvas id="avgBar"></canvas></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header"><h6 class="mb-0">Grade Distribution (Aggregated)</h6></div>
                                <div class="card-body"><canvas id="gradePie"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Announcement</th>
                                    <th>Generated</th>
                                    <th>Pass %</th>
                                    <th>Top Score</th>
                                    <th>Average</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats as $s)
                                  <tr>
                                    <td>{{ $s->title }}</td>
                                    <td>{{ optional($s->resultAnnouncement)->title ?? '-' }}</td>
                                    <td>{{ optional($s->generated_at)->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $s->metrics['pass_percentage'] ?? 0 }}%</td>
                                    <td>{{ $s->metrics['top_score'] ?? 0 }}</td>
                                    <td>{{ $s->metrics['average_score'] ?? 0 }}</td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.result-announcement.statistics.show', $s->id) }}">View</a>
                                    </td>
                                  </tr>
                                @empty
                                  <tr><td colspan="7" class="text-center text-muted">No statistics yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function(){
        const donutCtx = document.getElementById('overallDonut');
        if(donutCtx){
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Passed', 'Failed'],
                    datasets: [{
                        data: [{{ (int)($aggregate['passed'] ?? 0) }}, {{ (int)($aggregate['failed'] ?? 0) }}],
                        backgroundColor: ['#22c55e','#ef4444'],
                    }]
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        const barCtx = document.getElementById('passBar');
        if(barCtx){
            const labels = {!! json_encode($stats->pluck('title')->toArray()) !!};
            const data = {!! json_encode($stats->map(fn($s)=> (float)($s->metrics['pass_percentage'] ?? 0))->toArray()) !!};
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pass %',
                        data: data,
                        backgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true, max: 100 } },
                    plugins: { legend: { display: false } },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        const avgCtx = document.getElementById('avgBar');
        if(avgCtx){
            const labels = {!! json_encode($stats->pluck('title')->toArray()) !!};
            const avgs = {!! json_encode($stats->map(fn($s)=> (float)($s->metrics['average_score'] ?? 0))->toArray()) !!};
            new Chart(avgCtx, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Average', data: avgs, backgroundColor: '#06b6d4' }] },
                options: { plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false }
            });
        }

        const gradeCtx = document.getElementById('gradePie');
        if(gradeCtx){
            const grades = {!! json_encode($gradeLabels) !!};
            const totals = {!! json_encode($gradeTotals) !!};
            new Chart(gradeCtx, {
                type: 'pie',
                data: {
                    labels: grades,
                    datasets: [{ data: totals, backgroundColor: ['#16a34a','#60a5fa','#f59e0b','#a855f7','#ef4444'] }]
                },
                options: { plugins: { legend: { position: 'bottom' } }, responsive: true, maintainAspectRatio: false }
            });
        }
    })();
</script>
@endsection


