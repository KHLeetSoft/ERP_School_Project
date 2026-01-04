@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Staff Attendance Dashboard</h6>
        <a href="{{ route('admin.attendance.staff.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
    <div class="row g-3">
        <div class="col-md-2">
            <div class="border rounded p-3 text-center">
                <div class="text-muted small">Present</div>
                <div class="fs-4 fw-semibold">{{ $todayCounts['present'] }}</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="border rounded p-3 text-center">
                <div class="text-muted small">Absent</div>
                <div class="fs-4 fw-semibold">{{ $todayCounts['absent'] }}</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="border rounded p-3 text-center">
                <div class="text-muted small">Late</div>
                <div class="fs-4 fw-semibold">{{ $todayCounts['late'] }}</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="border rounded p-3 text-center">
                <div class="text-muted small">Half Day</div>
                <div class="fs-4 fw-semibold">{{ $todayCounts['half_day'] }}</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="border rounded p-3 text-center">
                <div class="text-muted small">Leave</div>
                <div class="fs-4 fw-semibold">{{ $todayCounts['leave'] }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Last 7 Days</h6></div>
                <div class="card-body"><canvas id="trendChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h6 class="mb-0">Today Distribution</h6></div>
                <div class="card-body"><canvas id="todayDonut"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h6 class="mb-0">Weekly Totals by Status</h6></div>
                <div class="card-body"><canvas id="weeklyStacked"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h6 class="mb-0">Attendance Rate (7 days)</h6></div>
                <div class="card-body"><canvas id="rateLine"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h6 class="mb-0">Top Absentees (30 days)</h6></div>
                <div class="card-body"><canvas id="topAbsentBar"></canvas></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function(){
        const ctx = document.getElementById('trendChart');
        if(!ctx) return;
        const labels = {!! json_encode($days->map(fn($d)=> \Carbon\Carbon::parse($d)->format('d M'))) !!};
        const series = {!! json_encode($series) !!};
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Present', data: series.present, borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.2)' },
                    { label: 'Absent', data: series.absent, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.2)' },
                    { label: 'Late', data: series.late, borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.2)' },
                    { label: 'Half Day', data: series.half_day, borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.2)' },
                    { label: 'Leave', data: series.leave, borderColor: '#06b6d4', backgroundColor: 'rgba(6,182,212,0.2)' },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Today donut
        const donut = document.getElementById('todayDonut');
        if (donut) {
            const todayData = [
                {{ (int)($todayCounts['present'] ?? 0) }},
                {{ (int)($todayCounts['absent'] ?? 0) }},
                {{ (int)($todayCounts['late'] ?? 0) }},
                {{ (int)($todayCounts['half_day'] ?? 0) }},
                {{ (int)($todayCounts['leave'] ?? 0) }}
            ];
            new Chart(donut, {
                type: 'doughnut',
                data: {
                    labels: ['Present','Absent','Late','Half Day','Leave'],
                    datasets: [{ data: todayData, backgroundColor: ['#22c55e','#ef4444','#f59e0b','#6366f1','#06b6d4'] }]
                },
                options: { plugins: { legend: { position: 'bottom' } }, responsive: true, maintainAspectRatio: false }
            });
        }

        // Weekly stacked totals
        const stacked = document.getElementById('weeklyStacked');
        if (stacked) {
            const totals = {
                present: series.present.reduce((a,b)=>a+b,0),
                absent: series.absent.reduce((a,b)=>a+b,0),
                late: series.late.reduce((a,b)=>a+b,0),
                half_day: series.half_day.reduce((a,b)=>a+b,0),
                leave: series.leave.reduce((a,b)=>a+b,0),
            };
            new Chart(stacked, {
                type: 'bar',
                data: {
                    labels: ['Last 7 Days'],
                    datasets: [
                        { label: 'Present', data: [totals.present], backgroundColor: '#22c55e' },
                        { label: 'Absent', data: [totals.absent], backgroundColor: '#ef4444' },
                        { label: 'Late', data: [totals.late], backgroundColor: '#f59e0b' },
                        { label: 'Half Day', data: [totals.half_day], backgroundColor: '#6366f1' },
                        { label: 'Leave', data: [totals.leave], backgroundColor: '#06b6d4' },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
                }
            });
        }

        // Attendance rate
        const rate = document.getElementById('rateLine');
        if (rate) {
            const labels2 = labels;
            const data2 = {!! json_encode($attendanceRateSeries) !!};
            new Chart(rate, {
                type: 'line',
                data: { labels: labels2, datasets: [{ label: 'Attendance %', data: data2, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.2)' }] },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
            });
        }

        // Top absentees
        const topAbs = document.getElementById('topAbsentBar');
        if (topAbs) {
            const l = {!! json_encode($topAbsentLabels) !!};
            const v = {!! json_encode($topAbsentValues) !!};
            new Chart(topAbs, {
                type: 'bar',
                data: { labels: l, datasets: [{ label: 'Absent days', data: v, backgroundColor: '#ef4444' }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }
    })();
</script>
@endsection


