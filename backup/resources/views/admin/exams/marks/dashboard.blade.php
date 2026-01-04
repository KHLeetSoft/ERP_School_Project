@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.exams.marks.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Index
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle fs-3"></i>
                    <div class="fw-bold">Published</div>
                    <div class="display-6">{{ $statusCounts['published'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-secondary text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text fs-3"></i>
                    <div class="fw-bold">Draft</div>
                    <div class="display-6">{{ $statusCounts['draft'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-award fs-3"></i>
                    <div class="fw-bold">Pass</div>
                    <div class="display-6">{{ $resultCounts['pass'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-danger text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle fs-3"></i>
                    <div class="fw-bold">Fail</div>
                    <div class="display-6">{{ $resultCounts['fail'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4 g-3">
        <!-- Subject Avg -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header fw-bold">
                    <i class="bi bi-bar-chart-fill text-primary me-2"></i> Subject Averages (Top 10)
                </div>
                <div class="card-body">
                    <div id="subjectAvgChart" style="height:280px;"></div>
                </div>
            </div>
        </div>

        <!-- Pass/Fail Ratio -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header fw-bold">
                    <i class="bi bi-pie-chart-fill text-success me-2"></i> Pass/Fail Ratio
                </div>
                <div class="card-body">
                    <div id="passFailChart" style="height:280px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- More Charts -->
    <div class="row mt-4 g-3">
        <!-- Marks Trend -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header fw-bold">
                    <i class="bi bi-graph-up-arrow text-info me-2"></i> Marks Trend (Last 10 Records)
                </div>
                <div class="card-body">
                    <div id="marksTrendChart" style="height:280px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Students -->
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header fw-bold">
            <i class="bi bi-stars text-primary me-2"></i> Top Students
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr><th>Student</th><th class="text-end">Avg%</th></tr>
                </thead>
                <tbody>
                    @forelse($topStudents as $s)
                        <tr>
                            <td>{{ $s->student_name }}</td>
                            <td class="text-end">{{ number_format($s->avg_pct,2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Marks -->
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header fw-bold">
            <i class="bi bi-clock-history text-secondary me-2"></i> Recent Marks
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr><th>Exam</th><th>Student</th><th>Subject</th><th>Marks</th><th>%</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recent as $r)
                            <tr>
                                <td>{{ optional($r->exam)->title }}</td>
                                <td>{{ $r->student_name }}</td>
                                <td>{{ $r->subject_name }}</td>
                                <td>{{ $r->obtained_marks }}/{{ $r->max_marks }}</td>
                                <td>{{ $r->percentage }}</td>
                                <td class="text-capitalize">{{ $r->status }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No records</td></tr>
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
        // Subject Average Chart
        const subjectAvg = @json($subjectAvg);
        new ApexCharts(document.querySelector('#subjectAvgChart'), {
            chart:{ type:'bar', height:280, toolbar:{show:false} },
            xaxis:{ categories: Object.keys(subjectAvg||{}) },
            series:[{ name:'Avg Marks', data: Object.values(subjectAvg||{}) }],
            colors:['#7367f0']
        }).render();

        // Pass/Fail Donut Chart
        const resultCounts = @json($resultCounts);
        new ApexCharts(document.querySelector('#passFailChart'), {
            chart:{ type:'donut', height:280 },
            series:[resultCounts.pass || 0, resultCounts.fail || 0],
            labels:['Pass','Fail'],
            colors:['#28c76f','#ea5455']
        }).render();

        // Marks Trend Line Chart
        const recent = @json($recent);
        new ApexCharts(document.querySelector('#marksTrendChart'), {
            chart:{ type:'line', height:280, toolbar:{show:false} },
            series:[{ name:'% Marks', data: recent.map(r => r.percentage) }],
            xaxis:{ categories: recent.map(r => r.student_name) },
            stroke:{ curve:'smooth' },
            colors:['#00cfe8']
        }).render();

    })();
</script>
@endsection
