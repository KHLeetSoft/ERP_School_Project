@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.exams.sms.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Index
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body text-center">
                    <div class="fw-bold">Total Campaigns</div>
                    <div class="display-6">{{ $totals['all'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-white">
                <div class="card-body text-center">
                    <div class="fw-bold">Draft</div>
                    <div class="display-6">{{ $totals['draft'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body text-center">
                    <div class="fw-bold">Scheduled</div>
                    <div class="display-6">{{ $totals['scheduled'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body text-center">
                    <div class="fw-bold">Sent</div>
                    <div class="display-6">{{ $totals['sent'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphs & Tables -->
    <div class="row mt-4 g-3">
        <!-- Recent Campaigns -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Recent Campaigns</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Exam</th>
                                <th>Status</th>
                                <th>Schedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent as $r)
                                <tr>
                                    <td>{{ $r->title }}</td>
                                    <td>{{ optional($r->exam)->title ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $r->status=='sent'?'success':($r->status=='scheduled'?'info':($r->status=='draft'?'warning':'secondary')) }}">
                                            {{ ucfirst($r->status) }}
                                        </span>
                                    </td>
                                    <td>{{ optional($r->schedule_at)?->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Audience Mix -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Audience Mix</div>
                <div class="card-body">
                    <div id="audienceChart" style="height:280px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <!-- Campaign Status Chart -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">Campaign Status Breakdown</div>
                <div class="card-body">
                    <div id="statusChart" style="height:280px;"></div>
                </div>
            </div>
        </div>

        <!-- SMS Sent Over Time -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold">SMS Sent Over Time</div>
                <div class="card-body">
                    <div id="sentOverTimeChart" style="height:280px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Campaigns -->
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header fw-bold">Top Campaigns (By Messages Sent)</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>Title</th><th>Exam</th><th>Total Sent</th></tr>
                </thead>
                <tbody>
                    @forelse($topCampaigns as $c)
                        <tr>
                            <td>{{ $c->title }}</td>
                            <td>{{ optional($c->exam)->title ?? '-' }}</td>
                            <td>{{ $c->sent_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
(function(){
    // Audience Mix
    const dataAudience = @json($byAudience);
    new ApexCharts(document.querySelector('#audienceChart'), {
        chart:{ type:'donut', height:280 },
        labels: Object.keys(dataAudience||{}),
        series: Object.values(dataAudience||{}),
        legend:{ position:'bottom' },
        colors:['#0d6efd','#198754','#ffc107','#dc3545']
    }).render();

    // Campaign Status Breakdown
    const dataStatus = @json($byStatus);
    new ApexCharts(document.querySelector('#statusChart'), {
        chart:{ type:'pie', height:280 },
        labels: Object.keys(dataStatus||{}),
        series: Object.values(dataStatus||{}),
        colors:['#ffc107','#0dcaf0','#20c997','#6c757d']
    }).render();

    // SMS Sent Over Time
    const dataTime = @json($sentOverTime);
    new ApexCharts(document.querySelector('#sentOverTimeChart'), {
        chart:{ type:'area', height:280, toolbar:{show:false} },
        xaxis:{ categories: Object.keys(dataTime||{}) },
        series:[{ name:'Messages Sent', data: Object.values(dataTime||{}) }],
        colors:['#0d6efd'],
        fill:{ type:'gradient', gradient:{shadeIntensity:1,opacityFrom:0.4,opacityTo:0.1,stops:[0,90,100]} }
    }).render();
})();
</script>
@endsection
