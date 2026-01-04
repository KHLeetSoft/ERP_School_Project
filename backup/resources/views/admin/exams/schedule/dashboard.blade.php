@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.exams.schedule.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Index
        </a>
    </div>

    <div class="row g-3">
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-primary text-white"><div class="card-body text-center"><div class="fw-bold">Total Schedules</div><div class="display-6">{{ $totalSchedules }}</div></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-info text-white"><div class="card-body text-center"><div class="fw-bold">Today</div><div class="display-6">{{ $todayCount }}</div></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-warning text-dark"><div class="card-body text-center"><div class="fw-bold">Postponed</div><div class="display-6">{{ $postponedCount }}</div></div></div></div>
        <div class="col-md-3"><div class="card shadow-sm border-0 bg-success text-white"><div class="card-body text-center"><div class="fw-bold">Completed</div><div class="display-6">{{ $statusCounts['completed'] ?? 0 }}</div></div></div></div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Status Distribution</div>
                <div class="card-body"><div id="esStatusChart" style="height:260px;"></div></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Top Classes</div>
                <div class="card-body"><div id="esClassChart" style="height:260px;"></div></div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Subjects (Top 10)</div>
                <div class="card-body"><div id="esSubjectChart" style="height:260px;"></div></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Monthly Trend</div>
                <div class="card-body"><div id="esMonthlyChart" style="height:260px;"></div></div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Top Invigilators</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($invigilatorCounts as $name => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $name }}
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">This Week</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead><tr><th>Date</th><th>Exam</th><th>Class</th><th>Subject</th><th class="text-end">Action</th></tr></thead>
                        <tbody>
                            @forelse($thisWeek as $r)
                                <tr>
                                    <td>{{ optional($r->exam_date)->format('Y-m-d') }}</td>
                                    <td>{{ optional($r->exam)->title }}</td>
                                    <td>{{ $r->class_name }} {{ $r->section_name }}</td>
                                    <td>{{ $r->subject_name }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.exams.schedule.show', $r) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No schedules</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-header fw-bold">Upcoming (Next 10)</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Date</th><th>Exam</th><th>Class</th><th>Subject</th><th>Time</th><th class="text-end">Action</th></tr></thead>
                    <tbody>
                        @forelse($upcoming as $r)
                            <tr>
                                <td>{{ optional($r->exam_date)->format('Y-m-d') }}</td>
                                <td>{{ optional($r->exam)->title }}</td>
                                <td>{{ $r->class_name }} {{ $r->section_name }}</td>
                                <td>{{ $r->subject_name }}</td>
                                <td>{{ $r->start_time }} - {{ $r->end_time }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.exams.schedule.show', $r) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No upcoming schedules</td></tr>
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
        const status = @json($statusCounts);
        const classCounts = @json($classCounts);
        const subjectCounts = @json($subjectCounts);
        const monthlyCounts = @json($monthlyCounts);

        new ApexCharts(document.querySelector('#esStatusChart'), {
            chart:{type:'donut', height:260},
            labels: Object.keys(status||{}).map(s=>s.charAt(0).toUpperCase()+s.slice(1)),
            series: Object.values(status||{}),
            colors: ['#0dcaf0','#198754','#ffc107','#dc3545','#6c757d'],
            legend: { position: 'bottom' }
        }).render();

        new ApexCharts(document.querySelector('#esClassChart'), {
            chart:{type:'bar', height:260, toolbar:{show:false}},
            xaxis:{ categories: Object.keys(classCounts||{}) },
            series:[{ name:'Schedules', data: Object.values(classCounts||{}) }],
            colors:['#7367f0']
        }).render();

        new ApexCharts(document.querySelector('#esSubjectChart'), {
            chart:{type:'bar', height:260, toolbar:{show:false}},
            xaxis:{ categories: Object.keys(subjectCounts||{}) },
            series:[{ name:'Schedules', data: Object.values(subjectCounts||{}) }],
            colors:['#28c76f']
        }).render();

        new ApexCharts(document.querySelector('#esMonthlyChart'), {
            chart:{type:'area', height:260, toolbar:{show:false}},
            xaxis:{ categories: Object.keys(monthlyCounts||{}) },
            series:[{ name:'Schedules', data: Object.values(monthlyCounts||{}) }],
            dataLabels:{enabled:false}, stroke:{curve:'smooth', width:3}, colors:['#ff9f43'],
            fill:{ type:'gradient', gradient:{ shadeIntensity:0.7, opacityFrom:0.6, opacityTo:0.2 } }
        }).render();
    })();
</script>
@endsection


