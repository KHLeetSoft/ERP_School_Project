@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Statistic Details</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.result-announcement.statistics.edit', $statistic->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <a href="{{ route('admin.result-announcement.statistics.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <div class="fw-bold mb-2">Meta</div>
                                <div><strong>Title:</strong> {{ $statistic->title }}</div>
                                <div><strong>Announcement:</strong> {{ optional($statistic->resultAnnouncement)->title ?? '-' }}</div>
                                <div><strong>Generated:</strong> {{ optional($statistic->generated_at)->format('d M Y, h:i A') ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <div class="fw-bold mb-2">Filters</div>
                                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($statistic->filters, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header"><h6 class="mb-0">Pass vs Fail</h6></div>
                                <div class="card-body"><canvas id="statDonut"></canvas></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header"><h6 class="mb-0">Grade Distribution</h6></div>
                                <div class="card-body"><canvas id="gradeBar"></canvas></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header"><h6 class="mb-0">Score Summary</h6></div>
                                <div class="card-body"><canvas id="scoreRadar"></canvas></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded">
                                <div class="fw-bold mb-2">Metrics</div>
                                <div class="row g-3">
                                    @foreach(($statistic->metrics ?? []) as $key => $value)
                                        <div class="col-md-3">
                                            <div class="border rounded p-3 text-center">
                                                <div class="text-muted text-uppercase small">{{ str_replace('_',' ', $key) }}</div>
                                                <div class="fs-4 fw-semibold">{{ $value }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function(){
        const ctx = document.getElementById('statDonut');
        if(!ctx) return;
        const passed = {{ (int)($statistic->metrics['passed'] ?? 0) }};
        const failed = {{ (int)($statistic->metrics['failed'] ?? 0) }};
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Passed', 'Failed'],
                datasets: [{ data: [passed, failed], backgroundColor: ['#22c55e','#ef4444'] }]
            },
            options: {
                plugins: { legend: { position: 'bottom' } },
                responsive: true,
                maintainAspectRatio: false
            }
        });

        const gradeCtx = document.getElementById('gradeBar');
        if(gradeCtx){
            const dist = {!! json_encode($statistic->metrics['grade_distribution'] ?? ['A'=>0,'B'=>0,'C'=>0,'D'=>0,'F'=>0]) !!};
            const labels = Object.keys(dist);
            const values = Object.values(dist).map(v=> Number(v));
            new Chart(gradeCtx, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Count', data: values, backgroundColor: '#8b5cf6' }] },
                options: { plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false }
            });
        }

        const radarCtx = document.getElementById('scoreRadar');
        if(radarCtx){
            const avg = {{ (float)($statistic->metrics['average_score'] ?? 0) }};
            const top = {{ (float)($statistic->metrics['top_score'] ?? 0) }};
            const median = {{ (float)($statistic->metrics['median_score'] ?? 0) }};
            const low = {{ (float)($statistic->metrics['lowest_score'] ?? 0) }};
            new Chart(radarCtx, {
                type: 'radar',
                data: {
                    labels: ['Average','Top','Median','Lowest'],
                    datasets: [{ label: 'Scores', data: [avg, top, median, low], backgroundColor: 'rgba(59,130,246,0.2)', borderColor: '#3b82f6' }]
                },
                options: { plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false }
            });
        }
    })();
</script>
@endsection


